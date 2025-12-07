<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PanenHarian;
use App\Models\Absensi;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\PengeluaranPupuk;
use App\Models\PengeluaranTransportasi;
use App\Models\PengeluaranPerawatan;
use App\Models\PengeluaranGaji;
use App\Models\LaporanMasalah;
use App\Models\BlokLadang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
   public function index()
{
    $today = Carbon::today();
    
    // 1. Dashboard stats dari view
    $dashboardStats = DB::table('v_dashboard_admin')
        ->where('tanggal', $today)
        ->first();

    // 2. Data absensi hari ini (COLLECTION)
    $absensiHariIni = DB::table('absensi')
        ->select('absensi.*', 
            DB::raw('fn_cek_telat(jam_masuk) as status_telat')
        )
        ->whereDate('tanggal', $today)
        ->get();

    // 3. Hitung ringkasan absensi
    $totalHadir = $absensiHariIni->where('status_kehadiran', 'Hadir')->count();
    $totalIzin = $absensiHariIni->where('status_kehadiran', 'Izin')->count();
    $totalSakit = $absensiHariIni->where('status_kehadiran', 'Sakit')->count();
    $totalAlpha = $absensiHariIni->where('status_kehadiran', 'Alpha')->count();
    
    // 4. Karyawan yang belum absen
    $karyawanBelumAbsen = DB::table('users')
        ->where('role', 'karyawan')
        ->where('status_aktif', 1)
        ->whereNotIn('id_user', function($query) use ($today) {
            $query->select('id_user')
                  ->from('absensi')
                  ->whereDate('tanggal', $today);
        })
        ->get();

    // 5. Produktivitas hari ini
    $produktivitasHariIni = DB::table('panen_harian')
        ->select(DB::raw('SUM(jumlah_kg) as total_kg'))
        ->whereDate('tanggal', $today)
        ->where('status_panen', 'diverifikasi')
        ->first();

    // 6. Blok terproduktif
    $blokTerproduktif = DB::table('panen_harian')
        ->join('blok_ladang', 'panen_harian.id_blok', '=', 'blok_ladang.id_blok')
        ->select('blok_ladang.nama_blok', DB::raw('SUM(jumlah_kg) as total_kg'))
        ->whereDate('panen_harian.tanggal', $today)
        ->where('panen_harian.status_panen', 'diverifikasi')
        ->groupBy('panen_harian.id_blok', 'blok_ladang.nama_blok')
        ->orderByDesc('total_kg')
        ->first();

    return view('admin.dashboard', [
        'dashboard_stats' => $dashboardStats,
        'absensi_hari_ini' => $absensiHariIni, // COLLECTION, bukan object
        'karyawan_belum_absen' => $karyawanBelumAbsen,
        'produktivitas_hari_ini' => $produktivitasHariIni, // Object dengan total_kg
        'blok_terproduktif' => $blokTerproduktif,
        // Tambahkan ringkasan absensi sebagai object terpisah
        'ringkasan_absensi' => (object)[
            'total_hadir' => $totalHadir,
            'total_izin' => $totalIzin,
            'total_sakit' => $totalSakit,
            'total_alpha' => $totalAlpha,
        ]
    ]);
}
    public function verifikasiPanen()
    {
        $panenDraft = PanenHarian::with(['user', 'blok'])
            ->where('status_panen', 'draft')
            ->orderBy('tanggal', 'desc')
            ->orderBy('id_panen', 'desc')
            ->get();

        return view('admin.verifikasi-panen', [
            'panen_draft' => $panenDraft
        ]);
    }

    public function prosesVerifikasiPanen(Request $request, $id)
    {
        try {
            $panen = PanenHarian::findOrFail($id);
            
            $request->validate([
                'action' => 'required|in:approve,reject',
                'keterangan' => 'nullable|string|max:500'
            ]);

            if ($request->action === 'approve') {
                $panen->update([
                    'status_panen' => 'diverifikasi',
                    'diverifikasi_oleh' => auth()->id(),
                    'keterangan' => $request->keterangan
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Panen berhasil diverifikasi'
                ]);
            } else {
                $panen->update([
                    'status_panen' => 'draft',
                    'keterangan' => $request->keterangan ?: 'Ditolak - perlu perbaikan'
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Panen ditolak'
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memverifikasi panen: ' . $e->getMessage()
            ], 500);
        }
    }

/**
 * Get data absensi untuk edit
 */
public function editAbsensi($id)
{
    try {
        \Log::info('Mengambil data absensi ID: ' . $id);
        
        $absensi = DB::table('absensi')
            ->join('users', 'absensi.id_user', '=', 'users.id_user')
            ->select('absensi.*', 'users.nama_lengkap')
            ->where('absensi.id_absensi', $id)
            ->first();
        
        \Log::info('Data absensi ditemukan: ' . ($absensi ? 'Ya' : 'Tidak'));
        
        if (!$absensi) {
            \Log::warning('Absensi tidak ditemukan untuk ID: ' . $id);
            return response()->json([
                'success' => false,
                'message' => 'Data absensi tidak ditemukan'
            ], 404);
        }
        
        // Debug log data
        \Log::info('Data absensi:', [
            'id_absensi' => $absensi->id_absensi,
            'nama_lengkap' => $absensi->nama_lengkap,
            'status_kehadiran' => $absensi->status_kehadiran,
            'jam_masuk' => $absensi->jam_masuk,
            'keterangan' => $absensi->keterangan
        ]);
        
        // Format jam_masuk untuk input type="time"
        $jam_masuk_formatted = '';
        if ($absensi->jam_masuk) {
            if (strlen($absensi->jam_masuk) > 5) {
                $jam_masuk_formatted = substr($absensi->jam_masuk, 0, 5);
            } else {
                $jam_masuk_formatted = $absensi->jam_masuk;
            }
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'id_absensi' => $absensi->id_absensi,
                'nama_lengkap' => $absensi->nama_lengkap,
                'status_kehadiran' => $absensi->status_kehadiran,
                'jam_masuk' => $jam_masuk_formatted,
                'keterangan' => $absensi->keterangan
            ]
        ]);
        
    } catch (\Exception $e) {
        \Log::error('Error edit absensi: ' . $e->getMessage());
        \Log::error('Stack trace: ' . $e->getTraceAsString());
        
        return response()->json([
            'success' => false,
            'message' => 'Gagal mengambil data: ' . $e->getMessage(),
            'debug' => 'ID: ' . $id
        ], 500);
    }
}

    /**
     * Update data absensi
     */
    public function updateAbsensi(Request $request, $id)
    {
        try {
            $request->validate([
                'status_kehadiran' => 'required|in:Hadir,Izin,Sakit,Alpha,Libur_Agama',
                'jam_masuk' => 'nullable|date_format:H:i',
                'keterangan' => 'nullable|string|max:500'
            ]);
            
            $absensi = DB::table('absensi')->where('id_absensi', $id)->first();
            
            if (!$absensi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data absensi tidak ditemukan'
                ], 404);
            }
            
            // Hitung keterangan telat jika Hadir
            $keterangan_final = $request->keterangan;
            
            if ($request->status_kehadiran == 'Hadir' && $request->jam_masuk) {
                $jam_standar = '07:00:00';
                $menit_telat = max(0, 
                    (strtotime($request->jam_masuk) - strtotime($jam_standar)) / 60
                );
                
                if ($menit_telat > 0) {
                    $keterangan_final = trim(
                        ($request->keterangan ? $request->keterangan . ' - ' : '') 
                        . "Telat " . round($menit_telat) . " menit"
                    );
                } else {
                    // Hapus keterangan telat jika tidak telat
                    if (strpos($keterangan_final, 'Telat') !== false) {
                        $keterangan_final = str_replace([' - Telat', 'Telat'], '', $keterangan_final);
                        $keterangan_final = trim($keterangan_final, ' -');
                    }
                }
            }
            
            DB::table('absensi')->where('id_absensi', $id)->update([
                'status_kehadiran' => $request->status_kehadiran,
                'jam_masuk' => $request->jam_masuk,
                'keterangan' => $keterangan_final,
                'updated_at' => now()
            ]);
            
            // Log aktivitas
            DB::table('log_aktivitas')->insert([
                'id_user' => auth()->id(),
                'aksi' => 'UPDATE_ABSENSI',
                'tabel_terkait' => 'absensi',
                'deskripsi' => 'Admin mengupdate absensi ID: ' . $id,
                'waktu' => now(),
                'ip_address' => $request->ip()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Absensi berhasil diupdate!'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error update absensi: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate absensi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hapus data absensi
     */
    public function deleteAbsensi(Request $request, $id)
    {
        try {
            $absensi = DB::table('absensi')->where('id_absensi', $id)->first();
            
            if (!$absensi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data absensi tidak ditemukan'
                ], 404);
            }
            
            DB::table('absensi')->where('id_absensi', $id)->delete();
            
            // Log aktivitas
            DB::table('log_aktivitas')->insert([
                'id_user' => auth()->id(),
                'aksi' => 'DELETE_ABSENSI',
                'tabel_terkait' => 'absensi',
                'deskripsi' => 'Admin menghapus absensi ID: ' . $id,
                'waktu' => now(),
                'ip_address' => $request->ip()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Absensi berhasil dihapus!'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error delete absensi: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus absensi: ' . $e->getMessage()
            ], 500);
        }
    }

