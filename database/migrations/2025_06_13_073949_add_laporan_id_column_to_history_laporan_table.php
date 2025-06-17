<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('history_laporan', function (Blueprint $table) {
            $table->unsignedBigInteger('laporan_id')->after('user_id');
            $table->foreign('laporan_id')->references('id')->on('pelaporan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('history_laporan', function (Blueprint $table) {
            $table->dropForeign(['laporan_id']);
            $table->dropColumn('laporan_id');
        });
    }
};
