<template>
  <div class="p-4 md:p-6 max-w-7xl mx-auto font-['Inter'] antialiased text-gray-900 bg-transparent">

    <!-- Header -->
    <div class="flex items-center gap-3 mb-2">
      <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-700 flex items-center justify-center text-white shadow-md">
        <span class="material-icons text-xl">analytics</span>
      </div>
      <div>
        <h2 class="text-2xl font-extrabold text-emerald-950">Edu TOPSIS</h2>
        <p class="text-[10px] text-gray-400">Sistem Penunjang Keputusan - Metode TOPSIS</p>
      </div>
    </div>

    <div class="space-y-2 mb-4 animate-fadeInUp">
      <div class="inline-flex items-center px-3 py-1 bg-emerald-50 text-emerald-700 text-[10px] font-bold rounded-full animate-pulse-slow">
        Report Generated: {{ formattedDate }}
      </div>
      <h2 class="text-2xl font-extrabold text-emerald-950">Laporan Hasil Perhitungan TOPSIS</h2>
      <p class="text-gray-500 text-sm max-w-2xl">
        Daftar peringkat mahasiswa berdasarkan metode TOPSIS.
      </p>
    </div>

    <!-- FILTER DATA PERHITUNGAN -->
    <div class="bg-white rounded-xl p-4 mb-6 shadow-sm border border-gray-100 animate-fadeInUp">
      <div class="flex items-center gap-2 mb-3">
        <span class="material-icons text-emerald-600 text-sm">filter_alt</span>
        <h4 class="font-semibold text-gray-700 text-sm">Filter Data Perhitungan</h4>
        <span class="text-[10px] text-gray-400 ml-2">(Pilih filter untuk melihat hasil perhitungan)</span>
      </div>

      <!-- Baris 1: Filter (5 Kolom) -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-4">
        <div>
          <label class="block text-[10px] font-bold text-gray-500 mb-1">Fakultas</label>
          <select v-model="selectedFakultas" @change="onFakultasChange" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-emerald-500 focus:outline-none">
            <option value="">-- Pilih Fakultas --</option>
            <option v-for="f in fakultasList" :key="f.id" :value="f.id">{{ f.nama }}</option>
          </select>
        </div>
        <div>
          <label class="block text-[10px] font-bold text-gray-500 mb-1">Program Studi</label>
          <select v-model="selectedProdi" @change="onFilterChange" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-emerald-500 focus:outline-none">
            <option value="">-- Pilih Program Studi --</option>
            <option v-for="p in filteredProdi" :key="p.id" :value="p.id">{{ p.nama }}</option>
          </select>
        </div>
        <div>
          <label class="block text-[10px] font-bold text-gray-500 mb-1">Angkatan</label>
          <select v-model="selectedAngkatan" @change="onFilterChange" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-emerald-500 focus:outline-none">
            <option value="">-- Pilih Angkatan --</option>
            <option v-for="a in angkatanList" :key="a.id" :value="a.id">{{ a.tahun }}</option>
          </select>
        </div>
        <div>
          <label class="block text-[10px] font-bold text-gray-500 mb-1">Semester</label>
          <select v-model="selectedSemester" @change="onFilterChange" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-emerald-500 focus:outline-none">
            <option value="">-- Pilih Semester --</option>
            <option v-for="s in semesterList" :key="s.id" :value="s.id">{{ s.nama }}</option>
          </select>
        </div>
        <div>
          <label class="block text-[10px] font-bold text-gray-500 mb-1">Kelas</label>
          <select v-model="selectedKelas" @change="onFilterChange" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-emerald-500 focus:outline-none">
            <option value="">-- Semua Kelas --</option>
            <option v-for="k in kelasList" :key="k.id" :value="k.id">{{ k.nama }}</option>
          </select>
        </div>
      </div>

      <!-- Baris 2: PENCARIAN (hanya muncul jika sudah ada data) -->
      <div v-if="currentData" class="border-t border-gray-100 pt-4 mt-2">
        <div class="flex flex-col sm:flex-row gap-3 items-end">
          <div class="flex-1">
            <label class="block text-[10px] font-bold text-gray-500 mb-1">
              <span class="material-icons text-xs align-middle">search</span> Cari Mahasiswa
            </label>
            <div class="relative">
              <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                <span class="material-icons text-sm">search</span>
              </span>
              <input
                type="text"
                v-model="searchQuery"
                @input="onSearchChange"
                placeholder="Cari berdasarkan Nama atau NIM..."
                class="w-full pl-9 pr-10 py-2 border border-gray-200 rounded-lg text-sm focus:border-emerald-500 focus:outline-none"
              />
              <button
                v-if="searchQuery"
                @click="clearSearch"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
              >
                <span class="material-icons text-sm">close</span>
              </button>
            </div>
          </div>
          <div class="flex gap-2">
            <button @click="resetFilter" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg text-xs font-medium transition-all flex items-center gap-1">
              <span class="material-icons text-sm">refresh</span>
              Reset Filter
            </button>
          </div>
        </div>

        <div v-if="searchQuery" class="mt-2 text-right">
          <span class="text-[10px] text-emerald-600">
            Ditemukan {{ filteredStudents.length }} dari {{ baseSortedStudents.length }} mahasiswa
          </span>
        </div>
      </div>

      <div class="mt-3 text-center">
        <span v-if="!isFilterComplete" class="text-[11px] text-amber-600 bg-amber-50 px-3 py-1.5 rounded-full inline-flex items-center gap-1">
          <span class="material-icons text-sm">info</span>
          Silakan pilih Fakultas, Program Studi, Angkatan, dan Semester untuk melihat hasil perhitungan
        </span>
        <span v-else-if="hasMatchingData" class="text-[11px] text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded-full inline-flex items-center gap-1">
          <span class="material-icons text-sm">check_circle</span>
          Menampilkan hasil perhitungan untuk filter yang dipilih
        </span>
        <span v-else-if="isFilterComplete && !hasMatchingData" class="text-[11px] text-amber-600 bg-amber-50 px-3 py-1.5 rounded-full inline-flex items-center gap-1">
          <span class="material-icons text-sm">warning</span>
          Tidak ada perhitungan untuk filter yang dipilih
        </span>
      </div>
    </div>

    <!-- Alert jika belum ada data perhitungan di localStorage -->
    <Transition name="alert">
      <div v-if="riwayatPerhitungan.length === 0" class="mb-6 p-5 bg-amber-50 rounded-xl border-l-4 border-l-amber-500 animate-fadeInUp">
        <div class="flex items-start gap-3">
          <span class="material-icons text-amber-500 animate-pulse-slow">warning</span>
          <div>
            <p class="font-bold text-amber-900 text-sm">Belum Ada Data Perhitungan</p>
            <p class="text-xs text-amber-700">Silakan lakukan perhitungan TOPSIS terlebih dahulu di menu "Proses Perhitungan".</p>
          </div>
        </div>
      </div>
    </Transition>

    <!-- HASIL PERHITUNGAN (hanya tampil jika filter lengkap DAN ada data) -->
    <div v-if="isFilterComplete && hasMatchingData && currentData" class="space-y-6">

      <!-- Info Filter Ringkas -->
      <div class="bg-gradient-to-r from-emerald-50 to-white rounded-xl border border-emerald-100 shadow-sm overflow-hidden animate-fadeInUp">
        <div class="px-5 py-3">
          <div class="flex flex-wrap items-center gap-4 text-xs">
            <div class="flex items-center gap-1">
              <span class="material-icons text-emerald-500 text-sm">business</span>
              <span class="text-gray-500">Fakultas:</span>
              <span class="font-semibold text-gray-700">{{ currentData.filter.fakultasNama }}</span>
            </div>
            <div class="flex items-center gap-1">
              <span class="material-icons text-emerald-500 text-sm">school</span>
              <span class="text-gray-500">Program Studi:</span>
              <span class="font-semibold text-gray-700">{{ currentData.filter.prodiNama }}</span>
            </div>
            <div class="flex items-center gap-1">
              <span class="material-icons text-emerald-500 text-sm">event</span>
              <span class="text-gray-500">Angkatan:</span>
              <span class="font-semibold text-gray-700">{{ currentData.filter.angkatanTahun }}</span>
            </div>
            <div class="flex items-center gap-1">
              <span class="material-icons text-emerald-500 text-sm">book</span>
              <span class="text-gray-500">Semester:</span>
              <span class="font-semibold text-gray-700">{{ currentData.filter.semesterNama }}</span>
            </div>
            <div class="flex items-center gap-1" v-if="currentData.filter.kelasNama && currentData.filter.kelasNama !== '-'">
              <span class="material-icons text-emerald-500 text-sm">class</span>
              <span class="text-gray-500">Kelas:</span>
              <span class="font-semibold text-gray-700">{{ currentData.filter.kelasNama }}</span>
            </div>
            <div class="flex items-center gap-1">
              <span class="material-icons text-emerald-500 text-sm">schedule</span>
              <span class="text-gray-500">Waktu:</span>
              <span class="font-semibold text-gray-700">{{ currentData.waktu }}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Statistik Ringkas -->
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div v-for="(stat, idx) in statsData" :key="idx"
             class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300 animate-fadeInUp"
             :style="{ animationDelay: `${idx * 0.1}s` }">
          <p class="text-[10px] font-bold text-gray-400 uppercase">{{ stat.label }}</p>
          <p class="text-2xl font-black text-emerald-700">{{ stat.value }}</p>
          <div class="mt-2 h-0.5 w-full bg-emerald-100 rounded-full overflow-hidden">
            <div class="h-full bg-emerald-500 rounded-full animate-slideIn" :style="{ width: stat.percentage + '%' }"></div>
          </div>
        </div>
      </div>

      <!-- Top 5 Mahasiswa -->
      <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden animate-fadeInUp" style="animation-delay: 0.2s">
        <div class="bg-emerald-50 px-5 py-3 border-b border-emerald-100">
          <h3 class="font-bold text-emerald-800 text-sm flex items-center gap-2">
            <span class="material-icons text-emerald-600 text-sm animate-pulse-slow">military_tech</span>
            🏆 Top 5 Mahasiswa Terbaik
          </h3>
        </div>
        <div class="p-5 space-y-4">
          <div v-for="(student, index) in top5Students" :key="student.id"
               class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0 group hover:bg-gray-50 rounded-lg transition-all duration-300 px-3">
            <div class="flex items-center gap-4">
              <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-sm">{{ index + 1 }}</div>
              <div>
                <span class="font-semibold text-gray-800 group-hover:text-emerald-700 transition-colors">{{ student.nama }}</span>
                <p class="text-[10px] text-gray-400">{{ student.nim }}</p>
              </div>
            </div>
            <div class="flex items-center gap-4">
              <div class="w-32 h-2 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full bg-emerald-500 rounded-full" :style="{ width: ((student.preferensi || student.v || 0) * 100) + '%' }"></div>
              </div>
              <span class="text-sm font-bold text-emerald-600 min-w-[70px] text-right">{{ ((student.preferensi || student.v || 0) * 100).toFixed(2) }}%</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Tabel Seluruh Data -->
      <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="bg-gray-50 px-5 py-3 border-b border-gray-100">
          <div class="flex items-center justify-between flex-wrap gap-2">
            <h3 class="font-bold text-gray-700 text-sm flex items-center gap-2">
              <span class="material-icons text-gray-500 text-sm">table_rows</span>
              Seluruh Data Mahasiswa
              <span v-if="searchQuery" class="text-[10px] font-normal text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">
                Hasil Pencarian: {{ filteredStudents.length }}
              </span>
            </h3>
          </div>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full text-sm">
            <thead class="bg-gray-50">
              <tr class="text-[10px] font-bold text-gray-400 uppercase">
                <th class="px-4 py-3 text-left w-16">Rank</th>
                <th class="px-4 py-3 text-left">NIM</th>
                <th class="px-4 py-3 text-left">Nama Mahasiswa</th>
                <th class="px-4 py-3 text-left">Kelas</th>
                <th v-for="k in dataKriteria" :key="k.id" class="px-4 py-3 text-center w-24">{{ k.nama }}</th>
                <th class="px-4 py-3 text-center w-24">Nilai V</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
              <tr v-for="(item, index) in paginatedData" :key="item.id" class="hover:bg-gray-50 transition-all duration-200">
                <td class="px-4 py-2 text-center font-bold text-gray-500">{{ item.rank }}</td>
                <td class="px-4 py-2 font-medium text-gray-800">{{ item.nim || '-' }}</td>
                <td class="px-4 py-2 font-medium text-gray-800">{{ item.nama }}</td>
                <td class="px-4 py-2 font-medium text-gray-600">{{ item.kelas || '-' }}</td>
                <td v-for="k in dataKriteria" :key="k.id" class="px-4 py-2 text-center text-gray-600">
                  {{ getNilaiMahasiswa(item.id, k.id) }}
                </td>
                <td class="px-4 py-2 text-center font-semibold text-emerald-600">{{ ((item.preferensi || item.v || 0) * 100).toFixed(2) }}%</td>
              </tr>
              <tr v-if="filteredStudents.length === 0 && searchQuery">
                <td :colspan="4 + dataKriteria.length" class="px-4 py-12 text-center text-gray-400">
                  <div class="flex flex-col items-center gap-2">
                    <span class="material-icons text-4xl">search_off</span>
                    <p>Tidak ada mahasiswa yang cocok dengan pencarian "{{ searchQuery }}"</p>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div class="px-4 py-3 border-t border-gray-100 flex justify-between items-center flex-wrap gap-2">
          <p class="text-[10px] text-gray-400">
            Menampilkan {{ ((currentPage-1)*itemsPerPage)+1 }} - {{ Math.min(currentPage*itemsPerPage, filteredStudents.length) }} dari {{ filteredStudents.length }} mahasiswa
          </p>
          <div class="flex gap-1">
            <button @click="prevPage" :disabled="currentPage === 1" class="w-7 h-7 flex items-center justify-center rounded-md text-gray-400 hover:bg-gray-100 hover:text-emerald-600 transition-all duration-200 disabled:opacity-40">
              <span class="material-icons text-sm">chevron_left</span>
            </button>
            <button @click="nextPage" :disabled="currentPage === totalPages" class="w-7 h-7 flex items-center justify-center rounded-md text-gray-400 hover:bg-gray-100 hover:text-emerald-600 transition-all duration-200 disabled:opacity-40">
              <span class="material-icons text-sm">chevron_right</span>
            </button>
          </div>
        </div>
      </div>

      <!-- Tombol Download -->
      <div class="flex justify-end mt-6">
        <div class="relative" ref="dropdownRef">
          <button @click="toggleDropdown" class="flex items-center gap-2 px-5 py-2.5 bg-emerald-600 text-white rounded-lg font-bold text-xs uppercase tracking-wider hover:bg-emerald-700 transition-all duration-300 shadow-md hover:shadow-lg active:scale-95 group">
            <span class="material-icons text-lg transition-transform duration-300 group-hover:rotate-12">download</span>
            Unduh Laporan
            <span class="material-icons text-sm transition-transform duration-300" :class="dropdownOpen ? 'rotate-180' : ''">expand_more</span>
          </button>

          <Transition name="dropdown">
            <div v-if="dropdownOpen" class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-100 z-50 overflow-hidden animate-fadeInDown">
              <div class="py-2">
                <button @click="exportReport('top5')" class="w-full px-4 py-2.5 text-left text-sm hover:bg-emerald-50 transition-all duration-200 flex items-center gap-3 group">
                  <span class="material-icons text-emerald-500 text-sm">looks_one</span>
                  <span>Top 5 Mahasiswa Terbaik</span>
                </button>
                <button @click="exportReport('top10')" class="w-full px-4 py-2.5 text-left text-sm hover:bg-emerald-50 transition-all duration-200 flex items-center gap-3 group">
                  <span class="material-icons text-emerald-500 text-sm">looks_two</span>
                  <span>Top 10 Mahasiswa Terbaik</span>
                </button>
                <button @click="exportReport('all')" class="w-full px-4 py-2.5 text-left text-sm hover:bg-emerald-50 transition-all duration-200 flex items-center gap-3 group">
                  <span class="material-icons text-emerald-500 text-sm">table_rows</span>
                  <span>Seluruh Data Mahasiswa</span>
                </button>
                <div class="border-t border-gray-100 my-1"></div>
                <button @click="exportCompleteReport" class="w-full px-4 py-2.5 text-left text-sm hover:bg-emerald-50 transition-all duration-200 flex items-center gap-3 group">
                  <span class="material-icons text-emerald-500 text-sm">description</span>
                  <span>Laporan Lengkap (PDF)</span>
                </button>
              </div>
            </div>
          </Transition>
        </div>
      </div>
    </div>

    <!-- Empty State saat filter lengkap tapi tidak ada data -->
    <div v-else-if="isFilterComplete && !hasMatchingData && riwayatPerhitungan.length > 0" class="bg-white rounded-xl border-2 border-dashed border-gray-200 p-12 text-center">
      <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
        <span class="material-icons text-3xl text-gray-400">search_off</span>
      </div>
      <h3 class="font-semibold text-gray-700">Tidak Ada Perhitungan</h3>
      <p class="text-sm text-gray-400">Belum ada perhitungan untuk filter yang dipilih.</p>
      <p class="text-xs text-gray-400 mt-2">Silakan buka menu <strong class="text-emerald-600">Proses Perhitungan</strong> dan lakukan perhitungan dengan filter yang sesuai.</p>
      <button @click="resetFilter" class="mt-4 text-emerald-600 text-sm font-medium">Reset Filter</button>
    </div>

  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, watch } from 'vue';
