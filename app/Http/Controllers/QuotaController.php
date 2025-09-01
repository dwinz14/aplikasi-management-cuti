<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class QuotaController extends Controller
{
    public function index()
    {
        $users = User::where('role', '!=', 'super_admin')->with('division')->get();
        return view('hrd.quota', compact('users'));
    }

    public function resetAll(Request $request)
    {
        $defaultQuota = $request->get('default_quota', 12);

        User::where('role', '!=', 'super_admin')->update(['sisa_cuti' => $defaultQuota]);

        return redirect()->route('hrd.quota.index')->with('success', 'Kuota cuti semua karyawan berhasil direset.');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'sisa_cuti' => 'required|integer|min:0'
        ]);

        $user->update(['sisa_cuti' => $request->sisa_cuti]);

        return redirect()->route('hrd.quota.index')->with('success', "Kuota cuti {$user->name} berhasil diperbarui.");
    }
}
