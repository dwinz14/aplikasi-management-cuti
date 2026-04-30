<?php

namespace App\Services;

use App\Models\Leave;
use App\Models\UserLeaveBalance;
use App\Jobs\SendNotification;
use Illuminate\Support\Facades\DB;

class LeaveApprovalService
{
    public function finalApprove(Leave $leave)
    {
        $leave->update(['status_final' => 'approved']);

        $leaveType = $leave->leaveType;
        if ($leaveType->quota > 0) {
            $balance = UserLeaveBalance::where('user_id', $leave->user_id)
                ->where('leave_type_id', $leave->leave_type_id)
                ->where('year', now()->year)
                ->first();

            if ($balance) {
                $balance->update([
                    'used' => DB::raw("used + {$leave->total_hari}"),
                    'remaining' => DB::raw("remaining - {$leave->total_hari}"),
                ]);
            } else {
                UserLeaveBalance::create([
                    'user_id' => $leave->user_id,
                    'leave_type_id' => $leave->leave_type_id,
                    'year' => now()->year,
                    'total_quota' => $leaveType->quota,
                    'used' => $leave->total_hari,
                    'remaining' => $leaveType->quota - $leave->total_hari,
                ]);
            }
        }

        SendNotification::dispatch(
            $leave->user_id,
            'leave_final_approved',
            'Cuti Final Disetujui',
            "Pengajuan cuti Anda telah disetujui secara final.",
            ['leave_id' => $leave->id]
        );
    }
}