import * as XLSX from 'xlsx';
import html2pdf from 'html2pdf.js';

// Props
const props = defineProps({
  dataMahasiswa: { type: Array, default: () => [] },
  dataKriteria: { type: Array, default: () => [] },
  dataPenilaian: { type: Array, default: () => [] }
});

// State
const dropdownOpen = ref(false);
const currentPage = ref(1);
const itemsPerPage = ref(10);
const dropdownRef = ref(null);
const riwayatPerhitungan = ref([]);
const currentData = ref(null);
const searchQuery = ref('');
const nilaiCache = ref({});

// Filter state
const fakultasList = ref([]);
const prodiList = ref([]);
const angkatanList = ref([]);
const semesterList = ref([]);
const kelasList = ref([]);
const selectedFakultas = ref('');
const selectedProdi = ref('');
const selectedAngkatan = ref('');
const selectedSemester = ref('');
const selectedKelas = ref('');

// Data kelas default
const defaultKelas = [
  { id: 1, nama: "Reguler - C01", kode: "C01", kelompok: "Reguler" },
  { id: 2, nama: "Reguler - C02", kode: "C02", kelompok: "Reguler" },
  { id: 3, nama: "Reguler - B01", kode: "B01", kelompok: "Reguler" },
  { id: 4, nama: "Karyawan - K01", kode: "K01", kelompok: "Karyawan" }
];

