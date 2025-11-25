<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemasukan extends Model
{
    use HasFactory;

    protected $table = 'pemasukan';
    protected $primaryKey = 'id_pemasukan';

    protected $fillable = [
        'id_penjualan',
        'tanggal',
        'sumber_pemasukan',
        'total_pemasukan',
        'keterangan',
        'id_user_pencatat',
        'status_verifikasi'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'total_pemasukan' => 'decimal:2',
        'status_verifikasi' => 'boolean'
    ];

    // Relationship dengan user yang mencatat
    public function pencatat()
    {
        return $this->belongsTo(User::class, 'id_user_pencatat');
    }

    // Relationship dengan penjualan
    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'id_penjualan');
    }

    // Scope untuk pemasukan yang perlu diverifikasi
    public function scopePerluVerifikasi($query)
    {
        return $query->where('status_verifikasi', 0);
    }

    // Scope untuk pemasukan yang sudah diverifikasi
    public function scopeSudahDiverifikasi($query)
    {
        return $query->where('status_verifikasi', 1);
    }

    // Accessor untuk status verifikasi
    public function getStatusVerifikasiTextAttribute()
    {
        return $this->status_verifikasi ? 'Terverifikasi' : 'Menunggu Verifikasi';
    }

    // Accessor untuk sumber pemasukan dalam bahasa Indonesia
    public function getSumberPemasukanTextAttribute()
    {
        $sumber = [
            'penjualan_buah' => 'Penjualan Buah',
            'lainnya' => 'Lainnya'
        ];

        return $sumber[$this->sumber_pemasukan] ?? $this->sumber_pemasukan;
    }
}