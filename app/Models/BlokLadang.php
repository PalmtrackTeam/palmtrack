<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlokLadang extends Model
{
    use HasFactory;

    protected $table = 'blok_ladang';
    protected $primaryKey = 'id_blok';

    protected $fillable = [
        'nama_blok',
        'kategori',
        'luas_hektar',
        'harga_upah_per_kg'
    ];

    protected $casts = [
        'luas_hektar' => 'decimal:2',
        'harga_upah_per_kg' => 'decimal:2',
    ];

    // Relationship dengan panen harian
    public function panenHarian()
    {
        return $this->hasMany(PanenHarian::class, 'id_blok');
    }

    // Relationship dengan laporan masalah
    public function laporanMasalah()
    {
        return $this->hasMany(LaporanMasalah::class, 'id_blok');
    }

    // Scope untuk blok dekat
    public function scopeDekat($query)
    {
        return $query->where('kategori', 'dekat');
    }

    // Scope untuk blok jauh
    public function scopeJauh($query)
    {
        return $query->where('kategori', 'jauh');
    }

    // Accessor untuk nama blok lengkap
    public function getNamaBlokLengkapAttribute()
    {
        return "Blok {$this->nama_blok} ({$this->kategori})";
    }

    // Method untuk mendapatkan total panen per periode
    public function getTotalPanen($startDate = null, $endDate = null)
    {
        $query = $this->panenHarian();

        if ($startDate && $endDate) {
            $query->whereBetween('tanggal', [$startDate, $endDate]);
        }

        return $query->sum('jumlah_kg');
    }

    // Method untuk mendapatkan rata-rata panen harian
    public function getRataRataPanenHarian($startDate = null, $endDate = null)
    {
        $query = $this->panenHarian();

        if ($startDate && $endDate) {
            $query->whereBetween('tanggal', [$startDate, $endDate]);
        }

        $totalPanen = $query->sum('jumlah_kg');
        $totalHari = $query->distinct('tanggal')->count('tanggal');

        return $totalHari > 0 ? $totalPanen / $totalHari : 0;
    }
}