// Cek apakah filter sudah lengkap (Fakultas, Prodi, Angkatan, Semester wajib, Kelas opsional)
const isFilterComplete = computed(() => {
  return selectedFakultas.value !== '' &&
         selectedProdi.value !== '' &&
         selectedAngkatan.value !== '' &&
         selectedSemester.value !== '';
});

const filteredProdi = computed(() => {
  if (!selectedFakultas.value) return prodiList.value;
  return prodiList.value.filter(p => p.fakultas_id === selectedFakultas.value);
});

const baseSortedStudents = computed(() => {
  if (!currentData.value || !currentData.value.hasil) return [];
  return [...currentData.value.hasil].sort((a, b) =>
    (b.preferensi || b.v || 0) - (a.preferensi || a.v || 0)
  );
});

const filteredStudents = computed(() => {
  if (!searchQuery.value.trim()) {
    return baseSortedStudents.value;
  }
  const query = searchQuery.value.toLowerCase().trim();
  return baseSortedStudents.value.filter(student =>
    student.nama?.toLowerCase().includes(query) ||
    student.nim?.toLowerCase().includes(query)
  );
});

const sortedStudents = computed(() => {
  return filteredStudents.value.map((item, idx) => ({
    ...item,
    rank: idx + 1
  }));
});

const top5Students = computed(() => sortedStudents.value.slice(0, 5));
const top10Students = computed(() => sortedStudents.value.slice(0, 10));

