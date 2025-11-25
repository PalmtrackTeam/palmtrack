<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pengeluaran_pupuk', function (Blueprint $table) {
            $table->id('id_pupuk');
            $table->foreignId('id_pengeluaran')->constrained('pengeluaran', 'id_pengeluaran')->onDelete('cascade');
            $table->string('jenis_pupuk', 100);
            $table->decimal('jumlah', 10, 2);
            $table->decimal('harga_satuan', 12, 2);
            $table->decimal('total_harga', 12, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pengeluaran_pupuk');
    }
};