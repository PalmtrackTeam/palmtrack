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
    $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
    $endDate   = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

    // Siapkan variabel default agar tidak muncul error "unassigned variable"
    $produktivitasKaryawan = collect([]);
    $produktivitasPerBlok = collect([]);
    $topAbsensi = collect([]);

    // ======================== PRODUKTIVITAS KARYAWAN ========================
    $produktivitasKaryawan = DB::table('panen_harian')
        ->join('users', 'panen_harian.id_user', '=', 'users.id_user')
        ->whereBetween('panen_harian.tanggal', [$startDate, $endDate])
        ->where('panen_harian.status_panen', 'diverifikasi')
        ->where('users.status_aktif', 1)
        ->where('users.role', 'karyawan')
        ->selectRaw('
            users.id_user,
            users.nama_lengkap,
            users.role,
            COALESCE(COUNT(DISTINCT panen_harian.tanggal), 0) as hari_kerja,
            COALESCE(SUM(panen_harian.jumlah_kg), 0) as total_kg,
            COALESCE(SUM(panen_harian.total_upah), 0) as total_upah,
            CASE 
                WHEN COUNT(DISTINCT panen_harian.tanggal) > 0 
                THEN COALESCE(SUM(panen_harian.jumlah_kg), 0) / COUNT(DISTINCT panen_harian.tanggal)
                ELSE 0
            END as rata_kg_per_hari
        ')
        ->groupBy('users.id_user', 'users.nama_lengkap', 'users.role')
        ->orderBy('total_kg', 'desc')
        ->get();

    // ======================== PRODUKTIVITAS PER BLOK ========================
    $produktivitasPerBlok = DB::table('panen_harian')
        ->rightJoin('blok_ladang', 'panen_harian.id_blok', '=', 'blok_ladang.id_blok')
        ->where(function ($q) use ($startDate, $endDate) {
            $q->whereBetween('panen_harian.tanggal', [$startDate, $endDate])
              ->orWhereNull('panen_harian.tanggal');
        })
        ->selectRaw('
            blok_ladang.id_blok,
            blok_ladang.nama_blok,
            COALESCE(SUM(panen_harian.jumlah_kg), 0) as total_berat,
            COALESCE(COUNT(panen_harian.id_panen), 0) as total_panen,
            CASE
                WHEN COUNT(panen_harian.id_panen) > 0
                THEN AVG(panen_harian.jumlah_kg)
                ELSE 0
            END as rata_per_panen,
            COALESCE(SUM(panen_harian.total_upah), 0) as total_upah,
            COALESCE(GROUP_CONCAT(DISTINCT panen_harian.jenis_buah), "Tidak ada") as jenis_buah
        ')
        ->groupBy('blok_ladang.id_blok', 'blok_ladang.nama_blok')
        ->orderBy('total_berat', 'desc')
        ->get();

    // ======================== TOP ABSENSI ========================
    $topAbsensi = DB::table('absensi')
        ->join('users', 'absensi.id_user', '=', 'users.id_user')
        ->whereBetween('absensi.tanggal', [$startDate, $endDate])
        ->where('users.role', 'karyawan')
        ->where('users.status_aktif', 1)
        ->selectRaw('
            users.id_user,
            users.nama_lengkap,
            users.role,
            SUM(CASE WHEN absensi.status_kehadiran = "Alpha" THEN 1 ELSE 0 END) as total_alpha,
            SUM(CASE WHEN absensi.status_kehadiran = "Hadir" THEN 1 ELSE 0 END) as total_hadir,
            COUNT(absensi.id_absensi) as total_absensi
        ')
        ->groupBy('users.id_user', 'users.nama_lengkap', 'users.role')
        ->orderBy('total_alpha', 'desc')
        ->orderBy('total_hadir', 'asc')
        ->take(3)
        ->get();

    // ======================== RETURN VIEW ========================
    return view('admin.rekap-produktivitas', [
        'produktivitas_karyawan' => $produktivitasKaryawan,
        'produktivitas_per_blok' => $produktivitasPerBlok,
        'top_absensi' => $topAbsensi,
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