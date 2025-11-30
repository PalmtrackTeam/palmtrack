<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\BlokLadang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        $blok = BlokLadang::all(); // untuk dropdown blok
        return view('auth.register', compact('blok'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'username'       => 'required|string|max:50|unique:users,username',
            'nama_lengkap'   => 'required|string|max:255',
            'email'          => 'required|string|email|max:255|unique:users,email',
            'password'       => 'required|string|min:8|confirmed',

            // dropdown baru
            'role'           => 'required|in:owner,admin,karyawan',
            'jabatan'        => 'required|in:mandor,asisten_mandor,anggota',
            'id_blok'        => 'required|exists:blok_ladang,id_blok',

            'no_telepon'     => 'nullable|string|max:20',
            'alamat'         => 'nullable|string'
        ]);

        $user = User::create([
            'username'       => $request->username,
            'nama_lengkap'   => $request->nama_lengkap,
            'email'          => $request->email,
            'password'       => Hash::make($request->password),

            'jabatan'        => $request->jabatan, // tidak auto lagi
            'role'           => $request->role,
            'id_blok'        => $request->id_blok,

            'status_aktif'   => true,
            'no_telepon'     => $request->no_telepon,
            'alamat'         => $request->alamat,
            'tanggal_bergabung' => now(),

            // aturan izin (bebas kalau mau ubah)
            'bisa_input_panen' => $request->role === 'karyawan',
            'bisa_input_absen' => $request->role === 'karyawan',
        ]);

        Auth::login($user);

        return $this->redirectByRole($user);
    }


    private function redirectByRole($user)
    {
        switch ($user->role) {
            case 'owner':
                return redirect()->route('owner.dashboard')
                                 ->with('success', 'Akun owner berhasil dibuat!');

            case 'admin':
                return redirect()->route('admin.dashboard')
                                 ->with('success', 'Akun admin berhasil dibuat!');

            case 'karyawan':
                return redirect()->route('karyawan.dashboard')
                                 ->with('success', 'Akun karyawan berhasil dibuat!');
        }

        return redirect()->route('dashboard')->with('success', 'Akun berhasil dibuat!');
    }
}
