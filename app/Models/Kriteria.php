<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    use HasFactory;

    protected $table = 'kriteria'; // Tetap pertahankan ini

    // Sesuaikan nama kolom dengan class diagram
    protected $fillable = ['kode_kriteria', 'nama_kriteria', 'jenis_atribut', 'bobot', 'tipe'];

    // Tambahkan relasi ke tabel Penilaian
    public function penilaian()
    {
        return $this->hasMany(Penilaian::class);
    }
}