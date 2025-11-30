<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'username'       => 'required|string|max:50|unique:users,username',
            'nama_lengkap' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role'           => 'required|in:owner,admin,karyawan',
            'no_telepon'     => 'nullable|string|max:20',
            'alamat'         => 'nullable|string',
            'status_tinggal' => 'required|in:barak,keluarga_barak,luar',
        ]);

        // Auto set jabatan berdasarkan role
        $jabatan = $this->getJabatanFromRole($request->role);


        $user = User::create([
            'username'       => $request->username,
            'nama_lengkap'   => $request->nama_lengkap,
            'email'          => $request->email,
            'password'       => Hash::make($request->password),
            'jabatan'        => $jabatan,
            'role'           => $request->role,
            'status_aktif'   => true,
            'no_telepon'     => $request->no_telepon,
            'alamat'         => $request->alamat,
            'tanggal_bergabung' => now(),
            'status_tinggal' => $request->status_tinggal,
            'bisa_input_panen' => $request->role === 'karyawan',
            'bisa_input_absen' => $request->role === 'karyawan',
        ]);

        Auth::login($user);

        return $this->redirectByRole($user);
    }

    private function getJabatanFromRole($role)
    {
        return [
            'owner'    => 'mandor',
            'admin'    => 'mandor',
            'karyawan' => 'anggota'
        ][$role] ?? 'anggota';
    }

   private function redirectByRole($user)
{
    switch ($user->role) {
        case 'owner':
            return redirect()->route('owner.dashboard')->with('success', 'Akun owner berhasil dibuat!');
        case 'admin':
            return redirect()->route('admin.dashboard')->with('success', 'Akun mandor berhasil dibuat!');
        case 'karyawan':
            return redirect()->route('karyawan.dashboard')->with('success', 'Akun karyawan berhasil dibuat!');
    }

    return redirect()->route('dashboard')->with('success', 'Akun berhasil dibuat!');
}

}