const highestV = computed(() => {
  if (sortedStudents.value.length === 0) return '-';
  const maxV = Math.max(...sortedStudents.value.map(s => s.preferensi || s.v || 0));
  return (maxV * 100).toFixed(2);
});

const averageV = computed(() => {
  if (sortedStudents.value.length === 0) return '-';
  const avg = sortedStudents.value.reduce((sum, s) => sum + (s.preferensi || s.v || 0), 0) / sortedStudents.value.length;
  return (avg * 100).toFixed(2);
});

const statsData = computed(() => [
  { label: 'Total Mahasiswa', value: sortedStudents.value.length, percentage: 100 },
  { label: 'Total Kriteria', value: props.dataKriteria?.length || 0, percentage: 100 },
  { label: 'Nilai V Tertinggi', value: highestV.value + '%', percentage: parseFloat(highestV.value) || 0 },
  { label: 'Rata-rata V', value: averageV.value + '%', percentage: parseFloat(averageV.value) || 0 }
]);

const totalPages = computed(() => Math.ceil(sortedStudents.value.length / itemsPerPage.value));

const paginatedData = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value;
  const end = start + itemsPerPage.value;
  return sortedStudents.value.slice(start, end);
});

// Fungsi GET NILAI MAHASISWA
const getNilaiMahasiswa = (siswaId, kriteriaId) => {
  const cacheKey = `${siswaId}_${kriteriaId}_${selectedSemester.value}`;

  if (nilaiCache.value[cacheKey] !== undefined) {
    return nilaiCache.value[cacheKey];
  }

  let nilai = '-';

  if (props.dataPenilaian && props.dataPenilaian.length > 0) {
    const penilaian = props.dataPenilaian.find(p => p.mahasiswa_id === siswaId);
    if (penilaian && penilaian.nilai && penilaian.nilai[kriteriaId] !== undefined) {
      nilai = penilaian.nilai[kriteriaId];
      nilaiCache.value[cacheKey] = nilai;
      return nilai;
    }
  }

  try {
    const savedPenilaian = localStorage.getItem('penilaian_per_semester');
    if (savedPenilaian) {
      const penilaianData = JSON.parse(savedPenilaian);
      const penilaianSiswa = penilaianData.find(p =>
        p.mahasiswa_id === siswaId && p.semester_id === selectedSemester.value
      );
      if (penilaianSiswa && penilaianSiswa.nilai && penilaianSiswa.nilai[kriteriaId] !== undefined) {
        nilai = penilaianSiswa.nilai[kriteriaId];
        nilaiCache.value[cacheKey] = nilai;
        return nilai;
      }
    }
  } catch (e) {
    console.error('Gagal baca penilaian_per_semester:', e);
  }

  try {
    const savedPenilaian = localStorage.getItem('penilaian');
    if (savedPenilaian) {
      const penilaianData = JSON.parse(savedPenilaian);
      const penilaianSiswa = penilaianData.find(p => p.mahasiswa_id === siswaId);
      if (penilaianSiswa && penilaianSiswa.nilai && penilaianSiswa.nilai[kriteriaId] !== undefined) {
        nilai = penilaianSiswa.nilai[kriteriaId];
        nilaiCache.value[cacheKey] = nilai;
        return nilai;
      }
    }
  } catch (e) {
    console.error('Gagal baca penilaian:', e);
  }

  const siswa = props.dataMahasiswa.find(s => s.id === siswaId);
  if (siswa && siswa.nilai && siswa.nilai[kriteriaId] !== undefined) {
    nilai = siswa.nilai[kriteriaId];
    nilaiCache.value[cacheKey] = nilai;
    return nilai;
  }

  if (currentData.value && currentData.value.matriksKeputusan) {
    const siswaIndex = currentData.value.hasil?.findIndex(h => h.id === siswaId);
    const kriteriaIndex = props.dataKriteria.findIndex(k => k.id === kriteriaId);
    if (siswaIndex !== -1 && kriteriaIndex !== -1 && currentData.value.matriksKeputusan[siswaIndex]) {
      const nilaiMatriks = currentData.value.matriksKeputusan[siswaIndex][kriteriaIndex];
      if (nilaiMatriks !== undefined && nilaiMatriks !== null) {
        nilai = nilaiMatriks;
        nilaiCache.value[cacheKey] = nilai;
        return nilai;
      }
    }
  }

  nilaiCache.value[cacheKey] = nilai;
  return nilai;
};

