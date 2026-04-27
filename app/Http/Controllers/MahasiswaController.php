<?php

namespace App\Http\Controllers; // Pastikan ini ada dan benar

use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class MahasiswaController extends Controller 
{
    public function index()
    {
        $students = Mahasiswa::all();
        return view('topsis.index', compact('students')); // pastikan nama file view-nya sesuai
    }
}