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
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class LeaveController extends Controller
{
    public function index()
    {
        $leaves = Leave::with('approvals.approver')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(5);

        return view('leaves.index', compact('leaves'));
    }

    public function create()
    {
        $user = Auth::user();
        $requiresReplacement = in_array($user->role, ['staff', 'kasie', 'kabag'], true);

        $penggantiList = $requiresReplacement
            ? Cache::remember("pengganti_{$user->office_id}", 300, fn() =>
            User::select('id', 'name', 'role')->where('office_id', $user->office_id)->where('id', '!=', $user->id)->get())
            : collect();

        $requiresAtasan = !in_array($user->role, ['direksi'], true);
        $atasanList = collect();

        if ($requiresAtasan) {
            $direksi = Cache::remember('direksi_users', 300, fn() =>
            User::select('id', 'name', 'role')->where('role', 'direksi')->get());

            $atasanList = $atasanList->merge($direksi);

            if ($user->role !== 'hrd') {
                $others = Cache::remember("atasan_{$user->office_id}", 300, fn() =>
                User::select('id', 'name', 'role')
                    ->where('office_id', $user->office_id)
                    ->whereIn('role', ['hrd', 'kasie', 'kabag'])
                    ->where('id', '!=', $user->id)
                    ->get());

                $atasanList = $atasanList->merge($others);
            }
        }

        return view('leaves.create', compact('penggantiList', 'requiresReplacement', 'atasanList', 'requiresAtasan'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'start_date'    => 'required|date|after_or_equal:today',
            'end_date'      => 'required|date|after_or_equal:start_date',
            'alasan'        => ['required', 'string', 'max:500', 'regex:/^[a-zA-Z0-9\s.,()-]+$/'],
            'pengganti_id'  => (in_array($user->role, ['staff', 'kasie', 'kabag'], true) ? 'required' : 'nullable') . '|nullable|exists:users,id',
            'atasan_id'     => (!in_array($user->role, ['direksi'], true) ? 'required' : 'nullable') . '|nullable|exists:users,id',
        ]);

        $totalHari = Carbon::parse($request->start_date)->diffInDays(Carbon::parse($request->end_date)) + 1;

        if ($user->sisa_cuti < $totalHari && $user->role !== 'direksi') {
            return back()->withErrors(['msg' => 'Sisa cuti tidak mencukupi.'])->withInput();
        }

        if ($this->hasOverlapLeave($user->id, $request->start_date, $request->end_date)) {
            return back()->withErrors(['msg' => 'Masih ada cuti aktif atau pending.']);
        }

        if ($request->pengganti_id && $this->hasOverlapLeave($request->pengganti_id, $request->start_date, $request->end_date)) {
            return back()->withErrors(['msg' => 'Pengganti yang dipilih sedang cuti di tanggal tersebut.']);
        }

        if ($this->hasOverlapReplacement($request->pengganti_id, $request->start_date, $request->end_date)) {
            return back()->withErrors(['msg' => 'Pengganti tersebut sudah ditugaskan pada cuti lain.']);
        }

        if ($this->hasOverlapReplacement($user->id, $request->start_date, $request->end_date)) {
            return back()->withErrors(['msg' => 'Anda sedang jadi pengganti di tanggal tersebut.']);
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

            $approvers = collect([$request->pengganti_id, $request->atasan_id])
                ->filter()
                ->unique()
                ->values();

            foreach ($approvers as $index => $approverId) {
                Approval::create([
                    'leave_id'    => $leave->id,
                    'approver_id' => $approverId,
                    'step'        => $index + 1,
                    'status'      => 'pending',
                ]);
            }

            SendNotification::dispatch(
                $approvers->first(),
                'leave_request',
                'Pengajuan Cuti Baru',
                "Pengajuan cuti dari {$user->name} membutuhkan persetujuan Anda.",
                ['leave_id' => $leave->id, 'requester_id' => $user->id]
            );

            return redirect()->route('cuti.index')->with('success', 'Pengajuan cuti berhasil dibuat.');
        });
    }

    private function hasOverlapLeave($userId, $start, $end)
    {
        return Leave::where('user_id', $userId)
            ->whereIn('status_final', ['pending', 'approved'])
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('start_date', [$start, $end])
                    ->orWhereBetween('end_date', [$start, $end])
                    ->orWhere(function ($q2) use ($start, $end) {
                        $q2->where('start_date', '<=', $start)
                            ->where('end_date', '>=', $end);
                    });
            })
            ->exists();
    }

    private function hasOverlapReplacement($replacementId, $start, $end)
    {
        return Leave::where('pengganti_id', $replacementId)
            ->whereNotIn('status_final', ['rejected'])
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('start_date', [$start, $end])
                    ->orWhereBetween('end_date', [$start, $end])
                    ->orWhere(function ($q2) use ($start, $end) {
                        $q2->where('start_date', '<=', $start)
                            ->where('end_date', '>=', $end);
                    });
            })
            ->exists();
    }

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
