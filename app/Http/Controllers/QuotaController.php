<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Division;
use Illuminate\Http\Request;

class QuotaController extends Controller
{
    public function index(Request $request)
    {
        $divisions = Division::all();

        $search = $request->get('search');
        $divisionId = $request->get('division_id');
        $role = $request->get('role');

        $users = User::where('role', '!=', 'super_admin')
            ->with('division')
            ->when($search, function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })
            ->when($divisionId, function ($q) use ($divisionId) {
                $q->where('division_id', $divisionId);
            })
            ->when($role, function ($q) use ($role) {
                $q->where('role', $role);
            })
            ->orderBy('name')
            ->paginate(4)
            ->withQueryString();

        return view('hrd.quota', compact('users', 'divisions', 'search', 'divisionId', 'role'));
    }

    public function resetAll(Request $request)
    {
        $defaultQuota = $request->get('default_quota', 12);
        User::where('role', '!=', 'super_admin')->update(['sisa_cuti' => $defaultQuota]);

        return back()->with('success', 'Kuota cuti semua karyawan berhasil direset.');
    }

    public function resetDivision(Request $request)
    {
        $request->validate(['division_id' => 'required|exists:divisions,id']);
        $defaultQuota = $request->get('default_quota', 12);

        User::where('division_id', $request->division_id)->update(['sisa_cuti' => $defaultQuota]);

        return back()->with('success', 'Kuota cuti divisi terpilih berhasil direset.');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'sisa_cuti' => 'required|integer|min:0'
        ]);

        $user->update(['sisa_cuti' => $request->sisa_cuti]);

        return back()->with('success', "Kuota cuti {$user->name} berhasil diperbarui.");
    }
}
