<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id_user';

    protected $fillable = [
        'username',
        'nama_lengkap',
        'email',
        'password',
        'role',
        'status_aktif',
        'no_telepon',
        'alamat',
        'id_blok',
        'tanggal_bergabung',
        'bisa_input_panen',
        'bisa_input_absen',
        'email_verified_at', // opsional (umumnya tidak perlu dimasukkan)
    ];

    protected $hidden = [
        'password',
        // 'remember_token', // tambahkan kalau kolomnya ada di tabel
    ];

    protected $casts = [
        'status_aktif' => 'boolean',
        'bisa_input_panen' => 'boolean',
        'bisa_input_absen' => 'boolean',
        'tanggal_bergabung' => 'date',
        'email_verified_at' => 'datetime', // penting untuk fitur verifikasi email
    ];

    public function username()
    {
        return 'username';
    }

    public function getEmailForPasswordReset()
    {
        return $this->email;
    }

    public function routeNotificationFor($driver)
    {
        if ($driver === 'mail') {
            return $this->email;
        }
    }

    public function isOwner()
    {
        return $this->role === 'owner';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isKaryawan()
    {
        return $this->role === 'karyawan';
    }

    public function isAktif()
    {
        return $this->status_aktif === true;
    }

    public function gaji()
    {
        return $this->hasMany(PengeluaranGaji::class, 'id_user', 'id_user');
    }

    public function karyawan()
    {
        return $this->hasMany(PengeluaranGaji::class, 'id_user', 'id_user');
    }

    public function laporanMasalah()
    {
        return $this->hasMany(LaporanMasalah::class, 'id_user');
    }

    public function laporanDitangani()
    {
        return $this->hasMany(LaporanMasalah::class, 'ditangani_oleh');
    }
}
