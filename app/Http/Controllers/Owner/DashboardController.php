<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PanenHarian;
use App\Models\Absensi;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\Penjualan;
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
    // Ambil data dari view (satu baris)
    $data = DB::table('v_dashboard_owner')->first();

    // Jika chart 6 bulan terakhir sudah disiapkan dalam view sebagai JSON
    $bulanLabels = json_decode($data->bulan_labels ?? '[]', true);
    $pemasukanData = json_decode($data->pemasukan_data ?? '[]', true);
    $pengeluaranData = json_decode($data->pengeluaran_data ?? '[]', true);

    // Data aktivitas terbaru tetap bisa dibuat manual
    $aktivitasTerbaru = [
        [
            'icon' => 'fas fa-seedling',
            'color' => 'text-green-500',
            'title' => 'Panen Terbaru',
            'description' => 'Total ' . number_format($data->panen_bulan_ini ?? 0, 0, ',', '.') . ' kg bulan ini',
            'time' => 'Hari ini'
        ],
        [
            'icon' => 'fas fa-users',
            'color' => 'text-blue-500',
            'title' => 'Tim Aktif',
            'description' => ($data->total_karyawan ?? 0) . ' karyawan dan ' . ($data->total_admin ?? 0) . ' mandor',
            'time' => 'Hari ini'
        ],
        [
            'icon' => 'fas fa-chart-line',
            'color' => 'text-purple-500',
            'title' => 'Performansi Baik',
            'description' => 'Sistem berjalan optimal',
            'time' => 'Hari ini'
        ]
    ];

    return view('owner.dashboard', [
        'total_karyawan' => $data->total_karyawan ?? 0,
        'total_admin' => $data->total_admin ?? 0,
        'panen_bulan_ini' => $data->panen_bulan_ini ?? 0,
        'pemasukan_bulan_ini' => $data->pemasukan_bulan_ini ?? 0,
        'pengeluaran_bulan_ini' => $data->pengeluaran_bulan_ini ?? 0,
        'laporan_masalah_baru' => $data->laporan_masalah_baru ?? 0,
        'bulan_labels' => $bulanLabels,
        'pemasukan_data' => $pemasukanData,
        'pengeluaran_data' => $pengeluaranData,
        'aktivitas_terbaru' => $aktivitasTerbaru
    ]);
}

