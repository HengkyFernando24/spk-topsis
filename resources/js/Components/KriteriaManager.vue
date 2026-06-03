<template>
  <div class="p-4 md:p-8 max-w-7xl mx-auto font-['Inter'] antialiased text-gray-900">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
      <div>
        <div class="flex items-center gap-3 mb-2">
          <div class="w-1.5 h-8 bg-emerald-500 rounded-full"></div>
          <h2 class="text-2xl font-bold text-emerald-900">Manajemen Kriteria</h2>
        </div>
        <p class="text-gray-500 text-sm">
          Kelola parameter penilaian untuk sistem pendukung keputusan menggunakan metode TOPSIS.
        </p>
      </div>
      <div class="flex gap-2">
        <button @click="resetToDefault"
                class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-4 py-2 rounded-lg flex items-center gap-2 transition text-sm font-medium">
          <span class="material-icons text-sm">restore</span>
          Reset Default
        </button>
        <button @click="showForm = true"
                class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition text-sm font-medium">
          <span class="material-icons text-sm">add</span>
          Tambah Kriteria
        </button>
      </div>
    </div>

    <!-- Stats Cards Sederhana -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
      <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
        <div class="flex items-center gap-2 mb-1">
          <span class="material-icons text-emerald-500 text-sm">assessment</span>
          <span class="text-[10px] font-semibold text-gray-400 uppercase">Total Kriteria</span>
        </div>
        <p class="text-2xl font-bold text-gray-800">{{ dataKriteria.length }}</p>
      </div>

      <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
        <div class="flex items-center gap-2 mb-1">
          <span class="material-icons text-emerald-500 text-sm">scale</span>
          <span class="text-[10px] font-semibold text-gray-400 uppercase">Akumulasi Bobot</span>
        </div>
        <p class="text-2xl font-bold" :class="totalBobot === 1.00 ? 'text-emerald-600' : 'text-amber-500'">
          {{ totalBobot.toFixed(2) }} / 1.00
        </p>
        <div class="mt-2 w-full bg-gray-200 h-1.5 rounded-full overflow-hidden">
          <div class="bg-emerald-500 h-full rounded-full transition-all" :style="{ width: `${Math.min(totalBobot * 100, 100)}%` }"></div>
        </div>
      </div>

      <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
        <div class="flex items-center gap-2 mb-1">
          <span class="material-icons text-emerald-500 text-sm">info</span>
          <span class="text-[10px] font-semibold text-gray-400 uppercase">Status</span>
        </div>
        <p class="text-sm font-medium" :class="dataKriteria.length >= 2 ? 'text-emerald-600' : 'text-amber-500'">
          {{ dataKriteria.length >= 2 ? '✓ Siap melakukan perhitungan' : '⚠ Minimal 2 kriteria' }}
        </p>
      </div>
    </div>

    <!-- FORM TAMBAH KRITERIA -->
    <div v-if="showForm" class="mb-6">
      <TambahKriteria
        :jumlah-kriteria="dataKriteria.length"
        @simpan="simpanKriteriaBaru"
        @batal="showForm = false"
        @go-home="$emit('go-home')"
        @go-kriteria="$emit('go-kriteria')"
      />
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div v-if="showDeleteConfirm" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
      <div class="bg-white rounded-xl p-5 max-w-sm w-full mx-4 shadow-xl">
        <div class="flex items-center gap-3 mb-4">
          <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
            <span class="material-icons text-red-500">warning</span>
          </div>
          <h3 class="font-bold text-gray-800">Hapus Kriteria?</h3>
        </div>
        <p class="text-sm text-gray-500 mb-5">
          Kriteria "<span class="font-semibold text-gray-700">{{ kriteriaToDelete?.nama }}</span>" akan dihapus permanen.
        </p>
        <div class="flex gap-3">
          <button @click="showDeleteConfirm = false" class="flex-1 py-2 border border-gray-200 rounded-lg text-sm font-medium hover:bg-gray-50 transition">Batal</button>
          <button @click="confirmDelete" class="flex-1 py-2 bg-red-500 text-white rounded-lg text-sm font-medium hover:bg-red-600 transition">Hapus</button>
        </div>
      </div>
    </div>

    <!-- Notifikasi -->
    <div v-if="notification" class="fixed bottom-6 right-6 z-50">
      <div :class="notification.type === 'success' ? 'bg-emerald-500' : 'bg-red-500'" class="text-white px-4 py-2 rounded-lg shadow-lg text-sm">
        {{ notification.message }}
      </div>
    </div>

    <!-- Tabel Data Kriteria -->
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
      <div class="bg-gray-50 px-5 py-3 border-b border-gray-200">
        <h3 class="font-semibold text-gray-700 text-sm">Daftar Kriteria Keputusan</h3>
        <p class="text-[10px] text-gray-400 mt-0.5">Kelola parameter penilaian untuk seleksi siswa</p>
      </div>

      <!-- Peringatan Bobot Tidak Seimbang -->
      <div v-if="totalBobot !== 1.00 && dataKriteria.length > 0" class="mx-5 mt-4">
        <div class="p-3 bg-amber-50 rounded-lg border-l-4 border-l-amber-500 text-xs text-amber-700">
          ⚠ Total bobot saat ini {{ totalBobot.toFixed(2) }}. Pastikan total berjumlah 1.00 untuk validitas algoritma TOPSIS.
        </div>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="bg-gray-50 border-b border-gray-200 text-[10px] font-semibold text-gray-500 uppercase">
              <th class="px-5 py-3 text-left w-16">No</th>
              <th class="px-5 py-3 text-left w-20">Kode</th>
              <th class="px-5 py-3 text-left">Nama Kriteria</th>
              <th class="px-5 py-3 text-left w-40">Tipe</th>
              <th class="px-5 py-3 text-left w-32">Bobot</th>
              <th class="px-5 py-3 text-right w-24">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            <tr v-for="(item, index) in dataKriteria" :key="item.id" class="hover:bg-gray-50 transition">
              <td class="px-5 py-3">
                <span class="font-semibold text-gray-500 text-sm">{{ index + 1 }}</span>
              </td>
              <td class="px-5 py-3">
                <span class="font-bold text-emerald-600 text-sm">{{ item.kode }}</span>
              </td>
              <td class="px-5 py-3">
                <input v-model="item.nama" @input="simpanPerubahan"
                       class="w-full bg-transparent border-b border-transparent hover:border-gray-300 focus:border-emerald-500 focus:outline-none px-0 py-1 transition text-gray-700 text-sm"
                       placeholder="Nama kriteria...">
              </td>
              <td class="px-5 py-3">
                <div class="flex gap-2">
                  <button @click="ubahTipe(index, 'benefit')"
                          :class="item.tipe === 'benefit' ? 'bg-emerald-500 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                          class="px-3 py-1 rounded-full text-xs font-medium transition">
                    Benefit
                  </button>
                  <button @click="ubahTipe(index, 'cost')"
                          :class="item.tipe === 'cost' ? 'bg-rose-500 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                          class="px-3 py-1 rounded-full text-xs font-medium transition">
                    Cost
                  </button>
                </div>
              </td>
              <td class="px-5 py-3">
                <div class="flex items-center gap-2">
                  <input type="number" step="0.05" min="0" max="1" v-model="item.bobot" @input="simpanPerubahan"
                         class="w-20 px-2 py-1.5 border border-gray-200 rounded-lg text-center text-emerald-600 font-semibold text-sm focus:border-emerald-500 focus:outline-none">
                  <span class="text-gray-400 text-xs">({{ (item.bobot * 100).toFixed(0) }}%)</span>
                </div>
                <div class="mt-1 w-20 bg-gray-100 rounded-full h-1 overflow-hidden">
                  <div class="h-full rounded-full transition-all bg-emerald-400" :style="{ width: (item.bobot * 100) + '%' }"></div>
                </div>
              </td>
              <td class="px-5 py-3 text-right">
                <div class="flex gap-1 justify-end">
                  <button @click="duplikatKriteria(index)"
                          class="p-1.5 text-blue-500 hover:bg-blue-50 rounded-lg transition"
                          title="Duplikat">
                    <span class="material-icons text-sm">content_copy</span>
                  </button>
                  <button @click="konfirmasiHapus(index)"
                          class="p-1.5 text-red-500 hover:bg-red-50 rounded-lg transition"
                          title="Hapus">
                    <span class="material-icons text-sm">delete</span>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Tips Footer -->
    <div class="mt-6 bg-emerald-50 border border-emerald-200 rounded-lg p-4">
      <div class="flex items-start gap-3">
        <span class="material-icons text-emerald-500 text-sm">lightbulb</span>
        <div>
          <h5 class="font-semibold text-emerald-800 text-xs mb-0.5">Tips Manajemen Kriteria</h5>
          <p class="text-emerald-700/80 text-xs">Pastikan total bobot semua kriteria berjumlah <strong>1.00</strong> (100%) untuk validitas perhitungan TOPSIS.</p>
        </div>
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
const showDeleteConfirm = ref(false);
const kriteriaToDelete = ref(null);
const deleteIndex = ref(null);

