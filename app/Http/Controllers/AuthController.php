<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    // Show login form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Process login - PERBAIKAN: gunakan Auth facade dengan benar
    public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
    ]);

    if (Auth::attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();
        $user = Auth::user();

        if (!$user->status_aktif) {
            Auth::logout();
            return back()->withErrors([
                'email' => 'Akun Anda tidak aktif. Hubungi admin.'
            ]);
        }

        return $this->redirectByRole($user);
    }

    return back()->withErrors([
        'email' => 'Email atau password salah.',
    ]);
}


    // Redirect berdasarkan role user
    private function redirectByRole($user)
    {
        switch ($user->role) {
            case 'owner':
                return redirect()->route('owner.laporan-keuangan')->with('success', 'Selamat datang, Owner!');
            case 'admin':
                return redirect()->route('admin.verifikasi-panen')->with('success', 'Selamat datang, Admin!');
            case 'karyawan':
                return redirect()->route('karyawan.input-panen')->with('success', 'Selamat datang, Karyawan!');
            default:
                return redirect()->route('dashboard')->with('success', 'Selamat datang!');
        }
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Anda telah berhasil logout.');
    }
}