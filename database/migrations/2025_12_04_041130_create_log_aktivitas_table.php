<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('log_aktivitas', function (Blueprint $table) {
        $table->id();

        $table->unsignedBigInteger('id_user');

        $table->foreign('id_user')
              ->references('id_user')   // <- disesuaikan
              ->on('users')
              ->onDelete('cascade');

        $table->string('aksi');
        $table->string('tabel_terkait')->nullable();
        $table->text('deskripsi')->nullable();
        $table->timestamp('waktu')->useCurrent();
        $table->string('ip_address')->nullable();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_aktivitas');
    }
};