public function kelolaAbsensi(Request $request)
{
    $selected_tanggal = $request->get('tanggal', Carbon::today()->format('Y-m-d'));
    
    // 1. Query data absensi
    $absensi = DB::table('absensi')
        ->select('absensi.*', 'users.nama_lengkap', 'users.role')
        ->join('users', 'absensi.id_user', '=', 'users.id_user')
        ->where('absensi.tanggal', $selected_tanggal)
        ->orderBy('users.nama_lengkap')
        ->get();
    
    // 2. Query karyawan aktif
    $karyawan_aktif = DB::table('users')
        ->where('role', 'karyawan')
        ->where('status_aktif', 1)
        ->orderBy('nama_lengkap')
        ->get();
    
    // 3. Hitung statistik lengkap
    $statistik = (object)[
        'hadir' => $absensi->where('status_kehadiran', 'Hadir')->count(),
        'izin' => $absensi->where('status_kehadiran', 'Izin')->count(),
        'sakit' => $absensi->where('status_kehadiran', 'Sakit')->count(),
        'alpha' => $absensi->where('status_kehadiran', 'Alpha')->count(),
        'libur' => $absensi->where('status_kehadiran', 'Libur_Agama')->count(),
        'belum_absen' => $karyawan_aktif->count() - $absensi->count(),
        'total_karyawan' => $karyawan_aktif->count(),
        'total_absen_hari_ini' => $absensi->count()
    ];
    
    // 4. Return view dengan semua data
    return view('admin.kelola-absensi', [
        'absensi' => $absensi,
        'karyawan_aktif' => $karyawan_aktif,
        'statistik' => $statistik,
        'selected_tanggal' => $selected_tanggal
    ]);
}

