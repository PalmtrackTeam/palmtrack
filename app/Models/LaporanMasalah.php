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

    // Scope untuk laporan yang perlu perhatian owner
    public function scopePerluPerhatianOwner($query)
    {
        return $query->where('diteruskan_ke_owner', true)
                    ->where('status_masalah', '!=', 'selesai');
    }

    public function scopeSudahSelesai($query)
    {
        return $query->where('status_masalah', 'selesai');
    }

    public function getStatusColorAttribute()
    {
        return match($this->status_masalah) {
            'baru' => 'bg-blue-100 text-blue-800',
            'dalam_penanganan' => 'bg-yellow-100 text-yellow-800',
            'selesai' => 'bg-green-100 text-green-800',
            default => 'bg-gray-100 text-gray-800'
        };
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