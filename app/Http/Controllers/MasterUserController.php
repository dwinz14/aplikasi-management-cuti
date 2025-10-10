<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
            ->paginate(10)
            ->withQueryString();

        $divisions = Cache::remember('divisions_all', 3600, fn() => Division::all());

        // // Convert user names to title case for display
        // $users->getCollection()->transform(function ($user) {
        //     $user->name = ucwords(strtolower($user->name));
        //     return $user;
        // });
        // $divisions->getCollection()->transform(function ($division) {
        //     $division->nama_divisi = ucwords(strtolower($division->nama_divisi));
        //     return $divisions;
        // });

        return view('admin.users.index', compact('users', 'divisions', 'search', 'role', 'divisionId'));
    }

    public function create()
    {
        $divisions = Cache::remember('divisions_all', 3600, fn() => Division::all());
        return view('admin.users.create', compact('divisions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik'        => [
                'required',
                'string',
                'size:11',
                'regex:/^[A-Z]{2}[0-9]{9}$/',
                'unique:users,nik',
            ],
            'name'        => ['bail', 'required', 'string', 'max:255', 'regex:/^[a-zA-Z\\s]+$/'],
            'email'       => ['required', 'email', 'unique:users,email'],
            'role'        => ['required', 'in:super_admin,hrd,kadiv,kasie,staff,direksi'],
            'division_id' => ['nullable', 'exists:divisions,id'],
            'sisa_cuti'   => ['required', 'integer', 'min:0'],

            // Pesan error kustom (opsional)
            'nik.regex' => 'Format NIK salah.',
        ]);


        // Normalisasi & sanitasi data
        $validated['name'] = strtolower(strip_tags(trim($validated['name'])));
        $validated['email'] = strtolower(trim($validated['email']));

        // Gunakan default password dari .env
        $defaultPassword = config('app.default_user_password', 'password123');
        $validated['password'] = Hash::make($defaultPassword);

        User::create($validated);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan dengan password default.');
    }

    public function edit(User $user)
    {
        $divisions = Cache::remember('divisions_all', 3600, fn() => Division::all());
        return view('admin.users.edit', compact('user', 'divisions'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'nik'        => [
                'required',
                'string',
                'size:11',
                'regex:/^[A-Z]{2}[0-9]{9}$/',
                // 'unique:users,nik',
            ],
            'name'        => ['bail', 'required', 'string', 'max:255', 'regex:/^[a-zA-Z\\s]+$/'],
            'email'       => ['required', 'email', 'unique:users,email,' . $user->id],
            'role'        => ['required', 'in:super_admin,hrd,kabag,kasie,staff,direksi'],
            'division_id' => ['nullable', 'exists:divisions,id'],
            'sisa_cuti'   => ['required', 'integer', 'min:0'],

            // Pesan error kustom (opsional)
            'nik.regex' => 'Format NIK salah.',
        ]);

        $validated['name'] = strtolower(strip_tags(trim($validated['name'])));
        $validated['email'] = strtolower(trim($validated['email']));

        $user->fill($validated)->save();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }

    public function resetPassword(User $user)
    {
        try {
            $defaultPassword = config('app.default_user_password', 'password123');
            $user->update(['password' => Hash::make($defaultPassword)]);
            return back()->with('success', "Password user berhasil direset ke default ({$defaultPassword}).");
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat mereset password. Silakan coba lagi.');
        }
    }
}
