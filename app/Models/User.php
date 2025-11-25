<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id_user';

    protected $fillable = [
        'username',
        'email', // Pastikan email ada di fillable
        'password',
        'nama_lengkap',
        'jabatan',
        'role',
        'status_aktif',
        'no_telepon',
        'alamat',
        'tanggal_bergabung',
        'status_tinggal',
        'bisa_input_panen',
        'bisa_input_absen'
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'status_aktif' => 'boolean',
        'bisa_input_panen' => 'boolean',
        'bisa_input_absen' => 'boolean',
        'tanggal_bergabung' => 'date',
    ];

    // Method untuk authentication dengan username
    public function username()
    {
        return 'username';
    }

    // Method untuk password reset dengan email
    public function getEmailForPasswordReset()
    {
        return $this->email;
    }

    // Untuk notifikasi
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
}