const prevPage = () => { if (currentPage.value > 1) currentPage.value--; };
const nextPage = () => { if (currentPage.value < totalPages.value) currentPage.value++; };

const formattedDate = computed(() => {
  const now = new Date();
  return now.toLocaleDateString('id-ID', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  }).toUpperCase();
});

const handleClickOutside = (event) => {
  if (dropdownRef.value && !dropdownRef.value.contains(event.target)) {
    dropdownOpen.value = false;
  }
};

const toggleDropdown = () => {
  dropdownOpen.value = !dropdownOpen.value;
};

// Load data kelas
const loadKelasData = () => {
  if (!localStorage.getItem('kelas')) {
    localStorage.setItem('kelas', JSON.stringify(defaultKelas));
  }
  kelasList.value = JSON.parse(localStorage.getItem('kelas') || '[]');
};

const loadMasterData = () => {
  fakultasList.value = JSON.parse(localStorage.getItem('fakultas') || '[]');
  prodiList.value = JSON.parse(localStorage.getItem('prodi') || '[]');
  angkatanList.value = JSON.parse(localStorage.getItem('angkatan') || '[]');
  semesterList.value = JSON.parse(localStorage.getItem('semester') || '[]');
  loadKelasData();
};

const loadRiwayatPerhitungan = () => {
  try {
    const savedData = localStorage.getItem('topsis_riwayat_perhitungan');
    if (savedData) {
      riwayatPerhitungan.value = JSON.parse(savedData);
      console.log('✅ Laporan: Memuat', riwayatPerhitungan.value.length, 'riwayat perhitungan');
    }
  } catch (e) {
    console.error('Gagal memuat riwayat perhitungan:', e);
  }
};

