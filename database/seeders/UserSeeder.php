<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ============================================
        // ADMIN USER (DENGAN DEPARTEMEN)
        // ============================================
        User::create([
            'nik' => '1234567890123456',
            'nama' => 'Admin User',
            'email' => 'admin@helpdesk.local',
            'no_telepon' => '081234567890',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'departemen_id' => 5,
        ]);

        // ============================================
        // ADMINISTRATOR USER (TANPA DEPARTEMEN)
        // ============================================
        User::create([
            'nik' => '1234567890123457',
            'nama' => 'Administrator User',
            'email' => 'administrator@helpdesk.local',
            'no_telepon' => '081234567891',
            'password' => Hash::make('administrator123'),
            'role' => 'administrator',
            'departemen_id' => null,
        ]);

        // ============================================
        // TEKNISI USERS
        // ============================================
        User::create([
            'nik' => '1234567890123458',
            'nama' => 'Teknisi Satu',
            'email' => 'teknisi1@helpdesk.local',
            'no_telepon' => '081234567892',
            'password' => Hash::make('teknisi123'),
            'role' => 'teknisi',
            'departemen_id' => 5,
        ]);

        User::create([
            'nik' => '1234567890123459',
            'nama' => 'Teknisi Dua',
            'email' => 'teknisi2@helpdesk.local',
            'no_telepon' => '081234567893',
            'password' => Hash::make('teknisi123'),
            'role' => 'teknisi',
            'departemen_id' => 6,
        ]);

        User::create([
            'nik' => '1234567890123460',
            'nama' => 'Teknisi Tiga',
            'email' => 'teknisi3@helpdesk.local',
            'no_telepon' => '081234567894',
            'password' => Hash::make('teknisi123'),
            'role' => 'teknisi',
            'departemen_id' => 7,
        ]);

        // ============================================
        // REGULAR USERS
        // ============================================
        User::create([
            'nik' => '1234567890123461',
            'nama' => 'User Satu',
            'email' => 'user1@helpdesk.local',
            'no_telepon' => '081234567895',
            'password' => Hash::make('user123'),
            'role' => 'user',
            'departemen_id' => 5,
        ]);

        User::create([
            'nik' => '1234567890123462',
            'nama' => 'User Dua',
            'email' => 'user2@helpdesk.local',
            'no_telepon' => '081234567896',
            'password' => Hash::make('user123'),
            'role' => 'user',
            'departemen_id' => 6,
        ]);

        User::create([
            'nik' => '1234567890123463',
            'nama' => 'User Tiga',
            'email' => 'user3@helpdesk.local',
            'no_telepon' => '081234567897',
            'password' => Hash::make('user123'),
            'role' => 'user',
            'departemen_id' => 7,
        ]);

        User::create([
            'nik' => '1234567890123464',
            'nama' => 'User Empat',
            'email' => 'user4@helpdesk.local',
            'no_telepon' => '081234567898',
            'password' => Hash::make('user123'),
            'role' => 'user',
            'departemen_id' => 8,
        ]);
    }
}
