<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pengeluaran', function (Blueprint $table) {
            $table->id('id_pengeluaran');
            $table->date('tanggal');
            $table->enum('jenis_pengeluaran', ['pupuk', 'transportasi', 'perawatan', 'gaji', 'lainnya']);
            $table->decimal('total_biaya', 12, 2);
            $table->text('keterangan')->nullable();
            $table->foreignId('id_user_pencatat')->constrained('users', 'id_user');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pengeluaran');
    }
};