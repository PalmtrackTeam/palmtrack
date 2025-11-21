<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Absensi;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // ROLE: Karyawan → ngeliay semua karyawan, tapi hanya bisa absen diri sendiri wak
        if ($user->role === 'karyawan') {
            $karyawan = User::where('role', 'karyawan')->get();
        }
        // ROLE: Mandor → semua mandor + karyawan
        elseif ($user->role === 'mandor') {
            $karyawan = User::whereIn('role', ['karyawan', 'mandor'])->get();
        }
        // ROLE: Owner → semua kecuali dirinya sendiri
        else {
            $karyawan = User::where('id', '!=', $user->id)->get();
        }

        return view('absensi.index', compact('karyawan', 'user'));
    }

    public function store(Request $request)
    {
        $tanggal = $request->tanggal;

        foreach ($request->absensi ?? [] as $id_user => $status) {

            Absensi::updateOrCreate(
                [
                    'id_user' => $id_user,
                    'tanggal' => $tanggal
                ],
                [
                    'status_kehadiran' => $status,
                    'keterangan' => $request->keterangan[$id_user] ?? null
                ]
            );
        }

        return back()->with('status', 'Absensi berhasil disimpan!');
    }
}
