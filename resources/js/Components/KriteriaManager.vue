<template>
  <div class="p-4 md:p-8 max-w-7xl mx-auto font-['Inter'] antialiased text-gray-900">
    <!-- Header Section dengan Animasi -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 animate-in fade-in slide-in-from-top-4 duration-500">
      <div>
        <div class="flex items-center gap-3 mb-2">
          <div class="w-1.5 h-8 bg-emerald-500 rounded-full animate-pulse"></div>
          <h2 class="text-3xl font-black text-emerald-900 tracking-tight">Manajemen Kriteria</h2>
        </div>
        <p class="text-gray-500 text-sm max-w-2xl leading-relaxed">
          Kelola parameter penilaian untuk sistem pendukung keputusan menggunakan algoritma <span class="font-bold text-emerald-700">TOPSIS</span>.
        </p>
      </div>
      <button @click="showForm = true"
              class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-2xl flex items-center gap-2 transition-all duration-300 shadow-lg hover:shadow-emerald-200 active:scale-95 group">
        <span class="material-icons text-lg group-hover:rotate-90 transition-transform duration-300">add</span>
        <span class="font-bold text-sm tracking-wide">Tambah Kriteria</span>
      </button>
    </div>

    <!-- Stats Overview Cards dengan Animasi Stagger -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
      <!-- Total Kriteria Card -->
      <div class="bg-gradient-to-br from-emerald-950 to-emerald-900 rounded-2xl overflow-hidden border border-emerald-800 shadow-xl hover:shadow-2xl transition-all duration-500 hover:-translate-y-1 animate-in fade-in slide-in-from-bottom-4 duration-500" style="animation-delay: 0.1s">
        <div class="bg-emerald-900/40 px-6 py-3 border-b border-emerald-800">
          <h3 class="text-[10px] font-black text-emerald-300 uppercase tracking-widest flex items-center gap-2">
            <span class="material-icons text-sm">assessment</span>
            Total Kriteria
          </h3>
        </div>
        <div class="p-6">
          <div class="text-5xl font-black text-white mb-2 transition-all duration-300 group-hover:scale-110">
            {{ dataKriteria.length }}
          </div>
          <p class="text-emerald-400/70 text-xs font-medium">
            {{ dataKriteria.length === 0 ? 'Belum ada kriteria terdaftar' : 'Kriteria aktif dalam sistem' }}
          </p>
        </div>
      </div>

      <!-- Akumulasi Bobot Card -->
      <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl overflow-hidden border border-slate-700 shadow-xl hover:shadow-2xl transition-all duration-500 hover:-translate-y-1 animate-in fade-in slide-in-from-bottom-4 duration-500" style="animation-delay: 0.2s">
        <div class="bg-slate-800/40 px-6 py-3 border-b border-slate-700">
          <h3 class="text-[10px] font-black text-slate-300 uppercase tracking-widest flex items-center gap-2">
            <span class="material-icons text-sm">scale</span>
            Akumulasi Bobot
          </h3>
        </div>
        <div class="p-6">
          <div class="flex items-baseline gap-2 mb-3">
            <span class="text-5xl font-black transition-all duration-500"
                  :class="totalBobot === 1.00 ? 'text-emerald-400' : 'text-amber-400'">
              {{ totalBobot.toFixed(2) }}
            </span>
            <span class="text-slate-500 text-lg font-bold">/ 1.00</span>
          </div>
          <div class="relative w-full bg-slate-700/50 h-2 rounded-full overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-emerald-500 to-emerald-400 h-full rounded-full transition-all duration-1000 ease-out"
                 :style="{ width: `${Math.min(totalBobot * 100, 100)}%` }">
              <div class="absolute inset-0 bg-white/20 animate-pulse"></div>
            </div>
          </div>
          <p class="text-slate-400 text-[10px] font-medium mt-3">
            {{ totalBobot === 1.00 ? '✓ Bobot seimbang' : '⚠ Total bobot harus 1.00' }}
          </p>
        </div>
      </div>

      <!-- Status Kelengkapan Card -->
      <div class="bg-white rounded-2xl overflow-hidden border border-gray-200 shadow-xl hover:shadow-2xl transition-all duration-500 hover:-translate-y-1 animate-in fade-in slide-in-from-bottom-4 duration-500" style="animation-delay: 0.3s">
        <div class="bg-gray-50 px-6 py-3 border-b border-gray-100 flex justify-between items-center">
          <h3 class="text-[10px] font-black text-gray-500 uppercase tracking-widest flex items-center gap-2">
            <span class="material-icons text-sm">info</span>
            Status Kelengkapan
          </h3>
          <div class="transition-all duration-300"
               :class="dataKriteria.length >= 2 ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700'"
               style="padding: 4px 8px; border-radius: 8px;">
            <span class="text-[9px] font-black uppercase tracking-wider flex items-center gap-1">
              <span class="material-icons text-xs">{{ dataKriteria.length >= 2 ? 'check_circle' : 'warning' }}</span>
              {{ dataKriteria.length >= 2 ? 'Siap' : 'Perhatian' }}
            </span>
          </div>
        </div>
        <div class="p-6">
          <p class="text-gray-600 text-sm leading-relaxed">
            {{ dataKriteria.length < 2 ? 'Sistem memerlukan minimal 2 kriteria untuk memulai proses perhitungan algoritma TOPSIS.' : 'Kriteria mencukupi untuk melakukan perhitungan.' }}
          </p>
        </div>
      </div>
    </div>

    <!-- FORM TAMBAH KRITERIA dengan Animasi -->
    <transition
      enter-active-class="transition-all duration-500 ease-out"
      enter-from-class="opacity-0 transform -translate-y-8 scale-95"
      enter-to-class="opacity-100 transform translate-y-0 scale-100"
      leave-active-class="transition-all duration-300 ease-in"
      leave-from-class="opacity-100 transform translate-y-0 scale-100"
      leave-to-class="opacity-0 transform -translate-y-8 scale-95">
      <div v-if="showForm" class="mb-8">
        <TambahKriteria
          :jumlah-kriteria="dataKriteria.length"
          @simpan="simpanKriteriaBaru"
          @batal="showForm = false"
          @go-home="$emit('go-home')"
          @go-kriteria="$emit('go-kriteria')"
        />
      </div>
    </transition>

    <!-- Main Content Card -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-xl overflow-hidden transition-all duration-300 hover:shadow-2xl">
      <!-- Card Header -->
      <div class="bg-gradient-to-r from-emerald-950 to-emerald-900 px-6 py-5">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
          <div>
            <h3 class="font-black text-white text-lg tracking-wide flex items-center gap-2">
              <span class="material-icons">table_view</span>
              Daftar Kriteria Keputusan
            </h3>
            <p class="text-emerald-400/70 text-xs mt-1">Kelola parameter penilaian untuk seleksi siswa</p>
          </div>
          <div class="flex gap-2">
            <div class="bg-emerald-800/50 rounded-lg px-3 py-1.5">
              <span class="text-emerald-300 text-[10px] font-black uppercase tracking-wider">
                Total: {{ dataKriteria.length }} Kriteria
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Notifikasi dengan Animasi -->
      <transition
        enter-active-class="transition-all duration-500 ease-out"
        enter-from-class="opacity-0 transform -translate-y-4"
        enter-to-class="opacity-100 transform translate-y-0"
        leave-active-class="transition-all duration-300 ease-in"
        leave-from-class="opacity-100 transform translate-y-0"
        leave-to-class="opacity-0 transform -translate-y-4">
        <div v-if="notification" class="mx-6 mt-6">
          <div :class="notification.type === 'success' ? 'bg-emerald-50 border-emerald-200 text-emerald-800' : 'bg-red-50 border-red-200 text-red-800'"
               class="p-4 rounded-xl border flex items-center gap-3 shadow-lg animate-in fade-in slide-in-from-top-2 duration-300">
            <div :class="notification.type === 'success' ? 'bg-emerald-500' : 'bg-red-500'"
                 class="w-8 h-8 rounded-full flex items-center justify-center text-white shrink-0 animate-bounce">
              <span class="material-icons text-sm">{{ notification.type === 'success' ? 'check' : 'close' }}</span>
            </div>
            <p class="text-sm font-bold">{{ notification.message }}</p>
          </div>
        </div>
      </transition>

      <!-- Peringatan Bobot Tidak Seimbang -->
      <transition
        enter-active-class="transition-all duration-500 ease-out"
        enter-from-class="opacity-0 transform -translate-x-4"
        enter-to-class="opacity-100 transform translate-x-0"
        leave-active-class="transition-all duration-300 ease-in"
        leave-from-class="opacity-100 transform translate-x-0"
        leave-to-class="opacity-0 transform -translate-x-4">
        <div v-if="totalBobot !== 1.00 && dataKriteria.length > 0" class="mx-6 mt-6">
          <div class="p-5 bg-amber-50 rounded-xl border-l-4 border-l-amber-500 border border-amber-100 flex items-start gap-4 shadow-lg animate-pulse">
            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-amber-500 shrink-0 shadow-sm">
              <span class="material-icons">warning</span>
            </div>
            <div>
              <p class="text-sm font-black text-amber-900 uppercase tracking-tight">Konfigurasi Bobot Tidak Seimbang</p>
              <p class="text-xs font-bold text-amber-700/80 mt-1">
                Total bobot saat ini {{ totalBobot.toFixed(2) }}. Pastikan total berjumlah 1.00 untuk validitas algoritma TOPSIS.
              </p>
            </div>
          </div>
        </div>
      </transition>

      <!-- Tabel Data Kriteria -->
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gray-50 border-b-2 border-gray-200">
            <tr>
              <th class="px-6 py-4 text-left text-xs font-black text-gray-500 uppercase tracking-wider">No</th>
              <th class="px-6 py-4 text-left text-xs font-black text-gray-500 uppercase tracking-wider">Kode</th>
              <th class="px-6 py-4 text-left text-xs font-black text-gray-500 uppercase tracking-wider">Nama Kriteria</th>
              <th class="px-6 py-4 text-left text-xs font-black text-gray-500 uppercase tracking-wider">Tipe</th>
              <th class="px-6 py-4 text-left text-xs font-black text-gray-500 uppercase tracking-wider">Bobot</th>
              <th class="px-6 py-4 text-right text-xs font-black text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            <tr v-for="(item, index) in dataKriteria" :key="item.id"
                class="group hover:bg-emerald-50/30 transition-all duration-300 hover:scale-[1.01] animate-in fade-in slide-in-from-left duration-500"
                :style="{ animationDelay: `${index * 0.05}s` }">
              <td class="px-6 py-4">
                <div class="w-8 h-8 bg-slate-100 rounded-xl flex items-center justify-center text-slate-500 font-black text-sm group-hover:bg-emerald-100 group-hover:text-emerald-600 transition-all duration-300">
                  {{ index + 1 }}
                </div>
              </td>
              <td class="px-6 py-4">
                <div class="font-bold text-emerald-700 text-sm">
                  {{ item.kode }}
                </div>
              </td>
              <td class="px-6 py-4">
                <input v-model="item.nama" @input="simpanPerubahan"
                       class="w-full bg-transparent border-b-2 border-transparent hover:border-gray-300 focus:border-emerald-500 focus:outline-none px-1 -ml-1 py-1 transition-all duration-300 font-medium text-gray-700"
                       placeholder="Nama kriteria...">
              </td>
              <td class="px-6 py-4">
                <div class="flex gap-2">
                  <button @click="ubahTipe(index, 'benefit')"
                          :class="item.tipe === 'benefit' ? 'bg-emerald-500 text-white shadow-md' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                          class="px-4 py-1.5 rounded-full text-xs font-bold transition-all duration-300 transform hover:scale-105">
                    Benefit
                  </button>
                  <button @click="ubahTipe(index, 'cost')"
                          :class="item.tipe === 'cost' ? 'bg-rose-500 text-white shadow-md' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                          class="px-4 py-1.5 rounded-full text-xs font-bold transition-all duration-300 transform hover:scale-105">
                    Cost
                  </button>
                </div>
              </td>
              <td class="px-6 py-4">
                <div class="relative w-32">
                  <input type="number" step="0.05" min="0" max="1" v-model="item.bobot" @input="simpanPerubahan"
                         class="w-full px-3 py-2 border-2 border-gray-200 rounded-xl text-center text-emerald-700 font-black focus:border-emerald-500 focus:outline-none transition-all duration-300">
                  <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                    <span class="text-gray-400 text-xs font-bold">%</span>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 text-right">
                <button @click="hapusKriteria(index)"
                        class="p-2 bg-red-50 text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition-all duration-300 transform hover:scale-110 active:scale-95 group-hover:opacity-100"
                        title="Hapus Kriteria">
                  <span class="material-icons text-sm">delete</span>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Tips Footer dengan Animasi -->
    <div class="mt-8 bg-gradient-to-r from-emerald-50 to-emerald-100/30 border border-emerald-200 rounded-2xl p-6 flex items-start gap-4 shadow-lg animate-in fade-in slide-in-from-bottom-4 duration-700" style="animation-delay: 0.4s">
      <div class="w-12 h-12 rounded-2xl bg-white flex items-center justify-center text-emerald-600 shadow-md animate-bounce">
        <span class="material-icons">lightbulb</span>
      </div>
      <div>
        <h5 class="font-black text-emerald-900 text-sm mb-1 flex items-center gap-2">
          Tips Manajemen Kriteria
          <span class="text-[10px] bg-emerald-200 text-emerald-800 px-2 py-0.5 rounded-full">Pro Tip</span>
        </h5>
        <p class="text-emerald-800/80 text-sm leading-relaxed">
          Pastikan jumlah total bobot dari semua kriteria yang Anda masukkan berjumlah tepat <strong class="font-black text-emerald-900">1.00</strong> (atau 100%) untuk menjamin validitas perhitungan matematis pada algoritma <strong class="font-black">TOPSIS</strong>.
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import TambahKriteria from './TambahKriteria.vue';

