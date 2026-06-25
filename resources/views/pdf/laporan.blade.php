<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <style>
        body { font-family: sans-serif; font-size: 11pt; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h2 { margin: 0; padding: 0; font-size: 16pt; }
        .header p { margin: 5px 0 0 0; font-size: 10pt; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #999; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; font-size: 10pt; }
        td { font-size: 10pt; }
        .text-center { text-align: center; }
    </style>
</head>
<body>

    <div class="header">
        <h2>{{ $title }}</h2>
        <p>Tanggal Cetak: {{ $date }} | Sistem Penunjang Keputusan Metode TOPSIS</p>
    </div>

    <h3>1. Daftar Nilai Alternatif</h3>
    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 15%">NIM</th>
                <th style="width: 30%">Nama Mahasiswa</th>
                @foreach($kriteria as $k)
                    <th>{{ $k['nama'] ?? $k['nama_kriteria'] }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($mahasiswa as $index => $m)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $m['nim'] ?? '-' }}</td>
                <td>{{ $m['nama'] ?? '-' }}</td>
                @foreach($kriteria as $k)
                    <td>
                        {{ $m['nilai_kriteria'][$k['id']] ?? '-' }}
                    </td>
                @endforeach
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center">Tidak ada data untuk ditampilkan</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <h3>2. Hasil Akhir & Peringkat</h3>
    <table>
        <thead>
            <tr>
                <th style="width: 10%">Peringkat</th>
                <th style="width: 20%">NIM</th>
                <th style="width: 40%">Nama Mahasiswa</th>
                <th style="width: 30%">Nilai Kedekatan Relatif (V)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($mahasiswa as $m)
            <tr>
                <td class="text-center"><b>{{ $m['peringkat'] }}</b></td>
                <td>{{ $m['nim'] ?? '-' }}</td>
                <td>{{ $m['nama'] ?? '-' }}</td>
                <td>{{ $m['nilai_v'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>