public function inputAbsensi(Request $request)
{
    try {
        // 1. Validasi input
        $request->validate([
            'id_user' => 'required|integer|exists:users,id_user',
            'tanggal' => 'required|date',
            'status_kehadiran' => 'required|string|in:Hadir,Izin,Sakit,Alpha,Libur_Agama',
            'jam_masuk' => 'nullable|date_format:H:i',
            'keterangan' => 'nullable|string|max:500'
        ]);
        
        $today = Carbon::parse($request->tanggal)->format('Y-m-d');
        
        // 2. Cek apakah sudah absen
        $existing = DB::table('absensi')
            ->where('id_user', $request->id_user)
            ->where('tanggal', $today)
            ->first();
            
        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Karyawan sudah absen pada tanggal ini!'
            ]);
        }
        
        // 3. Hitung telat jika Hadir dengan jam masuk
        $keterangan_final = $request->keterangan;
        
        if ($request->status_kehadiran == 'Hadir' && $request->jam_masuk) {
            $jam_standar = '07:00:00';
            $menit_telat = max(0, 
                (strtotime($request->jam_masuk) - strtotime($jam_standar)) / 60
            );
            
            if ($menit_telat > 0) {
                $keterangan_final = trim(
                    ($request->keterangan ? $request->keterangan . ' - ' : '') 
                    . "Telat " . round($menit_telat) . " menit"
                );
            }
        }
        
        // 4. Insert data absensi
        DB::table('absensi')->insert([
            'id_user' => $request->id_user,
            'tanggal' => $today,
            'status_kehadiran' => $request->status_kehadiran,
            'jam_masuk' => $request->jam_masuk,
            'keterangan' => $keterangan_final,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        // 5. Log aktivitas
        DB::table('log_aktivitas')->insert([
            'id_user' => auth()->id(),
            'aksi' => 'INPUT_ABSENSI_ADMIN',
            'tabel_terkait' => 'absensi',
            'deskripsi' => 'Admin input absensi untuk karyawan ID: ' . $request->id_user,
            'waktu' => now(),
            'ip_address' => $request->ip()
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Absensi berhasil disimpan!'
        ]);
        
    } catch (\Exception $e) {
        \Log::error('Error input absensi: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Gagal menyimpan absensi: ' . $e->getMessage()
        ], 500);
    }
}


   public function laporanMasalah()
{
    $laporanMasalah = LaporanMasalah::with(['pelapor', 'penangan'])
        ->join('users as pelapor', 'laporan_masalah.id_user', '=', 'pelapor.id_user')
        ->leftJoin('users as penangan', 'laporan_masalah.ditangani_oleh', '=', 'penangan.id_user')
        ->where('pelapor.role', 'karyawan') // TAMBAH INI - hanya dari karyawan
        ->select('laporan_masalah.*', 'pelapor.nama_lengkap as pelapor_nama', 'penangan.nama_lengkap as penangan_nama')
        ->orderBy('laporan_masalah.tanggal', 'desc')
        ->orderBy('laporan_masalah.status_masalah')
        ->get();

    return view('admin.laporan-masalah', [
        'laporan_masalah' => $laporanMasalah
    ]);
}

    public function teruskanKeOwner(Request $request, $id)
    {
        try {
            $laporan = LaporanMasalah::findOrFail($id);
            
            $laporan->update([
                'diteruskan_ke_owner' => 1,
                'ditandai_oleh' => auth()->id(),
                'status_masalah' => 'dalam_penanganan'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Laporan berhasil diteruskan ke owner'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal meneruskan laporan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function inputPemasukan()
    {
        return view('admin.input-pemasukan');
    }

    public function storePemasukan(Request $request)
    {
        try {
            $validated = $request->validate([
                'tanggal' => 'required|date',
                'sumber_pemasukan' => 'required|in:penjualan_buah,lainnya',
                'total_pemasukan' => 'required|numeric|min:0',
                'keterangan' => 'nullable|string|max:500',
            ]);

            Pemasukan::create([
                'tanggal' => $validated['tanggal'],
                'sumber_pemasukan' => $validated['sumber_pemasukan'],
                'total_pemasukan' => $validated['total_pemasukan'],
                'keterangan' => $validated['keterangan'] ?? null,
                'id_user_pencatat' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pemasukan berhasil dicatat!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mencatat pemasukan: ' . $e->getMessage()
            ], 500);
        }
    }
public function destroy($id)
{
    try {
        $pemasukan = Pemasukan::findOrFail($id);
        $pemasukan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data pemasukan berhasil dihapus.'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ]);
    }
}

    public function showPemasukan($id)
    {
        $pemasukan = Pemasukan::with('pencatat', 'penjualan')->find($id);

        if (!$pemasukan) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        return response()->json([
            'id_pemasukan' => $pemasukan->id_pemasukan,
            'tanggal' => $pemasukan->tanggal,
            'sumber_pemasukan' => $pemasukan->sumber_pemasukan,
            'sumber_pemasukan_text' => $pemasukan->sumber_pemasukan_text,
            'total_pemasukan' => $pemasukan->total_pemasukan,
            'keterangan' => $pemasukan->keterangan,
            'pencatat' => [
                'name' => $pemasukan->pencatat->name ?? 'N/A'
            ],
            'created_at' => $pemasukan->created_at,
            
        ]);
    }
public function deletePengeluaran($id)
{
    try {
        $pengeluaran = Pengeluaran::findOrFail($id);

        // Jika ada relasi yang harus dihapus dulu
        if ($pengeluaran->gaji) {
            $pengeluaran->gaji()->delete();
        }
        if ($pengeluaran->pupuk) {
            $pengeluaran->pupuk()->delete();
        }
        if ($pengeluaran->transportasi) {
            $pengeluaran->transportasi()->delete();
        }
        if ($pengeluaran->perawatan) {
            $pengeluaran->perawatan()->delete();
        }

        $pengeluaran->delete();

        return response()->json(['success' => true, 'message' => 'Data berhasil dihapus']);
    } catch (\Exception $e) {
        \Log::error('Gagal hapus pengeluaran: '.$e->getMessage());
        return response()->json(['success' => false, 'message' => 'Gagal menghapus data'], 500);
    }
}

    public function destroyPemasukan($id)
    {
        try {
            $pemasukan = Pemasukan::findOrFail($id);
            $pemasukan->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data pemasukan berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }
// Di controller admin
public function analyticsDashboard()
{
    // Data dari view dan function
    $trendProduktivitas = DB::select("
        SELECT 
            DATE_FORMAT(tanggal, '%Y-%m') as bulan,
            SUM(jumlah_kg) as total_kg,
            AVG(jumlah_kg) as rata_kg,
            fn_kategori_trend(SUM(jumlah_kg)) as trend
        FROM panen_harian
        WHERE tanggal >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
        GROUP BY DATE_FORMAT(tanggal, '%Y-%m')
        ORDER BY bulan
    ");
    
    $karyawanProblematic = DB::select("
        SELECT 
            u.nama_lengkap,
            fn_hitung_poin_kinerja(u.id_user, 
                DATE_SUB(NOW(), INTERVAL 30 DAY), 
                NOW()
            ) as poin,
            (SELECT COUNT(*) FROM laporan_masalah 
             WHERE id_user = u.id_user 
             AND tanggal >= DATE_SUB(NOW(), INTERVAL 30 DAY)
            ) as jumlah_masalah
        FROM users u
        WHERE u.role = 'karyawan'
        HAVING poin < 50 OR jumlah_masalah > 2
        ORDER BY poin ASC
        LIMIT 5
    ");
}

public function generateReport(Request $request)
{
    $type = $request->type; // 'absensi', 'produktivitas', 'keuangan'
    $period = $request->period; // 'harian', 'mingguan', 'bulanan'
    
    // Panggil stored procedure sesuai jenis laporan
    switch($type) {
        case 'absensi':
            $data = DB::select('CALL sp_report_absensi(?, ?)', 
                [$request->start_date, $request->end_date]);
            break;
            
        case 'produktivitas':
            $data = DB::select('CALL sp_report_produktivitas(?, ?)', 
                [$request->start_date, $request->end_date]);
            break;
            
        case 'keuangan':
            $data = DB::select('CALL sp_report_keuangan(?, ?)', 
                [$request->start_date, $request->end_date]);
            break;
    }
    
    // Format dengan function database
    $formattedData = array_map(function($item) {
        return [
            'nama' => $item->nama,
            'total' => DB::select('SELECT fn_format_rupiah(?) as f', [$item->total])[0]->f,
            'persentase' => $item->persentase . '%',
            'kategori' => DB::select('SELECT fn_kategori_nilai(?) as kategori', [$item->skor])[0]->kategori
        ];
    }, $data);
}
    public function riwayatPemasukan(Request $request)
    {
        $query = Pemasukan::with('pencatat')->latest();
        
        // Filter tanggal
        if ($request->has('tanggal_awal') && $request->tanggal_awal) {
            $query->whereDate('tanggal', '>=', $request->tanggal_awal);
        }
        
        if ($request->has('tanggal_akhir') && $request->tanggal_akhir) {
            $query->whereDate('tanggal', '<=', $request->tanggal_akhir);
        }
        
        // Filter sumber pemasukan
        if ($request->has('sumber_pemasukan') && $request->sumber_pemasukan) {
            $query->where('sumber_pemasukan', $request->sumber_pemasukan);
        }
        
        // Gunakan pagination, jangan get()
        $pemasukan = $query->paginate(10); // 10 item per halaman
        
        return view('admin.riwayat-pemasukan', compact('pemasukan'));
    }

    public function inputLaporanMasalah()
    {
        return view('admin.input-laporan-masalah');
    }

    public function cetakLaporanPemasukan(Request $request)
    {
        $query = Pemasukan::with('pencatat')->latest();
        
        // Filter berdasarkan parameter
        if ($request->has('tanggal_awal') && $request->tanggal_awal) {
            $query->whereDate('tanggal', '>=', $request->tanggal_awal);
        }
        
        if ($request->has('tanggal_akhir') && $request->tanggal_akhir) {
            $query->whereDate('tanggal', '<=', $request->tanggal_akhir);
        }
        
        if ($request->has('sumber_pemasukan') && $request->sumber_pemasukan) {
            $query->where('sumber_pemasukan', $request->sumber_pemasukan);
        }
        
        $pemasukan = $query->get();
        
        // Hitung total
        $totalPemasukan = $pemasukan->sum('total_pemasukan');
        $totalPenjualan = $pemasukan->where('sumber_pemasukan', 'penjualan_buah')->sum('total_pemasukan');
        $totalLainnya = $pemasukan->where('sumber_pemasukan', 'lainnya')->sum('total_pemasukan');
        
        return view('admin.cetak.pemasukan', compact(
            'pemasukan', 
            'totalPemasukan',
            'totalPenjualan', 
            'totalLainnya'
        ));
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

        // Buat laporan masalah dengan status langsung diteruskan ke owner
        LaporanMasalah::create([
            'id_user' => auth()->id(), // ID mandor yang membuat laporan
            'tanggal' => $request->tanggal,
            'jenis_masalah' => $request->jenis_masalah,
            'deskripsi' => $request->deskripsi,
            'tingkat_keparahan' => $request->tingkat_keparahan,
            'status_masalah' => 'dilaporkan',
            'diteruskan_ke_owner' => 1, // Langsung diteruskan ke owner
            'ditandai_oleh' => auth()->id(), // Mandor yang meneruskan

        ]);

        return response()->json([
            'success' => true,
            'message' => 'Laporan masalah berhasil dikirim dan diteruskan ke owner'
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

        return view('admin.riwayat-laporan-masalah', [
            'laporan_masalah' => $laporanMasalah,
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);
    }

    public function tanganiMasalah(Request $request, $id)
    {
        try {
            $laporan = LaporanMasalah::findOrFail($id);
            
            $request->validate([
                'tindakan' => 'required|string|max:1000',
                'status_masalah' => 'required|in:dalam_penanganan,selesai'
            ]);

            $updateData = [
                'tindakan' => $request->tindakan,
                'status_masalah' => $request->status_masalah,
                'ditangani_oleh' => auth()->id()
            ];

            if ($request->status_masalah === 'selesai') {
                $updateData['tanggal_selesai'] = now();
            }

            $laporan->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Laporan masalah berhasil diperbarui'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui laporan: ' . $e->getMessage()
            ], 500);
        }
    }

   public function inputPengeluaran()
{
    $users = User::where('role', 'karyawan')
                ->where('status_aktif', 1)
                ->get(['id_user', 'nama_lengkap', 'email']);
    
    return view('admin.input-pengeluaran', compact('users'));
}

    public function editPengeluaran($id)
    {
        $pengeluaran = Pengeluaran::findOrFail($id);
        return view('admin.edit-pengeluaran', compact('pengeluaran'));
    }

    public function updatePengeluaran(Request $request, $id)
    {
        $pengeluaran = Pengeluaran::findOrFail($id);
        $pengeluaran->update($request->all());
        return redirect()->route('admin.riwayat-pengeluaran')->with('success', 'Pengeluaran berhasil diupdate');
    }

    public function storePupuk(Request $request)
    {
        try {
            $request->validate([
                'tanggal' => 'required|date',
                'jenis_pupuk' => 'required|string|max:100',
                'jumlah' => 'required|numeric|min:0',
                'harga_satuan' => 'required|numeric|min:0',
                'keterangan' => 'nullable|string|max:500'
            ]);

            DB::transaction(function () use ($request) {
                // Buat record pengeluaran utama
                $pengeluaran = Pengeluaran::create([
                    'tanggal' => $request->tanggal,
                    'jenis_pengeluaran' => 'pupuk',
                    'total_biaya' => $request->jumlah * $request->harga_satuan,
                    'keterangan' => $request->keterangan,
                    'id_user_pencatat' => auth()->id()
                ]);

                // Buat record detail pupuk
                PengeluaranPupuk::create([
                    'id_pengeluaran' => $pengeluaran->id_pengeluaran,
                    'jenis_pupuk' => $request->jenis_pupuk,
                    'jumlah' => $request->jumlah,
                    'harga_satuan' => $request->harga_satuan,
                    'total_harga' => $request->jumlah * $request->harga_satuan
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Pengeluaran pupuk berhasil dicatat'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mencatat pengeluaran: ' . $e->getMessage()
            ], 500);
        }
    }

    public function storeTransportasi(Request $request)
    {
        try {
            $request->validate([
                'tanggal' => 'required|date',
                'tujuan' => 'required|string|max:100',
                'biaya' => 'required|numeric|min:0',
                'keterangan' => 'nullable|string|max:500'
            ]);

            DB::transaction(function () use ($request) {
                $pengeluaran = Pengeluaran::create([
                    'tanggal' => $request->tanggal,
                    'jenis_pengeluaran' => 'transportasi',
                    'total_biaya' => $request->biaya,
                    'keterangan' => $request->keterangan,
                    'id_user_pencatat' => auth()->id()
                ]);

                // SIMPAN KE TABEL PENGELUARAN_TRANSPORTASI
                PengeluaranTransportasi::create([
                    'id_pengeluaran' => $pengeluaran->id_pengeluaran,
                    'tujuan' => $request->tujuan,
                    'biaya' => $request->biaya
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Pengeluaran transportasi berhasil dicatat'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mencatat pengeluaran: ' . $e->getMessage()
            ], 500);
        }
    }

    public function storePerawatan(Request $request)
    {
        try {
            $request->validate([
                'tanggal' => 'required|date',
                'jenis_perawatan' => 'required|string|max:100',
                'biaya' => 'required|numeric|min:0',
                'keterangan' => 'nullable|string|max:500'
            ]);

            DB::transaction(function () use ($request) {
                $pengeluaran = Pengeluaran::create([
                    'tanggal' => $request->tanggal,
                    'jenis_pengeluaran' => 'perawatan',
                    'total_biaya' => $request->biaya,
                    'keterangan' => $request->keterangan,
                    'id_user_pencatat' => auth()->id()
                ]);

                // SIMPAN KE TABEL PENGELUARAN_PERAWATAN
                PengeluaranPerawatan::create([
                    'id_pengeluaran' => $pengeluaran->id_pengeluaran,
                    'jenis_perawatan' => $request->jenis_perawatan,
                    'biaya' => $request->biaya
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Pengeluaran perawatan berhasil dicatat'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mencatat pengeluaran: ' . $e->getMessage()
            ], 500);
        }
    }

    // METHOD BARU UNTUK Gaji
// App\Http\Controllers\Admin\DashboardController.php

public function storeGaji(Request $request)
{
    try {
        $request->validate([
            'id_user' => 'required|exists:users,id_user', // HAPUS id_pengeluaran dari sini
            'periode' => 'required|in:Mingguan,Bulanan,Tahunan',
            'total_gaji' => 'required|numeric|min:0',
            'tanggal_generate' => 'required|date',
        ]);

        DB::transaction(function () use ($request) {
            // Dapatkan data karyawan untuk keterangan
            $karyawan = User::find($request->id_user);

            // Buat record pengeluaran utama
            $pengeluaran = Pengeluaran::create([
                'tanggal' => $request->tanggal_generate,
                'jenis_pengeluaran' => 'gaji',
                'total_biaya' => $request->total_gaji,
                'keterangan' => 'Pengeluaran gaji ' . $request->periode . ' - ' . $karyawan->nama_lengkap,
                'id_user_pencatat' => auth()->id()
            ]);

            // Buat record detail gaji - id_pengeluaran akan otomatis dari create di atas
            PengeluaranGaji::create([
                'id_pengeluaran' => $pengeluaran->id_pengeluaran, // INI otomatis dari Pengeluaran::create
                'id_user' => $request->id_user,
                'periode' => $request->periode,
                'total_gaji' => $request->total_gaji,
                'tanggal_generate' => $request->tanggal_generate,
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Pengeluaran gaji berhasil dicatat!'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Gagal mencatat pengeluaran gaji: ' . $e->getMessage()
        ], 500);
    }
}
    public function storeLainnya(Request $request)
    {
        try {
            $request->validate([
                'tanggal' => 'required|date',
                'total_biaya' => 'required|numeric|min:0',
                'keterangan' => 'required|string|max:500'
            ]);

            Pengeluaran::create([
                'tanggal' => $request->tanggal,
                'jenis_pengeluaran' => 'lainnya',
                'total_biaya' => $request->total_biaya,
                'keterangan' => $request->keterangan,
                'id_user_pencatat' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pengeluaran lainnya berhasil dicatat'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mencatat pengeluaran: ' . $e->getMessage()
            ], 500);
        }
    }


public function rekapProduktivitas(Request $request)
    {
        $start_date = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $end_date = $request->get('end_date', Carbon::now()->format('Y-m-d'));

        // 1. PRODUKTIVITAS PER BLOK LADANG - TANPA FILTER STATUS
        $produktivitas_per_blok = DB::table('panen_harian as ph')
            ->join('blok_ladang as bl', 'ph.id_blok', '=', 'bl.id_blok')
            ->select([
                'bl.id_blok',
                'bl.nama_blok',
                DB::raw('COUNT(DISTINCT ph.tanggal) as total_panen'),
                DB::raw('COALESCE(SUM(ph.jumlah_kg), 0) as total_berat'),
                DB::raw('CASE 
                    WHEN COUNT(DISTINCT ph.tanggal) > 0 
                    THEN COALESCE(SUM(ph.jumlah_kg), 0) / COUNT(DISTINCT ph.tanggal)
                    ELSE 0 
                END as rata_per_panen'),
                DB::raw('COALESCE(SUM(ph.total_upah), 0) as total_upah'),
                DB::raw('CASE 
                    WHEN COALESCE(SUM(ph.jumlah_kg), 0) > 0 
                    THEN COALESCE(SUM(ph.total_upah), 0) / COALESCE(SUM(ph.jumlah_kg), 1)
                    ELSE 0 
                END as rata_upah_per_kg'),
                // Tambahan: jenis buah yang dominan
                DB::raw('(
                    SELECT jenis_buah 
                    FROM panen_harian ph2 
                    WHERE ph2.id_blok = bl.id_blok 
                        AND ph2.tanggal BETWEEN ? AND ?
                    GROUP BY jenis_buah 
                    ORDER BY SUM(jumlah_kg) DESC 
                    LIMIT 1
                ) as jenis_buah_dominan')
            ])
            ->addBinding($start_date, 'select')
            ->addBinding($end_date, 'select')
            ->whereBetween('ph.tanggal', [$start_date, $end_date])
            ->groupBy('bl.id_blok', 'bl.nama_blok')
            ->orderBy('total_berat', 'desc')
            ->get();

        // 2. PRODUKTIVITAS KARYAWAN - TANPA FILTER STATUS
        $produktivitas_karyawan = DB::table('panen_harian as ph')
            ->join('users as u', 'ph.id_user', '=', 'u.id_user')
            ->select([
                'u.id_user',
                'u.nama_lengkap',
                'u.role',
                DB::raw('COUNT(DISTINCT ph.tanggal) as hari_kerja'),
                DB::raw('COALESCE(SUM(ph.jumlah_kg), 0) as total_kg'),
                DB::raw('COALESCE(SUM(ph.total_upah), 0) as total_upah'),
                DB::raw('CASE 
                    WHEN COUNT(DISTINCT ph.tanggal) > 0 
                    THEN COALESCE(SUM(ph.jumlah_kg), 0) / COUNT(DISTINCT ph.tanggal)
                    ELSE 0 
                END as rata_perhari'),
                DB::raw('CASE 
                    WHEN COUNT(DISTINCT ph.tanggal) > 0 
                    THEN COALESCE(SUM(ph.total_upah), 0) / COUNT(DISTINCT ph.tanggal)
                    ELSE 0 
                END as rata_upah_per_hari'),
                // Tambahan: persentase buah segar vs gugur
                DB::raw('ROUND(
                    SUM(CASE WHEN ph.jenis_buah = "buah_segar" THEN ph.jumlah_kg ELSE 0 END) * 100.0 / 
                    NULLIF(SUM(ph.jumlah_kg), 0), 
                    1
                ) as persentase_buah_segar')
            ])
            ->where('u.role', 'karyawan')
            ->where('u.status_aktif', 1)
            ->whereBetween('ph.tanggal', [$start_date, $end_date])
            ->groupBy('u.id_user', 'u.nama_lengkap', 'u.role')
            ->orderBy('total_kg', 'desc')
            ->get();

        // 3. TOP PERFORMERS - Berdasarkan total hadir
$top_performers = DB::table('users as u')
    ->leftJoin('absensi as a', function($join) use ($start_date, $end_date) {
        $join->on('u.id_user', '=', 'a.id_user')
             ->whereBetween('a.tanggal', [$start_date, $end_date]);
    })
    ->select([
        'u.id_user',
        'u.nama_lengkap',
        DB::raw('SUM(CASE WHEN a.status_kehadiran = "Hadir" THEN 1 ELSE 0 END) as total_hadir'),
        DB::raw('SUM(CASE WHEN a.status_kehadiran = "Alpha" THEN 1 ELSE 0 END) as total_alpha'),
        DB::raw('COUNT(DISTINCT a.id_absensi) as total_hari_absensi')
    ])
    ->where('u.role', 'karyawan')
    ->where('u.status_aktif', 1)
    ->groupBy('u.id_user', 'u.nama_lengkap')
    ->orderBy('total_hadir', 'desc')   // Ranking berdasarkan hadir
    ->orderBy('total_alpha', 'asc')
    ->limit(5)
    ->get();


        // 4. STATISTIK KESELURUHAN - Semua data tanpa filter status
        $total_berat_kg = $produktivitas_per_blok->sum('total_berat');
        $total_upah_keseluruhan = $produktivitas_per_blok->sum('total_upah');
        $rata_per_panen_keseluruhan = $produktivitas_per_blok->avg('rata_per_panen') ?? 0;
        $jumlah_karyawan_aktif = $produktivitas_karyawan->count();
        
        // Hitung total buah segar vs gugur
        $jenis_buah_stats = DB::table('panen_harian')
            ->select([
                DB::raw('SUM(CASE WHEN jenis_buah = "buah_segar" THEN jumlah_kg ELSE 0 END) as total_buah_segar'),
                DB::raw('SUM(CASE WHEN jenis_buah = "buah_gugur" THEN jumlah_kg ELSE 0 END) as total_buah_gugur'),
                DB::raw('COUNT(DISTINCT id_user) as jumlah_karyawan_total')
            ])
            ->whereBetween('tanggal', [$start_date, $end_date])
            ->first();

        // 5. DATA CHART
        $chart_data = [
            'labels' => $produktivitas_per_blok->pluck('nama_blok')->toArray(),
            'berat' => $produktivitas_per_blok->pluck('total_berat')->toArray(),
            'upah' => $produktivitas_per_blok->pluck('total_upah')->toArray(),
            'jenis_buah' => [
                'segar' => $jenis_buah_stats->total_buah_segar ?? 0,
                'gugur' => $jenis_buah_stats->total_buah_gugur ?? 0
            ]
        ];

        return view('admin.rekap-produktivitas', [
            'produktivitas_per_blok' => $produktivitas_per_blok,
            'produktivitas_karyawan' => $produktivitas_karyawan,
            'top_performers' => $top_performers,
            'chart_data' => $chart_data,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'total_berat_kg' => $total_berat_kg,
            'total_upah_keseluruhan' => $total_upah_keseluruhan,
            'rata_per_panen_keseluruhan' => $rata_per_panen_keseluruhan,
            'jumlah_karyawan_aktif' => $jumlah_karyawan_aktif,
            'jenis_buah_stats' => $jenis_buah_stats
        ]);
    }
    // Fungsi format berat (ton/kg)
    private function formatBerat($beratKg)
    {
        if ($beratKg >= 1000) {
            return number_format($beratKg / 1000, 2) . ' ton';
        }
        return number_format($beratKg, 0) . ' kg';
    }

    // Fungsi format rupiah
    private function formatRupiah($angka)
    {
        return 'Rp ' . number_format($angka, 0, ',', '.');
    }
    public function getDashboardStats()
    {
        try {
            $today = Carbon::today();
            
            $totalKaryawanAktif = User::where('role', 'karyawan')
                ->where('status_aktif', 1)
                ->count();

            $panenHariIni = PanenHarian::where('tanggal', $today)
                ->where('status_panen', 'diverifikasi')
                ->sum('jumlah_kg');

            $absensiHariIni = Absensi::where('tanggal', $today)
                ->where('status_kehadiran', 'Hadir')
                ->count();

            $panenPerluVerifikasi = PanenHarian::where('status_panen', 'draft')
                ->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'total_karyawan_aktif' => $totalKaryawanAktif,
                    'panen_hari_ini' => $panenHariIni,
                    'absensi_hari_ini' => $absensiHariIni,
                    'panen_perlu_verifikasi' => $panenPerluVerifikasi,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data statistik'
            ], 500);
        }
    }

    // =================================================================
    // METHOD UNTUK RIWAYAT PENGELUARAN
    // =================================================================

    public function riwayatPengeluaran(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $jenis = $request->get('jenis', 'semua');

        $pengeluaranQuery = Pengeluaran::with(['pencatat'])
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->where('id_user_pencatat', auth()->id());

        if ($jenis !== 'semua') {
            $pengeluaranQuery->where('jenis_pengeluaran', $jenis);
        }

        $pengeluaran = $pengeluaranQuery->orderBy('tanggal', 'desc')
            ->orderBy('id_pengeluaran', 'desc')
            ->paginate(10);

        $summary = $this->getPengeluaranSummary($startDate, $endDate);

        return view('admin.riwayat-pengeluaran', [
            'pengeluaran' => $pengeluaran,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'jenis_filter' => $jenis,
            'summary' => $summary
        ]);
    }

  public function getDetailPengeluaran($id)
{
    try {
        $pengeluaran = Pengeluaran::with([
            'pencatat',
            'pupuk',
            'transportasi',
            'perawatan',
            'gaji' // PASTIKAN relasi karyawan
        ])->where('id_pengeluaran', $id)
          ->where('id_user_pencatat', auth()->id())
          ->first();

        if (!$pengeluaran) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        // Format data berdasarkan jenis pengeluaran
        $detailData = [
            'id_pengeluaran' => $pengeluaran->id_pengeluaran,
            'tanggal' => $pengeluaran->tanggal,
            'jenis_pengeluaran' => $pengeluaran->jenis_pengeluaran,
            'total_biaya' => $pengeluaran->total_biaya,
            'keterangan' => $pengeluaran->keterangan,
            'pencatat' => $pengeluaran->pencatat->nama_lengkap ?? '-',
            'detail' => null
        ];

        // Tambahkan detail berdasarkan jenis
        switch ($pengeluaran->jenis_pengeluaran) {
            case 'pupuk':
                if ($pengeluaran->pupuk) {
                    $detailData['detail'] = [
                        'jenis_pupuk' => $pengeluaran->pupuk->jenis_pupuk,
                        'jumlah' => $pengeluaran->pupuk->jumlah,
                        'harga_satuan' => $pengeluaran->pupuk->harga_satuan,
                        'total_harga' => $pengeluaran->pupuk->total_harga
                    ];
                }
                break;

            case 'transportasi':
                if ($pengeluaran->transportasi) {
                    $detailData['detail'] = [
                        'tujuan' => $pengeluaran->transportasi->tujuan,
                        'biaya' => $pengeluaran->transportasi->biaya
                    ];
                }
                break;

            case 'perawatan':
                if ($pengeluaran->perawatan) {
                    $detailData['detail'] = [
                        'jenis_perawatan' => $pengeluaran->perawatan->jenis_perawatan,
                        'biaya' => $pengeluaran->perawatan->biaya
                    ];
                }
                break;

            case 'gaji':
                if ($pengeluaran->gaji) {
                    $detailData['detail'] = [
                        'karyawan' => $pengeluaran->gaji->karyawan->nama_lengkap ?? '-',
                        'periode' => $pengeluaran->gaji->periode,
                        'total_gaji' => $pengeluaran->gaji->total_gaji,
                        'tanggal_generate' => $pengeluaran->gaji->tanggal_generate
                    ];
                }
                break;
        }

        return response()->json([
            'success' => true,
            'data' => $detailData
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Gagal memuat detail: ' . $e->getMessage()
        ], 500);
    }
}

    public function exportPengeluaran(Request $request)
    {
        try {
            $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
            $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
            $jenis = $request->get('jenis', 'semua');

            $pengeluaran = Pengeluaran::with(['pencatat', 'pupuk'])
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->where('id_user_pencatat', auth()->id());

            if ($jenis !== 'semua') {
                $pengeluaran->where('jenis_pengeluaran', $jenis);
            }

            $data = $pengeluaran->orderBy('tanggal', 'desc')
                ->orderBy('id_pengeluaran', 'desc')
                ->get();

            // Format data untuk export
            $exportData = $data->map(function ($item) {
                $row = [
                    'Tanggal' => $item->tanggal,
                    'Jenis Pengeluaran' => $this->formatJenisPengeluaran($item->jenis_pengeluaran),
                    'Total Biaya' => 'Rp ' . number_format((float)($item->total_biaya ?? 0), 0, ',', '.'),
                    'Keterangan' => $item->keterangan,
                    'Pencatat' => $item->pencatat->nama_lengkap ?? '-'
                ];

                // Tambahkan kolom khusus berdasarkan jenis
                if ($item->jenis_pengeluaran === 'pupuk' && $item->pupuk) {
                    $row['Jenis Pupuk'] = $item->pupuk->jenis_pupuk;
                    $row['Jumlah'] = $item->pupuk->jumlah . ' kg';
                    $row['Harga Satuan'] = 'Rp ' . number_format((float)($item->pupuk->harga_satuan ?? 0), 0, ',', '.');
                }

                return $row;
            });

            return response()->json([
                'success' => true,
                'data' => $exportData,
                'filename' => 'pengeluaran_' . $startDate . '_to_' . $endDate . '.xlsx'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal export data: ' . $e->getMessage()
            ], 500);
        }
    }

    // =================================================================
    // HELPER METHODS
    // =================================================================

    private function getPengeluaranSummary($startDate = null, $endDate = null)
    {
        $query = Pengeluaran::where('id_user_pencatat', auth()->id());

        if ($startDate && $endDate) {
            $query->whereBetween('tanggal', [$startDate, $endDate]);
        } else {
            // Default bulan ini
            $firstDay = date('Y-m-01');
            $lastDay = date('Y-m-t');
            $query->whereBetween('tanggal', [$firstDay, $lastDay]);
        }

        $results = $query->select(
            DB::raw('COALESCE(SUM(total_biaya), 0) as total'),
            DB::raw('COALESCE(SUM(CASE WHEN jenis_pengeluaran = "pupuk" THEN total_biaya ELSE 0 END), 0) as pupuk'),
            DB::raw('COALESCE(SUM(CASE WHEN jenis_pengeluaran = "transportasi" THEN total_biaya ELSE 0 END), 0) as transportasi'),
            DB::raw('COALESCE(SUM(CASE WHEN jenis_pengeluaran = "perawatan" THEN total_biaya ELSE 0 END), 0) as perawatan'),
            DB::raw('COALESCE(SUM(CASE WHEN jenis_pengeluaran = "gaji" THEN total_biaya ELSE 0 END), 0) as gaji'),
            DB::raw('COALESCE(SUM(CASE WHEN jenis_pengeluaran = "lainnya" THEN total_biaya ELSE 0 END), 0) as lainnya')
        )->first();

        return [
            'total' => $results->total,
            'pupuk' => $results->pupuk,
            'transportasi' => $results->transportasi,
            'perawatan' => $results->perawatan,
            'gaji' => $results->gaji,
            'lainnya' => $results->lainnya
        ];
    }
/**
 * Method untuk mendapatkan detail pengeluaran (dipanggil dari JavaScript)
 */
public function getDetail($id)
{
    try {
        $pengeluaran = Pengeluaran::with([
            'pencatat',
            'pupuk',
            'transportasi', 
            'perawatan',
            'gaji.karyawan'
        ])->where('id_pengeluaran', $id)
          ->where('id_user_pencatat', auth()->id())
          ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $pengeluaran,
            'message' => 'Data detail berhasil diambil'
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Data tidak ditemukan',
            'error' => $e->getMessage()
        ], 404);
    }
}

/**
 * Method untuk edit pengeluaran
 */
public function edit($id)
{
    try {
        $pengeluaran = Pengeluaran::with([
            'pupuk',
            'transportasi',
            'perawatan',
            'gaji'
        ])->where('id_pengeluaran', $id)
          ->where('id_user_pencatat', auth()->id())
          ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $pengeluaran
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Data tidak ditemukan'
        ], 404);
    }
}

/**
 * Method untuk update pengeluaran
 */
public function update(Request $request, $id)
{
    try {
        $pengeluaran = Pengeluaran::where('id_pengeluaran', $id)
            ->where('id_user_pencatat', auth()->id())
            ->firstOrFail();

        DB::transaction(function () use ($request, $pengeluaran) {
            // Update data utama
            $pengeluaran->update([
                'tanggal' => $request->tanggal,
                'total_biaya' => $request->total_biaya,
                'keterangan' => $request->keterangan
            ]);

            // Update data detail berdasarkan jenis
            switch ($pengeluaran->jenis_pengeluaran) {
                case 'pupuk':
                    if ($pengeluaran->pupuk) {
                        $pengeluaran->pupuk->update([
                            'jenis_pupuk' => $request->jenis_pupuk,
                            'jumlah' => $request->jumlah,
                            'harga_satuan' => $request->harga_satuan,
                            'total_harga' => $request->jumlah * $request->harga_satuan
                        ]);
                    }
                    break;

                case 'transportasi':
                    if ($pengeluaran->transportasi) {
                        $pengeluaran->transportasi->update([
                            'tujuan' => $request->tujuan,
                            'biaya' => $request->biaya
                        ]);
                    }
                    break;

                case 'perawatan':
                    if ($pengeluaran->perawatan) {
                        $pengeluaran->perawatan->update([
                            'jenis_perawatan' => $request->jenis_perawatan,
                            'biaya' => $request->biaya
                        ]);
                    }
                    break;

                case 'gaji':
                    if ($pengeluaran->gaji) {
                        $pengeluaran->gaji->update([
                            'periode' => $request->periode,
                            'total_gaji' => $request->total_gaji
                        ]);
                    }
                    break;
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Pengeluaran berhasil diupdate'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Gagal mengupdate pengeluaran: ' . $e->getMessage()
        ], 500);
    }
}
    private function formatJenisPengeluaran($jenis)
    {
        $jenisMap = [
            'pupuk' => 'Pupuk',
            'transportasi' => 'Transportasi',
            'perawatan' => 'Perawatan',
            'gaji' => 'Gaji',
            'lainnya' => 'Lainnya'
        ];

        return $jenisMap[$jenis] ?? $jenis;
    }
}