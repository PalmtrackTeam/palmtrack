<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PanenHarian;
use App\Models\Absensi;
use App\Models\Pemasukan; // PERBAIKI: Hapus "Karyawan\\"
use App\Models\LaporanMasalah; // PERBAIKI: Hapus "Karyawan\\"
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; // PERBAIKI: Hapus "Karyawan\\"

class DashboardController extends Controller
{
    public function index()
{
    $user = auth()->user();
    $today = today();

    $status_absen_hari_ini = Absensi::where('id_user', $user->id_user)
                                ->whereDate('tanggal', $today)
                                ->first();

    $panen_hari_ini = PanenHarian::where('id_user', $user->id_user)
                            ->whereDate('tanggal', $today)
                            ->sum('jumlah_kg');

    $total_panen_bulan_ini = PanenHarian::where('id_user', $user->id_user)
                                ->whereMonth('tanggal', $today->month)
                                ->whereYear('tanggal', $today->year)
                                ->sum('jumlah_kg');

    // Tambahkan laporan terbaru
    $laporan_terbaru = LaporanMasalah::where('id_user', $user->id_user)
                        ->orderBy('tanggal', 'desc')
                        ->orderBy('id_masalah', 'desc')
                        ->take(5)
                        ->get();

    return view('karyawan.dashboard', compact(
        'panen_hari_ini',
        'status_absen_hari_ini',
        'total_panen_bulan_ini',
        'laporan_terbaru'
    ));
}


    // Method untuk input pemasukan
    public function inputPemasukan()
    {
        return view('karyawan.input-pemasukan');
    }

    public function storePemasukan(Request $request)
    {
        try {
            $request->validate([
                'tanggal' => 'required|date',
                'sumber_pemasukan' => 'required|in:penjualan_buah,lainnya',
                'total_pemasukan' => 'required|numeric|min:0',
                'keterangan' => 'required|string|max:500'
            ]);

            Pemasukan::create([
                'tanggal' => $request->tanggal,
                'sumber_pemasukan' => $request->sumber_pemasukan,
                'total_pemasukan' => $request->total_pemasukan,
                'keterangan' => $request->keterangan,
                'id_user_pencatat' => auth()->id(),
                'status_verifikasi' => false
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pemasukan berhasil dicatat dan menunggu verifikasi owner'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mencatat pemasukan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function riwayatPemasukan(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $pemasukan = Pemasukan::with(['pencatat'])
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->where('id_user_pencatat', auth()->id())
            ->orderBy('tanggal', 'desc')
            ->orderBy('id_pemasukan', 'desc')
            ->get();

        return view('karyawan.riwayat-pemasukan', [
            'pemasukan' => $pemasukan,
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);
    }

    // Method untuk laporan masalah
  public function inputLaporanMasalah()
{
$laporan_terbaru = LaporanMasalah::where('id_user', auth()->id())
->orderBy('tanggal', 'desc')
->orderBy('id_masalah', 'desc')
->take(5)
->get();

$status_absen_hari_ini = Absensi::where('id_user', auth()->id())
                            ->whereDate('tanggal', today())
                            ->first();

return view('karyawan.input-laporan-masalah', [
    'laporan_terbaru' => $laporan_terbaru,
    'status_absen_hari_ini' => $status_absen_hari_ini
]);


}

    public function storeLaporanMasalah(Request $request)
    {
        try {
            $request->validate([
                'tanggal' => 'required|date',
                'jenis_masalah' => 'required|in:Cuaca Buruk,Kemalingan,Serangan Hama,Kerusakan Alat,Lainnya',
                'deskripsi' => 'required|string|max:1000',
                'tingkat_keparahan' => 'required|in:ringan,sedang,berat'
            ]);

            LaporanMasalah::create([
                'id_user' => auth()->id(),
                'tanggal' => $request->tanggal,
                'jenis_masalah' => $request->jenis_masalah,
                'deskripsi' => $request->deskripsi,
                'tingkat_keparahan' => $request->tingkat_keparahan,
                'status_masalah' => 'dilaporkan',
               
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Laporan masalah berhasil dikirim dan menunggu verifikasi owner'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim laporan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function riwayatLaporanMasalah(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $laporanMasalah = LaporanMasalah::with(['pelapor'])
            ->where('id_user', auth()->id())
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->orderBy('tanggal', 'desc')
            ->orderBy('id_masalah', 'desc')
            ->get();

        return view('karyawan.riwayat-laporan-masalah', [
            'laporan_masalah' => $laporanMasalah,
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);
    }

    // Method untuk absensi (jika belum ada)
    public function absensi()
    {
        $today = today();
        $absensiHariIni = Absensi::where('id_user', auth()->id())
            ->whereDate('tanggal', $today)
            ->first();

        return view('karyawan.absensi', [
            'absensi_hari_ini' => $absensiHariIni
        ]);
    }
}