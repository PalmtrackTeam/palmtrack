<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengeluaranPupuk extends Model
{
    use HasFactory;

    protected $table = 'pengeluaran_pupuk';
    protected $primaryKey = 'id_pupuk';

    protected $fillable = [
        'id_pengeluaran',
        'jenis_pupuk',
        'jumlah',
        'harga_satuan',
        'total_harga'
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'harga_satuan' => 'decimal:2',
        'total_harga' => 'decimal:2'
    ];

    public function pengeluaran()
    {
        return $this->belongsTo(Pengeluaran::class, 'id_pengeluaran');
    }
}