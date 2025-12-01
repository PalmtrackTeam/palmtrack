<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
  Schema::create('users', function (Blueprint $table) {
    $table->id('id_user');
    $table->string('username', 50);
    $table->string('email');
    $table->string('password');
    $table->string('nama_lengkap', 100);
    $table->enum('role', ['owner', 'admin', 'karyawan'])->default('karyawan');
    $table->boolean('status_aktif')->default(true);
    $table->string('no_telepon', 20)->nullable();
    $table->text('alamat')->nullable();
    $table->date('tanggal_bergabung')->nullable();
    $table->unsignedBigInteger('id_blok')->nullable(); // hapus ->after('alamat')
    $table->boolean('bisa_input_panen')->default(true);
    $table->boolean('bisa_input_absen')->default(true);
    $table->rememberToken();
    $table->timestamps();
});

    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['id_blok']);
        });

        Schema::dropIfExists('users');
    }
};
