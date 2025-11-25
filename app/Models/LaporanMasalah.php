<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanMasalah extends Model
{
    use HasFactory;

    protected $table = 'laporan_masalah';
    protected $primaryKey = 'id_masalah';

    protected $fillable = [
        'id_user',
        'tanggal',
        'jenis_masalah',
        'deskripsi',
        'tindakan',
        'status_masalah',
        'ditangani_oleh',
        'tanggal_selesai',
        'tingkat_keparahan',
        'diteruskan_ke_owner',
        'ditandai_oleh',
        'status_verifikasi'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'tanggal_selesai' => 'datetime',
        'diteruskan_ke_owner' => 'boolean',
        'status_verifikasi' => 'boolean'
    ];

    public function pelapor()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function penangan()
    {
        return $this->belongsTo(User::class, 'ditangani_oleh');
    }

    public function penanda()
    {
        return $this->belongsTo(User::class, 'ditandai_oleh');
    }

    public function scopePerluVerifikasi($query)
    {
        return $query->where('status_verifikasi', 0);
    }

    public function scopeSudahDiverifikasi($query)
    {
        return $query->where('status_verifikasi', 1);
    }

    public function getStatusVerifikasiTextAttribute()
    {
        return $this->status_verifikasi ? 'Terverifikasi' : 'Menunggu Verifikasi';
    }

    public function getJenisMasalahTextAttribute()
    {
        $jenis = [
            'Cuaca Buruk' => 'Cuaca Buruk',
            'Kemalingan' => 'Kemalingan',
            'Serangan Hama' => 'Serangan Hama',
            'Kerusakan Alat' => 'Kerusakan Alat',
            'Lainnya' => 'Lainnya'
        ];

        return $jenis[$this->jenis_masalah] ?? $this->jenis_masalah;
    }

    public function getTingkatKeparahanTextAttribute()
    {
        $tingkat = [
            'ringan' => 'Ringan',
            'sedang' => 'Sedang',
            'berat' => 'Berat'
        ];

        return $tingkat[$this->tingkat_keparahan] ?? $this->tingkat_keparahan;
    }
}