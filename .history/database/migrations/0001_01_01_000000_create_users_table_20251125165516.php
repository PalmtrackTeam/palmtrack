<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            // Primary Key
            $table->id('id_user');

            // Auth fields
            $table->string('username', 50)->unique();
            $table->string('email')->unique(); // WAJIB karena register memerlukan email
            $table->string('password');

            // Data lengkap
            $table->string('nama_lengkap', 100);
            $table->enum('jabatan', ['mandor', 'asisten_mandor', 'anggota'])->default('anggota');
            $table->enum('role', ['owner', 'admin', 'karyawan'])->default('karyawan');
            $table->boolean('status_aktif')->default(true);
            $table->string('no_telepon', 20)->nullable();
            $table->text('alamat')->nullable();
            $table->date('tanggal_bergabung')->nullable();


            // Hak akses
            $table->boolean('bisa_input_panen')->default(true);
            $table->boolean('bisa_input_absen')->default(true);

            // Laravel default
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
