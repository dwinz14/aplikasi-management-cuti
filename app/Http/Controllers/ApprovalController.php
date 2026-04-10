<?php

namespace App\Http\Controllers;

use App\Models\Approval;
use App\Models\ApprovalHistory;
use App\Jobs\SendNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ApprovalController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $approvals = Approval::with(['leave.user', 'approver'])
            ->where('approver_id', $userId)
            ->where('status', 'pending')
            ->get()
            ->filter(function (Approval $a) {
                $prev = $a->leave->approvals->where('step', '<', $a->step);
                return $prev->every(fn($x) => $x->status === 'approved');
            })
            ->values();

        return view('approvals.index', compact('approvals'));
    }

    public function history()
    {
        $histories = ApprovalHistory::with([
            'leave.user.position',
            'leave.leaveType',
            'leave.approvals' => function ($query) {
                $query->orderBy('step');
            },
            'approver'
        ])
            ->where('approved_by', Auth::id())
            ->latest()
            ->paginate(10);

        return view('approvals.history', compact('histories'));
    }

    public function approve(Approval $approval, Request $request)
    {
        $this->authorizeApproval($approval);

        $approval->update(['status' => 'approved']);

        ApprovalHistory::create([
            'leave_id'    => $approval->leave_id,
            'approved_by' => Auth::id(),
            'role'        => Auth::user()->role,
            'status'      => 'approved',
            'catatan'     => $request->input('catatan'),
        ]);

        // Kirim notifikasi ke pemohon cuti
        SendNotification::dispatch(
            $approval->leave->user_id,
            'leave_approved',
            'Cuti Disetujui',
            "Pengajuan cuti Anda telah disetujui oleh " . Auth::user()->name,
            ['leave_id' => $approval->leave_id, 'approver_id' => Auth::id()]
        );

        // Cek apakah ada approver berikutnya
        $nextApproval = $approval->leave->approvals()->where('step', '>', $approval->step)->orderBy('step')->first();
        if ($nextApproval) {
            // Kirim notifikasi ke approver berikutnya
            SendNotification::dispatch(
                $nextApproval->approver_id,
                'leave_request',
                'Pengajuan Cuti Baru',
                "Pengajuan cuti dari {$approval->leave->user->name} membutuhkan persetujuan Anda.",
                ['leave_id' => $approval->leave_id, 'requester_id' => $approval->leave->user_id]
            );
        } else {
            // Jika tidak ada approver berikutnya, final approve + potong cuti
            $this->finalApprove($approval->leave);
        }

        return back()->with('success', 'Approval disetujui.');
    }

    public function requestRevision(Approval $approval, Request $request)
    {
        $this->authorizeApproval($approval);

        $request->validate([
            'revised_start_date' => 'required|date',
            'revised_end_date' => 'required|date|after_or_equal:revised_start_date',
        ]);

        $revisedTotalHari = $this->calculateWorkingDays(
            $request->revised_start_date,
            $request->revised_end_date
        );

        // Update approval dengan data revisi
        $approval->update([
            'revised_start_date' => $request->revised_start_date,
            'revised_end_date' => $request->revised_end_date,
            'revised_total_hari' => $revisedTotalHari,
            'revised_at' => now(),
        ]);

        // Update leave status
        $approval->leave->update([
            'is_revision_pending' => true,
            'revision_by_approval_id' => $approval->id,
        ]);

        ApprovalHistory::create([
            'leave_id'    => $approval->leave_id,
            'approved_by' => Auth::id(),
            'role'        => Auth::user()->role,
            'status'      => 'revision_requested',
            'catatan'     => "Revisi tanggal: {$request->revised_start_date} s/d {$request->revised_end_date} ({$revisedTotalHari} hari)",
        ]);

        // Kirim notifikasi ke pemohon
        SendNotification::dispatch(
            $approval->leave->user_id,
            'leave_revision_requested',
            'Revisi Tanggal Cuti',
            Auth::user()->name . " meminta revisi tanggal cuti Anda. Silakan tinjau dan berikan tanggapan.",
            ['leave_id' => $approval->leave_id, 'approver_id' => Auth::id()]
        );

        return back()->with('success', 'Permintaan revisi tanggal telah dikirim ke pemohon.');
    }

    public function reject(Approval $approval, Request $request)
    {
        $this->authorizeApproval($approval);

        $approval->update(['status' => 'rejected']);

        $approval->leave->update(['status_final' => 'rejected']);

        ApprovalHistory::create([
            'leave_id'    => $approval->leave_id,
            'approved_by' => Auth::id(),
            'role'        => Auth::user()->role,
            'status'      => 'rejected',
            'catatan'     => $request->input('catatan'),
        ]);

        // Kirim notifikasi ke pemohon cuti
        SendNotification::dispatch(
            $approval->leave->user_id,
            'leave_rejected',
            'Cuti Ditolak',
            "Pengajuan cuti Anda telah ditolak oleh " . Auth::user()->name,
            ['leave_id' => $approval->leave_id, 'approver_id' => Auth::id()]
        );

        return back()->with('error', 'Approval ditolak.');
    }

    // proteksi supaya hanya approver terkait yang bisa bertindak & tidak lompat step
    private function authorizeApproval(Approval $approval): void
    {
        abort_unless($approval->approver_id === Auth::id(), 403);

        $prev = $approval->leave->approvals->where('step', '<', $approval->step);
        abort_unless($prev->every(fn($x) => $x->status === 'approved'), 403);
        abort_if($approval->status !== 'pending', 400);
    }

    private function finalApprove($leave)
    {
        $leave->update(['status_final' => 'approved']);

        // Potong cuti berdasarkan jenis cuti dari user_leave_balances
        $userLeaveBalance = \App\Models\UserLeaveBalance::where('user_id', $leave->user_id)
            ->where('leave_type_id', $leave->leave_type_id)
            ->where('year', now()->year)
            ->first();

        if ($userLeaveBalance) {
            $userLeaveBalance->increment('used', $leave->total_hari);
            $userLeaveBalance->decrement('remaining', $leave->total_hari);
        } else {
            \App\Models\UserLeaveBalance::create([
                'user_id' => $leave->user_id,
                'leave_type_id' => $leave->leave_type_id,
                'year' => now()->year,
                'allocated' => $leave->leaveType->quota ?? 0,
                'used' => $leave->total_hari,
                'remaining' => ($leave->leaveType->quota ?? 0) - $leave->total_hari,
            ]);
        }

        // Kirim notifikasi final approval
        SendNotification::dispatch(
            $leave->user_id,
            'leave_final_approved',
            'Cuti Final Disetujui',
            "Pengajuan cuti Anda telah disetujui secara final dan cuti telah dipotong dari kuota Anda.",
            ['leave_id' => $leave->id]
        );
    }

    private function calculateWorkingDays($startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $workingDays = 0;

        while ($start <= $end) {
            if ($start->dayOfWeek !== Carbon::SATURDAY && $start->dayOfWeek !== Carbon::SUNDAY) {
                $workingDays++;
            }
            $start->addDay();
        }

        return $workingDays;
    }
}