// Props dari parent component
const props = defineProps({
    dataKriteria: { type: Array, default: () => [] }
});

// Emits ke parent
const emit = defineEmits(['update', 'go-home', 'go-kriteria']);

// State management
const showForm = ref(false);
const notification = ref(null);

// DATA KRITERIA FIXS (5 Kriteria sesuai musyawarah)
const defaultKriteria = [
    { id: 1, kode: 'C1', nama: 'IPK', tipe: 'benefit', bobot: 0.30 },
    { id: 2, kode: 'C2', nama: 'Kehadiran', tipe: 'benefit', bobot: 0.20 },
    { id: 3, kode: 'C3', nama: 'Prestasi', tipe: 'benefit', bobot: 0.15 },
    { id: 4, kode: 'C4', nama: 'Keterlambatan', tipe: 'cost', bobot: 0.10 },
    { id: 5, kode: 'C5', nama: 'Tugas', tipe: 'benefit', bobot: 0.25 }
];

// Computed properties
const totalBobot = computed(() => {
    const total = props.dataKriteria.reduce((sum, item) => sum + parseFloat(item.bobot || 0), 0);
    return parseFloat(total);
});

// Helper functions
const showNotification = (message, type = 'success') => {
    notification.value = { message, type };
    setTimeout(() => { notification.value = null; }, 3000);
};

