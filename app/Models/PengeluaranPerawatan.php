<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengeluaranPerawatan extends Model
{
    protected $table = 'pengeluaran_perawatan';

    protected $primaryKey = 'id_perawatan';

    protected $fillable = [
        'id_pengeluaran',
        'jenis_perawatan',
        'biaya',
    ];

    public function pengeluaran()
    {
        return $this->belongsTo(Pengeluaran::class, 'id_pengeluaran', 'id_pengeluaran');
    }
}
