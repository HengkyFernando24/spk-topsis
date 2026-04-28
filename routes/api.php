<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TopsisController;
use App\Http\Controllers\MahasiswaController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/mahasiswa', [TopsisController::class, 'getMahasiswa']);
Route::get('/mahasiswa', [MahasiswaController::class, 'index']);



Route::get('/kriteria', [TopsisController::class, 'getKriteria']);
Route::put('/kriteria/{id}', [TopsisController::class, 'updateKriteria']);
Route::delete('/kriteria/{id}', [TopsisController::class, 'deleteKriteria']);

Route::post('/topsis/hitung', [TopsisController::class, 'hitungTopsis']);
