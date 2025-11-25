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

    protected $casts = [
        'tanggal' => 'date',
        'total_biaya' => 'decimal:2'
    ];

    public function pencatat()
    {
        return $this->belongsTo(User::class, 'id_user_pencatat');
    }
    public function pupuk()
{
    return $this->hasOne(PengeluaranPupuk::class, 'id_pengeluaran', 'id_pengeluaran');
}

}

