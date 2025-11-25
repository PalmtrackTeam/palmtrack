<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('laporan_masalah', function (Blueprint $table) {
            $table->id('id_masalah');

            // === Foreign key ke users ===
            $table->foreignId('id_user')->constrained('users', 'id_user');

            // === Foreign key ke blok_ladang ===
            $table->unsignedBigInteger('id_blok')->nullable();
            
            $table->date('tanggal');
            $table->enum('jenis_masalah', ['Cuaca Buruk', 'Kemalingan', 'Serangan Hama', 'Kerusakan Alat', 'Lainnya']);
            $table->text('deskripsi');
            $table->text('tindakan')->nullable();
            $table->enum('status_masalah', ['dilaporkan', 'dalam_penanganan', 'selesai'])->default('dilaporkan');
            $table->foreignId('ditangani_oleh')->nullable()->constrained('users', 'id_user');
            $table->datetime('tanggal_selesai')->nullable();
            $table->enum('tingkat_keparahan', ['ringan', 'sedang', 'berat'])->default('ringan');
            $table->boolean('diteruskan_ke_owner')->default(false);
            $table->foreignId('ditandai_oleh')->nullable()->constrained('users', 'id_user');
            $table->timestamps();
        });

        // FK setelah tabel dibuat
        Schema::table('laporan_masalah', function (Blueprint $table) {
            $table->foreign('id_blok')
                  ->references('id_blok')
                  ->on('blok_ladang')
                  ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('laporan_masalah');
    }
};
