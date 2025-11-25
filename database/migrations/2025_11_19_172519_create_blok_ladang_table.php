<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('blok_ladang', function (Blueprint $table) {
            $table->id('id_blok');
            $table->string('nama_blok', 50);
            $table->enum('kategori', ['dekat', 'jauh']);
            $table->decimal('luas_hektar', 8, 2)->nullable();
            $table->decimal('harga_upah_per_kg', 10, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('blok_ladang');
    }
};