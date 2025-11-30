<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    use HasFactory;

    protected $table = 'pengeluaran';
    protected $primaryKey = 'id_pengeluaran';
    
    protected $fillable = [
        'tanggal',
        'jenis_pengeluaran', 
        'total_biaya',
        'keterangan',
        'id_user_pencatat'
    ];

    // Relationship dengan user pencatat
    public function pencatat()
    {
        return $this->belongsTo(User::class, 'id_user_pencatat', 'id_user');
    }

    // Relationship dengan pupuk
    public function pupuk()
    {
        return $this->hasOne(PengeluaranPupuk::class, 'id_pengeluaran', 'id_pengeluaran');
    }

    // Relationship dengan transportasi
    public function transportasi()
    {
        return $this->hasOne(PengeluaranTransportasi::class, 'id_pengeluaran', 'id_pengeluaran');
    }

    // Relationship dengan perawatan
    public function perawatan()
    {
        return $this->hasOne(PengeluaranPerawatan::class, 'id_pengeluaran', 'id_pengeluaran');
    }

    // Relationship dengan gaji
    public function gaji()
    {
        return $this->hasOne(PengeluaranGaji::class, 'id_pengeluaran', 'id_pengeluaran');
    }
}