<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengeluaranGaji extends Model
{
    protected $table = 'pengeluaran_gaji';
    protected $primaryKey = 'id_gaji';

    protected $fillable = [
        'id_pengeluaran',
        'id_user',
        'periode',
        'total_gaji',
        'tanggal_generate',
    ];

    public function pengeluaran()
    {
        return $this->belongsTo(Pengeluaran::class, 'id_pengeluaran', 'id_pengeluaran');
    }

    // Relasi ke user karyawan
    public function karyawan()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user'); // id_user di tabel user
    }
}
