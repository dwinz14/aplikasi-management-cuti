<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class MasterUserController extends Controller
{
    public function index(Request $request)
    {
        $search     = $request->get('search');
        $role       = $request->get('role');
        $divisionId = $request->get('division_id');

        $users = User::with('division')
            ->when($search, fn($q) => $q->where('name', 'like', "%{$search}%"))
            ->when($role, fn($q) => $q->where('role', $role))
            ->when($divisionId, fn($q) => $q->where('division_id', $divisionId))
            ->orderBy('name')
            ->paginate(5)
            ->withQueryString();

        //  Cache divisions untuk 1 jam
        $divisions = Cache::remember('divisions_all', 3600, fn() => Division::all());

        return view('admin.users.index', compact('users', 'divisions', 'search', 'role', 'divisionId'));
    }

    public function create()
    {
        $divisions = Cache::remember('divisions_all', 3600, fn() => Division::all());
        return view('admin.users.create', compact('divisions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik'        => [
                'required',
                'string',
                'size:11',
                'regex:/^[A-Z]{2}[0-9]{9}$/',
                'unique:users,nik',
            ],
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users',
            'password'    => 'required|min:6',
            'role'        => 'required|in:super_admin,hrd,kabag,kasie,staff,direksi',
            'division_id' => 'nullable|exists:divisions,id',
            'sisa_cuti'   => 'required|integer|min:0',
        ], [
            // Pesan error kustom (opsional)
            'nik.regex' => 'Format NIK salah.',
        ]);

        User::create([
            'nik'        => $request->nik,
            'name'        => $request->name,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
            'role'        => $request->role,
            'division_id' => $request->division_id,
            'sisa_cuti'   => $request->sisa_cuti,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $divisions = Cache::remember('divisions_all', 3600, fn() => Division::all());
        return view('admin.users.edit', compact('user', 'divisions'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'nik'        => [
                'required',
                'string',
                'size:11',
                'regex:/^[A-Z]{2}[0-9]{9}$/',
                'unique:users,nik',
            ],
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,' . $user->id,
            'role'        => 'required|in:super_admin,hrd,kabag,kasie,staff,direksi',
            'division_id' => 'nullable|exists:divisions,id',
            'sisa_cuti'   => 'required|integer|min:0',
        ], [
            // Pesan error kustom (opsional)
            'nik.regex' => 'Format NIK salah.',
        ]);

        $user->update($request->only(['nik', 'name', 'email', 'role', 'division_id', 'sisa_cuti']));

        return redirect()->route('admin.users.index')->with('success', ' Data User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete(); // pakai soft delete
        return redirect()->route('admin.users.index')->with('success', 'Data User berhasil dihapus.');
    }

    public function resetPassword(User $user)
    {
        try {
            // Default password configurable dari .env
            $defaultPassword = config('app.default_user_password', 'password123');

            $user->update([
                'password' => Hash::make($defaultPassword),
                // Set flag to force user to change password on next login
                'must_change_password' => true,
            ]);

            $message = "Password user berhasil direset ke default dan user harus mengganti password saat login berikutnya.";

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                ]);
            }

            return back()->with('success', $message);
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat mereset password. Silakan coba lagi.',
                ], 500);
            }

            return back()->with('error', 'Terjadi kesalahan saat mereset password. Silakan coba lagi.');
        }
    }
}
