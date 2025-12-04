<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pemasukan', function (Blueprint $table) {
            $table->id('id_pemasukan');

            // FIX: tidak lagi foreign key ke tabel penjualan
            $table->unsignedBigInteger('id_penjualan')->nullable();

            $table->date('tanggal');
            $table->enum('sumber_pemasukan', ['penjualan_buah', 'lainnya'])
                  ->default('penjualan_buah');
            $table->decimal('total_pemasukan', 12, 2);
            $table->string('keterangan', 255)->nullable();

            $table->foreignId('id_user_pencatat')
                  ->constrained('users', 'id_user');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pemasukan');
    }
};
