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
        $dashboardTitle = match ($user->role) {
            'super_admin' => 'Super Admin Dashboard',
            'direksi' => 'DIreksi Dashboard',
            'hrd' => 'HRD Dashboard',
            'kabag' => 'Kepala Divisi Dashboard',
            'kasie' => 'Kasie Dashboard',
            'staff' => 'Staff Dashboard',
            default => 'Dashboard',
        };

        // Ambil data cuti user
        $sisaCuti = $user->sisa_cuti;

        $cutiDigunakan = Leave::where('user_id', $user->id)
            ->where('status_final', 'approved')
            ->sum('total_hari');

        $menungguPersetujuan = Leave::where('user_id', $user->id)
            ->where('status_final', 'pending')
            ->count();


        // Ambil pengajuan cuti pending user dengan approvals
        $pendingLeaves = Leave::with(['approvals.approver'])
            ->where('user_id', $user->id)
            ->where('status_final', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        // Kirimkan variabel ke view
        return view('dashboard', [
            'dashboardTitle' => $dashboardTitle,
            'sisaCuti' => $sisaCuti,
            'cutiDigunakan' => $cutiDigunakan,
            'menungguPersetujuan' => $menungguPersetujuan,
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