// DATA KRITERIA DEFAULT (5 Kriteria)
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

// Reset ke default
const resetToDefault = () => {
    if(confirm('Reset ke 5 kriteria default? Data yang diubah akan hilang.')) {
        emit('update', defaultKriteria);
        showNotification("Kriteria dikembalikan ke default.");
    }
};

// Konfirmasi hapus dengan modal
const konfirmasiHapus = (index) => {
    kriteriaToDelete.value = props.dataKriteria[index];
    deleteIndex.value = index;
    showDeleteConfirm.value = true;
};

const confirmDelete = () => {
    if(deleteIndex.value !== null) {
        const newData = [...props.dataKriteria];
        newData.splice(deleteIndex.value, 1);

        // Update kode
        newData.forEach((item, i) => {
            item.kode = 'C' + (i + 1);
        });

        emit('update', newData);
        showNotification("Kriteria berhasil dihapus.");
    }
    showDeleteConfirm.value = false;
    kriteriaToDelete.value = null;
    deleteIndex.value = null;
};

// Duplikat kriteria
const duplikatKriteria = (index) => {
    const item = props.dataKriteria[index];
    const newData = [...props.dataKriteria];
    newData.push({
        id: Date.now(),
        kode: `C${newData.length + 1}`,
        nama: `${item.nama} (Copy)`,
        tipe: item.tipe,
        bobot: item.bobot
    });
    emit('update', newData);
    showNotification("Kriteria berhasil diduplikasi.");
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
    showNotification("Kriteria baru berhasil ditambahkan!");
};

const ubahTipe = (index, tipeBaru) => {
    const newData = [...props.dataKriteria];
    newData[index].tipe = tipeBaru;
    emit('update', newData);
    showNotification(`Tipe kriteria diubah menjadi ${tipeBaru}.`);
};

const simpanPerubahan = () => {
    emit('update', props.dataKriteria);
};

// INISIALISASI DATA SAAT KOMPONEN DIMOUNT
onMounted(() => {
    if (props.dataKriteria.length === 0) {
        emit('update', defaultKriteria);
    }
});
</script>

<style scoped>
@import url('https://fonts.googleapis.com/icon?family=Material+Icons');
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

/* Hilangkan spinner di input number */
input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Custom Scrollbar */
::-webkit-scrollbar {
  width: 6px;
  height: 6px;
}

::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 10px;
}

::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
  background: #10b981;
}
</style>