// CRUD Operations
const simpanKriteriaBaru = (dataBaru) => {
    const newData = [...props.dataKriteria];
    newData.push({
        id: Date.now(),
        kode: dataBaru.code || `C${newData.length + 1}`,
        nama: dataBaru.name,
        tipe: dataBaru.type,
        bobot: parseFloat(dataBaru.weight)
    });

    emit('update', newData);
    showForm.value = false;
    showNotification("Kriteria baru berhasil ditambahkan!", "success");
};

const hapusKriteria = (index) => {
    if(confirm('Yakin ingin menghapus kriteria ini?')) {
        const newData = [...props.dataKriteria];
        newData.splice(index, 1);

        // Update kode
        newData.forEach((item, i) => {
            item.kode = 'C' + (i + 1);
        });

        emit('update', newData);
        showNotification("Kriteria berhasil dihapus.", "success");
    }
};

const ubahTipe = (index, tipeBaru) => {
    const newData = [...props.dataKriteria];
    newData[index].tipe = tipeBaru;
    emit('update', newData);
    showNotification(`Tipe kriteria diubah menjadi ${tipeBaru}.`, "success");
};

const simpanPerubahan = () => {
    emit('update', props.dataKriteria);
};

// INISIALISASI DATA FIXS SAAT KOMPONEN DIMOUNT
onMounted(() => {
    // Cek apakah data kriteria masih kosong
    if (props.dataKriteria.length === 0) {
        // Kirim data kriteria fixs ke parent
        emit('update', defaultKriteria);
    }
});
</script>

