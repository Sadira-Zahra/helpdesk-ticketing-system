<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Jalankan DepartemenSeeder terlebih dahulu
        $this->call([
            DepartemenSeeder::class,
            UserSeeder::class,
        ]);
    }
}
