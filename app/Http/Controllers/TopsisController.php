<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\Kriteria;

class TopsisController extends Controller
{
    public function index()
    {
        // 1. AMBIL DATA KRITERIA DARI DATABASE
        $dataKriteria = Kriteria::all();
        
        // Kita petakan biar gampang dipake rumusnya
        $bobot = $dataKriteria->pluck('bobot', 'kode')->toArray(); // ['C1' => 0.3, 'C2' => 0.2, ...]
        $tipe = $dataKriteria->pluck('tipe', 'kode')->toArray();   // ['C1' => 'benefit', ...]
        $kriteriaKeys = $dataKriteria->pluck('kode')->toArray();   // ['C1', 'C2', ...]

        // 2. AMBIL DATA MAHASISWA DARI DATABASE
        $students = Mahasiswa::all()->toArray();

        // Cek dulu, kalau kosong jangan lanjut ngitung biar gak error
        if (empty($students) || empty($bobot)) {
            return view('topsis.index', [
                'dataMahasiswa' => [],
                'kriteria' => $dataKriteria
            ]);
        }

        // --- ALGORITMA TOPSIS MULAI ---

        // TAHAP 1: MENGHITUNG PEMBAGI NORMALISASI
        $pembagi = [];
        foreach ($kriteriaKeys as $c) {
            $sumSq = 0;
            $kodeKriteria = strtolower($c); // Sesuaikan dengan kolom di DB (misal: c1)
            foreach ($students as $s) {
                $sumSq += pow($s[$kodeKriteria], 2);
            }
            $pembagi[$c] = sqrt($sumSq);
        }

        // TAHAP 2: NORMALISASI MATRIKS & KALIKAN BOBOT
        $matriksTerbobot = [];
        foreach ($students as $k => $s) {
            foreach ($kriteriaKeys as $c) {
                $kodeKriteria = strtolower($c);
                $nilaiNormalisasi = ($pembagi[$c] == 0) ? 0 : ($s[$kodeKriteria] / $pembagi[$c]);
                $matriksTerbobot[$k][$c] = $nilaiNormalisasi * $bobot[$c];
            }
        }

        // TAHAP 3: SOLUSI IDEAL POSITIF (A+) & NEGATIF (A-)
        $aPlus = [];
        $aMinus = [];
        foreach ($kriteriaKeys as $c) {
            $kolomNilai = array_column($matriksTerbobot, $c);
            
            if ($tipe[$c] == 'benefit') {
                $aPlus[$c] = max($kolomNilai);
                $aMinus[$c] = min($kolomNilai);
            } else { // Jika Cost
                $aPlus[$c] = min($kolomNilai);
                $aMinus[$c] = max($kolomNilai);
            }
        }

        // TAHAP 4: JARAK EUCLIDEAN & NILAI PREFERENSI (V)
        foreach ($students as $k => $s) {
            $dPlus = 0;
            $dMinus = 0;

            foreach ($kriteriaKeys as $c) {
                $dPlus += pow($matriksTerbobot[$k][$c] - $aPlus[$c], 2);
                $dMinus += pow($matriksTerbobot[$k][$c] - $aMinus[$c], 2);
            }

            $dPlus = sqrt($dPlus);
            $dMinus = sqrt($dMinus);

            $v = ($dPlus + $dMinus) == 0 ? 0 : ($dMinus / ($dPlus + $dMinus));
            $students[$k]['v'] = $v;
        }

        // TAHAP 5: PERANKINGAN (Urutkan dari V tertinggi)
        usort($students, function($a, $b) {
            return $b['v'] <=> $a['v'];
        });

        // 3. KIRIM KE VIEW
        return view('topsis.index', [
            'dataMahasiswa' => $students,
            'kriteria' => $dataKriteria
        ]);
    }

    // Fungsi tambahan buat update kriteria dari Web
   public function updateKriteria(Request $request, $id) {
    $kriteria = \App\Models\Kriteria::find($id);
    $kriteria->update($request->all());
    return response()->json(['message' => 'Success']);
}
}