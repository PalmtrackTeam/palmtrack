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
        // hanya ambil kategori unik: dekat / jauh
        $kategori = BlokLadang::select('kategori')->groupBy('kategori')->get();

        return view('auth.register', compact('kategori'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'username'     => 'required|string|max:50|unique:users,username',
            'nama_lengkap' => 'required|string|max:255',
            'email'        => 'required|string|email|max:255|unique:users,email',
            'password'     => 'required|string|min:8|confirmed',
            'kategori'     => 'required|in:dekat,jauh',
        ]);

        // Cari blok pertama berdasarkan kategori
        $blok = BlokLadang::where('kategori', $request->kategori)->first();

        if (!$blok) {
            return back()->withErrors(['kategori' => 'Kategori blok tidak ditemukan!']);
        }

        $user = User::create([
            'username'     => $request->username,
            'nama_lengkap' => $request->nama_lengkap,
            'email'        => $request->email,
            'password'     => Hash::make($request->password),

            // fixed → simpan id angka!
            'id_blok'      => $blok->id_blok,

            'role'         => 'karyawan',
            'status_aktif' => true,
            'no_telepon'   => $request->no_telepon,
            'alamat'       => $request->alamat,
            'tanggal_bergabung' => now(),

            'bisa_input_panen' => true,
            'bisa_input_absen' => true,
        ]);

        Auth::login($user);

        return redirect()->route('karyawan.dashboard')
            ->with('success', 'Akun karyawan berhasil dibuat!');
    }
}
