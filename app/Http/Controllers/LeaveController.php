<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\User;
use App\Models\Approval;
use App\Models\ApprovalHistory;
use App\Jobs\SendNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LeaveController extends Controller
{

    // helper private agar controller tetap bersih & konsisten via config
    // private function getApprovalStepsFor(User $user): array
    // {
    //     $map = config('approval_flow');
    //     return $map[$user->role] ?? [];
    // }

    // private function resolveApproverId(string $step, User $requester, ?int $replacementIdFromForm): ?int
    // {
    //     if ($step === 'pengganti') {
    //         return $replacementIdFromForm;
    //     }

    //     if ($step === 'atasan_divisi') {
    //         $kasie = User::where('division_id', $requester->division_id)->where('role', 'kasie')->first();
    //         if ($kasie) return $kasie->id;
    //         $kabag = User::where('division_id', $requester->division_id)->where('role', 'kabag')->first();
    //         return $kabag?->id;
    //     }

    //     if ($step === 'kabag') {
    //         return User::where('division_id', $requester->division_id)->where('role', 'kabag')->value('id');
    //     }

    //     if ($step === 'direksi') {
    //         return User::where('role', 'direksi')->value('id'); // asumsi minimal 1
    //     }

    //     if ($step === 'auto') {
    //         return null;
    //     }

    //     return null;
    // }

    public function index()
    {
        $leaves = Leave::with('approvals.approver')->where('user_id', Auth::id())->latest()->paginate(5);
        return view('leaves.index', compact('leaves'));
    }

    public function create()
    {
        $user = Auth::user();
        $requiresReplacement = in_array($user->role, ['staff', 'kasie', 'kabag'], true);
        $penggantiList = $requiresReplacement
            ? User::where('division_id', $user->division_id)->where('id', '!=', $user->id)->get()
            : collect();

        $requiresAtasan = !in_array($user->role, ['direksi'], true);
        $atasanList = $requiresAtasan
            ? ($user->role === 'hrd'
                ? User::where('role', 'direksi')->get()
                : User::where('division_id', $user->division_id)->whereIn('role', ['kasie', 'kabag'])->get())
            : collect();

        return view('leaves.create', compact('penggantiList', 'requiresReplacement', 'atasanList', 'requiresAtasan'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'start_date'    => 'required|date|after_or_equal:today',
            'end_date'      => 'required|date|after_or_equal:start_date',
            'alasan'        => 'required|string',
            'pengganti_id'  => (in_array($user->role, ['staff', 'kasie', 'kabag'], true) ? 'required' : 'nullable') . '|nullable|exists:users,id',
            'atasan_id'     => (!in_array($user->role, ['direksi'], true) ? 'required' : 'nullable') . '|nullable|exists:users,id',
        ]);

        $totalHari = Carbon::parse($request->start_date)->diffInDays(Carbon::parse($request->end_date)) + 1;

        // cegah minus sisa_cuti (kecuali direksi auto approve; untuk simplicity tetap validasi juga)
        if ($user->sisa_cuti < $totalHari && $user->role !== 'direksi') {
            return back()->withErrors(['msg' => 'Sisa cuti tidak mencukupi.'])->withInput();
        }

        //  Validasi masih ada pengajuan aktif
        $hasActiveLeave = Leave::where('user_id', $user->id)
            ->whereIn('status_final', ['pending', 'approved'])
            ->where(function ($q) {
                $q->where('end_date', '>=', now()->toDateString()); // masih berjalan
            })
            ->exists();

        if ($hasActiveLeave) {
            return back()->withErrors(['msg' => 'Masih ada cuti aktif atau pending, tidak bisa ajukan cuti baru.']);
        }

        // Validasi pengganti tidak sedang cuti
        $penggantiOnLeave = Leave::where('user_id', $request->pengganti_id)
            ->whereIn('status_final', ['approved', 'pending'])
            ->where(function ($q) use ($request) {
                $q->whereBetween('start_date', [$request->start_date, $request->end_date])
                    ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                    ->orWhere(function ($q2) use ($request) {
                        $q2->where('start_date', '<=', $request->start_date)
                            ->where('end_date', '>=', $request->end_date);
                    });
            })
            ->exists();

        if ($penggantiOnLeave) {
            return back()->withErrors(['msg' => 'Pengganti yang dipilih sedang cuti pada tanggal tersebut.']);
        }

        // Validasi pengganti tidak double approve di rentang waktu sama
        $penggantiOverlap = Leave::where('pengganti_id', $request->pengganti_id)
            ->where(function ($q) use ($request) {
                $q->whereBetween('start_date', [$request->start_date, $request->end_date])
                    ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                    ->orWhere(function ($q2) use ($request) {
                        $q2->where('start_date', '<=', $request->start_date)
                            ->where('end_date', '>=', $request->end_date);
                    });
            })
            ->whereNotIn('status_final', ['rejected']) //  semua selain rejected dianggap blocking
            ->exists();

        if ($penggantiOverlap) {
            return back()->withErrors(['msg' => 'Pengganti yang dipilih sudah ditugaskan pada cuti lain di tanggal tersebut.']);
        }

        // Validasi user tidak sedang sebagai pengganti di rentang waktu tersebut
        $userAsReplacement = Leave::where('pengganti_id', $user->id)
            ->where(function ($q) use ($request) {
                $q->whereBetween('start_date', [$request->start_date, $request->end_date])
                    ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                    ->orWhere(function ($q2) use ($request) {
                        $q2->where('start_date', '<=', $request->start_date)
                            ->where('end_date', '>=', $request->end_date);
                    });
            })
            ->whereNotIn('status_final', ['rejected'])
            ->exists();

        if ($userAsReplacement) {
            return back()->withErrors(['msg' => 'Anda sedang ditugaskan sebagai pengganti pada tanggal tersebut, tidak bisa ajukan cuti.']);
        }

        return DB::transaction(function () use ($request, $user, $totalHari) {
            $leave = Leave::create([
                'user_id'     => $user->id,
                'pengganti_id' => $request->pengganti_id,
                'start_date'  => $request->start_date,
                'end_date'    => $request->end_date,
                'total_hari'  => $totalHari,
                'alasan'      => $request->alasan,
                'status_final' => 'pending',
            ]);

            // direksi -> auto approve
            if ($user->role === 'direksi') {
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

            // buat approval steps: pengganti dulu jika berbeda, lalu atasan
            $approvers = [];
            if ($request->pengganti_id && $request->pengganti_id != $request->atasan_id) {
                $approvers[] = $request->pengganti_id;
            }
            $approvers[] = $request->atasan_id;

            foreach ($approvers as $index => $approverId) {
                Approval::create([
                    'leave_id'    => $leave->id,
                    'approver_id' => $approverId,
                    'step'        => $index + 1,
                    'status'      => 'pending',
                ]);

                // Kirim notifikasi ke approver
                $approver = User::find($approverId);
                SendNotification::dispatch(
                    $approverId,
                    'leave_request',
                    'Pengajuan Cuti Baru',
                    "Pengajuan cuti dari {$user->name} membutuhkan persetujuan Anda.",
                    ['leave_id' => $leave->id, 'requester_id' => $user->id]
                );
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

    //     $kabagList = User::where('role', 'kabag')
    //         ->where('division_id', $user->division_id)
    //         ->get();

    //     return view('cuti.edit', compact('leave', 'penggantiList', 'kabagList'));
    // }

    /**
     * Update cuti.
     */
    public function update(Request $request, Leave $leave)
    {
        // $request->validate([
        //     'start_date' => 'required|date|after_or_equal:today',
        //     'end_date' => 'required|date|after_or_equal:start_date',
        //     'alasan' => 'required|string',
        //     'pengganti_id' => 'nullable|exists:users,id',
        //     'kabag_id' => 'nullable|exists:users,id',
        // ]);

        // $totalHari = (new \Carbon\Carbon($request->start_date))
        //     ->diffInDays(new \Carbon\Carbon($request->end_date)) + 1;

        // $leave->update([
        //     'pengganti_id' => $request->pengganti_id,
        //     'kabag_id' => $request->kabag_id,
        //     'start_date' => $request->start_date,
        //     'end_date' => $request->end_date,
        //     'total_hari' => $totalHari,
        //     'alasan' => $request->alasan,
        // ]);

        // return redirect()->route('cuti.index')->with('success', 'Pengajuan cuti berhasil diperbarui.');
    }

    /**
     * Hapus cuti.
     */
    public function destroy(Leave $leave)
    {
        $leave->delete();
        return redirect()->route('cuti.index')->with('success', 'Pengajuan cuti berhasil dihapus.');
    }

    public function replacements()
    {
        $leaves = Leave::with('user')->where('pengganti_id', Auth::id())->latest()->paginate(10);
        return view('replacements.index', compact('leaves'));
    }
}
