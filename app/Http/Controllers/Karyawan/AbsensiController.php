<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Tambahkan ini
use Carbon\Carbon; // Tambahkan ini

class AbsensiController extends Controller
{
    // Tampilkan form absensi
    public function create()
    {
        // Cek apakah sudah absen hari ini - LEBIH EFISIEN
        $absenHariIni = DB::table('absensi')
            ->where('id_user', Auth::id())
            ->whereDate('tanggal', Carbon::today())
            ->first();

        // FITUR BARU: Dapatkan statistik bulan ini
        $bulanIni = Carbon::now()->format('Y-m');
        $statistikBulanIni = DB::table('v_rekap_absensi')
            ->where('id_user', Auth::id())
            ->where('bulan', $bulanIni)
            ->first();

        // FITUR BARU: Cek apakah ada notifikasi terkait absensi
        $notifikasiAbsensi = DB::table('notifikasi')
            ->where('id_user', Auth::id())
            ->where('dibaca', 0)
            ->where('judul', 'like', '%Absensi%')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        return view('karyawan.absensi', compact(
            'absenHariIni', 
            'statistikBulanIni',
            'notifikasiAbsensi'
        ));
    }

    // Simpan data absensi - VERSI BARU DENGAN SP
    public function store(Request $request)
    {
        // Validasi tetap sama
        $request->validate([
            'status_kehadiran' => 'required|in:Hadir,Izin,Sakit,Alpha,Libur_Agama',
            'jam_masuk' => 'required_if:status_kehadiran,Hadir|nullable|date_format:H:i',
            'keterangan' => 'nullable|string|max:255'
        ]);

        try {
            // PANGGIL STORED PROCEDURE BARU! 🎯
            DB::statement('CALL sp_proses_absen(?, ?, ?, ?)', [
                Auth::id(),
                $request->status_kehadiran,
                $request->jam_masuk,
                $request->keterangan
            ]);

            // Log tambahan di Laravel jika perlu
            activity()
                ->causedBy(Auth::user())
                ->log('Melakukan absensi: ' . $request->status_kehadiran);

            return redirect()->route('karyawan.absensi')
                            ->with('success', 'Absensi berhasil dicatat!');

        } catch (\Exception $e) {
            // Tangkap error dari database (termasuk trigger error)
            $errorMessage = $e->getMessage();
            
            // User-friendly messages
            if (str_contains($errorMessage, 'User sudah absen hari ini')) {
                return redirect()->back()->with('error', 'Anda sudah melakukan absensi hari ini.');
            }
            
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $errorMessage);
        }
    }

    // Riwayat absensi - VERSI LEBIH KAYA
    public function riwayat()
    {
        $riwayatAbsensi = DB::table('absensi')
            ->select('absensi.*', 
                // TAMBAHKAN FUNCTION DATABASE
                DB::raw('fn_cek_telat(jam_masuk) as status_telat'),
                DB::raw('fn_hitung_menit_telat(jam_masuk) as menit_telat'),
                DB::raw('DAYNAME(tanggal) as hari')
            )
            ->where('id_user', Auth::id())
            ->orderBy('tanggal', 'desc')
            ->paginate(20); // Pagination untuk performa

        // FITUR BARU: Statistik keseluruhan
        $statistikTotal = DB::select("
            SELECT 
                COUNT(*) as total_hari,
                SUM(CASE WHEN status_kehadiran = 'Hadir' THEN 1 ELSE 0 END) as total_hadir,
                SUM(CASE WHEN status_kehadiran = 'Izin' THEN 1 ELSE 0 END) as total_izin,
                SUM(CASE WHEN status_kehadiran = 'Sakit' THEN 1 ELSE 0 END) as total_sakit,
                AVG(fn_hitung_menit_telat(jam_masuk)) as rata_telat
            FROM absensi 
            WHERE id_user = ?
        ", [Auth::id()]);

        // FITUR BARU: Data untuk chart
        $chartData = DB::select("
            SELECT 
                DATE_FORMAT(tanggal, '%Y-%m') as bulan,
                COUNT(*) as total,
                SUM(CASE WHEN status_kehadiran = 'Hadir' THEN 1 ELSE 0 END) as hadir
            FROM absensi 
            WHERE id_user = ?
            GROUP BY DATE_FORMAT(tanggal, '%Y-%m')
            ORDER BY bulan DESC
            LIMIT 6
        ", [Auth::id()]);

        return view('karyawan.riwayat-absensi', compact(
            'riwayatAbsensi', 
            'statistikTotal',
            'chartData'
        ));
    }

    // FITUR BARU: Download rekap absensi
    public function downloadRekap()
    {
        $user = Auth::user();
        $bulan = request('bulan', Carbon::now()->format('m'));
        $tahun = request('tahun', Carbon::now()->format('Y'));
        
        // PANGGIL VIEW YANG SUDAH ADA
        $rekap = DB::table('v_rekap_absensi')
            ->where('id_user', $user->id)
            ->where('bulan', $tahun . '-' . str_pad($bulan, 2, '0', STR_PAD_LEFT))
            ->first();

        // Generate PDF atau Excel (bisa menggunakan package)
        // Contoh sederhana:
        $data = [
            'nama' => $user->nama_lengkap,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'rekap' => $rekap
        ];

        // Return view untuk PDF atau response JSON
        return response()->json($data);
    }

    // FITUR BARU: Cek apakah bisa absen (untuk mobile API)
    public function cekStatusAbsen()
    {
        $today = Carbon::today()->format('Y-m-d');
        
        $absenHariIni = DB::table('absensi')
            ->where('id_user', Auth::id())
            ->whereDate('tanggal', $today)
            ->first();

        $jamSekarang = Carbon::now()->format('H:i');
        $bisaAbsen = !$absenHariIni;

        return response()->json([
            'sudah_absen' => !!$absenHariIni,
            'bisa_absen' => $bisaAbsen,
            'data_absen' => $absenHariIni,
            'jam_sekarang' => $jamSekarang,
            'batas_absen' => '07:00' // Dari function database nanti
        ]);
    }
}