public function laporanKeuangan(Request $request)
{
    // Determine date range based on filter
    $periode = $request->get('periode', 'bulan_ini');
    
    switch ($periode) {
        case 'bulan_lalu':
            $startDate = Carbon::now()->subMonth()->startOfMonth();
            $endDate = Carbon::now()->subMonth()->endOfMonth();
            break;
        case '3_bulan':
            $startDate = Carbon::now()->subMonths(3)->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();
            break;
        case 'tahun_ini':
            $startDate = Carbon::now()->startOfYear();
            $endDate = Carbon::now()->endOfYear();
            break;
        default: // bulan_ini
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();
            break;
    }

    // Override with custom dates if provided
    if ($request->has('start_date')) {
        $startDate = Carbon::createFromFormat('Y-m-d', $request->start_date);
    }
    if ($request->has('end_date')) {
        $endDate = Carbon::createFromFormat('Y-m-d', $request->end_date);
    }

    // PERBAIKAN: Ambil semua data pemasukan berdasarkan periode
    $semuaPemasukan = Pemasukan::whereBetween('tanggal', [$startDate, $endDate])
        ->orderBy('tanggal', 'desc')
        ->get();

    // Pisahkan pemasukan berdasarkan sumber
    $pemasukanPenjualanBuah = $semuaPemasukan->where('sumber_pemasukan', 'penjualan_buah');
    $pemasukanLainnya = $semuaPemasukan->where('sumber_pemasukan', 'lainnya');

    // Total pemasukan
    $totalPemasukan = $semuaPemasukan->sum('total_pemasukan');

    // Data pengeluaran periode
    $pengeluaranBulanIni = Pengeluaran::whereBetween('tanggal', [$startDate, $endDate])
        ->orderBy('tanggal', 'desc')
        ->get();

    $totalPengeluaran = $pengeluaranBulanIni->sum('total_biaya');

    // Data untuk comparison (periode sebelumnya)
    $previousStartDate = $startDate->copy()->subMonth();
    $previousEndDate = $endDate->copy()->subMonth();
    
    // Total pemasukan periode sebelumnya
    $pemasukanBulanLalu = Pemasukan::whereBetween('tanggal', [$previousStartDate, $previousEndDate])
        ->sum('total_pemasukan');
    
    // Total pengeluaran periode sebelumnya
    $pengeluaranBulanLalu = Pengeluaran::whereBetween('tanggal', [$previousStartDate, $previousEndDate])
        ->sum('total_biaya');

    // Growth calculation
    $growthPemasukan = $pemasukanBulanLalu > 0 
        ? round((($totalPemasukan - $pemasukanBulanLalu) / $pemasukanBulanLalu) * 100, 2)
        : ($totalPemasukan > 0 ? 100 : 0);

    $growthPengeluaran = $pengeluaranBulanLalu > 0 
        ? round((($totalPengeluaran - $pengeluaranBulanLalu) / $pengeluaranBulanLalu) * 100, 2)
        : ($totalPengeluaran > 0 ? 100 : 0);

    // Detail pengeluaran per kategori
    $pengeluaranPerKategori = Pengeluaran::whereBetween('tanggal', [$startDate, $endDate])
        ->selectRaw('jenis_pengeluaran, SUM(total_biaya) as total')
        ->groupBy('jenis_pengeluaran')
        ->get();

    return view('owner.laporan-keuangan', [
        'total_pemasukan' => $totalPemasukan,
        'total_pengeluaran' => $totalPengeluaran,
        'growth_pemasukan' => $growthPemasukan,
        'growth_pengeluaran' => $growthPengeluaran,
        'pengeluaran_per_kategori' => $pengeluaranPerKategori,
        'pemasukan_penjualan_buah' => $pemasukanPenjualanBuah, // Variabel yang sesuai
        'pemasukan_lainnya' => $pemasukanLainnya,
        'pengeluaran_list' => $pengeluaranBulanIni,
        'start_date' => $startDate->format('Y-m-d'),
        'end_date' => $endDate->format('Y-m-d'),
        'periode' => $periode
    ]);
}

    public function manajemenUser()
    {
        $users = User::whereIn('role', ['admin', 'karyawan'])
            ->orderBy('role')
            ->orderBy('nama_lengkap')
            ->get();

        $statistics = [
            'total_users' => User::where('status_aktif', 1)->count(),
            'total_owners' => User::where('role', 'owner')->where('status_aktif', 1)->count(),
            'total_admins' => User::where('role', 'admin')->where('status_aktif', 1)->count(),
            'total_karyawans' => User::where('role', 'karyawan')->where('status_aktif', 1)->count(),
        ];

        return view('owner.manajemen-user', [
            'users' => $users,
            'statistics' => $statistics
        ]);
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

        // 3. TOP PERFORMERS - Berdasarkan total panen tanpa filter status
        $top_performers = DB::table('users as u')
            ->leftJoin('panen_harian as ph', function($join) use ($start_date, $end_date) {
                $join->on('u.id_user', '=', 'ph.id_user')
                     ->whereBetween('ph.tanggal', [$start_date, $end_date]);
            })
            ->leftJoin('absensi as a', function($join) use ($start_date, $end_date) {
                $join->on('u.id_user', '=', 'a.id_user')
                     ->whereBetween('a.tanggal', [$start_date, $end_date]);
            })
            ->select([
                'u.id_user',
                'u.nama_lengkap',
                'u.role',
                DB::raw('COUNT(DISTINCT ph.id_panen) as total_panen_dilakukan'),
                DB::raw('COUNT(DISTINCT a.id_absensi) as total_hari_absensi'),
                DB::raw('SUM(CASE WHEN a.status_kehadiran = "Hadir" THEN 1 ELSE 0 END) as total_hadir'),
                DB::raw('SUM(CASE WHEN a.status_kehadiran = "Alpha" THEN 1 ELSE 0 END) as total_alpha'),
                DB::raw('COALESCE(SUM(ph.jumlah_kg), 0) as total_panen_kg'),
                DB::raw('COALESCE(SUM(ph.total_upah), 0) as total_upah_didapat'),
                DB::raw('COUNT(DISTINCT ph.tanggal) as hari_panen'),
                // Hitung efisiensi (kg per hari panen)
                DB::raw('CASE 
                    WHEN COUNT(DISTINCT ph.tanggal) > 0 
                    THEN COALESCE(SUM(ph.jumlah_kg), 0) / COUNT(DISTINCT ph.tanggal)
                    ELSE 0 
                END as efisiensi_kg_per_hari')
            ])
            ->where('u.role', 'karyawan')
            ->where('u.status_aktif', 1)
            ->groupBy('u.id_user', 'u.nama_lengkap', 'u.role')
            ->orderBy('total_panen_kg', 'desc')
            ->orderBy('efisiensi_kg_per_hari', 'desc')
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

        return view('owner.rekap-produktivitas', [
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

    public function updateUserStatus(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->status_aktif = $request->status_aktif;
            $user->save();

            return response()->json([
                'success' => true, 
                'message' => 'Status user berhasil diperbarui',
                'new_status' => $user->status_aktif ? 'Aktif' : 'Nonaktif'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status user: ' . $e->getMessage()
            ], 500);
        }
    }

    public function resetPassword($id)
    {
        try {
            $user = User::findOrFail($id);
            $defaultPassword = 'password123';
            $user->password = Hash::make($defaultPassword);
            $user->save();

            return response()->json([
                'success' => true, 
                'message' => 'Password berhasil direset ke default'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal reset password: ' . $e->getMessage()
            ], 500);
        }
    }

    public function storeUser(Request $request)
    {
        try {
            $request->validate([
                'nama_lengkap' => 'required|string|max:255',
                'username' => 'required|string|unique:users,username',
                'role' => 'required|in:admin,karyawan',
                'jabatan' => 'required|in:mandor,asisten_mandor,anggota',
                'no_telepon' => 'nullable|string|max:20',
                'status_tinggal' => 'nullable|in:barak,keluarga_barak,luar',
            ]);

            $user = User::create([
                'nama_lengkap' => $request->nama_lengkap,
                'username' => $request->username,
                'password' => Hash::make('password123'), // default password
                'role' => $request->role,
                'jabatan' => $request->jabatan,
                'no_telepon' => $request->no_telepon,
                'status_tinggal' => $request->status_tinggal,
                'status_aktif' => true,
                'bisa_input_panen' => true,
                'bisa_input_absen' => true,
                'tanggal_bergabung' => now(),
            ]);

            return response()->json([
                'success' => true, 
                'message' => 'User berhasil ditambahkan', 
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan user: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateUser(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            $request->validate([
                'nama_lengkap' => 'required|string|max:255',
                'username' => 'required|string|unique:users,username,' . $id . ',id_user',
                'role' => 'required|in:admin,karyawan',
                'jabatan' => 'required|in:mandor,asisten_mandor,anggota',
                'no_telepon' => 'nullable|string|max:20',
                'status_tinggal' => 'nullable|in:barak,keluarga_barak,luar',
                'bisa_input_panen' => 'sometimes|boolean',
                'bisa_input_absen' => 'sometimes|boolean',
            ]);

            $user->update([
                'nama_lengkap' => $request->nama_lengkap,
                'username' => $request->username,
                'role' => $request->role,
                'jabatan' => $request->jabatan,
                'no_telepon' => $request->no_telepon,
                'status_tinggal' => $request->status_tinggal,
                'bisa_input_panen' => $request->boolean('bisa_input_panen'),
                'bisa_input_absen' => $request->boolean('bisa_input_absen'),
            ]);

            return response()->json([
                'success' => true, 
                'message' => 'User berhasil diperbarui', 
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui user: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getUser($id)
    {
        try {
            $user = User::findOrFail($id);
            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ], 404);
        }
    }

    public function laporanMasalah()
    {
        try {
            $laporanMasalah = DB::select('CALL sp_masalah_diteruskan_owner()');
        } catch (\Exception $e) {
            // Fallback query
            $laporanMasalah = LaporanMasalah::with(['pelapor', 'penanda'])
                ->where('diteruskan_ke_owner', 1)
                ->where('status_masalah', '!=', 'selesai')
                ->orderBy('tanggal', 'desc')
                ->get();
        }

        return view('owner.laporan-masalah', [
            'laporan_masalah' => collect($laporanMasalah)
        ]);
    }
public function markSolved(Request $request)
{
    try {
        $request->validate([
            'id_masalah' => 'required|exists:laporan_masalah,id_masalah',
            'tindakan' => 'required|string|min:10'
        ]);

        $laporan = LaporanMasalah::findOrFail($request->id_masalah);
        
        $laporan->update([
            'status_masalah' => 'selesai',
            'tindakan' => $request->tindakan,
            'ditangani_oleh' => auth()->id(),
            'tanggal_selesai' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil ditandai sebagai selesai'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ], 500);
    }
}
   // Di Owner DashboardController
public function detailLaporan($id)
{
    try {
        $laporan = LaporanMasalah::with(['pelapor', 'penanda', 'penangan'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'laporan' => $laporan
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Laporan tidak ditemukan'
        ], 404);
    }
}

public function markProblemAsSolved(Request $request)
{
    try {
        \Log::info('Mark as solved request:', $request->all());
        
        $request->validate([
            'id_masalah' => 'required|exists:laporan_masalah,id_masalah',
            'tindakan' => 'required|string|min:10'
        ]);

        DB::beginTransaction();

        $laporan = LaporanMasalah::findOrFail($request->id_masalah);
        
        \Log::info('Found laporan:', ['id' => $laporan->id_masalah, 'status' => $laporan->status_masalah]);
        
        $laporan->update([
            'tindakan' => $request->tindakan,
            'status_masalah' => 'selesai',
            'ditangani_oleh' => auth()->id(),
            'tanggal_selesai' => now()
        ]);

        DB::commit();

        \Log::info('Laporan updated successfully');

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil ditandai sebagai selesai'
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        DB::rollBack();
        \Log::error('Validation error:', $e->errors());
        
        // PERBAIKAN: Ganti array_flatten dengan collect()->flatten()
        $errorMessages = collect($e->errors())->flatten()->implode(', ');
        
        return response()->json([
            'success' => false,
            'message' => 'Validasi gagal: ' . $errorMessages
        ], 422);
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Error marking problem as solved: ' . $e->getMessage());
        \Log::error('Stack trace: ' . $e->getTraceAsString());
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan server: ' . $e->getMessage()
        ], 500);
    }
}
    public function getDashboardStats()
    {
        try {
            $totalKaryawan = User::where('role', 'karyawan')->where('status_aktif', 1)->count();
            $totalAdmin = User::where('role', 'admin')->where('status_aktif', 1)->count();
            $panenBulanIni = PanenHarian::whereMonth('tanggal', Carbon::now()->month)
                ->whereYear('tanggal', Carbon::now()->year)
                ->where('status_panen', 'diverifikasi')
                ->sum('jumlah_kg');
            $laporanMasalahBaru = LaporanMasalah::where('diteruskan_ke_owner', 1)
                ->where('status_masalah', '!=', 'selesai')
                ->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'total_karyawan' => $totalKaryawan,
                    'total_admin' => $totalAdmin,
                    'panen_bulan_ini' => $panenBulanIni,
                    'laporan_masalah_baru' => $laporanMasalahBaru,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data statistik'
            ], 500);
        }
    }
// Tambahkan method ini ke OwnerDashboardController

public function pemasukan(Request $request)
{
    $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
    $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

    $pemasukan = Pemasukan::with(['pencatat', 'penjualan'])
        ->whereBetween('tanggal', [$startDate, $endDate])
        ->orderBy('tanggal', 'desc')
        ->get();

    $totalPemasukan = $pemasukan->sum('total_pemasukan');

    return view('owner.pemasukan', [
        'pemasukan' => $pemasukan,
        'total_pemasukan' => $totalPemasukan,
        'start_date' => $startDate,
        'end_date' => $endDate
    ]);
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
            'id_user_pencatat' => auth()->id()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pemasukan berhasil dicatat'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Gagal mencatat pemasukan: ' . $e->getMessage()
        ], 500);
    }
}

public function verifikasiPengeluaran()
{
    $pengeluaranPerluVerifikasi = Pengeluaran::with(['pencatat', 'pupuk', 'transportasi', 'perawatan'])
        ->perluVerifikasi()
        ->orderBy('tanggal', 'desc')
        ->orderBy('id_pengeluaran', 'desc')
        ->get();

    return view('owner.verifikasi-pengeluaran', [
        'pengeluaran_perlu_verifikasi' => $pengeluaranPerluVerifikasi
    ]);
}

public function verifyPengeluaran(Request $request, $id)
{
    try {
        $pengeluaran = Pengeluaran::findOrFail($id);
        
        $request->validate([
            'action' => 'required|in:approve,reject',
            'catatan_verifikasi' => 'nullable|string|max:500'
        ]);

        if ($request->action === 'approve') {
            $pengeluaran->update([
                'status_verifikasi' => true,
                'keterangan' => $pengeluaran->keterangan . ' | ' . ($request->catatan_verifikasi ?: 'Terverifikasi oleh owner')
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pengeluaran berhasil diverifikasi'
            ]);
        } else {
            $pengeluaran->update([
                'keterangan' => $pengeluaran->keterangan . ' | Ditolak: ' . ($request->catatan_verifikasi ?: 'Tidak disetujui')
            ]);

            // Optional: Hapus pengeluaran yang ditolak
            // $pengeluaran->delete();

            return response()->json([
                'success' => true,
                'message' => 'Pengeluaran ditolak'
            ]);
        }

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Gagal memverifikasi pengeluaran: ' . $e->getMessage()
        ], 500);
    }
}

public function laporanPengeluaran(Request $request)
{
    $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
    $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

    $pengeluaran = Pengeluaran::with(['pencatat', 'pupuk', 'transportasi', 'perawatan'])
        ->whereBetween('tanggal', [$startDate, $endDate])
        ->sudahDiverifikasi()
        ->orderBy('tanggal', 'desc')
        ->get();

    // Statistik pengeluaran
    $statistik = [
        'total_pengeluaran' => $pengeluaran->sum('total_biaya'),
        'total_pupuk' => $pengeluaran->where('jenis_pengeluaran', 'pupuk')->sum('total_biaya'),
        'total_transportasi' => $pengeluaran->where('jenis_pengeluaran', 'transportasi')->sum('total_biaya'),
        'total_perawatan' => $pengeluaran->where('jenis_pengeluaran', 'perawatan')->sum('total_biaya'),
        'total_lainnya' => $pengeluaran->where('jenis_pengeluaran', 'lainnya')->sum('total_biaya'),
    ];

    return view('owner.laporan-pengeluaran', [
        'pengeluaran' => $pengeluaran,
        'statistik' => $statistik,
        'start_date' => $startDate,
        'end_date' => $endDate
    ]);
}

// Tambahkan method ini ke OwnerDashboardController

public function verifikasiPemasukan()
{
    $pemasukanPerluVerifikasi = Pemasukan::with(['pencatat'])
        ->perluVerifikasi()
        ->orderBy('tanggal', 'desc')
        ->orderBy('id_pemasukan', 'desc')
        ->get();

    return view('owner.verifikasi-pemasukan', [
        'pemasukan_perlu_verifikasi' => $pemasukanPerluVerifikasi
    ]);
}

public function verifyPemasukan(Request $request, $id)
{
    try {
        $pemasukan = Pemasukan::findOrFail($id);
        
        $request->validate([
            'action' => 'required|in:approve,reject',
            'catatan_verifikasi' => 'nullable|string|max:500'
        ]);

        if ($request->action === 'approve') {
            $pemasukan->update([
                'status_verifikasi' => true,
                'keterangan' => $pemasukan->keterangan . ' | ' . ($request->catatan_verifikasi ?: 'Terverifikasi oleh owner')
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pemasukan berhasil diverifikasi'
            ]);
        } else {
            $pemasukan->update([
                'keterangan' => $pemasukan->keterangan . ' | Ditolak: ' . ($request->catatan_verifikasi ?: 'Tidak disetujui')
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pemasukan ditolak'
            ]);
        }

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Gagal memverifikasi pemasukan: ' . $e->getMessage()
        ], 500);
    }
}

