<?php

namespace Database\Seeders;

use App\Models\Mahasiswa;
use Illuminate\Database\Seeder;

class MahasiswaSeeder extends Seeder
{
    public function run(): void
    {
        // Sengaja dikosongin blay, 
        // karena data mahasiswa bakal diinput manual lewat form di Web.

        // Opsional: Kalau lo mau tiap kali seeder dijalankan tabelnya bersih dulu
        Mahasiswa::truncate(); 
    }
}