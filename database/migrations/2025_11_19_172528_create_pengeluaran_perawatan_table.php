<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pengeluaran_perawatan', function (Blueprint $table) {
            $table->id('id_perawatan');
            $table->foreignId('id_pengeluaran')->constrained('pengeluaran', 'id_pengeluaran')->onDelete('cascade');
            $table->string('jenis_perawatan', 100);
            $table->decimal('biaya', 12, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pengeluaran_perawatan');
    }
};