const findMatchingData = () => {
  nilaiCache.value = {};

  if (!isFilterComplete.value) {
    currentData.value = null;
    return null;
  }

  if (riwayatPerhitungan.value.length === 0) {
    currentData.value = null;
    return null;
  }

  const matchingData = riwayatPerhitungan.value.find(item => {
    let match = item.filter.fakultasId === selectedFakultas.value &&
                item.filter.prodiId === selectedProdi.value &&
                item.filter.angkatanId === selectedAngkatan.value &&
                item.filter.semesterId === selectedSemester.value;
    if (match) {
      if (selectedKelas.value) {
        match = item.filter.kelasId === selectedKelas.value;
      } else {
        match = !item.filter.kelasId || item.filter.kelasId === null || item.filter.kelasId === '';
      }
    }
    return match;
  });

  currentData.value = matchingData || null;
  currentPage.value = 1;
  searchQuery.value = '';
  return currentData.value;
};

const hasMatchingData = computed(() => {
  return currentData.value !== null;
});

const resetFilter = () => {
  selectedFakultas.value = '';
  selectedProdi.value = '';
  selectedAngkatan.value = '';
  selectedSemester.value = '';
  selectedKelas.value = '';
  searchQuery.value = '';
  currentData.value = null;
  nilaiCache.value = {};
};

