<template>
    <div class="flex min-h-screen bg-[#F8FAFC] font-['Plus_Jakarta_Sans'] antialiased">
        
        <aside class="fixed left-0 top-0 h-full w-72 z-50 bg-gradient-to-b from-emerald-950 via-green-950 to-emerald-950 text-white flex flex-col py-6 shadow-2xl no-print">
            <div class="absolute top-0 left-0 w-full h-64 bg-emerald-400 opacity-5 blur-[100px] rounded-full pointer-events-none"></div>
            <div class="px-8 mb-10 flex items-center gap-4 relative z-10">
                <div class="w-12 h-12 rounded-2xl bg-white/10 backdrop-blur-md border border-white/10 flex items-center justify-center text-emerald-400 shadow-lg">
                    <span class="material-symbols-outlined !text-3xl">school</span>
                </div>
                <div>
                    <h1 class="text-xl font-black text-white tracking-tighter leading-none">EduDecision</h1>
                    <p class="text-emerald-400 text-[9px] font-bold uppercase tracking-widest mt-1 opacity-80">Smart SPK System</p>
                </div>
            </div>
            <nav class="flex-1 flex flex-col gap-2 px-4 overflow-y-auto relative z-10 custom-scrollbar">
                <p class="px-4 text-[10px] font-black text-emerald-500/40 uppercase tracking-[0.3em] mb-4">Menu Utama</p>
                <button v-for="(menu, index) in menus" :key="index" @click="activeTab = menu.id"
                    class="flex items-center px-5 py-4 group cursor-pointer transition-all duration-300 border-l-[4px] rounded-2xl text-left w-full"
                    :class="activeTab === menu.id ? 'bg-white/10 text-white border-emerald-400 shadow-xl backdrop-blur-md' : 'text-emerald-100/60 border-transparent hover:text-white hover:bg-white/5'">
                    <span class="material-symbols-outlined mr-4 text-xl">{{ menu.icon }}</span>
                    <span class="text-sm font-bold tracking-wide">{{ menu.title }}</span>
                </button>
            </nav>
            <div class="mt-auto px-4 border-t border-emerald-800/30 pt-8 relative z-10">
                <button class="w-full text-left text-rose-400 hover:bg-rose-500/10 flex items-center px-5 py-3.5 transition-all rounded-2xl">
                    <span class="material-symbols-outlined mr-4 text-xl">logout</span>
                    <span class="text-sm font-bold">Keluar Sistem</span>
                </button>
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0 md:ml-72 transition-all duration-500">
            
            <header class="h-24 bg-white/70 backdrop-blur-2xl border-b border-slate-200/50 flex items-center justify-between px-12 sticky top-0 z-40">
                <div>
                    <h2 class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.4em] mb-1">Administrator</h2>
                    <p class="text-2xl font-extrabold text-slate-800 tracking-tight capitalize">{{ formattedTitle }} Overview</p>
                </div>
                <div class="flex items-center gap-6">
                    <div class="w-12 h-12 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-400 hover:text-emerald-600 transition-all cursor-pointer">
                        <span class="material-symbols-outlined">notifications</span>
                    </div>
                </div>
            </header>

            <main class="p-12 space-y-12 overflow-y-auto custom-scrollbar">
                
                <div v-if="activeTab === 'dashboard'" class="space-y-12 animate-in fade-in duration-700">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-8">
                        <div v-for="(stat, i) in stats" :key="i" class="bg-white p-8 rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.02)] border border-slate-100 hover:shadow-xl transition-all group">
                            <div class="flex justify-between items-start mb-6">
                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-500" :class="stat.bg">
                                    <span class="material-symbols-outlined !text-3xl" :class="stat.text">{{ stat.icon }}</span>
                                </div>
                                <span class="text-[10px] font-black text-emerald-500 bg-emerald-50 px-3 py-1 rounded-full">+12%</span>
                            </div>
                            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">{{ stat.label }}</p>
                            <h3 class="text-4xl font-black text-slate-800 tracking-tighter">{{ stat.value }}</h3>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                        
                        <div class="xl:col-span-2 bg-white p-10 rounded-[3rem] shadow-[0_30px_60px_rgba(0,0,0,0.02)] border border-slate-100">
                            <div class="flex justify-between items-center mb-10">
                                <div>
                                    <h3 class="text-xl font-black text-slate-800 tracking-tight">Statistik Performa Mahasiswa</h3>
                                    <p class="text-sm text-slate-400 font-medium">Visualisasi perbandingan skor preferensi (V).</p>
                                </div>
                                <div class="flex gap-2">
                                    <div class="flex items-center gap-2"><span class="w-3 h-3 bg-emerald-500 rounded-full"></span><span class="text-[10px] font-bold text-slate-400 uppercase">Skor V</span></div>
                                </div>
                            </div>

                            <div class="flex items-end justify-between h-64 gap-4 px-4 border-b border-slate-100 pb-2">
                                <div v-for="(bar, i) in chartData" :key="i" class="flex-1 flex flex-col items-center group relative">
                                    <div class="absolute -top-10 opacity-0 group-hover:opacity-100 transition-opacity bg-slate-800 text-white text-[10px] font-bold px-3 py-1.5 rounded-lg mb-2 pointer-events-none">
                                        {{ bar.value }}
                                    </div>
                                    <div :style="{ height: (bar.value * 100) + '%' }" 
                                         class="w-full max-w-[40px] bg-gradient-to-t from-emerald-600 to-emerald-400 rounded-t-xl group-hover:from-emerald-500 group-hover:to-emerald-300 transition-all duration-700 shadow-lg shadow-emerald-200/50">
                                    </div>
                                    <span class="absolute -bottom-8 text-[10px] font-black text-slate-400 uppercase tracking-tighter truncate w-full text-center">
                                        {{ bar.name }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white p-10 rounded-[3rem] shadow-[0_30px_60px_rgba(0,0,0,0.02)] border border-slate-100">
                            <h3 class="text-xl font-black text-slate-800 tracking-tight mb-8">Aktivitas Sistem</h3>
                            <div class="space-y-6">
                                <div v-for="n in 4" :key="n" class="flex items-start gap-4">
                                    <div class="w-2 h-2 mt-2 rounded-full bg-emerald-500"></div>
                                    <div>
                                        <p class="text-xs font-bold text-slate-700 leading-snug">Input Data Siswa Berhasil</p>
                                        <p class="text-[10px] text-slate-400 font-medium mt-0.5">2 Jam yang lalu</p>
                                    </div>
                                </div>
                                <button class="w-full py-4 bg-slate-50 text-slate-400 text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-slate-100 transition-colors">
                                    Lihat Semua Log
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-[3rem] shadow-[0_30px_60px_rgba(0,0,0,0.03)] border border-slate-100 overflow-hidden">
                        <div class="p-10 border-b border-slate-50 flex justify-between items-center bg-white/50">
                            <h3 class="font-black text-2xl text-slate-800 tracking-tight">Peringkat 5 Teratas</h3>
                            <button class="px-6 py-2.5 bg-slate-900 text-white rounded-xl text-[10px] font-black tracking-widest uppercase transition-all hover:bg-emerald-600">Export PDF</button>
                        </div>
                        <TopsisTable v-if="students.length > 0" :data-mahasiswa="students.slice(0, 5)" />
                        <div v-else class="p-20 text-center text-slate-400 font-bold uppercase tracking-widest text-xs">Menunggu Input Data Mahasiswa...</div>
                    </div>
                </div>

                <div v-if="activeTab === 'manajemen-kriteria'" class="animate-in fade-in slide-in-from-bottom-6 duration-700">
                    <KriteriaManager />
                </div>
                <div v-if="activeTab === 'data-siswa'" class="animate-in fade-in duration-700">
                    <DataSiswa :students-data="students" />
                </div>
            </main>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import TopsisTable from '../Components/TopsisTable.vue';
import KriteriaManager from '../Components/KriteriaManager.vue';
import DataSiswa from '../Components/DataSiswa.vue';

const activeTab = ref('dashboard');

// --- PUSAT DATA LOKAL ---
const students = ref([]); 
const criteria = ref([]);

const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

// Judul Header Otomatis
const formattedTitle = computed(() => {
    return activeTab.value.split('-').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
});

// --- DIAGRAM BATANG OTOMATIS ---
// Mengambil data langsung dari daftar mahasiswa yang diinput
const chartData = computed(() => {
    if (students.value.length === 0) {
        // Data default jika masih kosong
        return [{ name: 'N/A', value: 0 }];
    }
    // Menampilkan skor V mahasiswa yang sudah diinput (maksimal 7 orang di grafik)
    return students.value.slice(0, 7).map(s => ({
        name: s.nama.split(' ')[0], // Ambil nama depan agar tidak terlalu panjang
        value: parseFloat(s.v) || 0.1 // Berikan nilai default kecil jika skor V belum tersedia
    }));
});

// --- STATISTIK OTOMATIS ---
const stats = computed(() => {
    // Hitung rata-rata skor V
    const avgV = students.value.length > 0 
        ? (students.value.reduce((acc, s) => acc + (parseFloat(s.v) || 0), 0) / students.value.length).toFixed(2)
        : '0.00';

    return [
        { label: 'Total Mahasiswa', value: students.value.length, icon: 'groups', bg: 'bg-emerald-50', text: 'text-emerald-600' },
        { label: 'Kriteria Aktif', value: criteria.value.length, icon: 'list_alt', bg: 'bg-blue-50', text: 'text-blue-600' },
        { label: 'Rata-rata Skor', value: avgV, icon: 'analytics', bg: 'bg-amber-50', text: 'text-amber-600' },
        { label: 'Admin Online', value: '1', icon: 'verified_user', bg: 'bg-emerald-50', text: 'text-emerald-600' },
    ];
});

// Fungsi untuk menerima update dari menu lain
const updateStudents = (newData) => { students.value = newData; };
const updateCriteria = (newData) => { criteria.value = newData; };

const menus = [
    { id: 'dashboard', title: 'Dashboard', icon: 'dashboard' },
    { id: 'manajemen-kriteria', title: 'Manajemen Kriteria', icon: 'list_alt' },
    { id: 'data-siswa', title: 'Data Siswa', icon: 'groups' },
    { id: 'proses-perhitungan', title: 'Proses Perhitungan', icon: 'calculate' },
];
</script>

<style>
.custom-scrollbar::-webkit-scrollbar { width: 5px; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 20px; }
.animate-in { animation: fade-in 0.5s ease-out; }
@keyframes fade-in { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>