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
    Schema::table('users', function (Blueprint $table) {
        if (!Schema::hasColumn('users', 'id_blok')) {
            $table->unsignedBigInteger('id_blok')->nullable()->after('alamat');

            $table->foreign('id_blok')
                ->references('id_blok')
                ->on('blok_ladang')
                ->onDelete('set null');
        }
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        if (Schema::hasColumn('users', 'id_blok')) {
            $table->dropForeign(['id_blok']);
            $table->dropColumn('id_blok');
        }
    });
}

};
