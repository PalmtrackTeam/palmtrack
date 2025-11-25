<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    protected $table = 'penjualan';
    protected $primaryKey = 'id_penjualan';

    protected $fillable = [
        'tanggal',
        'tujuan_jual',
        'pembeli',
        'total_berat_kg',
        'total_pemasukan',
        'id_user_pencatat',
        'keterangan'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'total_berat_kg' => 'decimal:2',
        'total_pemasukan' => 'decimal:2'
    ];

    public function detailPenjualan()
    {
        return $this->hasMany(DetailPenjualan::class, 'id_penjualan');
    }

    public function pencatat()
    {
        return $this->belongsTo(User::class, 'id_user_pencatat');
    }
}