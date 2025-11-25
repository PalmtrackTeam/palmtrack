<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Users
        DB::table('users')->insert([
            [
                'username' => 'owner',
                'password' => Hash::make('owner123'),
                'nama_lengkap' => 'Pemilik Kebun',
                'jabatan' => 'mandor',
                'role' => 'owner',
                'status_aktif' => true,
                'no_telepon' => '081234567890',
                'status_tinggal' => 'luar',
                'bisa_input_panen' => false,
                'bisa_input_absen' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'mandor1',
                'password' => Hash::make('mandor123'),
                'nama_lengkap' => 'Mandor Utama',
                'jabatan' => 'mandor',
                'role' => 'admin',
                'status_aktif' => true,
                'no_telepon' => '081234567891',
                'status_tinggal' => 'barak',
                'bisa_input_panen' => false,
                'bisa_input_absen' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'karyawan1',
                'password' => Hash::make('karyawan123'),
                'nama_lengkap' => 'Budi Santoso',
                'jabatan' => 'anggota',
                'role' => 'karyawan',
                'status_aktif' => true,
                'no_telepon' => '081234567893',
                'status_tinggal' => 'barak',
                'bisa_input_panen' => true,
                'bisa_input_absen' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // Blok Ladang
        DB::table('blok_ladang')->insert([
            [
                'nama_blok' => 'Blok A',
                'kategori' => 'dekat',
                'luas_hektar' => 50.00,
                'harga_upah_per_kg' => 200.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_blok' => 'Blok B',
                'kategori' => 'dekat',
                'luas_hektar' => 50.00,
                'harga_upah_per_kg' => 200.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_blok' => 'Blok C',
                'kategori' => 'jauh',
                'luas_hektar' => 50.00,
                'harga_upah_per_kg' => 220.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_blok' => 'Blok D',
                'kategori' => 'jauh',
                'luas_hektar' => 50.00,
                'harga_upah_per_kg' => 220.00,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}