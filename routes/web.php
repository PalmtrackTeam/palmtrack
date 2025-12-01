<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AbsensiController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// 🏠 Halaman utama (guest)
Route::get('/', fn() => view('home'));
Route::get('/tentang', fn() => view('tentang'));
Route::get('/produk', fn() => view('produk'));
Route::get('/berita', fn() => view('berita'));
Route::get('/kontak', fn() => view('kontak'));

// 📌 Halaman beranda & menu di sidebar (umum)
Route::get('/beranda', fn() => view('beranda.index'))->name('beranda.index');

// ❗ DIGANTI: absensi harus pakai controller, bukan view langsung
Route::get('/absensi', [AbsensiController::class, 'index'])->middleware('auth')->name('absensi.index');
Route::post('/absensi', [AbsensiController::class, 'store'])->middleware('auth')->name('absensi.store');

Route::get('/pengeluaran', fn() => view('pengeluaran.index'))->name('pengeluaran.index');
Route::get('/pemasukan', fn() => view('pemasukan.index'))->name('pemasukan.index');

// =====================================================
// ✅ Dashboard berdasarkan role user (utama)
// =====================================================
Route::get('/dashboard', function () {
    $user = Auth::user();

    if (!$user) {
        return redirect('/login');
    }

    switch ($user->role) {
        case 'super_admin':
            return view('dashboard.pimpinan', compact('user'));
        case 'mandor':
            return view('dashboard.mandor', compact('user'));
        default:
            return view('dashboard.karyawan', compact('user'));
    }
})->middleware(['auth', 'verified'])->name('dashboard');

// =====================================================
// ✅ Route tambahan untuk sidebar (biar gak error)
// =====================================================
Route::middleware(['auth'])->group(function () {
    // --- ABSENSI ROLE KHUSUS ---
    Route::get('/absensi/karyawan', [AbsensiController::class, 'index'])->name('absensi.karyawan');
    Route::get('/absensi/mandor', [AbsensiController::class, 'index'])->name('absensi.mandor');

    // --- PROFIL ---
    Route::get('/profil/karyawan', fn() => view('profile.edit'))->name('profil.karyawan');
});

// =====================================================
// 🔐 Profil & Ubah Password
// =====================================================
Route::get('/profile/password', [ProfileController::class, 'editPassword'])->name('password.edit');
Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('password.update');

Route::middleware('auth')->group(function () {
    Route::get('/profile/info', [ProfileController::class, 'info'])->name('profile.info');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
