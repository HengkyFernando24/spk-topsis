<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    // Kalau nama tabel di DB adalah 'mahasiswa', paksa di sini:
    protected $table = 'mahasiswa'; 

    protected $fillable = ['nama', 'nim', 'c1', 'c2', 'c3', 'c4', 'c5'];

public function penilaian() {
    return $this->hasMany(Penilaian::class);
}

public function hasilSPK() {
    return $this->hasOne(HasilSPK::class); // Karena 1 mahasiswa mendapat 1 hasil SPK
}




}
