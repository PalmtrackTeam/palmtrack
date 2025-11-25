<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rekap_keuangan', function (Blueprint $table) {
            $table->id('id_rekap');
            $table->datetime('tanggal_generate')->useCurrent();
            $table->string('periode', 20);
            $table->enum('tipe_periode', ['harian', '10_harian', 'bulanan', 'tahunan']);
            $table->decimal('total_pemasukan', 12, 2)->default(0);
            $table->decimal('total_pengeluaran_pupuk', 12, 2)->default(0);
            $table->decimal('total_pengeluaran_transport', 12, 2)->default(0);
            $table->decimal('total_pengeluaran_perawatan', 12, 2)->default(0);
            $table->decimal('total_pengeluaran_gaji', 12, 2)->default(0);
            $table->decimal('total_pengeluaran_lainnya', 12, 2)->default(0);
            $table->decimal('total_pengeluaran_all', 12, 2)->default(0);
            $table->integer('total_kehadiran')->default(0);
            $table->integer('total_izin')->default(0);
            $table->integer('total_sakit')->default(0);
            $table->integer('total_alpha')->default(0);
            $table->integer('total_karyawan_aktif')->default(0);
            $table->integer('total_laporan_masalah')->default(0);
            $table->integer('total_masalah_selesai')->default(0);
            $table->decimal('laba_bersih', 12, 2)->default(0);
            $table->decimal('margin_keuntungan', 5, 2)->default(0);
            $table->decimal('efisiensi_kehadiran', 5, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rekap_keuangan');
    }
};