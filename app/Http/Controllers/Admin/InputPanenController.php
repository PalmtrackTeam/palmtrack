<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PanenHarian;
use App\Models\BlokLadang;

class InputPanenController extends Controller
{
    public function create()
    {
        $blokLadang = BlokLadang::all();
        return view('admin.input-panen', compact('blokLadang'));
    }

   public function store(Request $request)
{
    $request->validate([
        'id_blok' => 'required|exists:blok_ladang,id_blok',
        'tanggal' => 'required|date',
        'jumlah_kg' => 'required|numeric|min:0',
        'jenis_buah' => 'required|in:buah_segar,buah_gugur',
        'harga_gugur' => 'nullable|numeric|min:0',
        'keterangan' => 'nullable|string|max:255',
    ]);

    $blok = BlokLadang::findOrFail($request->id_blok);

    // Jika buah gugur, ambil harga dari input; jika segar, ambil dari database
    $hargaUpahPerKg = $request->jenis_buah === 'buah_gugur'
                      ? $request->harga_gugur
                      : $blok->harga_upah_per_kg;

    $totalUpah = $request->jumlah_kg * $hargaUpahPerKg;

    PanenHarian::create([
        'id_user' => auth()->id(),
        'id_blok' => $request->id_blok,
        'tanggal' => $request->tanggal,
        'jumlah_kg' => $request->jumlah_kg,
        'jenis_buah' => $request->jenis_buah,
        'harga_upah_per_kg' => $hargaUpahPerKg,
        'total_upah' => $totalUpah,
        'status_panen' => 'draft', // GUNAKAN 'draft' karena default
        'keterangan' => $request->keterangan ?? '-',
    ]);

    return redirect()
        ->route('admin.riwayat-panen')
        ->with('success', 'Panen berhasil disimpan dengan status DRAFT!');
}

public function riwayat(Request $request)
{
    $query = PanenHarian::with('blokLadang');

    // Filter blok
    if ($request->filled('id_blok')) {
        $query->where('id_blok', $request->id_blok);
    }

    // Filter tanggal
    if ($request->filled('tanggal_mulai')) {
        $query->where('tanggal', '>=', $request->tanggal_mulai);
    }
    if ($request->filled('tanggal_selesai')) {
        $query->where('tanggal', '<=', $request->tanggal_selesai);
    }

    $riwayatPanen = $query->orderBy('tanggal', 'desc')->get();
    $blokLadang = BlokLadang::all(); // Untuk dropdown filter

    return view('admin.riwayat-panen', compact('riwayatPanen', 'blokLadang'));
}
}
