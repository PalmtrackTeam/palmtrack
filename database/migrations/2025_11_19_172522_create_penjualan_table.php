<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('penjualan', function (Blueprint $table) {
            $table->id('id_penjualan');
            $table->date('tanggal');
            $table->enum('tujuan_jual', ['ram_family', 'pemilik_setempat', 'pabrik', 'lainnya']);
            $table->string('pembeli', 100);
            $table->decimal('total_berat_kg', 10, 2)->default(0);
            $table->decimal('total_pemasukan', 12, 2)->default(0);
            $table->foreignId('id_user_pencatat')->constrained('users', 'id_user');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('penjualan');
    }
};