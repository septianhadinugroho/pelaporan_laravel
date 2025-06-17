<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema; // Add this line

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Disable foreign key constraints
        Schema::disableForeignKeyConstraints(); // Add this line

        // Truncate the table to ensure a clean slate before seeding
        DB::table('status')->truncate();

        // Enable foreign key constraints
        Schema::enableForeignKeyConstraints(); // Add this line

        DB::table('status')->insert([
            ['id' => 1, 'nama_status' => 'Menunggu'],
            ['id' => 2, 'nama_status' => 'Selesai'],
        ]);
    }
}