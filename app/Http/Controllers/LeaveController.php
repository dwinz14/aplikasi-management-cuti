<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\User;
use App\Models\Approval;
use App\Models\ApprovalHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LeaveController extends Controller
{

    // helper private agar controller tetap bersih & konsisten via config
    private function getApprovalStepsFor(User $user): array
    {
        $map = config('approval_flow');
        return $map[$user->role] ?? [];
    }

    private function resolveApproverId(string $step, User $requester, ?int $replacementIdFromForm): ?int
    {
        if ($step === 'pengganti') {
            return $replacementIdFromForm;
        }

        if ($step === 'atasan_divisi') {
            $kasie = User::where('division_id', $requester->division_id)->where('role', 'kasie')->first();
            if ($kasie) return $kasie->id;
            $kadiv = User::where('division_id', $requester->division_id)->where('role', 'kadiv')->first();
            return $kadiv?->id;
        }

        if ($step === 'kadiv') {
            return User::where('division_id', $requester->division_id)->where('role', 'kadiv')->value('id');
        }

        if ($step === 'direksi') {
            return User::where('role', 'direksi')->value('id'); // asumsi minimal 1
        }

        if ($step === 'auto') {
            return null;
        }

        return null;
    }

    public function index()
    {
        $leaves = Leave::with('approvals.approver')->where('user_id', Auth::id())->latest()->get();
        return view('leaves.index', compact('leaves'));
    }

    public function create()
    {
        $user = Auth::user();
        $requiresReplacement = in_array($user->role, ['staff', 'kasie', 'kadiv'], true);
        $penggantiList = $requiresReplacement
            ? User::where('division_id', $user->division_id)->where('id', '!=', $user->id)->get()
            : collect();

        return view('leaves.create', compact('penggantiList', 'requiresReplacement'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'start_date'    => 'required|date|after_or_equal:today',
            'end_date'      => 'required|date|after_or_equal:start_date',
            'alasan'        => 'required|string',
            'pengganti_id'  => (in_array($user->role, ['staff', 'kasie', 'kadiv'], true) ? 'required' : 'nullable') . '|nullable|exists:users,id',
        ]);

        $totalHari = Carbon::parse($request->start_date)->diffInDays(Carbon::parse($request->end_date)) + 1;

        // cegah minus sisa_cuti (kecuali direksi auto approve; untuk simplicity tetap validasi juga)
        if ($user->sisa_cuti < $totalHari && $user->role !== 'direksi') {
            return back()->withErrors(['msg' => 'Sisa cuti tidak mencukupi.'])->withInput();
        }

        //  Validasi masih ada pengajuan aktif
        $hasActiveLeave = \App\Models\Leave::where('user_id', $user->id)
            ->whereIn('status_final', ['pending'])
            ->where(function ($q) {
                $q->where('end_date', '>=', now()->toDateString()); // masih berjalan
            })
            ->exists();

        if ($hasActiveLeave) {
            return back()->withErrors(['msg' => 'Masih ada cuti aktif atau pending, tidak bisa ajukan cuti baru.']);
        }

        return DB::transaction(function () use ($request, $user, $totalHari) {
            $leave = Leave::create([
                'user_id'     => $user->id,
                'start_date'  => $request->start_date,
                'end_date'    => $request->end_date,
                'total_hari'  => $totalHari,
                'alasan'      => $request->alasan,
                'status_final' => 'pending',
            ]);

            $steps = $this->getApprovalStepsFor($user);

            // direksi -> auto approve
            if ($user->role === 'direksi' || (count($steps) === 1 && $steps[0] === 'auto')) {
                $leave->update(['status_final' => 'approved']);
                $leave->user->decrement('sisa_cuti', $leave->total_hari);

                ApprovalHistory::create([
                    'leave_id'    => $leave->id,
                    'approved_by' => $user->id,
                    'role'        => $user->role,
                    'status'      => 'approved',
                ]);

                return redirect()->route('cuti.index')->with('success', 'Pengajuan cuti disetujui otomatis.');
            }

            // buat list approver sesuai urutan, hindari duplikasi approver
            $uniqueApprovers = [];
            foreach ($steps as $symbol) {
                if ($symbol === 'auto') {
                    continue;
                } // pemohon non-direksi tidak pakai auto
                $approverId = $this->resolveApproverId($symbol, $user, $request->pengganti_id);
                if ($approverId && !in_array($approverId, $uniqueApprovers)) {
                    $uniqueApprovers[] = $approverId;
                }
            }

            // buat approval dengan step berurutan
            foreach ($uniqueApprovers as $index => $approverId) {
                Approval::create([
                    'leave_id'    => $leave->id,
                    'approver_id' => $approverId,
                    'step'        => $index + 1,
                    'status'      => 'pending',
                ]);
            }

            return redirect()->route('cuti.index')->with('success', 'Pengajuan cuti berhasil dibuat.');
        });
    }
    // public function edit(Leave $leave)
    // { // Policy optional

    //     $user = Auth::user();
    //     $penggantiList = User::where('division_id', $user->division_id)
    //         ->where('id', '!=', $user->id)
    //         ->get();

    //     $kadivList = User::where('role', 'kadiv')
    //         ->where('division_id', $user->division_id)
    //         ->get();

    //     return view('cuti.edit', compact('leave', 'penggantiList', 'kadivList'));
    // }

    /**
     * Update cuti.
     */
    public function update(Request $request, Leave $leave)
    {
        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'alasan' => 'required|string',
            'pengganti_id' => 'nullable|exists:users,id',
            'kadiv_id' => 'nullable|exists:users,id',
        ]);

        $totalHari = (new \Carbon\Carbon($request->start_date))
            ->diffInDays(new \Carbon\Carbon($request->end_date)) + 1;

        $leave->update([
            'pengganti_id' => $request->pengganti_id,
            'kadiv_id' => $request->kadiv_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_hari' => $totalHari,
            'alasan' => $request->alasan,
        ]);

        return redirect()->route('cuti.index')->with('success', 'Pengajuan cuti berhasil diperbarui.');
    }

    /**
     * Hapus cuti.
     */
    public function destroy(Leave $leave)
    {
        $leave->delete();
        return redirect()->route('cuti.index')->with('success', 'Pengajuan cuti berhasil dihapus.');
    }
}
