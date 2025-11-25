<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pengeluaran_transportasi', function (Blueprint $table) {
            $table->id('id_transport');
            $table->foreignId('id_pengeluaran')->constrained('pengeluaran', 'id_pengeluaran')->onDelete('cascade');
            $table->string('tujuan', 100);
            $table->decimal('biaya', 12, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pengeluaran_transportasi');
    }
};