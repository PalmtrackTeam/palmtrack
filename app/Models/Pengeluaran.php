<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    protected $table = 'pengeluaran';
    protected $primaryKey = 'id_pengeluaran';

    protected $fillable = [
        'tanggal',
        'jenis_pengeluaran',
        'total_biaya',
        'keterangan',
        'id_user_pencatat',
       
    ];

    // Relasi ke user pencatat
    public function pencatat()
    {
        return $this->belongsTo(User::class, 'id_user_pencatat', 'id_user');
    }

    // Relasi ke detail pupuk
    public function pupuk()
    {
        return $this->hasOne(PengeluaranPupuk::class, 'id_pengeluaran', 'id_pengeluaran');
    }

    // Relasi ke detail transportasi
    public function transportasi()
    {
        return $this->hasOne(PengeluaranTransportasi::class, 'id_pengeluaran', 'id_pengeluaran');
    }

    // Relasi ke detail perawatan
    public function perawatan()
    {
        return $this->hasOne(PengeluaranPerawatan::class, 'id_pengeluaran', 'id_pengeluaran');
    }

    // Relasi ke detail gaji
    // Di Model Pengeluaran, update relasi gaji:
public function gaji()
{
    return $this->hasOne(PengeluaranGaji::class, 'id_pengeluaran', 'id_pengeluaran');
}
}