public function laporanPemasukan(Request $request)
{
    $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
    $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

    $pemasukan = Pemasukan::with(['pencatat'])
        ->whereBetween('tanggal', [$startDate, $endDate])
        ->sudahDiverifikasi()
        ->orderBy('tanggal', 'desc')
        ->get();

    $statistik = [
        'total_pemasukan' => $pemasukan->sum('total_pemasukan'),
        'total_penjualan_buah' => $pemasukan->where('sumber_pemasukan', 'penjualan_buah')->sum('total_pemasukan'),
        'total_lainnya' => $pemasukan->where('sumber_pemasukan', 'lainnya')->sum('total_pemasukan'),
    ];

    return view('owner.laporan-pemasukan', [
        'pemasukan' => $pemasukan,
        'statistik' => $statistik,
        'start_date' => $startDate,
        'end_date' => $endDate
    ]);
}

public function verifikasiLaporanMasalah()
{
    $laporanPerluVerifikasi = LaporanMasalah::with(['pelapor'])
        ->perluVerifikasi()
        ->orderBy('tanggal', 'desc')
        ->orderBy('id_masalah', 'desc')
        ->get();

    return view('owner.verifikasi-laporan-masalah', [
        'laporan_perlu_verifikasi' => $laporanPerluVerifikasi
    ]);
}

