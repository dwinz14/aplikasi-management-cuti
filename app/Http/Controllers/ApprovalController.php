<?php

namespace App\Http\Controllers;

use App\Helpers\LeaveHelper;
use App\Models\Approval;
use App\Models\ApprovalHistory;
use App\Jobs\SendNotification;
use App\Services\LeaveApprovalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ApprovalController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Subquery untuk mengecek apakah semua approval step sebelumnya sudah approved
        $approvals = Approval::with(['leave.user', 'approver'])
            ->where('approver_id', $userId)
            ->where('status', 'pending')
            ->whereNotExists(function ($query) {
                $query->select(\DB::raw(1))
                    ->from('approvals as a2')
                    ->whereColumn('a2.leave_id', 'approvals.leave_id')
                    ->whereColumn('a2.step', '<', 'approvals.step')
                    ->where('a2.status', '!=', 'approved');
            })
            ->get();

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
            'step'        => $approval->step,
            'status'      => 'approved',
            'catatan'     => $request->input('catatan'),
        ]);

        SendNotification::dispatch(
            $approval->leave->user_id,
            'leave_approved',
            'Cuti Disetujui',
            "Pengajuan cuti Anda telah disetujui oleh " . Auth::user()->name,
            ['leave_id' => $approval->leave_id, 'approver_id' => Auth::id()]
        );

        $nextApproval = $approval->leave->approvals()
            ->where('step', '>', $approval->step)
            ->orderBy('step')
            ->first();

        if ($nextApproval) {
            SendNotification::dispatch(
                $nextApproval->approver_id,
                'leave_request',
                'Pengajuan Cuti Baru',
                "Pengajuan cuti dari {$approval->leave->user->name} membutuhkan persetujuan Anda.",
                ['leave_id' => $approval->leave_id, 'requester_id' => $approval->leave->user_id]
            );
        } else {
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

        $revisedTotalHari = LeaveHelper::calculateWorkingDays(
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
            'step'        => $approval->step,
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
            'step'        => $approval->step,
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

        return back()->with('success', 'Approval ditolak.');
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
        app(LeaveApprovalService::class)->finalApprove($leave);
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
