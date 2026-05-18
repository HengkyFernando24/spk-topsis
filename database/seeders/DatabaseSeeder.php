<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // Jangan lupa import ini blay

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Pakai User::create aja biar lebih simpel dan langsung eksekusi
        User::create([
          'name' => 'admin',
    'email' => 'admin@spk.com', // Wajib diisi meski gak dipake login
    'password' => Hash::make('admin123'),
        ]);
    }
}