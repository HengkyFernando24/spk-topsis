<template>
    <div class="space-y-6 animate-in fade-in slide-in-from-bottom-4 duration-700">
        
        <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-200 relative overflow-hidden">
            <div class="absolute -right-20 -top-20 w-80 h-80 bg-emerald-50 rounded-full blur-3xl pointer-events-none opacity-60"></div>
            <div class="absolute -left-20 -bottom-20 w-80 h-80 bg-blue-50 rounded-full blur-3xl pointer-events-none opacity-60"></div>
            
            <div class="relative z-10 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-8">
                <div class="max-w-xl">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-1.5 h-8 bg-emerald-500 rounded-full"></div>
                        <h3 class="text-3xl font-black text-slate-800 tracking-tight">Manajemen Kriteria</h3>
                    </div>
                    <p class="text-slate-500 font-medium leading-relaxed mb-6">
                        Konfigurasi parameter kriteria untuk perhitungan algoritma TOPSIS. Sesuaikan bobot dan tentukan tipe kriteria sesuai kebutuhan analisis Anda.
                    </p>

                    <button v-if="!showForm" @click="showForm = true" class="flex items-center gap-2 px-6 py-3 bg-slate-900 hover:bg-emerald-600 text-white rounded-2xl text-xs font-black tracking-widest uppercase transition-all shadow-lg hover:shadow-emerald-200 active:scale-95 w-fit">
                        <span class="material-symbols-outlined !text-lg">add_circle</span>
                        <span>Tambah Data Kriteria</span>
                    </button>
                </div>
                
                <div class="w-full lg:w-72 space-y-3">
                    <div class="flex justify-between items-end">
                        <div>
                            <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Akumulasi Bobot</p>
                            <p class="text-2xl font-black transition-colors duration-500" :class="totalBobot === 1.00 ? 'text-emerald-600' : 'text-amber-500'">
                                {{ totalBobot.toFixed(2) }} <span class="text-sm font-bold text-slate-300">/ 1.00</span>
                            </p>
                        </div>
                        <div :class="[
                            'w-12 h-12 rounded-2xl flex items-center justify-center transition-all duration-500 shadow-sm',
                            totalBobot === 1.00 ? 'bg-emerald-500 text-white shadow-emerald-200' : 'bg-amber-100 text-amber-600 shadow-amber-100'
                        ]">
                            <span class="material-symbols-outlined">{{ totalBobot === 1.00 ? 'check_circle' : 'warning' }}</span>
                        </div>
                    </div>
                    <div class="h-2.5 w-full bg-slate-100 rounded-full overflow-hidden border border-slate-50 p-0.5">
                        <div class="h-full rounded-full transition-all duration-1000 ease-out"
                             :style="{ width: `${Math.min(totalBobot * 100, 100)}%` }"
                             :class="totalBobot === 1.00 ? 'bg-emerald-500' : 'bg-amber-400'">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="showForm">
            <TambahKriteria :jumlah-kriteria="dataKriteria.length" @simpan="simpanKriteriaBaru" @batal="showForm = false" />
        </div>

        <div v-else class="space-y-6">
            
            <transition enter-active-class="transition duration-300 ease-out" enter-from-class="opacity-0 translate-y-[-10px]" enter-to-class="opacity-100 translate-y-0" leave-active-class="transition duration-200 ease-in" leave-from-class="opacity-100" leave-to-class="opacity-0">
                <div v-if="notification" :class="notification.type === 'success' ? 'bg-emerald-50 border-emerald-100 text-emerald-800' : 'bg-red-50 border-red-100 text-red-800'" class="p-4 rounded-2xl border flex items-center gap-3 shadow-sm">
                    <div :class="notification.type === 'success' ? 'bg-emerald-500' : 'bg-red-500'" class="w-8 h-8 rounded-full flex items-center justify-center text-white shrink-0">
                        <span class="material-symbols-outlined !text-sm">{{ notification.type === 'success' ? 'check' : 'close' }}</span>
                    </div>
                    <p class="text-sm font-bold">{{ notification.message }}</p>
                </div>
            </transition>

            <div v-if="totalBobot !== 1.00 && dataKriteria.length > 0" class="p-6 bg-amber-50 rounded-3xl border border-amber-100 flex items-start md:items-center gap-4 border-l-4 border-l-amber-400 animate-in fade-in">
                <div class="w-10 h-10 bg-white rounded-2xl flex items-center justify-center text-amber-500 shrink-0 shadow-sm border border-amber-100">
                    <span class="material-symbols-outlined">warning</span>
                </div>
                <div>
                    <p class="text-sm font-black text-amber-900 uppercase tracking-tight">Konfigurasi Bobot Tidak Seimbang</p>
                    <p class="text-xs font-bold text-amber-700/80 mt-1 uppercase tracking-wider">
                        Total bobot saat ini {{ totalBobot.toFixed(2) }}. Pastikan total berjumlah 1.00 untuk validitas algoritma TOPSIS.
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4">
                <div v-if="dataKriteria.length === 0" class="bg-white p-20 rounded-[2.5rem] text-center border-2 border-dashed border-slate-200">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="material-symbols-outlined !text-4xl text-slate-300">list_alt_add</span>
                    </div>
                    <p class="text-slate-400 font-bold uppercase tracking-widest text-xs">Belum ada data kriteria, blay!</p>
                </div>

                <div v-for="(item, index) in dataKriteria" :key="item.id" 
                     class="bg-white p-4 md:p-6 rounded-3xl shadow-[0_10px_40px_rgba(0,0,0,0.02)] border border-slate-100 flex flex-col lg:flex-row items-center gap-6 group hover:border-emerald-500/30 hover:shadow-xl hover:shadow-emerald-500/5 transition-all duration-500 relative overflow-hidden">
                    
                    <button @click="hapusKriteria(index)" class="absolute top-4 right-4 p-2 bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white rounded-xl transition-all duration-300 opacity-0 group-hover:opacity-100 scale-90 group-hover:scale-100 z-10" title="Hapus Kriteria">
                        <span class="material-symbols-outlined !text-sm">delete</span>
                    </button>

                    <div class="w-14 h-14 bg-slate-50 text-slate-400 rounded-2xl flex items-center justify-center font-black text-lg border border-slate-100 group-hover:bg-emerald-50 group-hover:text-emerald-600 group-hover:border-emerald-100 transition-all duration-500">
                        C{{ index + 1 }}
                    </div>

                    <div class="flex-1 w-full grid grid-cols-1 md:grid-cols-2 gap-6 pr-8 lg:pr-0">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Kriteria</label>
                            <input v-model="item.nama" @input="simpanPerubahan"
                                   class="w-full bg-slate-50 border-2 border-slate-50 rounded-2xl px-4 py-3 text-sm font-bold text-slate-800 focus:border-emerald-500 focus:bg-white focus:ring-0 transition-all" 
                                   placeholder="Contoh: Indeks Prestasi">
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Tipe Kriteria</label>
                            <div class="flex p-1 bg-slate-50 rounded-2xl border-2 border-slate-50 h-[46px]">
                                <button @click="ubahTipe(index, 'benefit')" 
                                        :class="item.tipe === 'benefit' ? 'bg-white text-emerald-600 shadow-sm border-emerald-100' : 'text-slate-400 hover:text-slate-600'"
                                        class="flex-1 rounded-xl text-[11px] font-black uppercase tracking-wider transition-all border border-transparent">
                                    Benefit
                                </button>
                                <button @click="ubahTipe(index, 'cost')" 
                                        :class="item.tipe === 'cost' ? 'bg-white text-rose-600 shadow-sm border-rose-100' : 'text-slate-400 hover:text-slate-600'"
                                        class="flex-1 rounded-xl text-[11px] font-black uppercase tracking-wider transition-all border border-transparent">
                                    Cost
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="w-full lg:w-40 space-y-1.5 text-center lg:text-left">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Bobot (0.0 - 1.0)</label>
                        <div class="relative group/input">
                            <input type="number" step="0.05" min="0" max="1" v-model="item.bobot" @input="simpanPerubahan"
                                   class="w-full bg-slate-50 border-2 border-slate-50 rounded-2xl px-4 py-3 font-black text-emerald-700 text-center focus:border-emerald-500 focus:bg-white focus:ring-0 transition-all appearance-none">
                            <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-300 font-bold text-xs uppercase group-focus-within/input:text-emerald-500">W</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import TambahKriteria from './TambahKriteria.vue'; // Pastiin ini udah bener letaknya ya blay!