public function verifyLaporanMasalah(Request $request, $id)
{
    try {
        $laporan = LaporanMasalah::findOrFail($id);
        
        $request->validate([
            'action' => 'required|in:approve,reject',
            'catatan_verifikasi' => 'nullable|string|max:500'
        ]);

        if ($request->action === 'approve') {
            $laporan->update([
                'status_verifikasi' => true,
                'deskripsi' => $laporan->deskripsi . ' | ' . ($request->catatan_verifikasi ?: 'Terverifikasi oleh owner')
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Laporan masalah berhasil diverifikasi'
            ]);
        } else {
            $laporan->update([
                'deskripsi' => $laporan->deskripsi . ' | Ditolak: ' . ($request->catatan_verifikasi ?: 'Tidak valid')
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Laporan masalah ditolak'
            ]);
        }

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Gagal memverifikasi laporan: ' . $e->getMessage()
        ], 500);
    }
}
    public function exportUsers(Request $request)
    {
        try {
            $users = User::whereIn('role', ['admin', 'karyawan'])
                ->orderBy('role')
                ->orderBy('nama_lengkap')
                ->get();

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="users_' . date('Y-m-d') . '.csv"',
            ];

            $callback = function() use ($users) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['Nama Lengkap', 'Username', 'Role', 'Jabatan', 'Status', 'No. Telepon', 'Status Tinggal']);
                
                foreach ($users as $user) {
                    fputcsv($file, [
                        $user->nama_lengkap,
                        $user->username,
                        $user->role,
                        $user->jabatan,
                        $user->status_aktif ? 'Aktif' : 'Nonaktif',
                        $user->no_telepon ?? '-',
                        $user->status_tinggal ?? '-'
                    ]);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal export data user: ' . $e->getMessage()
            ], 500);
        }
    }
}