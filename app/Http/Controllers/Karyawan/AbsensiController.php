<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    // Tampilkan form absensi
    public function create()
    {
        $absenHariIni = DB::table('absensi')
            ->where('id_user', Auth::id())
            ->whereDate('tanggal', Carbon::today())
            ->first();

        $bulanIni = Carbon::now()->format('Y-m');
        $statistikBulanIni = DB::table('v_rekap_absensi')
            ->where('id_user', Auth::id())
            ->where('bulan', $bulanIni)
            ->first();

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
        $request->validate([
            'status_kehadiran' => 'required|in:Hadir,Izin,Sakit,Alpha,Libur_Agama',
            'jam_masuk' => 'required_if:status_kehadiran,Hadir|nullable|date_format:H:i',
            'keterangan' => 'nullable|string|max:255'
        ]);

        try {
            DB::statement('CALL sp_proses_absen(?, ?, ?, ?)', [
                Auth::id(),
                $request->status_kehadiran,
                $request->jam_masuk,
                $request->keterangan
            ]);

            // INSERT ke tabel log_aktivitas (sesuai struktur DB kamu)
            DB::table('log_aktivitas')->insert([
                'id_user'     => Auth::id(),
                'aksi'        => 'INPUT_ABSENSI', // lebih deskriptif
                'tabel_terkait' => 'absensi',
                'deskripsi'   => 'Melakukan absensi: ' . $request->status_kehadiran,
                'ip_address'  => $request->ip(),
                'waktu'       => now(), // kolom nama 'waktu' di dump SQL kamu
            ]);

            return redirect()->route('karyawan.absensi')
                             ->with('success', 'Absensi berhasil dicatat!');
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();

            if (str_contains($errorMessage, 'User sudah absen hari ini')) {
                return redirect()->back()->with('error', 'Anda sudah melakukan absensi hari ini.');
            }

            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $errorMessage);
        }
    }

    // Riwayat absensi
    public function riwayat()
    {
        $riwayatAbsensi = DB::table('absensi')
            ->select(
                'absensi.*',
                DB::raw('fn_cek_telat(jam_masuk) as status_telat'),
                DB::raw('fn_hitung_menit_telat(jam_masuk) as menit_telat'),
                DB::raw('DAYNAME(tanggal) as hari')
            )
            ->where('id_user', Auth::id())
            ->orderBy('tanggal', 'desc')
            ->paginate(20);

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

    // Download rekap absensi
    public function downloadRekap()
    {
        $user = Auth::user();
        $bulan = request('bulan', Carbon::now()->format('m'));
        $tahun = request('tahun', Carbon::now()->format('Y'));

        $rekap = DB::table('v_rekap_absensi')
            ->where('id_user', $user->id)
            ->where('bulan', $tahun . '-' . str_pad($bulan, 2, '0', STR_PAD_LEFT))
            ->first();

        $data = [
            'nama' => $user->nama_lengkap,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'rekap' => $rekap
        ];

        return response()->json($data);
    }

    // API cek status absensi
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
            'bisa_absen'  => $bisaAbsen,
            'data_absen'  => $absenHariIni,
            'jam_sekarang'=> $jamSekarang,
            'batas_absen' => '07:00'
        ]);
    }
}
