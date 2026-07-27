<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Lapangan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // User Admin
        User::create([
            'name' => 'Admin SM Sport Center',
            'email' => 'admin@smsport.com',
            'password' => Hash::make('password123'),
            'no_hp' => '081234567890',
            'role' => 'admin',
        ]);

        // User Pelanggan Dummy
        User::create([
            'name' => 'Pelanggan Dummy',
            'email' => 'pelanggan@gmail.com',
            'password' => Hash::make('password123'),
            'no_hp' => '089876543210',
            'role' => 'pelanggan',
        ]);

        // 2 Lapangan Futsal & 3 Lapangan Badminton
        $lapangans = [
            ['nama_lapangan' => 'Lapangan Futsal A (Vinyl)', 'jenis' => 'futsal', 'harga_per_jam' => 150000],
            ['nama_lapangan' => 'Lapangan Futsal B (Sintetis)', 'jenis' => 'futsal', 'harga_per_jam' => 120000],
            ['nama_lapangan' => 'Lapangan Badminton 1', 'jenis' => 'badminton', 'harga_per_jam' => 50000],
            ['nama_lapangan' => 'Lapangan Badminton 2', 'jenis' => 'badminton', 'harga_per_jam' => 50000],
            ['nama_lapangan' => 'Lapangan Badminton 3', 'jenis' => 'badminton', 'harga_per_jam' => 50000],
        ];

        foreach ($lapangans as $lapangan) {
            Lapangan::create($lapangan);
        }
    }
}