// TERIMA DATA DARI DASHBOARD
const props = defineProps({
    dataKriteria: { type: Array, default: () => [] }
});
const emit = defineEmits(['update']);

// STATE BUAT NAMPILIN FORM ATAU LIST
const showForm = ref(false);
const notification = ref(null);

// Hitung Bobot Real-time dari Props
const totalBobot = computed(() => {
    const total = props.dataKriteria.reduce((sum, item) => sum + parseFloat(item.bobot || 0), 0);
    return parseFloat(total);
});

const showNotification = (message, type = 'success') => {
    notification.value = { message, type };
    setTimeout(() => { notification.value = null; }, 3000);
};

// FUNGSI MENERIMA DATA DARI KOMPONEN TAMBAHKRITERIA
const simpanKriteriaBaru = (dataBaru) => {
    const newData = [...props.dataKriteria];
    newData.push(dataBaru);
    emit('update', newData);
    showForm.value = false; // Tutup form setelah simpan
    showNotification("Kriteria baru berhasil disimpan.", "success");
};

// --- LOGIC HAPUS DATA ---
const hapusKriteria = (index) => {
    if(confirm('Yakin mau hapus kriteria ini blay?')) {
        const newData = [...props.dataKriteria];
        newData.splice(index, 1);
        
        // Benerin urutan kode C1, C2, dst setelah dihapus
        newData.forEach((item, i) => { item.kode = 'C' + (i + 1); });
        
        emit('update', newData);
        showNotification("Kriteria berhasil dihapus.", "success");
    }
};

// --- LOGIC SIMPAN OTOMATIS ---
const ubahTipe = (index, tipeBaru) => {
    const newData = [...props.dataKriteria];
    newData[index].tipe = tipeBaru;
    emit('update', newData);
};

const simpanPerubahan = () => {
    emit('update', props.dataKriteria);
};
</script>

<style scoped>
/* Ilangin tombol panah (spinners) di input number biar rapi */
input[type=number]::-webkit-inner-spin-button, 
input[type=number]::-webkit-outer-spin-button { 
  -webkit-appearance: none; 
  margin: 0; 
}
</style>