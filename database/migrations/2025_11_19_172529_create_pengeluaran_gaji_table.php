<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pengeluaran_gaji', function (Blueprint $table) {
            $table->id('id_gaji');
            $table->foreignId('id_pengeluaran')->constrained('pengeluaran', 'id_pengeluaran')->onDelete('cascade');
            $table->foreignId('id_user')->constrained('users', 'id_user');
            $table->string('periode', 20);
            $table->decimal('total_gaji', 12, 2);
            $table->datetime('tanggal_generate')->useCurrent();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pengeluaran_gaji');
    }
};