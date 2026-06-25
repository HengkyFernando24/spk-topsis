<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->id();
             $table->string('nim')->nullable(); // Tambahkan ini agar tidak error!
            $table->string('nama');
            $table->float('c1'); // IPK
            $table->integer('c2'); // Kehadiran
            $table->integer('c3'); // Prestasi
            $table->integer('c4'); // Terlambat
            $table->integer('c5'); // Tugas
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswa');
    }
};
