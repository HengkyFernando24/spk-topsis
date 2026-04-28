<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class MahasiswaController extends Controller 
{
    public function index()
    {
        // 1. Ambil semua data mahasiswa
        $students = Mahasiswa::all();

        // 2. KIRIM SEBAGAI JSON (Bukan view blay!)
        // Ini yang bikin Vue lo bisa baca datanya
        return response()->json($students);
    }
}