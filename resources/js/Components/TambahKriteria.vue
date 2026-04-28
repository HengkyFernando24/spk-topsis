<template>
    <div class="bg-white rounded-[2.5rem] shadow-[0_30px_60px_rgba(0,0,0,0.04)] border border-slate-100 overflow-hidden animate-in fade-in slide-in-from-bottom-4 duration-500">
        
        <div class="bg-slate-900 p-8 relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-emerald-500 rounded-full blur-3xl opacity-30 pointer-events-none"></div>
            <div class="relative z-10 flex items-center gap-4">
                <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center text-emerald-400 backdrop-blur-md border border-white/10">
                    <span class="material-symbols-outlined !text-2xl">post_add</span>
                </div>
                <div>
                    <h3 class="text-xl font-black text-white tracking-tight">Form Kriteria Baru</h3>
                    <p class="text-xs text-slate-400 font-medium mt-1 tracking-wide">Tambahkan parameter referensi untuk perhitungan.</p>
                </div>
            </div>
        </div>

        <div class="p-8 md:p-10 space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                <div class="space-y-2 group/input">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 group-focus-within/input:text-emerald-500 transition-colors">Kode Kriteria</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="material-symbols-outlined text-slate-300 !text-lg group-focus-within/input:text-emerald-500 transition-colors">tag</span>
                        </div>
                        <input v-model="form.kode" type="text" placeholder="Auto..." readonly
                               class="w-full bg-slate-100 border-2 border-slate-50 rounded-2xl pl-12 pr-4 py-3.5 text-sm font-bold text-slate-500 cursor-not-allowed uppercase">
                    </div>
                </div>

                <div class="space-y-2 group/input">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 group-focus-within/input:text-emerald-500 transition-colors">Nama Kriteria</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="material-symbols-outlined text-slate-300 !text-lg group-focus-within/input:text-emerald-500 transition-colors">format_align_left</span>
                        </div>
                        <input v-model="form.nama" type="text" placeholder="Contoh: Indeks Prestasi..." autofocus
                               class="w-full bg-slate-50 border-2 border-slate-50 rounded-2xl pl-12 pr-4 py-3.5 text-sm font-bold text-slate-800 focus:border-emerald-500 focus:bg-white focus:ring-0 transition-all">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Tipe Kriteria</label>
                    <div class="flex p-1.5 bg-slate-50 rounded-2xl border-2 border-slate-50 h-[52px] relative">
                        <button @click="form.tipe = 'benefit'" 
                                :class="form.tipe === 'benefit' ? 'text-emerald-700 shadow-md border-emerald-100 bg-white' : 'text-slate-400 hover:text-slate-600'"
                                class="flex-1 flex items-center justify-center gap-2 rounded-xl text-xs font-black uppercase tracking-wider transition-all duration-300 border border-transparent z-10">
                            <span class="material-symbols-outlined !text-base">trending_up</span>
                            Benefit
                        </button>
                        <button @click="form.tipe = 'cost'" 
                                :class="form.tipe === 'cost' ? 'text-rose-700 shadow-md border-rose-100 bg-white' : 'text-slate-400 hover:text-slate-600'"
                                class="flex-1 flex items-center justify-center gap-2 rounded-xl text-xs font-black uppercase tracking-wider transition-all duration-300 border border-transparent z-10">
                            <span class="material-symbols-outlined !text-base">trending_down</span>
                            Cost
                        </button>
                    </div>
                </div>

                <div class="space-y-2 group/input">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 group-focus-within/input:text-emerald-500 transition-colors">Bobot (0.0 - 1.0)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="material-symbols-outlined text-slate-300 !text-lg group-focus-within/input:text-emerald-500 transition-colors">weight</span>
                        </div>
                        <input v-model="form.bobot" type="number" step="0.05" min="0" max="1" 
                               class="w-full bg-slate-50 border-2 border-slate-50 rounded-2xl pl-12 pr-4 py-3.5 text-sm font-black text-emerald-600 focus:border-emerald-500 focus:bg-white focus:ring-0 transition-all appearance-none">
                    </div>
                </div>
            </div>
        </div>

        <div class="p-8 md:p-10 pt-0 flex flex-col-reverse md:flex-row gap-4 justify-end border-t border-slate-50 mt-4">
            <button @click="batal" class="px-8 py-3.5 bg-slate-50 text-slate-500 hover:bg-slate-100 hover:text-slate-700 rounded-2xl text-xs font-black tracking-widest uppercase transition-all">
                Batal
            </button>
            <button @click="simpanData" class="px-8 py-3.5 bg-emerald-600 text-white hover:bg-emerald-700 rounded-2xl text-xs font-black tracking-widest uppercase transition-all shadow-lg shadow-emerald-200 flex items-center justify-center gap-2 active:scale-95">
                <span class="material-symbols-outlined !text-lg">save</span>
                Simpan Kriteria
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

// Tangkap props dari induknya biar kita tau jumlah kriteria saat ini
const props = defineProps({
    jumlahKriteria: { type: Number, default: 0 }
});

const emit = defineEmits(['simpan', 'batal']);

const form = ref({
    kode: '',
    nama: '',
    tipe: 'benefit',
    bobot: 0.1
});

// Pas komponen form ini muncul, otomatis generate kodenya
onMounted(() => {
    form.value.kode = 'C' + (props.jumlahKriteria + 1);
});

const batal = () => { emit('batal'); };

const simpanData = () => {
    if (!form.value.nama) {
        alert("Nama Kriteria wajib diisi blay!");
        return;
    }
    
    emit('simpan', {
        id: Date.now(),
        kode: form.value.kode,
        nama: form.value.nama,
        tipe: form.value.tipe,
        bobot: parseFloat(form.value.bobot)
    });
};
</script>

<style scoped>
input[type=number]::-webkit-inner-spin-button, 
input[type=number]::-webkit-outer-spin-button { 
  -webkit-appearance: none; 
  margin: 0; 
}
</style>