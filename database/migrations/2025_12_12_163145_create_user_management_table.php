<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        //  HAK AKSES UNTUK USER MANDOR/admin
        DB::statement("GRANT SELECT, INSERT, UPDATE ON sawit_db.absensi TO 'admin'@'localhost'");
        DB::statement("GRANT SELECT, INSERT, UPDATE ON sawit_db.blok_ladang TO 'admin'@'localhost'");
        DB::statement("GRANT SELECT, INSERT, UPDATE ON sawit_db.laporan_masalah TO 'admin'@'localhost'");
        DB::statement("GRANT SELECT, INSERT, UPDATE ON sawit_db.panen_harian TO 'admin'@'localhost'");

        DB::statement("GRANT SELECT, INSERT, UPDATE ON sawit_db.pengeluaran_gaji TO 'admin'@'localhost'");
        DB::statement("GRANT SELECT, INSERT, UPDATE ON sawit_db.pengeluaran_perawatan TO 'admin'@'localhost'");
        DB::statement("GRANT SELECT, INSERT, UPDATE ON sawit_db.pengeluaran_pupuk TO 'admin'@'localhost'");
        DB::statement("GRANT SELECT, INSERT, UPDATE ON sawit_db.pengeluaran_transportasi TO 'admin'@'localhost'");

        DB::statement("GRANT SELECT ON sawit_db.v_kinerja_karyawan TO 'admin'@'localhost'");
        DB::statement("GRANT SELECT ON sawit_db.v_rekap_harian TO 'admin'@'localhost'");

        //  HAK AKSES UNTUK USER OWNER
        //  Owner bisa akses semua di DB
        DB::statement("GRANT ALL PRIVILEGES ON sawit_db.* TO 'owner'@'localhost'");


        // Terapkan perubahan
        DB::statement("FLUSH PRIVILEGES");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Untuk rollback, cukup reload privilege
        DB::statement("FLUSH PRIVILEGES");
    }
};
