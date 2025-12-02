<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;

use App\Http\Controllers\Owner\DashboardController as OwnerDashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Karyawan\DashboardController as KaryawanDashboardController;

use App\Http\Controllers\Admin\InputPanenController;
use App\Http\Controllers\Karyawan\AbsensiController;

use Illuminate\Support\Facades\Route;

// ==================== PUBLIC ROUTES ====================
Route::get('/', fn() => view('home'))->name('home');
Route::get('/home', fn() => view('home'))->name('home.page');


// ==================== AUTH ROUTES ====================
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');


// ==================== PROTECTED ROUTES ====================
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // =================================================================
    // == OWNER ROUTES
    // =================================================================
    Route::middleware(['owner'])->prefix('owner')->name('owner.')->group(function () {

        // Dashboard
        Route::get('/dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/stats', [OwnerDashboardController::class, 'getDashboardStats'])->name('dashboard.stats');

        // Rekap Produktivitas
        Route::get('/rekap-produktivitas', [OwnerDashboardController::class, 'rekapProduktivitas'])->name('rekap-produktivitas');

        // Laporan Keuangan
        Route::get('/laporan-keuangan', [OwnerDashboardController::class, 'laporanKeuangan'])->name('laporan-keuangan');

        // Manajemen User
        Route::get('/manajemen-user', [OwnerDashboardController::class, 'manajemenUser'])->name('manajemen-user');
        Route::post('/users', [OwnerDashboardController::class, 'storeUser'])->name('users.store');
        Route::get('/users/{id}', [OwnerDashboardController::class, 'getUser'])->name('users.show');
        Route::put('/users/{id}', [OwnerDashboardController::class, 'updateUser'])->name('users.update');
        Route::post('/users/{id}/status', [OwnerDashboardController::class, 'updateUserStatus'])->name('users.update-status');
        Route::post('/users/{id}/reset-password', [OwnerDashboardController::class, 'resetPassword'])->name('users.reset-password');
        Route::get('/users/export', [OwnerDashboardController::class, 'exportUsers'])->name('users.export');

        // Laporan Masalah
        Route::get('/laporan-masalah', [OwnerDashboardController::class, 'laporanMasalah'])->name('laporan-masalah');
        Route::get('/laporan-masalah/{id}/detail', [OwnerDashboardController::class, 'detailLaporan'])->name('laporan-masalah.detail');
        Route::post('/laporan-masalah/mark-solved', [OwnerDashboardController::class, 'markProblemAsSolved'])->name('laporan-masalah.mark-solved');

        // Verifikasi Pemasukan
        Route::get('/verifikasi-pemasukan', [OwnerDashboardController::class, 'verifikasiPemasukan'])->name('verifikasi-pemasukan');
        Route::post('/pemasukan/{id}/verify', [OwnerDashboardController::class, 'verifyPemasukan'])->name('pemasukan.verify');
        Route::get('/laporan-pemasukan', [OwnerDashboardController::class, 'laporanPemasukan'])->name('laporan-pemasukan');

        // Verifikasi Pengeluaran
        Route::get('/verifikasi-pengeluaran', [OwnerDashboardController::class, 'verifikasiPengeluaran'])->name('verifikasi-pengeluaran');
        Route::post('/pengeluaran/{id}/verify', [OwnerDashboardController::class, 'verifyPengeluaran'])->name('pengeluaran.verify');
        Route::get('/laporan-pengeluaran', [OwnerDashboardController::class, 'laporanPengeluaran'])->name('laporan-pengeluaran');

        // Verifikasi Laporan Masalah
        Route::get('/verifikasi-laporan-masalah', [OwnerDashboardController::class, 'verifikasiLaporanMasalah'])->name('verifikasi-laporan-masalah');
        Route::post('/laporan-masalah/{id}/verify', [OwnerDashboardController::class, 'verifyLaporanMasalah'])->name('laporan-masalah.verify');
    });


    // =================================================================
    // == ADMIN ROUTES
    // =================================================================
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {

        // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard-stats', [AdminDashboardController::class, 'getDashboardStats'])->name('dashboard-stats');

        // PANEN (final fix, tidak dobel)
        Route::get('/input-panen', [InputPanenController::class, 'create'])->name('input-panen');
        Route::post('/store-panen', [InputPanenController::class, 'store'])->name('store-panen');
        Route::get('/riwayat-panen', [InputPanenController::class, 'riwayat'])->name('riwayat-panen');
Route::delete('/pemasukan/{id}', [AdminDashboardController::class, 'destroy'])->name('admin.pemasukan.destroy');

        // Verifikasi Panen
        Route::get('/verifikasi-panen', [AdminDashboardController::class, 'verifikasiPanen'])->name('verifikasi-panen');
        Route::post('/verifikasi-panen/{id}', [AdminDashboardController::class, 'prosesVerifikasiPanen'])->name('proses-verifikasi-panen');

        // Rekap Produktivitas
        Route::get('/rekap-produktivitas', [AdminDashboardController::class, 'rekapProduktivitas'])->name('rekap-produktivitas');

        // Absensi
        Route::get('/kelola-absensi', [AdminDashboardController::class, 'kelolaAbsensi'])->name('kelola-absensi');
        Route::post('/input-absensi', [AdminDashboardController::class, 'inputAbsensi'])->name('input-absensi');

        // Laporan Masalah
        Route::get('/laporan-masalah', [AdminDashboardController::class, 'laporanMasalah'])->name('laporan-masalah');
        Route::post('/laporan-masalah/{id}/teruskan-owner', [AdminDashboardController::class, 'teruskanKeOwner'])->name('teruskan-owner');
        Route::post('/laporan-masalah/{id}/tangani', [AdminDashboardController::class, 'tanganiMasalah'])->name('tangani-masalah');

        // Pemasukan
        Route::get('/input-pemasukan', [AdminDashboardController::class, 'inputPemasukan'])->name('input-pemasukan');
        Route::post('/pemasukan', [AdminDashboardController::class, 'storePemasukan'])->name('pemasukan.store');
        Route::get('/riwayat-pemasukan', [AdminDashboardController::class, 'riwayatPemasukan'])->name('riwayat-pemasukan');

        // Pengeluaran
        Route::get('/input-pengeluaran', [AdminDashboardController::class, 'inputPengeluaran'])->name('input-pengeluaran');
        Route::get('/riwayat-pengeluaran', [AdminDashboardController::class, 'riwayatPengeluaran'])->name('riwayat-pengeluaran');

        // Pengeluaran per jenis
        Route::post('/pengeluaran/gaji', [AdminDashboardController::class, 'storeGaji'])->name('pengeluaran.gaji.store');
        Route::post('/pengeluaran/pupuk', [AdminDashboardController::class, 'storePupuk'])->name('pengeluaran.pupuk');
        Route::post('/pengeluaran/transportasi', [AdminDashboardController::class, 'storeTransportasi'])->name('pengeluaran.transportasi');
        Route::post('/pengeluaran/perawatan', [AdminDashboardController::class, 'storePerawatan'])->name('pengeluaran.perawatan');
        Route::post('/pengeluaran/lainnya', [AdminDashboardController::class, 'storeLainnya'])->name('pengeluaran.lainnya');

        Route::get('/pengeluaran/edit/{id}', [AdminDashboardController::class, 'editPengeluaran'])->name('pengeluaran.edit');
        Route::put('/pengeluaran/update/{id}', [AdminDashboardController::class, 'updatePengeluaran'])->name('pengeluaran.update');

        // API Pengeluaran
        Route::get('/api/pengeluaran/detail/{id}', [AdminDashboardController::class, 'getDetail'])->name('pengeluaran.detail');
        Route::get('/api/pengeluaran/{jenis}', [AdminDashboardController::class, 'getPengeluaranByJenis']);
        Route::delete('/api/pengeluaran/{id}', [AdminDashboardController::class, 'deletePengeluaran']);

        // Laporan masalah input + riwayat
        Route::get('/input-laporan-masalah', [AdminDashboardController::class, 'inputLaporanMasalah'])->name('input-laporan-masalah');
        Route::post('/laporan-masalah', [AdminDashboardController::class, 'storeLaporanMasalah'])->name('laporan-masalah.store');
        Route::get('/riwayat-laporan-masalah', [AdminDashboardController::class, 'riwayatLaporanMasalah'])->name('riwayat-laporan-masalah');
    });


    // =================================================================
    // == KARYAWAN ROUTES
    // =================================================================
    Route::middleware(['karyawan'])->prefix('karyawan')->name('karyawan.')->group(function () {

        Route::get('/dashboard', [KaryawanDashboardController::class, 'index'])->name('dashboard');

        // Absensi
        Route::get('/absensi', [KaryawanDashboardController::class, 'absensi'])->name('absensi');
        Route::post('/absensi', [AbsensiController::class, 'store'])->name('store-absensi');
        Route::get('/riwayat-absensi', [AbsensiController::class, 'riwayat'])->name('riwayat-absensi');

        // Profil
        Route::get('/profile', fn() => view('karyawan.profile'))->name('profile');

        // Pemasukan
        Route::get('/input-pemasukan', [KaryawanDashboardController::class, 'inputPemasukan'])->name('input-pemasukan');
        Route::post('/pemasukan', [KaryawanDashboardController::class, 'storePemasukan'])->name('pemasukan.store');
        Route::get('/riwayat-pemasukan', [KaryawanDashboardController::class, 'riwayatPemasukan'])->name('riwayat-pemasukan');

        // Laporan Masalah
        Route::get('/input-laporan-masalah', [KaryawanDashboardController::class, 'inputLaporanMasalah'])->name('input-laporan-masalah');
        Route::post('/laporan-masalah', [KaryawanDashboardController::class, 'storeLaporanMasalah'])->name('laporan-masalah.store');
        Route::get('/riwayat-laporan-masalah', [KaryawanDashboardController::class, 'riwayatLaporanMasalah'])->name('riwayat-laporan-masalah');
 Route::post('/laporan-masalah/store', [KaryawanDashboardController::class, 'storeLaporanMasalah'])->name('laporan-masalah.store');
        Route::get('/laporan-masalah', [DashboardController::class, 'riwayatLaporanMasalah'])
            ->name('karyawan.riwayat-laporan-masalah');
        Route::get('/laporan-masalah/create', [DashboardController::class, 'inputLaporanMasalah'])
            ->name('karyawan.input-laporan-masalah');
        Route::post('/laporan-masalah', [DashboardController::class, 'storeLaporanMasalah'])
            ->name('karyawan.laporan-masalah.store');
        Route::get('/laporan-masalah/{id}', [DashboardController::class, 'showLaporanMasalah'])
            ->name('karyawan.laporan-masalah.show');

    });

});

require __DIR__.'/auth.php';

