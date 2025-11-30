<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengeluaranTransportasi extends Model
{
    protected $table = 'pengeluaran_transportasi';

    protected $primaryKey = 'id_transport';

    protected $fillable = [
        'id_pengeluaran',
        'tujuan',
        'biaya',
    ];

    public function pengeluaran()
    {
        return $this->belongsTo(Pengeluaran::class, 'id_pengeluaran', 'id_pengeluaran');
    }
}
