<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PanenHarian extends Model
{
    use HasFactory;

    protected $table = 'panen_harian';
    protected $primaryKey = 'id_panen';

    protected $fillable = [
        'id_user',
        'id_blok', 
        'tanggal',
        'jumlah_kg',
        'jenis_buah',
        'harga_upah_per_kg',
        'total_upah',
        'status_panen',
        'diverifikasi_oleh',
        'keterangan'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah_kg' => 'decimal:2',
        'harga_upah_per_kg' => 'decimal:2',
        'total_upah' => 'decimal:2',
    ];
// Di dalam class PanenHarian

// Mutator untuk set harga_upah_per_kg otomatis
public function setHargaUpahPerKgAttribute($value)
{
    // Jika jenis buah adalah gugur, pakai value yang dikirim dari form
    if ($this->jenis_buah === 'buah_gugur') {
        $this->attributes['harga_upah_per_kg'] = $value ?? 0;
    } else {
        // Tentukan harga berdasarkan jarak blok
        $this->attributes['harga_upah_per_kg'] = $this->blokLadang->jarak === 'jauh' ? 220 : 200;
    }
}

// Mutator untuk set total_upah otomatis
public function setTotalUpahAttribute($value)
{
    if ($this->jenis_buah === 'buah_gugur') {
        $this->attributes['total_upah'] = $value ?? 0; // input manual
    } else {
        $this->attributes['total_upah'] = $this->jumlah_kg * $this->harga_upah_per_kg;
    }
}

    // Relationship dengan user (karyawan yang memanen)
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // Relationship dengan blok ladang
    public function blokLadang()
    {
        return $this->belongsTo(BlokLadang::class, 'id_blok');
    }

    // Alias supaya $panen->blok tetap bisa dipakai
    public function blok()
    {
        return $this->blokLadang();
    }

    // Relationship dengan user yang memverifikasi
    public function verifikator()
    {
        return $this->belongsTo(User::class, 'diverifikasi_oleh');
    }

    // Scope untuk panen yang perlu diverifikasi
    public function scopePerluVerifikasi($query)
    {
        return $query->where('status_panen', 'draft');
    }

    // Scope untuk panen yang sudah diverifikasi
    public function scopeSudahDiverifikasi($query)
    {
        return $query->where('status_panen', 'diverifikasi');
    }

    // Scope untuk panen berdasarkan tanggal
    public function scopePeriode($query, $startDate, $endDate)
    {
        return $query->whereBetween('tanggal', [$startDate, $endDate]);
    }

    // Accessor untuk status panen dalam bahasa Indonesia
    public function getStatusPanenTextAttribute()
    {
        $status = [
            'draft' => 'Menunggu Verifikasi',
            'diverifikasi' => 'Terverifikasi',
            'dibayar' => 'Sudah Dibayar'
        ];

        return $status[$this->status_panen] ?? $this->status_panen;
    }

    // Accessor untuk jenis buah dalam bahasa Indonesia
    public function getJenisBuahTextAttribute()
    {
        return $this->jenis_buah === 'buah_segar' ? 'Buah Segar' : 'Buah Gugur';
    }
}
