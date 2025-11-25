<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller
{
    // Tampilkan form absensi
    public function create()
    {
        // Cek apakah sudah absen hari ini
        $absenHariIni = Absensi::where('id_user', Auth::id())
                              ->whereDate('tanggal', today())
                              ->first();

        return view('karyawan.absensi', compact('absenHariIni'));
    }

    // Simpan data absensi
    public function store(Request $request)
    {
        // Cek apakah sudah absen hari ini
        $absenHariIni = Absensi::where('id_user', Auth::id())
                              ->whereDate('tanggal', today())
                              ->first();

        if ($absenHariIni) {
            return redirect()->back()->with('error', 'Anda sudah melakukan absensi hari ini.');
        }

        $request->validate([
            'status_kehadiran' => 'required|in:Hadir,Izin,Sakit,Alpha,Libur_Agama',
            'jam_masuk' => 'required_if:status_kehadiran,Hadir|nullable|date_format:H:i',
            'keterangan' => 'nullable|string|max:255'
        ]);

        Absensi::create([
            'id_user' => Auth::id(),
            'tanggal' => today(),
            'status_kehadiran' => $request->status_kehadiran,
            'jam_masuk' => $request->jam_masuk,
            'keterangan' => $request->keterangan
        ]);

        return redirect()->route('karyawan.absensi')
                        ->with('success', 'Absensi berhasil dicatat!');
    }

    // Riwayat absensi
    public function riwayat()
    {
        $riwayatAbsensi = Absensi::where('id_user', Auth::id())
                                ->orderBy('tanggal', 'desc')
                                ->get();

        return view('karyawan.riwayat-absensi', compact('riwayatAbsensi'));
    }
}