const onFakultasChange = () => {
  selectedProdi.value = '';
  onFilterChange();
};

const onFilterChange = () => {
  findMatchingData();
};

const onSearchChange = () => {
  currentPage.value = 1;
};

const clearSearch = () => {
  searchQuery.value = '';
  currentPage.value = 1;
};

const exportReport = (type) => {
  dropdownOpen.value = false;
  if (!currentData.value) return;

  let title = '';
  let data = [];

  switch(type) {
    case 'top5':
      title = 'TOP 5 MAHASISWA TERBAIK';
      data = top5Students.value.map((s, i) => ({
        'Rank': i + 1,
        'NIM': s.nim || '-',
        'Nama Mahasiswa': s.nama,
        'Kelas': s.kelas || '-',
        'Nilai V (%)': ((s.preferensi || s.v || 0) * 100).toFixed(2) + '%'
      }));
      break;
    case 'top10':
      title = 'TOP 10 MAHASISWA TERBAIK';
      data = top10Students.value.map((s, i) => ({
        'Rank': i + 1,
        'NIM': s.nim || '-',
        'Nama Mahasiswa': s.nama,
        'Kelas': s.kelas || '-',
        'Nilai V (%)': ((s.preferensi || s.v || 0) * 100).toFixed(2) + '%'
      }));
      break;
    case 'all':
      title = 'SELURUH DATA MAHASISWA';
      data = sortedStudents.value.map((s, i) => {
        const row = {
          'Rank': i + 1,
          'NIM': s.nim || '-',
          'Nama Mahasiswa': s.nama,
          'Kelas': s.kelas || '-'
        };
        props.dataKriteria.forEach(k => {
          row[k.nama] = getNilaiMahasiswa(s.id, k.id);
        });
        row['Nilai V (%)'] = ((s.preferensi || s.v || 0) * 100).toFixed(2) + '%';
        return row;
      });
      break;
  }

  const ws = XLSX.utils.json_to_sheet(data);
  const wb = XLSX.utils.book_new();
  XLSX.utils.book_append_sheet(wb, ws, title);
  XLSX.writeFile(wb, `Laporan_TOPSIS_${title}_${new Date().toISOString().split('T')[0]}.xlsx`);
};

