<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Leave extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'leave_type_id',
        'pengganti_id',
        'kabag-pincab_id',
        'start_date',
        'end_date',
        'total_hari',
        'alasan',
        'status_pengganti',
        'status_kabag-pincab',
        'status_hrd',
        'status_final',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function approvals()
    {
        return $this->hasMany(Approval::class);
    }
    public function approvalHistories()
    {
        return $this->hasMany(ApprovalHistory::class);
    }
    /** ambil riwayat terakhir tanpa N+1 saat rekap */
    public function lastHistory()
    {
        return $this->hasOne(ApprovalHistory::class)->latestOfMany();
    }
}
