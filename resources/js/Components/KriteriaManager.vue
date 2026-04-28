<template>
    <div class="space-y-6 animate-in fade-in slide-in-from-bottom-4 duration-700">
        <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-slate-100 relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-64 h-64 bg-emerald-50 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div>
                    <h3 class="text-3xl font-black text-slate-800 tracking-tight">Manajemen Kriteria</h3>
                    <p class="text-slate-500 font-medium mt-2">Konfigurasi bobot dan tipe kriteria untuk algoritma TOPSIS.</p>
                </div>
                
                <div class="flex items-center gap-4 bg-emerald-50 px-6 py-3 rounded-2xl border border-emerald-100">
                    <div class="text-right">
                        <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Total Bobot</p>
                        <p class="text-xl font-black text-emerald-900">{{ totalBobot }} / 1.00</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-emerald-500 flex items-center justify-center text-white shadow-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4">
            <div v-if="kriteriaList.length === 0" class="bg-white p-20 rounded-[2.5rem] text-center border-2 border-dashed border-slate-200">
                <p class="text-slate-400 font-bold">Data kriteria tidak ditemukan blay...</p>
            </div>

            <div v-for="(item, index) in kriteriaList" :key="item.id" 
                 class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 flex flex-col lg:flex-row items-center gap-6 group hover:border-emerald-500/50 transition-all duration-500">
                
                <div class="w-16 h-16 bg-slate-50 text-emerald-600 rounded-2xl flex items-center justify-center font-black text-xl shadow-inner group-hover:bg-emerald-500 group-hover:text-white transition-all duration-500">
                    {{ item.kode }}
                </div>

                <div class="flex-1 w-full space-y-1">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Kriteria</label>
                    <input v-model="item.nama" 
                           class="w-full bg-transparent border-none p-0 text-lg font-bold text-slate-800 focus:ring-0 placeholder:text-slate-300" 
                           placeholder="Contoh: Indeks Prestasi Kumulatif">
                    <div class="flex gap-4 mt-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" v-model="item.tipe" value="benefit" class="w-4 h-4 text-emerald-600 border-slate-300 focus:ring-emerald-500">
                            <span class="text-xs font-bold text-slate-600 uppercase tracking-wider">Benefit</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" v-model="item.tipe" value="cost" class="w-4 h-4 text-emerald-600 border-slate-300 focus:ring-emerald-500">
                            <span class="text-xs font-bold text-slate-600 uppercase tracking-wider">Cost</span>
                        </label>
                    </div>
                </div>

                <div class="w-full lg:w-40 space-y-1">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Bobot (0.00 - 1.00)</label>
                    <div class="relative">
                        <input type="number" step="0.01" v-model="item.bobot" 
                               class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-4 py-3 font-black text-emerald-700 focus:border-emerald-500 focus:bg-white focus:ring-0 transition-all">
                        <span class="absolute right-4 top-3 text-slate-300 font-bold">%</span>
                    </div>
                </div>

                <button @click="saveUpdate(item)" 
                        class="w-full lg:w-auto px-8 py-4 bg-emerald-600 text-white rounded-2xl font-black text-xs tracking-widest uppercase hover:bg-emerald-700 hover:shadow-lg hover:shadow-emerald-200 transition-all active:scale-95 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    <span>Simpan</span>
                </button>
            </div>
        </div>

        <div v-if="totalBobot != 1.0" class="p-6 bg-amber-50 rounded-[2rem] border border-amber-100 flex items-center gap-4">
            <div class="w-10 h-10 bg-amber-500 rounded-full flex items-center justify-center text-white shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <p class="text-sm font-bold text-amber-800">
                Peringatan: Total bobot saat ini adalah {{ totalBobot }}. Pastikan total bobot berjumlah tepat 1.00 untuk akurasi perhitungan TOPSIS.
            </p>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';

const kriteriaList = ref([]);

// Hitung total bobot secara real-time
const totalBobot = computed(() => {
    const total = kriteriaList.value.reduce((sum, item) => sum + parseFloat(item.bobot || 0), 0);
    return total.toFixed(2);
});

const fetchKriteria = async () => {
    try {
        const res = await axios.get('/api/kriteria');
        kriteriaList.value = res.data;
    } catch (error) {
        console.error("Gagal ambil data blay!", error);
    }
};

const saveUpdate = async (item) => {
    try {
        await axios.put(`/api/kriteria/${item.id}`, item);
        alert(`Kriteria ${item.kode} berhasil diperbarui, blay!`);
    } catch (error) {
        alert("Gagal menyimpan data. Cek koneksi API lo.");
    }
};

onMounted(() => {
    fetchKriteria();
});
</script>