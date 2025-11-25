<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\PanenHarian; 
use App\Models\Absensi;
use App\Models\LaporanMasalah;

class DashboardController extends Controller
{
    public function index()
{
    $user = Auth::user();

    if ($user->role === 'owner') {
        return redirect()->route('owner.dashboard');
    }

    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    if ($user->role === 'karyawan') {
        return redirect()->route('karyawan.dashboard');
    }

    abort(403, 'Role tidak dikenali');
}

    
    // GANTI MENJADI PUBLIC
   public function dashboardOwner()
{
    $data = [
        'total_karyawan' => User::where('status_aktif', true)->where('role', 'karyawan')->count(),
        'total_admin' => User::where('status_aktif', true)->where('role', 'admin')->count(),
        'panen_bulan_ini' => PanenHarian::whereMonth('tanggal', now()->month)
                                      ->whereYear('tanggal', now()->year)
                                      ->sum('jumlah_kg'),
        'penjualan_bulan_ini' => 0,
    ];

    return view('owner.dashboard', $data); // ← FIX
}

public function dashboardAdmin()
{
    $data = [
        'panen_perlu_verifikasi' => PanenHarian::where('status_panen', 'draft')->count(),
        'laporan_masalah_baru' => LaporanMasalah::where('status_masalah', 'dilaporkan')->count(),
        'karyawan_hadir_hari_ini' => Absensi::whereDate('tanggal', today())
                                            ->where('status_kehadiran', 'Hadir')
                                            ->count(),
    ];

    return view('admin.dashboard', $data); // ← FIX
}

public function dashboardKaryawan()
{
    $user = Auth::user();
    $today = today();

    $data = [
        'panen_hari_ini' => PanenHarian::where('id_user', $user->id_user)
                                  ->whereDate('tanggal', $today)
                                  ->sum('jumlah_kg'),
        'status_absen_hari_ini' => Absensi::where('id_user', $user->id_user)
                                     ->whereDate('tanggal', $today)
                                     ->first(),
        'total_panen_bulan_ini' => PanenHarian::where('id_user', $user->id_user)
                                         ->whereMonth('tanggal', $today->month)
                                         ->whereYear('tanggal', $today->year)
                                         ->sum('jumlah_kg'),
    ];

    return view('karyawan.dashboard', $data);
}

    // Method untuk routes lainnya
    public function laporanKeuangan()
    {
        return view('owner.laporan-keuangan');
    }
    
    public function verifikasiPanen()
    {
        return view('admin.verifikasi-panen');
    }
    
    public function inputPanen()
    {
        return view('karyawan.input-panen');
    }
}