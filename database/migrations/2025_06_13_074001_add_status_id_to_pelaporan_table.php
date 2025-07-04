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
        Schema::table('pelaporan', function (Blueprint $table) {
            $table->unsignedBigInteger('status_id')->after('lokasi');
            $table->foreign('status_id')->references('id')->on('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pelaporan', function (Blueprint $table) {
            $table->dropForeign(['status_id']); // Menghapus foreign key
            $table->dropColumn('status_id'); // Menghapus kolom status_id
        });
    }
};
