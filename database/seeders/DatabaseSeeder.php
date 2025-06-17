<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,      // Pastikan RoleSeeder dipanggil pertama
            KategoriSeeder::class,
            StatusSeeder::class,
        ]);

        User::factory()->create([
            'name' => 'Septian H N',
            'email' => 'septian@gmail.com',
            'password' => Hash::make('hadi1234'),
            'role_id' => 2,
        ]);

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'role_id' => 1,
        ]);
    }
}