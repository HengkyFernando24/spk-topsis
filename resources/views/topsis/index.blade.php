<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPK TOPSIS - Universitas Nurul Huda</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #10b981; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #047857; }
        .hover-lift { transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .hover-lift:hover { transform: translateY(-5px); box-shadow: 0 20px 25px -5px rgba(16, 185, 129, 0.15), 0 10px 10px -5px rgba(16, 185, 129, 0.04); }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased overflow-x-hidden">

    <div class="flex min-h-screen relative">
        
        <aside class="w-72 bg-gradient-to-b from-emerald-950 via-green-900 to-emerald-900 text-white flex-col fixed h-full z-40 hidden md:flex shadow-[4px_0_24px_rgba(4,47,46,0.5)] no-print">
            <div class="absolute top-0 left-0 w-full h-64 bg-white opacity-5 blur-[80px] rounded-full pointer-events-none"></div>
            
            <div class="p-8 border-b border-white/10 relative z-10">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-400 to-green-500 flex items-center justify-center shadow-lg shadow-emerald-500/40 transform hover:rotate-12 transition-transform duration-300">
                        <span class="font-black text-white text-2xl">S</span>
                    </div>
                    <div>
                        <h1 class="text-xl font-black tracking-tight leading-none text-transparent bg-clip-text bg-gradient-to-r from-emerald-100 to-white">SPK TOPSIS</h1>
                        <p class="text-[10px] text-emerald-200/70 font-bold uppercase tracking-widest mt-1.5">Univ. Nurul Huda</p>
                    </div>
                </div>
            </div>
            
            <nav class="flex-1 px-4 py-8 space-y-2 overflow-y-auto relative z-10 custom-scrollbar">
                <p class="px-4 text-xs font-bold text-emerald-300/50 uppercase tracking-widest mb-4">Menu Sistem</p>
                <a href="#" class="group flex items-center gap-3 px-4 py-3.5 bg-gradient-to-r from-emerald-600 to-green-600 text-white rounded-xl shadow-lg shadow-emerald-900/50 ring-1 ring-white/20 transition-all hover:scale-[1.02]">
                    <svg class="w-5 h-5 text-emerald-100 group-hover:animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    <span class="font-bold tracking-wide">Data & Hasil SPK</span>
                </a>
            </nav>
        </aside>

        <main class="flex-1 md:ml-72 p-6 md:p-10 relative min-w-0">
            
            <header class="flex flex-col xl:flex-row justify-between items-start xl:items-end mb-10 gap-6">
                <div class="relative z-10">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="px-3 py-1 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-800 border border-emerald-200 uppercase tracking-widest shadow-sm">Angkatan 2023</span>
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-white text-slate-500 border border-slate-200 shadow-sm">Informatika</span>
                    </div>
                    <h2 class="text-4xl font-black text-slate-800 tracking-tight">Dashboard TOPSIS</h2>
                    <p class="text-slate-500 mt-2 text-sm font-medium">Sistem Pendukung Keputusan Pemilihan Mahasiswa Terbaik.</p>
                </div>
                
                <div class="flex gap-3 flex-wrap relative z-10">
                    <button id="btn-add" class="group flex items-center gap-2 bg-white text-emerald-700 border-2 border-emerald-600 hover:bg-emerald-50 px-6 py-3 rounded-xl font-bold transition-all duration-300 transform active:scale-95 shadow-sm hover:shadow-emerald-600/20 no-print">
                        <svg class="w-5 h-5 transition-transform group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                        <span>Tambah Data</span>
                    </button>

                    <button id="btn-calculate" class="group flex items-center gap-2 bg-gradient-to-r from-emerald-700 to-green-600 hover:from-emerald-600 hover:to-green-500 text-white px-8 py-3 rounded-xl shadow-lg shadow-emerald-600/30 font-bold transition-all duration-300 transform hover:-translate-y-1 active:scale-95 no-print border border-emerald-500/50">
                        <svg class="w-5 h-5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        <span>Mulai Hitung SPK</span>
                    </button>
                </div>
            </header>

            <div id="stats-container" class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 no-print hidden">
                <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-emerald-400 hover-lift relative overflow-hidden">
                    <div class="absolute -right-4 -top-4 w-20 h-20 bg-emerald-50 rounded-full blur-xl"></div>
                    <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Mahasiswa</p>
                    <h3 class="text-4xl font-black text-slate-800" id="stat-total">0</h3>
                </div>

                <div class="bg-gradient-to-br from-emerald-900 via-green-800 to-emerald-700 p-6 rounded-2xl shadow-xl shadow-emerald-900/30 text-white relative overflow-hidden hover-lift border border-emerald-600/30">
                    <div class="absolute -right-10 -top-10 w-40 h-40 bg-white opacity-10 rounded-full blur-3xl animate-pulse"></div>
                    <div class="absolute -left-10 -bottom-10 w-32 h-32 bg-emerald-400 opacity-20 rounded-full blur-2xl"></div>
                    <p class="text-[11px] font-black text-emerald-200 uppercase tracking-widest mb-1 relative z-10">Nilai V Tertinggi</p>
                    <h3 class="text-4xl font-black tracking-tight mt-1 relative z-10" id="stat-highest">0.0000</h3>
                    <div class="flex items-center gap-2 mt-2 relative z-10 bg-black/20 self-start inline-flex px-3 py-1 rounded-full backdrop-blur-sm border border-white/10">
                        <p class="text-xs font-bold text-white truncate max-w-[150px]" id="stat-winner">-</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-green-500 hover-lift relative overflow-hidden">
                    <div class="absolute -right-4 -top-4 w-20 h-20 bg-green-50 rounded-full blur-xl"></div>
                    <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-1">Rata-rata Preferensi</p>
                    <h3 class="text-4xl font-black text-slate-800" id="stat-avg">0.000</h3>
                </div>
            </div>

            <div id="loading-container" class="hidden flex flex-col items-center justify-center py-16 w-full bg-white/80 backdrop-blur-md rounded-2xl border border-slate-200 mb-8 shadow-xl shadow-emerald-900/5 relative z-20">
                <div class="relative w-24 h-24 mb-8">
                    <div class="absolute inset-0 border-4 border-emerald-100 rounded-full"></div>
                    <div class="absolute inset-0 border-4 border-emerald-600 border-t-transparent rounded-full animate-spin"></div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <svg class="w-8 h-8 text-emerald-600 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    </div>
                </div>
                <h3 class="text-2xl font-black text-slate-800 mb-3 tracking-tight">Memproses Algoritma TOPSIS</h3>
                <p id="loading-text" class="text-emerald-600 font-bold bg-emerald-50 px-4 py-1.5 rounded-full border border-emerald-100">Mempersiapkan Data Matrix...</p>
            </div>

            <div id="custom-bar-chart" class="hidden w-full overflow-x-auto pb-4"></div>

            <div id="table-container" class="bg-white rounded-2xl shadow-xl shadow-slate-200/40 border border-slate-200 overflow-hidden mb-12 relative transition-all duration-500">
                <div class="px-8 py-6 border-b border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4 bg-white">
                    <div class="flex items-center gap-3">
                        <div class="w-2 h-8 bg-gradient-to-b from-emerald-500 to-green-600 rounded-full"></div>
                        <h3 class="font-black text-xl text-slate-800" id="table-title">Matriks Data Mahasiswa</h3>
                    </div>
                    <span id="badge-status" class="text-[10px] font-black bg-slate-100 text-slate-500 px-4 py-1.5 rounded-full border border-slate-200 uppercase tracking-widest whitespace-nowrap">Menunggu Kalkulasi</span>
                </div>
                
                <div class="overflow-x-auto bg-white">
                    <table class="w-full min-w-max text-left text-sm">
                        <thead class="bg-gradient-to-r from-emerald-950 via-green-900 to-emerald-900 text-emerald-50">
                            <tr id="table-headers" class="uppercase text-[11px] font-black tracking-widest border-b border-emerald-800 shadow-sm whitespace-nowrap">
                                <th class="px-6 py-5 text-center">No</th>
                                <th class="px-6 py-5">Nama Lengkap</th>
                                <th class="px-6 py-5 text-center"><div class="flex flex-col"><span class="text-emerald-300 text-[9px]">Benefit</span><span>IPK (C1)</span></div></th>
                                <th class="px-6 py-5 text-center"><div class="flex flex-col"><span class="text-emerald-300 text-[9px]">Benefit</span><span>Hadir (C2)</span></div></th>
                                <th class="px-6 py-5 text-center"><div class="flex flex-col"><span class="text-emerald-300 text-[9px]">Benefit</span><span>Prestasi (C3)</span></div></th>
                                <th class="px-6 py-5 text-center"><div class="flex flex-col"><span class="text-red-300/80 text-[9px]">Cost</span><span>Telat (C4)</span></div></th>
                                <th class="px-6 py-5 text-center"><div class="flex flex-col"><span class="text-emerald-300 text-[9px]">Benefit</span><span>Tugas (C5)</span></div></th>
                                <th class="px-6 py-5 text-center">Nilai V</th>
                            </tr>
                        </thead>
                        <tbody id="result-body">
                            @php $no = 1; @endphp
                            @forelse($dataMahasiswa ?? [] as $s)
                            <tr data-score="{{ $s['v'] ?? 0 }}" class="border-b border-slate-100 hover:bg-slate-50 transition-colors whitespace-nowrap">
                                <td class="p-4 text-sm text-slate-600 text-center">{{ $no++ }}</td>
                                <td class="p-4 text-sm font-bold text-slate-800">{{ $s['nama'] }}</td>
                                <td class="p-4 text-sm text-slate-600 text-center">{{ $s['c1'] }}</td>
                                <td class="p-4 text-sm text-slate-600 text-center">{{ $s['c2'] }}</td>
                                <td class="p-4 text-sm text-slate-600 text-center">{{ $s['c3'] }}</td>
                                <td class="p-4 text-sm text-slate-600 text-center">{{ $s['c4'] }}</td>
                                <td class="p-4 text-sm text-slate-600 text-center">{{ $s['c5'] }}</td>
                                <td class="p-4 text-sm font-black text-emerald-600 text-center">
                                    {{ isset($s['v']) ? number_format($s['v'], 4) : '0.0000' }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="p-8 text-center text-sm font-medium text-slate-500 bg-slate-50/50">
                                    Belum ada data mahasiswa untuk ditampilkan.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <div id="crud-modal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 text-center">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="document.getElementById('crud-modal').classList.add('hidden')"></div>
            <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:align-middle sm:max-w-lg w-full border border-slate-100 relative z-10">
                <div class="bg-white px-6 pt-6 pb-6">
                    <h3 class="text-xl font-black text-slate-800 mb-6" id="modal-title">Form Data Mahasiswa</h3>
                    <form id="student-form" class="space-y-5">
                        <input type="hidden" id="student-id">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">Nama Lengkap</label>
                                <input type="text" id="input-nama" class="w-full rounded-xl border-slate-200 border-2 focus:border-emerald-500 focus:ring-0 transition-all px-4 py-3 text-sm font-semibold bg-slate-50" required>
                            </div>
                            <div class="col-span-2">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">NIM</label>
                                <input type="text" id="input-nim" class="w-full rounded-xl border-slate-200 border-2 focus:border-emerald-500 focus:ring-0 transition-all px-4 py-3 text-sm font-semibold bg-slate-50" required>
                            </div>
                        </div>
                        <div class="border-2 border-slate-100 rounded-xl p-4 mt-2">
                            <div class="grid grid-cols-2 gap-4">
                                <div><label class="block text-[11px] font-bold text-slate-600 mb-1">IPK</label><input type="number" step="0.01" id="input-c1" class="w-full rounded-lg border-2 px-3 py-2 text-sm bg-slate-50" required></div>
                                <div><label class="block text-[11px] font-bold text-slate-600 mb-1">Kehadiran</label><input type="number" step="1" id="input-c2" class="w-full rounded-lg border-2 px-3 py-2 text-sm bg-slate-50" required></div>
                                <div><label class="block text-[11px] font-bold text-slate-600 mb-1">Prestasi</label><input type="number" step="1" id="input-c3" class="w-full rounded-lg border-2 px-3 py-2 text-sm bg-slate-50" required></div>
                                <div><label class="block text-[11px] font-bold text-red-500 mb-1">Terlambat</label><input type="number" step="1" id="input-c4" class="w-full rounded-lg border-2 px-3 py-2 text-sm bg-red-50" required></div>
                                <div class="col-span-2"><label class="block text-[11px] font-bold text-slate-600 mb-1">Tugas</label><input type="number" step="1" id="input-c5" class="w-full rounded-lg border-2 px-3 py-2 text-sm bg-slate-50" required></div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="bg-slate-50 px-6 py-4 flex flex-col-reverse sm:flex-row sm:justify-end gap-3 border-t">
                    <button type="button" onclick="document.getElementById('crud-modal').classList.add('hidden')" class="px-6 py-2.5 bg-white border-2 rounded-xl font-bold">Batal</button>
                    <button type="button" id="btn-save-modal" class="px-8 py-2.5 bg-emerald-600 text-white rounded-xl font-bold">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <div id="delete-modal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 text-center">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="document.getElementById('delete-modal').classList.add('hidden')"></div>
            <div class="inline-block bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:max-w-sm w-full relative z-10">
                <div class="bg-white px-6 pt-8 pb-6 text-center">
                    <h3 class="text-xl font-black text-slate-900 mb-2">Hapus Data?</h3>
                    <p class="text-sm text-slate-500">Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <div class="bg-slate-50 px-6 py-4 flex justify-end gap-3 border-t">
                    <button onclick="document.getElementById('delete-modal').classList.add('hidden')" class="px-6 py-2.5 bg-white border-2 rounded-xl font-bold">Batal</button>
                    <button id="btn-confirm-delete" class="px-8 py-2.5 bg-red-600 text-white rounded-xl font-bold">Hapus</button>
                </div>
            </div>
        </div>
    </div>

</body>
<style>
    @keyframes growUp { from { height: 0; opacity: 0; } to { opacity: 1; } }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    .window-pattern {
        background-image: linear-gradient(to right, rgba(255,255,255,0.1) 1px, transparent 1px),
                          linear-gradient(to bottom, rgba(255,255,255,0.1) 1px, transparent 1px);
        background-size: 8px 12px;
    }
    /* Custom Scrollbar & Drag Styling */
    .chart-scroll {
        cursor: grab;
    }
    .chart-scroll:active {
        cursor: grabbing;
    }
    .chart-scroll::-webkit-scrollbar { height: 10px; }
    .chart-scroll::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 10px; margin: 0 20px; }
    .chart-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; border: 2px solid #f1f5f9; }
    .chart-scroll::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    .chart-scroll.active { cursor: grabbing !important; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnCalculate = document.getElementById('btn-calculate');
    const tableContainer = document.getElementById('table-container');
    const loadingContainer = document.getElementById('loading-container');
    const statsContainer = document.getElementById('stats-container');
    const chartContainer = document.getElementById('custom-bar-chart');
    const resultBody = document.getElementById('result-body');
    
    if(!btnCalculate) return;

    btnCalculate.addEventListener('click', function(e) {
        e.preventDefault(); 
        
        tableContainer.style.opacity = '0.5';
        tableContainer.classList.add('pointer-events-none');
        loadingContainer.classList.remove('hidden');
        chartContainer.innerHTML = ''; 
        chartContainer.classList.add('hidden');
        statsContainer.classList.add('hidden');

        const originalBtnHtml = btnCalculate.innerHTML;
        btnCalculate.innerHTML = `<svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...`;

        setTimeout(() => {
            const rows = resultBody.querySelectorAll('tr');
            let studentData = [];

            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                if(cells.length > 2 && cells.length === 8) { 
                    const name = cells[1].innerText.trim();
                    const rawScore = row.getAttribute('data-score');
                    
                    if (rawScore && rawScore.trim() !== "") {
                        const score = parseFloat(rawScore);
                        if (!isNaN(score) && score >= 0) { 
                            studentData.push({ 
                                name: name, 
                                score: score, 
                                displayScore: score.toFixed(4)
                            });
                        }
                    }
                }
            });

            loadingContainer.classList.add('hidden');
            tableContainer.style.opacity = '1';
            tableContainer.classList.remove('pointer-events-none');
            btnCalculate.innerHTML = originalBtnHtml;

            if(studentData.length === 0) {
                chartContainer.classList.remove('hidden');
                chartContainer.className = "w-full p-6 bg-red-50 text-red-600 rounded-2xl border-2 border-red-200 mt-8 mb-8 text-center block clear-both";
                chartContainer.innerHTML = `
                    <h3 class="font-black text-xl mb-2">🚨 Oops! Data/Nilai Kosong</h3>
                    <p class="text-sm font-medium">Grafik tidak dapat ditampilkan karena belum ada data yang valid.<br>
                    Pastikan proses perhitungan di Controller sudah berjalan dan data berhasil dimuat di tabel.</p>
                `;
                return; 
            }

            studentData.sort((a, b) => b.score - a.score);
            renderBuildingChart(studentData);
            updateStats(studentData);

            chartContainer.classList.remove('hidden');
            statsContainer.classList.remove('hidden');
            
            const badge = document.getElementById('badge-status');
            if(badge) {
                badge.innerText = "Kalkulasi Selesai";
                badge.className = "text-[10px] font-black bg-emerald-100 text-emerald-700 px-4 py-1.5 rounded-full border border-emerald-200 uppercase tracking-widest";
            }
        }, 1000);
    });

    function renderBuildingChart(data) {
        chartContainer.className = "bg-white p-6 sm:p-8 rounded-2xl shadow-xl shadow-slate-200/40 border border-slate-200 mb-8 relative mt-8 w-full block clear-both";
        
        const chartTitle = document.createElement('div');
        chartTitle.className = "mb-2 relative z-20";
        chartTitle.innerHTML = `<h3 class="font-black text-lg text-slate-800">Visualisasi Peringkat</h3>
        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Gedung Tertinggi = Nilai Terbaik (Klik & Geser area ini)</p>`;
        chartContainer.appendChild(chartTitle);

        const scrollArea = document.createElement('div');
        // PERBAIKAN 1: Tambah pt-32 (jarak atas super lebar) & pb-8 agar pop-up bebas merdeka tidak terpotong
        scrollArea.className = "w-full overflow-x-auto chart-scroll select-none pb-8 pt-32";
        chartContainer.appendChild(scrollArea);

        const chartWrapper = document.createElement('div');
        // PERBAIKAN 2: Tambah px-20 agar gedung paling kiri & kanan tidak terpotong saat memunculkan pop-up
        chartWrapper.className = "flex items-end justify-start gap-4 sm:gap-6 h-[250px] min-w-max px-20";
        scrollArea.appendChild(chartWrapper);

        const maxScore = data[0].score > 0 ? data[0].score : 1; 
        
        data.forEach((item, index) => {
            const heightPercent = Math.max(2, (item.score / maxScore) * 100);
            const delay = Math.min(index * 30, 1000); 
            
            let colorClass = "from-emerald-500 to-green-700 border-emerald-800"; 
            let crown = "";

            if(index === 0) {
                colorClass = "from-yellow-400 to-amber-600 border-amber-700 shadow-amber-500/40"; 
                // PERBAIKAN 3: Menyesuaikan posisi mahkota agar pas & tidak nabrak pop-up
                crown = `<div class="absolute -top-12 text-3xl animate-bounce pointer-events-none z-10">👑</div>`;
            } else if(index === 1) {
                colorClass = "from-slate-300 to-slate-500 border-slate-600 shadow-slate-400/40";
            } else if(index === 2) {
                colorClass = "from-orange-400 to-orange-700 border-orange-800 shadow-orange-500/40"; 
            }

            const barHtml = `
                <div class="flex flex-col items-center justify-end h-full w-12 sm:w-16 group relative pointer-events-auto" style="animation: fadeIn 0.5s ease-out ${delay}ms forwards; opacity: 0;">
                    ${crown}
                    
                    <span class="text-[10px] font-black text-slate-500 mb-2 group-hover:-translate-y-2 transition-transform duration-300 relative flex justify-center w-full cursor-pointer">
                        ${item.displayScore}
                        
                        <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-3 opacity-0 group-hover:opacity-100 transition-all duration-300 bg-slate-900 text-white px-4 py-3 rounded-xl whitespace-nowrap pointer-events-none z-[100] shadow-2xl border border-slate-700 flex flex-col items-center group-hover:-translate-y-1">
                            <span class="text-emerald-400 font-black text-sm mb-1">${item.name}</span>
                            <span class="text-xs font-medium text-slate-300">Peringkat <strong class="text-white text-sm">${index + 1}</strong> &bull; Nilai V: <strong class="text-white">${item.displayScore}</strong></span>
                            
                            <div class="absolute -bottom-1.5 left-1/2 transform -translate-x-1/2 w-3 h-3 bg-slate-900 border-b border-r border-slate-700 rotate-45"></div>
                        </div>
                    </span>

                    <div class="w-full bg-gradient-to-t ${colorClass} rounded-t-md border-x-2 border-t-2 shadow-lg relative overflow-hidden transition-all duration-500 group-hover:brightness-110 group-hover:scale-105 origin-bottom" 
                         style="height: ${heightPercent}%; animation: growUp 1s ease-out ${delay}ms forwards;">
                        <div class="absolute inset-0 window-pattern opacity-40 mix-blend-overlay pointer-events-none"></div>
                        <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-4 sm:w-6 h-6 sm:h-8 bg-black/40 rounded-t-sm border-t border-x border-white/10 pointer-events-none"></div>
                    </div>
                    <span class="text-[9px] sm:text-[10px] font-bold text-slate-600 mt-3 truncate w-full text-center px-1" title="${item.name}">
                        ${item.name.split(' ')[0]} 
                    </span>
                </div>
            `;
            chartWrapper.insertAdjacentHTML('beforeend', barHtml);
        });

        // Logika Drag to Scroll
        let isDown = false;
        let startX;
        let scrollLeft;

        scrollArea.addEventListener('mousedown', (e) => {
            isDown = true;
            scrollArea.classList.add('active');
            startX = e.pageX - scrollArea.offsetLeft;
            scrollLeft = scrollArea.scrollLeft;
        });
        scrollArea.addEventListener('mouseleave', () => {
            isDown = false;
            scrollArea.classList.remove('active');
        });
        scrollArea.addEventListener('mouseup', () => {
            isDown = false;
            scrollArea.classList.remove('active');
        });
        scrollArea.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - scrollArea.offsetLeft;
            const walk = (x - startX) * 1.5;
            scrollArea.scrollLeft = scrollLeft - walk;
        });
    }

    function updateStats(data) {
        if(data.length === 0) return;
        let avgScore = data.reduce((sum, item) => sum + item.score, 0) / data.length;
        animateValue('stat-total', 0, data.length, 1000);
        animateValueFloat('stat-highest', 0, data[0].score, 1000, 4);
        animateValueFloat('stat-avg', 0, avgScore, 1000, 4);
        const winnerEl = document.getElementById('stat-winner');
        if(winnerEl) winnerEl.innerText = "🏆 " + data[0].name;
    }

    function animateValue(id, start, end, duration) {
        let obj = document.getElementById(id);
        if(!obj) return;
        let startTimestamp = null;
        const step = (timestamp) => {
            if (!startTimestamp) startTimestamp = timestamp;
            const progress = Math.min((timestamp - startTimestamp) / duration, 1);
            obj.innerHTML = Math.floor(progress * (end - start) + start);
            if (progress < 1) window.requestAnimationFrame(step);
        };
        window.requestAnimationFrame(step);
    }

    function animateValueFloat(id, start, end, duration, decimals) {
        let obj = document.getElementById(id);
        if(!obj) return; 
        let startTimestamp = null;
        const step = (timestamp) => {
            if (!startTimestamp) startTimestamp = timestamp;
            const progress = Math.min((timestamp - startTimestamp) / duration, 1);
            let current = (progress * (end - start)) + start;
            obj.innerHTML = current.toFixed(decimals);
            if (progress < 1) { 
                window.requestAnimationFrame(step); 
            } else {
                obj.innerHTML = end.toFixed(decimals);
            }
        };
        window.requestAnimationFrame(step);
    }
});
</script>
</script>
</body>
</html>