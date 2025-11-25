<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PanenHarian; // Ganti dengan model PanenHarian
use App\Models\BlokLadang; // Jika perlu memilih blok ladang

class InputPanenController extends Controller
{
    public function create()
    {
        // Ambil data blok ladang untuk dropdown (jika ada)
        $blokLadang = BlokLadang::all(); // Sesuaikan jika perlu query tertentu
        
        return view('karyawan.input-panen', compact('blokLadang'));
    }

    /**
     * Method untuk menyimpan data panen
     */
    public function store(Request $request)
    {
        // Validasi data
        $request->validate([
            'id_blok' => 'required|exists:blok_ladang,id_blok',
            'tanggal' => 'required|date',
            'jumlah_kg' => 'required|numeric|min:0',
            'jenis_buah' => 'required|in:buah_segar,buah_gugur',
            'keterangan' => 'nullable|string|max:500'
        ]);

        // Hitung total upah (contoh: harga tetap Rp 5.000 per kg)
        $hargaUpahPerKg = 5000; // Sesuaikan dengan logic bisnis kamu
        $totalUpah = $request->jumlah_kg * $hargaUpahPerKg;

        // Simpan data ke database
        PanenHarian::create([
            'id_user' => auth()->id(),
            'id_blok' => $request->id_blok,
            'tanggal' => $request->tanggal,
            'jumlah_kg' => $request->jumlah_kg,
            'jenis_buah' => $request->jenis_buah,
            'harga_upah_per_kg' => $hargaUpahPerKg,
            'total_upah' => $totalUpah,
            'status_panen' => 'draft', // Status awal menunggu verifikasi
            'keterangan' => $request->keterangan
        ]);

        return redirect()->route('karyawan.riwayat-panen')
            ->with('success', 'Data panen harian berhasil disimpan! Menunggu verifikasi admin.');
    }

    /**
     * Method untuk menampilkan riwayat panen
     */
    public function riwayat()
    {
        // Ambil data riwayat panen user yang login
        $riwayatPanen = PanenHarian::with('blokLadang') // Eager load relasi blok ladang
            ->where('id_user', auth()->id())
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('karyawan.riwayat-panen', compact('riwayatPanen'));
    }
}