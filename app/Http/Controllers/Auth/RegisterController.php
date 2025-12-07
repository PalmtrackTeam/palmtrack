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
    /**
     * Show register form
     */
    public function showRegistrationForm()
    {
        // Ambil semua blok ladang untuk dropdown
        $bloks = BlokLadang::orderBy('nama_blok')->get();

        return view('auth.register', compact('bloks'));
    }

    /**
     * Register process
     */
    public function register(Request $request)
    {
        $request->validate([
            'username'       => 'required|string|max:50|unique:users,username',
            'nama_lengkap'   => 'required|string|max:255',
            'email'          => 'required|string|email|max:255|unique:users,email',
            'password'       => 'required|string|min:8|confirmed',
            'id_blok'        => 'required|exists:blok_ladang,id_blok',
            'no_telepon'     => 'nullable|string|max:20',
            'alamat'         => 'nullable|string',
        ]);

        // Ambil data blok berdasarkan id_blok yang dipilih
        $blok = BlokLadang::findOrFail($request->id_blok);

        // Save user
        $user = User::create([
            'username'       => $request->username,
            'nama_lengkap'   => $request->nama_lengkap,
            'email'          => $request->email,
            'password'       => Hash::make($request->password),

            'role'           => 'karyawan', // otomatis

            // Simpan id_blok yang dipilih user
            'id_blok'        => $blok->id_blok,

            // Simpan kategori dari blok yang dipilih
            'kategori_blok'  => $blok->kategori,

            'status_aktif'   => true,
            'no_telepon'     => $request->no_telepon,
            'alamat'         => $request->alamat,
            'tanggal_bergabung' => now(),

            'bisa_input_panen' => true,
            'bisa_input_absen' => true,
        ]);

        Auth::login($user);

        return redirect()->route('karyawan.dashboard')
            ->with('success', 'Akun karyawan berhasil dibuat! Anda ditempatkan di blok ' . $blok->nama_blok);
    }
}