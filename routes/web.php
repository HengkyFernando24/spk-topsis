<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TopsisController;
use App\Http\Controllers\MahasiswaController;

// 1. Jadikan TopsisController sebagai halaman utama
Route::get('/', [TopsisController::class, 'index']);

// 2. Berikan alamat yang berbeda untuk MahasiswaController (misal: /mahasiswa)
Route::get('/mahasiswa', [MahasiswaController::class, 'index']);

// Route untuk API AJAX tetap aman
Route::get('/hitung-topsis', [TopsisController::class, 'hitungAPI'])->name('topsis.hitung.api');