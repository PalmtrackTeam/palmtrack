<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('panen_harian', function (Blueprint $table) {
            $table->id('id_panen');
            $table->foreignId('id_user')->constrained('users', 'id_user');
            $table->foreignId('id_blok')->constrained('blok_ladang', 'id_blok');
            $table->date('tanggal');
            $table->decimal('jumlah_kg', 10, 2);
            $table->enum('jenis_buah', ['buah_segar', 'buah_gugur']);
            $table->decimal('harga_upah_per_kg', 10, 2);
            $table->decimal('total_upah', 12, 2);
            $table->enum('status_panen', ['draft', 'diverifikasi', 'dibayar'])->default('draft');
            $table->foreignId('diverifikasi_oleh')->nullable()->constrained('users', 'id_user');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('panen_harian');
    }
};