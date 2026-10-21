<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;

class RegisterController extends Controller
{
    // Papar borang pendaftaran
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // Proses pendaftaran pengguna
    public function store(Request $request): RedirectResponse
    {
        // 1. Semakan Validation
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone'    => ['required', 'string', 'max:20'],
            'role'     => ['required', 'string'],
            'password' => ['required', 'string', 'min:8'], // Tiada 'confirmed'
        ]);

        // 2. Simpan Pengguna Baru
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'role'     => $request->role,
            'password' => Hash::make($request->password),
        ]);

        // 3. Panggil event registered
        event(new Registered($user));

        // 4. Redirect terus ke page Login (tanpa auto-login)
        return redirect()->route('login')->with('success', 'Pendaftaran berhasil! Silakan log masuk.');
    }
}