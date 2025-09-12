<?php

namespace App\Http\Controllers;

use App\Models\Approval;
use App\Models\ApprovalHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $histories = ApprovalHistory::with(['leave.user', 'approver'])
            ->where('approved_by', Auth::id())
            ->latest()
            ->paginate(5);

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

        // jika semua approved → final approve + potong cuti
        $allApproved = $approval->leave->approvals()->where('status', '!=', 'approved')->exists() === false;
        if ($allApproved) {
            $leave = $approval->leave;
            $leave->update(['status_final' => 'approved']);
            $leave->user->decrement('sisa_cuti', $leave->total_hari);
        }

        return back()->with('success', 'Approval disetujui.');
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
}
