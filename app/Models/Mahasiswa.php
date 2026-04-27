<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    // Tambahkan baris ini!
    protected $table = 'mahasiswa'; 

    protected $fillable = ['nama', 'nim', 'c1', 'c2', 'c3', 'c4', 'c5'];
}