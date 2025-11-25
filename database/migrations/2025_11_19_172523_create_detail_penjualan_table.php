<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('detail_penjualan', function (Blueprint $table) {
            $table->id('id_detail_penjualan');
            $table->foreignId('id_penjualan')->constrained('penjualan', 'id_penjualan')->onDelete('cascade');
            $table->enum('jenis_buah', ['buah_segar', 'buah_gugur']);
            $table->decimal('jumlah_kg', 10, 2);
            $table->decimal('harga_jual_kg', 10, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('detail_penjualan');
    }
};