<style scoped>
@import url('https://fonts.googleapis.com/icon?family=Material+Icons');
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');

/* Custom Animations */
@keyframes fade-in {
  from { opacity: 0; }
  to { opacity: 1; }
}

@keyframes slide-in-from-top-4 {
  from { transform: translateY(-1rem); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
}

@keyframes slide-in-from-bottom-4 {
  from { transform: translateY(1rem); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
}

@keyframes slide-in-from-left {
  from { transform: translateX(-1rem); opacity: 0; }
  to { transform: translateX(0); opacity: 1; }
}

@keyframes zoom-in {
  from { transform: scale(0.95); opacity: 0; }
  to { transform: scale(1); opacity: 1; }
}

@keyframes bounce {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-10px); }
}

@keyframes pulse {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.5; }
}

.animate-in {
  animation-fill-mode: both;
}

.fade-in {
  animation-name: fade-in;
}

.slide-in-from-top-4 {
  animation-name: slide-in-from-top-4;
}

.slide-in-from-bottom-4 {
  animation-name: slide-in-from-bottom-4;
}

.slide-in-from-left {
  animation-name: slide-in-from-left;
}

.zoom-in {
  animation-name: zoom-in;
}

.animate-bounce {
  animation: bounce 1s ease-in-out infinite;
}

.animate-pulse {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

.duration-500 {
  animation-duration: 500ms;
}

.duration-700 {
  animation-duration: 700ms;
}

/* Range Input Custom Styles */
input[type=range]::-webkit-slider-thumb {
  -webkit-appearance: none;
  height: 20px;
  width: 20px;
  border-radius: 50%;
  background: #10b981;
  cursor: pointer;
  box-shadow: 0 0 10px rgba(16, 185, 129, 0.3);
  margin-top: -6px;
}

input[type=range]::-webkit-slider-runnable-track {
  width: 100%;
  height: 8px;
  cursor: pointer;
  background: #1f2937;
  border-radius: 4px;
}

/* Hilangkan spinner di input number */
input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Custom Scrollbar */
::-webkit-scrollbar {
  width: 8px;
  height: 8px;
}

::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 10px;
}

::-webkit-scrollbar-thumb {
  background: #10b981;
  border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
  background: #059669;
}
</style>
