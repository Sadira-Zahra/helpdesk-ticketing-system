<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Departemen;

class DepartemenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departemen = [
            ['nama_departemen' => 'IT'],
            ['nama_departemen' => 'GA-EHS'],
            ['nama_departemen' => 'HR'],
            ['nama_departemen' => 'PUR'],
        ];

        foreach ($departemen as $data) {
            Departemen::create($data);
        }
    }
}
