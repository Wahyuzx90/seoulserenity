<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ── Customer Login ──
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            return match ($user->role) {
                'owner' => redirect()->route('owner.reports'),
                'staff' => redirect()->route('staff.orders'),
                default => redirect()->route('customer.menu'),
            };
        }

        return back()->withErrors(['email' => 'Email atau kata sandi salah.'])->onlyInput('email');
    }

    // ── Customer Register ──
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'phone'    => 'nullable|string|max:20',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
            'role'     => 'customer',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('customer.menu')->with('success', 'Selamat datang di Seoul Serenity! 🎉');
    }

    // ── Staff Login ──
    public function showStaffLogin()
    {
        return view('auth.staff-login');
    }

    public function staffLogin(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();

            if (!in_array($user->role, ['staff', 'owner'])) {
                Auth::logout();
                return back()->withErrors(['email' => 'Akun ini tidak memiliki akses staff.']);
            }

            $request->session()->regenerate();

            return match ($user->role) {
                'owner' => redirect()->route('owner.reports'),
                default => redirect()->route('staff.orders'),
            };
        }

        return back()->withErrors(['email' => 'ID Karyawan atau kata sandi salah.'])->onlyInput('email');
    }

    // ── Logout ──
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
