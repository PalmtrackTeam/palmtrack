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
     * Tampilkan halaman register
     */
    public function showRegistrationForm()
    {
        // Ambil kategori unik (dekat / jauh)
        $blok = BlokLadang::select('kategori')->groupBy('kategori')->get();

        return view('auth.register', compact('blok'));
    }

    /**
     * Proses register
     */
    public function register(Request $request)
    {
        $request->validate([
            'username'       => 'required|string|max:50|unique:users,username',
            'nama_lengkap'   => 'required|string|max:255',
            'email'          => 'required|string|email|max:255|unique:users,email',
            'password'       => 'required|string|min:8|confirmed',

            // user memilih dekat / jauh
            'id_blok'        => 'required|string|in:dekat,jauh',

            'no_telepon'     => 'nullable|string|max:20',
            'alamat'         => 'nullable|string',
        ]);

        // Pilih salah satu blok berdasarkan kategori
        $blok = BlokLadang::where('kategori', $request->id_blok)
            ->inRandomOrder()
            ->first();

        if (!$blok) {
            return back()->withErrors(['id_blok' => 'Kategori blok tidak ditemukan dalam database!']);
        }

        // Simpan user baru
        $user = User::create([
            'username'       => $request->username,
            'nama_lengkap'   => $request->nama_lengkap,
            'email'          => $request->email,
            'password'       => Hash::make($request->password),

            // otomatis selalu jadi karyawan (register umum)
            'role'           => 'karyawan',

            // isi id_blok dari hasil random blok
            'id_blok'        => $blok->id_blok,

            'status_aktif'   => true,
            'no_telepon'     => $request->no_telepon,
            'alamat'         => $request->alamat,
            'tanggal_bergabung' => now(),

            // akses default karyawan
            'bisa_input_panen' => true,
            'bisa_input_absen' => true,
        ]);

        Auth::login($user);

        return redirect()->route('karyawan.dashboard')
            ->with('success', 'Akun karyawan berhasil dibuat!');
    }
}
