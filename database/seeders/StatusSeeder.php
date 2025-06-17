<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('status')->insert([
            ['nama_status' => 'Dalam Antrian'],
            ['nama_status' => 'Sedang Diproses'],
            ['nama_status' => 'Selesai'],
        ]);
    }
}
