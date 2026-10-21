<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    // Papar borang login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Proses Log Masuk
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Semak role pengguna (RBAC)
            if ($user->role === 'admin') {
                return redirect()->intended('/tickets');
            } elseif ($user->role === 'technician') {
                return redirect()->intended('/tickets');
            } elseif ($user->role === 'staff') {
                return redirect()->intended('/tickets');
            }

            return redirect()->intended('/tickets');
        }

        return back()->withErrors([
            'email' => 'E-mel atau kata laluan tidak sah.',
        ])->onlyInput('email');
    }

    // Proses Log Keluar
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Anda telah berjaya log keluar.');
    }

    // -------------------------------------------------------------
    // FUNGSI FORGOT & RESET PASSWORD
    // -------------------------------------------------------------

    // 1. Papar Borang Forgot Password
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    // 2. Hantar Pautan Reset Ke E-mel Pengguna
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withErrors(['email' => __($status)]);
    }

    // 3. Papar Borang Reset Password
    public function showResetPasswordForm(Request $request, $token = null)
    {
        return view('auth.reset-password')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    // 4. Proses Kemaskini Password Baharu
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();
            }
        );

        return $status === Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('status', __($status))
                    : back()->withErrors(['email' => __($status)]);
    }
}