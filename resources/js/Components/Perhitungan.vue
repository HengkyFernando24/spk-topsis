<template>
    <div class="flex min-h-screen bg-slate-50 font-['Plus_Jakarta_Sans']">
        
        

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <header class="h-20 bg-white border-b border-slate-200 flex items-center justify-between px-8 sticky top-0 z-30">
                <div>
                    <h2 class="text-sm font-bold text-slate-400 uppercase tracking-widest">Proses SPK</h2>
                    <p class="text-xl font-black text-slate-800">Perhitungan TOPSIS</p>
                </div>
                
                <div class="flex gap-3">
                    <button @click="fetchData" class="p-2.5 text-slate-500 hover:bg-slate-100 rounded-xl transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    </button>
                    <button @click="handleCalculate" :disabled="isCalculating || students.length === 0" 
                        class="group flex items-center gap-2 bg-gradient-to-r from-emerald-700 to-green-600 text-white px-6 py-2.5 rounded-xl shadow-lg shadow-emerald-900/20 font-bold transition-all hover:scale-105 active:scale-95 disabled:opacity-50 disabled:grayscale">
                        <svg v-if="isCalculating" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        <span>{{ isCalculating ? 'Menghitung...' : 'Mulai Hitung' }}</span>
                    </button>
                </div>
            </header>

            <main class="p-8 overflow-y-auto">
                <div v-if="isCalculating" class="flex flex-col items-center justify-center py-20 w-full bg-white rounded-3xl border border-slate-200 mb-8 shadow-sm">
                    <div class="relative w-20 h-20 mb-6">
                        <div class="absolute inset-0 border-4 border-emerald-100 rounded-full"></div>
                        <div class="absolute inset-0 border-4 border-emerald-600 border-t-transparent rounded-full animate-spin"></div>
                    </div>
                    <h3 class="text-xl font-black text-slate-800 mb-2">Memproses Algoritma TOPSIS</h3>
                    <p class="text-slate-500 text-sm">Menghitung matriks keputusan dan normalisasi terbobot...</p>
                </div>

                <div v-if="!isCalculating && students.length === 0" class="bg-white p-12 rounded-3xl border-2 border-dashed border-slate-200 text-center">
                    <p class="text-slate-400 font-bold">Belum ada data mahasiswa. Silakan input data terlebih dahulu blay!</p>
                </div>

                <div v-show="!isCalculating && students.length > 0" class="space-y-8">
                    <div class="grid grid-cols-5 gap-4">
                        <div v-for="k in kriterias" :key="k.id" class="bg-white p-4 rounded-2xl border border-slate-100">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ k.kode }} - {{ k.tipe }}</p>
                            <p class="font-bold text-slate-700">{{ k.nama }}</p>
                            <p class="text-emerald-600 font-black text-sm">{{ k.bobot * 100 }}%</p>
                        </div>
                    </div>

                    <TopsisTable :data-mahasiswa="students" :status-badge="statusText" />
                </div>
            </main>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

import TopsisTable from '../Components/TopsisTable.vue';

// State
const isCalculating = ref(false);
const statusText = ref('Menunggu Kalkulasi');
const students = ref([]);
const kriterias = ref([]);

// 1. Fungsi Narik Data dari Database Laravel
const fetchData = async () => {
    try {
        const resSiswa = await axios.get('/api/mahasiswa'); // Ganti route sesuai api.php lo
        const resKriteria = await axios.get('/api/kriteria');
        students.value = resSiswa.data;
        kriterias.value = resKriteria.data;
    } catch (error) {
        console.error("Gagal narik data blay!", error);
    }
};

// 2. Fungsi Hitung Otomatis
const handleCalculate = () => {
    isCalculating.value = true;
    statusText.value = "Memproses...";
    
    // Kirim request ke controller untuk hitung TOPSIS
    axios.post('/api/topsis/hitung')
        .then(res => {
            setTimeout(() => {
                students.value = res.data; // Data mahasiswa yang udah ada nilai V nya
                isCalculating.value = false;
                statusText.value = "Kalkulasi Selesai";
            }, 1500);
        })
        .catch(err => {
            console.error(err);
            isCalculating.value = false;
            alert("Hitungan error blay!");
        });
};

onMounted(() => {
    fetchData();
});
</script>

<style scoped>
.custom-scrollbar::-webkit-scrollbar { width: 6px; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
</style>