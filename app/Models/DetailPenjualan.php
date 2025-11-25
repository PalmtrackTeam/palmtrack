<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPenjualan extends Model
{
    use HasFactory;

    protected $table = 'detail_penjualan';
    protected $primaryKey = 'id_detail_penjualan';

    protected $fillable = [
        'id_penjualan',
        'jenis_buah',
        'jumlah_kg',
        'harga_jual_kg',
        'subtotal'
    ];

    protected $casts = [
        'jumlah_kg' => 'decimal:2',
        'harga_jual_kg' => 'decimal:2',
        'subtotal' => 'decimal:2'
    ];

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'id_penjualan');
    }
}