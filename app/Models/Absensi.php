<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $table = 'absensi';
    protected $primaryKey = 'id_absensi';

    protected $fillable = [
        'id_user',
        'tanggal',
        'status_kehadiran',
        'keterangan'
    ];

    public $timestamps = false; 
}
