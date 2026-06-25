<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TopsisController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\AuthController;

// 1. PINTU MASUK UTAMA: Langsung lempar ke halaman login
Route::get('/', function () {
    return redirect('/login');
});

// 2. GESER TOPSIS: Jadikan TopsisController sebagai halaman Dashboard (setelah login)
Route::get('/dashboard', [TopsisController::class, 'index']);

// 3. Alamat untuk MahasiswaController
Route::get('/mahasiswa', [MahasiswaController::class, 'index']);

// 4. Route untuk API AJAX tetap aman
Route::get('/hitung-topsis', [TopsisController::class, 'hitungAPI'])->name('topsis.hitung.api');

// 5. NAMPILIN halaman UI login-nya
Route::get('/login', function () {
    return view('topsis.login'); // Memanggil file login.blade.php
})->name('login');

// 6. PROSES Login & Logout
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);



Route::post('/topsis/laporan-lengkap', [TopsisController::class, 'generateLaporan']);