const exportCompleteReport = async () => {
  dropdownOpen.value = false;
  const element = document.getElementById('reportContainer');
  if (!element) return;

  const opt = {
    margin: [0.5, 0.5, 0.5, 0.5],
    filename: `Laporan_TOPSIS_Complete_${new Date().toISOString().split('T')[0]}.pdf`,
    image: { type: 'jpeg', quality: 0.98 },
    html2canvas: { scale: 2, logging: false },
    jsPDF: { unit: 'in', format: 'a4', orientation: 'landscape' }
  };
  await html2pdf().set(opt).from(element).save();
};

watch([selectedFakultas, selectedProdi, selectedAngkatan, selectedSemester, selectedKelas], () => {
  findMatchingData();
});

onMounted(() => {
  document.addEventListener('click', handleClickOutside);
  loadMasterData();
  loadRiwayatPerhitungan();
});

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
});
</script>

<style scoped>
@import url('https://fonts.googleapis.com/icon?family=Material+Icons');
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

::-webkit-scrollbar { width: 5px; }
::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
::-webkit-scrollbar-thumb:hover { background: #10b981; }

@keyframes fadeInUp {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

@keyframes fadeInLeft {
  from { opacity: 0; transform: translateX(-15px); }
  to { opacity: 1; transform: translateX(0); }
}

@keyframes fadeInDown {
  from { opacity: 0; transform: translateY(-10px); }
  to { opacity: 1; transform: translateY(0); }
}

@keyframes pulse-slow {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.7; }
}

@keyframes slideIn {
  from { width: 0%; }
  to { width: var(--width); }
}

.animate-fadeInUp {
  animation: fadeInUp 0.5s ease-out forwards;
}

.animate-fadeInLeft {
  animation: fadeInLeft 0.4s ease-out forwards;
}

.animate-fadeInDown {
  animation: fadeInDown 0.3s ease-out forwards;
}

.animate-pulse-slow {
  animation: pulse-slow 2s ease-in-out infinite;
}

.animate-slideIn {
  animation: slideIn 0.8s ease-out forwards;
}

.dropdown-enter-active,
.dropdown-leave-active {
  transition: all 0.2s ease;
}
.dropdown-enter-from,
.dropdown-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}

.alert-enter-active,
.alert-leave-active {
  transition: all 0.3s ease;
}
.alert-enter-from,
.alert-leave-to {
  opacity: 0;
  transform: translateY(-20px);
}

.rotate-180 {
  transform: rotate(180deg);
}
</style>
