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
        $kategori = BlokLadang::select('kategori')->groupBy('kategori')->get();
        return view('auth.register', compact('kategori'));
    }

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
        // Ambil kategori unik (dekat, jauh)
        $kategori = BlokLadang::select('kategori')->groupBy('kategori')->get();

        return view('auth.register', compact('kategori'));
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

            // Kategori yang dikirim dari form
            'kategori'       => 'required|in:dekat,jauh',

            'no_telepon'     => 'nullable|string|max:20',
            'alamat'         => 'nullable|string',
        ]);

        // Ambil blok pertama berdasarkan kategori (misal dekat → ambil blok A)
        $blok = BlokLadang::where('kategori', $request->kategori)->first();

        if (!$blok) {
            return back()->withErrors(['kategori' => 'Kategori blok tidak ditemukan!']);
        }

        // Simpan user
        $user = User::create([
            'username'       => $request->username,
            'nama_lengkap'   => $request->nama_lengkap,
            'email'          => $request->email,
            'password'       => Hash::make($request->password),

            'role'           => 'karyawan', // otomatis karyawan
            'id_blok'        => $blok->id_blok, // ID blok valid, tidak null

            'status_aktif'   => true,
            'no_telepon'     => $request->no_telepon,
            'alamat'         => $request->alamat,
            'tanggal_bergabung' => now(),

            'bisa_input_panen' => true,
            'bisa_input_absen' => true,
        ]);

        Auth::login($user);

        return redirect()->route('karyawan.dashboard')
            ->with('success', 'Akun karyawan berhasil dibuat!');
    }
}
