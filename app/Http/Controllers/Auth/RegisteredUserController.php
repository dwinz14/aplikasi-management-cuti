<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.auth', ['mode' => 'register']);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nik' => ['required', 'string', 'size:11', 'regex:/^AP\d{9}$/', 'unique:' . User::class],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'role' => ['required', 'in:super_admin,hrd,direksi,kabag,kasie,staff'],
            'division_id' => ['nullable', 'exists:divisions,id'],
            'password' => ['required', 'confirmed', 'regex:/^[A-Z].{7,}$/', 'regex:/.*\d.*/', 'regex:/.*[!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?].*/'],
        ]);

        $user = User::create([
            'nik' => $request->nik,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'division_id' => $request->division_id,
            'sisa_cuti' => 12,
            'status' => 'pending',
        ]);

        event(new Registered($user));

        // Do not auto-login, redirect to pending approval page
        return redirect(route('registration.pending', absolute: false));
    }
}
