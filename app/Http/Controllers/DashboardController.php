<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Leave;

class DashboardController extends Controller
{

    public function index()
    {
        $user = Auth::user();

        // Logika untuk menentukan judul dashboard
        // $dashboardTitle = match ($user->role) {
        //     'super_admin' => 'Super Admin Dashboard',
        //     'direksi' => 'DIreksi Dashboard',
        //     'hrd' => 'HRD Dashboard',
        //     'kabag' => 'Kepala Divisi Dashboard',
        //     'kasie' => 'Kasie Dashboard',
        //     'staff' => 'Staff Dashboard',
        //     default => 'Dashboard',
        // };

        // Ambil data cuti user dari user_leave_balances
        $currentYear = now()->year;
        $leaveBalances = $user->userLeaveBalances()
            ->where('year', $currentYear)
            ->with('leaveType')
            ->get();

        // $totalQuota = $leaveBalances->sum('total_quota');
        $totalUsed = $leaveBalances->sum('used');
        // $totalRemaining = $leaveBalances->sum('remaining');

        // Total semua pengajuan cuti (approved + pending + rejected)
        $totalLeaveApplications = Leave::where('user_id', $user->id)->count();

        // 3 pengajuan cuti terakhir
        $recentLeaves = Leave::with(['leaveType', 'approvals.approver'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        // Ambil pengajuan cuti pending user dengan approvals
        $pendingLeaves = Leave::with(['approvals.approver'])
            ->where('user_id', $user->id)
            ->where('status_final', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        // Kirimkan variabel ke view
        return view('dashboard', [
            // 'dashboardTitle' => $dashboardTitle,
            // 'totalQuota' => $totalQuota,
            'totalUsed' => $totalUsed,
            // 'totalRemaining' => $totalRemaining,
            'totalLeaveApplications' => $totalLeaveApplications,
            'recentLeaves' => $recentLeaves,
            'pendingLeaves' => $pendingLeaves,
        ]);
    }

    public function admin()
    {
        return view('admin.dashboard');
    }

    public function direksi()
    {
        return view('direksi.dashboard');
    }
    public function hrd()
    {
        return view('hrd.dashboard');
    }
    public function kabag()
    {
        return view('kabag.dashboard');
    }
    public function kasie()
    {
        return view('kasie.dashboard');
    }
    public function staff()
    {
        return view('staff.dashboard');
    }
}
