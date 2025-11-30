<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PanenHarian;
use App\Models\Absensi;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\PengeluaranPupuk;
// HAPUS MODEL YANG TIDAK ADA
// use App\Models\PengeluaranTransportasi;
// use App\Models\PengeluaranPerawatan;
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
        
        // Data dashboard mandor
        $totalKaryawanAktif = User::where('role', 'karyawan')
            ->where('status_aktif', 1)
            ->count();

        // Produktivitas hari ini
        $produktivitasHariIni = PanenHarian::where('tanggal', $today)
            ->where('status_panen', 'diverifikasi')
            ->selectRaw('
                COUNT(DISTINCT id_user) as total_karyawan_panen,
                COALESCE(SUM(jumlah_kg), 0) as total_kg,
                COALESCE(AVG(jumlah_kg), 0) as rata_kg
            ')
            ->first();

        // Blok terproduktif hari ini
        $blokTerproduktif = PanenHarian::where('tanggal', $today)
            ->where('status_panen', 'diverifikasi')
            ->join('blok_ladang', 'panen_harian.id_blok', '=', 'blok_ladang.id_blok')
            ->selectRaw('blok_ladang.nama_blok, SUM(panen_harian.jumlah_kg) as total_kg')
            ->groupBy('blok_ladang.nama_blok')
            ->orderBy('total_kg', 'desc')
            ->first();

        // Data absensi hari ini
        $absensiHariIni = Absensi::where('tanggal', $today)
            ->selectRaw('
                COUNT(*) as total_absensi,
                SUM(CASE WHEN status_kehadiran = "Hadir" THEN 1 ELSE 0 END) as total_hadir,
                SUM(CASE WHEN status_kehadiran = "Izin" THEN 1 ELSE 0 END) as total_izin,
                SUM(CASE WHEN status_kehadiran = "Sakit" THEN 1 ELSE 0 END) as total_sakit,
                SUM(CASE WHEN status_kehadiran = "Alpha" THEN 1 ELSE 0 END) as total_alpha
            ')
            ->first();

        // Karyawan yang belum absen hari ini
        $karyawanBelumAbsen = User::where('role', 'karyawan')
            ->where('status_aktif', 1)
            ->whereNotExists(function ($query) use ($today) {
                $query->select(DB::raw(1))
                    ->from('absensi')
                    ->whereRaw('absensi.id_user = users.id_user')
                    ->where('absensi.tanggal', $today);
            })
            ->get(['id_user', 'nama_lengkap']);

        // Panen perlu verifikasi
        $panenPerluVerifikasi = PanenHarian::with(['user', 'blok'])
            ->where('status_panen', 'draft')
            ->orderBy('tanggal', 'desc')
            ->limit(5)
            ->get();

        // Laporan masalah baru
        $laporanMasalahBaru = LaporanMasalah::with(['pelapor'])
            ->where('status_masalah', 'dilaporkan')
            ->orderBy('tanggal', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', [
            'total_karyawan_aktif' => $totalKaryawanAktif,
            'produktivitas_hari_ini' => $produktivitasHariIni,
            'blok_terproduktif' => $blokTerproduktif,
            'absensi_hari_ini' => $absensiHariIni,
            'karyawan_belum_absen' => $karyawanBelumAbsen,
            'panen_perlu_verifikasi' => $panenPerluVerifikasi,
            'laporan_masalah_baru' => $laporanMasalahBaru,
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

    public function kelolaAbsensi(Request $request)
    {
        $tanggal = $request->get('tanggal', Carbon::today()->format('Y-m-d'));
        
        $absensi = Absensi::with(['user'])
            ->where('tanggal', $tanggal)
            ->join('users', 'absensi.id_user', '=', 'users.id_user')
            ->select('absensi.*')
            ->orderBy('absensi.status_kehadiran')
            ->orderBy('users.nama_lengkap')
            ->get();

        $karyawanAktif = User::where('role', 'karyawan')
            ->where('status_aktif', 1)
            ->orderBy('nama_lengkap')
            ->get();

        return view('admin.kelola-absensi', [
            'absensi' => $absensi,
            'karyawan_aktif' => $karyawanAktif,
            'selected_tanggal' => $tanggal
        ]);
    }

    public function inputAbsensi(Request $request)
    {
        try {
            $request->validate([
                'id_user' => 'required|exists:users,id_user',
                'tanggal' => 'required|date',
                'status_kehadiran' => 'required|in:Hadir,Izin,Sakit,Alpha,Libur_Agama',
                'jam_masuk' => 'nullable|date_format:H:i',
                'jam_pulang' => 'nullable|date_format:H:i',
                'keterangan' => 'nullable|string|max:500'
            ]);

            // Cek apakah sudah ada absensi untuk user di tanggal tersebut
            $existingAbsensi = Absensi::where('id_user', $request->id_user)
                ->where('tanggal', $request->tanggal)
                ->first();

            if ($existingAbsensi) {
                $existingAbsensi->update([
                    'status_kehadiran' => $request->status_kehadiran,
                    'jam_masuk' => $request->jam_masuk,
                    'jam_pulang' => $request->jam_pulang,
                    'keterangan' => $request->keterangan
                ]);
            } else {
                Absensi::create([
                    'id_user' => $request->id_user,
                    'tanggal' => $request->tanggal,
                    'status_kehadiran' => $request->status_kehadiran,
                    'jam_masuk' => $request->jam_masuk,
                    'jam_pulang' => $request->jam_pulang,
                    'keterangan' => $request->keterangan
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Absensi berhasil disimpan'
            ]);

        } catch (\Exception $e) {
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

        return view('admin.riwayat-pemasukan', [
            'pemasukan' => $pemasukan,
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);
    }

    public function inputLaporanMasalah()
    {
        return view('admin.input-laporan-masalah');
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
                'status_verifikasi' => false
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
        return view('admin.input-pengeluaran');
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
                    'id_user_pencatat' => auth()->id(),
                    'status_verifikasi' => false
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
                'message' => 'Pengeluaran pupuk berhasil dicatat dan menunggu verifikasi owner'
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
                    'id_user_pencatat' => auth()->id(),
                    'status_verifikasi' => false
                ]);

                // SIMPAN KE TABEL PENGELUARAN_PUPUK SEBAGAI FALLBACK
                // Atau buat tabel transportasi jika diperlukan
                PengeluaranPupuk::create([
                    'id_pengeluaran' => $pengeluaran->id_pengeluaran,
                    'jenis_pupuk' => 'Transportasi - ' . $request->tujuan,
                    'jumlah' => 1,
                    'harga_satuan' => $request->biaya,
                    'total_harga' => $request->biaya
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Pengeluaran transportasi berhasil dicatat dan menunggu verifikasi owner'
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
                'id_user_pencatat' => auth()->id(),
                'status_verifikasi' => false
            ]);

            // Simpan detail ke tabel pupuk sebagai fallback
            PengeluaranPupuk::create([
                'id_pengeluaran' => $pengeluaran->id_pengeluaran,
                'jenis_pupuk' => 'Perawatan - ' . $request->jenis_perawatan,
                'jumlah' => 1,
                'harga_satuan' => $request->biaya,
                'total_harga' => $request->biaya
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Pengeluaran perawatan berhasil dicatat dan menunggu verifikasi owner'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Gagal mencatat pengeluaran: ' . $e->getMessage()
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
                'id_user_pencatat' => auth()->id(),
                'status_verifikasi' => false
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pengeluaran lainnya berhasil dicatat dan menunggu verifikasi owner'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mencatat pengeluaran: ' . $e->getMessage()
            ], 500);
        }
    }

    public function riwayatPengeluaran(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $pengeluaran = Pengeluaran::with(['pencatat', 'pupuk'])
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->where('id_user_pencatat', auth()->id())
            ->orderBy('tanggal', 'desc')
            ->orderBy('id_pengeluaran', 'desc')
            ->get();

        return view('admin.riwayat-pengeluaran', [
            'pengeluaran' => $pengeluaran,
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);
    }

    public function rekapProduktivitas(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        try {
            // Query utama untuk produktivitas karyawan
            $produktivitasKaryawan = DB::table('panen_harian')
                ->join('users', 'panen_harian.id_user', '=', 'users.id_user')
                ->whereBetween('panen_harian.tanggal', [$startDate, $endDate])
                ->where('panen_harian.status_panen', 'diverifikasi')
                ->where('users.status_aktif', 1)
                ->where('users.role', 'karyawan')
                ->selectRaw('
                    users.id_user,
                    users.nama_lengkap,
                    users.jabatan,
                    COUNT(DISTINCT panen_harian.tanggal) as hari_kerja,
                    COALESCE(SUM(panen_harian.jumlah_kg), 0) as total_kg,
                    COALESCE(SUM(panen_harian.total_upah), 0) as total_upah,
                    CASE 
                        WHEN COUNT(DISTINCT panen_harian.tanggal) > 0 
                        THEN COALESCE(SUM(panen_harian.jumlah_kg), 0) / COUNT(DISTINCT panen_harian.tanggal)
                        ELSE 0 
                    END as rata_kg_per_hari
                ')
                ->groupBy('users.id_user', 'users.nama_lengkap', 'users.jabatan')
                ->orderBy('total_kg', 'desc')
                ->get();

        } catch (\Exception $e) {
            // Fallback query yang lebih sederhana
            \Log::error('Error in rekapProduktivitas: ' . $e->getMessage());
            
            $produktivitasKaryawan = DB::table('panen_harian')
                ->join('users', 'panen_harian.id_user', '=', 'users.id_user')
                ->whereBetween('panen_harian.tanggal', [$startDate, $endDate])
                ->where('panen_harian.status_panen', 'diverifikasi')
                ->where('users.status_aktif', 1)
                ->selectRaw('
                    users.id_user,
                    users.nama_lengkap,
                    users.jabatan,
                    COUNT(*) as hari_kerja,
                    SUM(panen_harian.jumlah_kg) as total_kg,
                    SUM(panen_harian.total_upah) as total_upah,
                    AVG(panen_harian.jumlah_kg) as rata_kg_per_hari
                ')
                ->groupBy('users.id_user', 'users.nama_lengkap', 'users.jabatan')
                ->orderBy('total_kg', 'desc')
                ->get();
        }

        // Data produktivitas per blok
        $produktivitasPerBlok = DB::table('panen_harian')
            ->join('blok_ladang', 'panen_harian.id_blok', '=', 'blok_ladang.id_blok')
            ->whereBetween('panen_harian.tanggal', [$startDate, $endDate])
            ->where('panen_harian.status_panen', 'diverifikasi')
            ->selectRaw('
                blok_ladang.nama_blok, 
                SUM(panen_harian.jumlah_kg) as total_berat, 
                COUNT(*) as total_panen
            ')
            ->groupBy('blok_ladang.nama_blok')
            ->orderBy('total_berat', 'desc')
            ->get();

        return view('admin.rekap-produktivitas', [
            'produktivitas_karyawan' => $produktivitasKaryawan,
            'produktivitas_per_blok' => $produktivitasPerBlok,
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);
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
}