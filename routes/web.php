<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return view('home');
});

Route::get('/tentang', fn() => view('tentang'));
Route::get('/produk', fn() => view('produk'));
Route::get('/berita', fn() => view('berita'));
Route::get('/kontak', fn() => view('kontak'));

// Route untuk beranda (yang muncul di navbar kiri)
Route::get('/beranda', function () {
    return view('beranda.index');
})->name('beranda.index');

Route::get('/absensi', function () {
    return view('absensi.index');
})->name('absensi.index');

Route::get('/pengeluaran', function () {
    return view('pengeluaran.index');
})->name('pengeluaran.index');

Route::get('/pemasukan', function () {
    return view('pemasukan.index');
})->name('pemasukan.index');


// // Tambah route profil
// Route::middleware(['auth'])->group(function () {
//     Route::get('/profil', function () {
//         return view('profil');
//     })->name('profil');
// });

// // Route tambahan untuk beranda yg nantinya di landing page
// Route::get('/beranda-lama', function () {
//     return view('beranda');
// })->name('beranda.lama');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Ubah password
Route::get('/profile/password', [ProfileController::class, 'editPassword'])->name('password.edit');
Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('password.update');

Route::middleware('auth')->group(function () {
    Route::get('/profile/info', [ProfileController::class, 'info'])->name('profile.info');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
