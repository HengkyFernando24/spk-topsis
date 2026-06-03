<!-- Perhitungan.vue - FINAL VERSION (Auto Load Hasil Sesuai Filter) -->
<template>
  <div class="p-4 md:p-6 max-w-7xl mx-auto font-['Inter'] antialiased text-gray-900">

    <!-- Header -->
    <div class="mb-6">
      <div class="flex items-center gap-3 mb-2">
        <div class="w-1.5 h-8 bg-emerald-500 rounded-full"></div>
        <h2 class="text-2xl font-bold text-emerald-900">Perhitungan TOPSIS</h2>
      </div>
      <p class="text-gray-500 text-sm">
        Menentukan Mahasiswa Terbaik menggunakan metode <span class="font-semibold text-emerald-700">TOPSIS</span>
      </p>
    </div>

    <!-- FILTER DATA -->
    <div class="bg-white rounded-xl p-4 mb-6 shadow-sm border border-gray-100">
      <div class="flex items-center gap-2 mb-3">
        <span class="material-icons text-emerald-600 text-sm">filter_alt</span>
        <h4 class="font-semibold text-gray-700 text-sm">Filter Data Perhitungan</h4>
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <div>
          <label class="block text-[10px] font-bold text-gray-500 mb-1">Fakultas</label>
          <select v-model="selectedFakultas" @change="onFakultasChange" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-emerald-500 focus:outline-none">
            <option v-for="f in fakultasList" :key="f.id" :value="f.id">{{ f.nama }}</option>
          </select>
        </div>
        <div>
          <label class="block text-[10px] font-bold text-gray-500 mb-1">Program Studi</label>
          <select v-model="selectedProdi" @change="onFilterChange" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-emerald-500 focus:outline-none">
            <option v-for="p in filteredProdi" :key="p.id" :value="p.id">{{ p.nama }}</option>
          </select>
        </div>
        <div>
          <label class="block text-[10px] font-bold text-gray-500 mb-1">Angkatan</label>
          <select v-model="selectedAngkatan" @change="onFilterChange" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-emerald-500 focus:outline-none">
            <option v-for="a in angkatanList" :key="a.id" :value="a.id">{{ a.tahun }}</option>
          </select>
        </div>
        <div>
          <label class="block text-[10px] font-bold text-gray-500 mb-1">Semester</label>
          <select v-model="selectedSemester" @change="onFilterChange" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-emerald-500 focus:outline-none">
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
      <div class="mt-3 text-right">
        <span class="text-[10px] text-gray-400">Data yang akan dihitung: {{ filteredMahasiswa.length }} mahasiswa</span>
      </div>
    </div>

    <!-- Alert Validasi -->
    <div v-if="!isDataValid" class="mb-6 p-4 bg-amber-50 rounded-xl border-l-4 border-l-amber-500">
      <div class="flex items-start gap-3">
        <span class="material-icons text-amber-500">warning</span>
        <div>
          <p class="font-semibold text-amber-900 text-sm">Data Belum Lengkap!</p>
          <p class="text-xs text-amber-700">{{ validationMessage }}</p>
        </div>
      </div>
    </div>

    <!-- NOTIFIKASI HASIL TERSIMPAN -->
    <div v-if="hasilPerhitungan.length > 0 && !loading && isSavedResultMatchingFilter" class="mb-4 p-3 bg-blue-50 rounded-lg border border-blue-200 text-xs flex items-center justify-between">
      <div class="flex items-center gap-2">
        <span class="material-icons text-blue-500 text-sm">saved_search</span>
        <span class="text-blue-700">Memuat hasil perhitungan sebelumnya untuk filter ini</span>
      </div>
      <span class="text-[10px] text-blue-400">Terakhir dihitung: {{ lastCalculationTime }}</span>
    </div>

    <!-- NOTIFIKASI KETIKA FILTER BERUBAH TAPI BELUM ADA HASIL -->
    <div v-if="showFilterChangeWarning && !isSavedResultMatchingFilter" class="mb-4 p-3 bg-amber-50 rounded-lg border border-amber-200 text-xs flex items-center justify-between">
      <div class="flex items-center gap-2">
        <span class="material-icons text-amber-500 text-sm">warning</span>
        <span class="text-amber-700">Filter berubah. Belum ada hasil perhitungan untuk filter ini. Silakan hitung.</span>
      </div>
      <button @click="clearFilterWarning" class="text-amber-500 hover:text-amber-700">
        <span class="material-icons text-sm">close</span>
      </button>
    </div>

    <!-- Info Panel -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
      <div class="bg-blue-50 rounded-xl p-3 text-center">
        <p class="text-[10px] font-semibold text-blue-600 uppercase">Mahasiswa (Filter)</p>
        <p class="text-xl font-bold text-blue-700">{{ filteredMahasiswa.length }}</p>
      </div>
      <div class="bg-emerald-50 rounded-xl p-3 text-center">
        <p class="text-[10px] font-semibold text-emerald-600 uppercase">Kriteria</p>
        <p class="text-xl font-bold text-emerald-700">{{ dataKriteria?.length || 0 }}</p>
      </div>
      <div class="bg-purple-50 rounded-xl p-3 text-center">
        <p class="text-[10px] font-semibold text-purple-600 uppercase">Data Penilaian</p>
        <p class="text-xl font-bold text-purple-700">{{ totalDataPenilaian }}</p>
      </div>
      <div class="rounded-xl p-3 text-center" :class="isDataValid ? 'bg-green-50' : 'bg-red-50'">
        <p class="text-[10px] font-semibold uppercase" :class="isDataValid ? 'text-green-600' : 'text-red-600'">Status</p>
        <p class="text-xl font-bold" :class="isDataValid ? 'text-green-700' : 'text-red-700'">
          {{ isDataValid ? 'READY' : 'PENDING' }}
        </p>
      </div>
    </div>

    <!-- Tombol Kontrol -->
    <div class="flex flex-wrap gap-3 mb-6">
      <button @click="hitungTOPSIS" :disabled="!isDataValid || filteredMahasiswa.length === 0" class="bg-emerald-600 hover:bg-emerald-700 disabled:bg-gray-300 text-white px-5 py-2 rounded-xl text-sm font-semibold flex items-center gap-2 transition shadow-sm">
        <span class="material-icons text-base">calculate</span>
        Mulai Perhitungan TOPSIS
      </button>
      <button @click="hitungTOPSISOtomatis" class="border border-blue-600 text-blue-600 hover:bg-blue-50 px-5 py-2 rounded-xl text-sm font-semibold flex items-center gap-2 transition">
        <span class="material-icons text-base">auto_awesome</span>
        Hitung Otomatis
      </button>
      <button v-if="hasilPerhitungan.length > 0" @click="clearSavedResult" class="border border-red-600 text-red-600 hover:bg-red-50 px-5 py-2 rounded-xl text-sm font-semibold flex items-center gap-2 transition">
        <span class="material-icons text-base">delete</span>
        Hapus Hasil Tersimpan
      </button>
    </div>

    <!-- Loading -->
    <div v-if="loading" class="flex items-center justify-center py-16">
      <div class="text-center">
        <div class="w-12 h-12 border-4 border-emerald-200 border-t-emerald-600 rounded-full animate-spin mx-auto mb-3"></div>
        <p class="text-gray-500 text-sm">Sedang menghitung TOPSIS...</p>
      </div>
    </div>

    <!-- HASIL PERHITUNGAN -->
    <div v-if="hasilPerhitungan.length > 0 && !loading && !showFilterChangeWarning">

      <!-- TOP 3 (Podium) -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div v-if="hasilPerhitungan[1]" class="bg-white rounded-xl border border-gray-200 p-5 text-center">
          <div class="text-4xl mb-2">🥈</div>
          <div class="text-gray-500 text-sm font-semibold">Runner Up</div>
          <div class="mt-2">
            <h3 class="font-bold text-gray-800">{{ hasilPerhitungan[1].nama }}</h3>
            <p class="text-[10px] text-gray-500">{{ hasilPerhitungan[1].nim }}</p>
            <p v-if="selectedKelas" class="text-[9px] text-emerald-600">{{ hasilPerhitungan[1].kelas }}</p>
          </div>
          <div class="mt-3">
            <span class="text-2xl font-bold text-gray-700">{{ (hasilPerhitungan[1].preferensi * 100).toFixed(2) }}%</span>
            <p class="text-[10px] text-gray-400 mt-0.5">Nilai Preferensi</p>
          </div>
        </div>
        <div v-if="hasilPerhitungan[0]" class="bg-gradient-to-r from-amber-50 to-yellow-100 rounded-xl border border-amber-200 p-5 text-center">
          <div class="text-5xl mb-2">🏆🥇</div>
          <div class="text-amber-600 text-sm font-bold">MAHASISWA TERBAIK</div>
          <div class="mt-2">
            <h3 class="font-bold text-amber-800 text-lg">{{ hasilPerhitungan[0].nama }}</h3>
            <p class="text-[10px] text-gray-500">{{ hasilPerhitungan[0].nim }}</p>
            <p v-if="selectedKelas" class="text-[9px] text-emerald-600">{{ hasilPerhitungan[0].kelas }}</p>
          </div>
          <div class="mt-3">
            <span class="text-3xl font-bold text-amber-700">{{ (hasilPerhitungan[0].preferensi * 100).toFixed(2) }}%</span>
            <p class="text-[10px] text-amber-500 mt-0.5">Nilai Preferensi Tertinggi</p>
          </div>
        </div>
        <div v-if="hasilPerhitungan[2]" class="bg-white rounded-xl border border-gray-200 p-5 text-center">
          <div class="text-4xl mb-2">🥉</div>
          <div class="text-gray-500 text-sm font-semibold">Third Place</div>
          <div class="mt-2">
            <h3 class="font-bold text-gray-800">{{ hasilPerhitungan[2].nama }}</h3>
            <p class="text-[10px] text-gray-500">{{ hasilPerhitungan[2].nim }}</p>
            <p v-if="selectedKelas" class="text-[9px] text-emerald-600">{{ hasilPerhitungan[2].kelas }}</p>
          </div>
          <div class="mt-3">
            <span class="text-2xl font-bold text-gray-700">{{ (hasilPerhitungan[2].preferensi * 100).toFixed(2) }}%</span>
            <p class="text-[10px] text-gray-400 mt-0.5">Nilai Preferensi</p>
          </div>
        </div>
      </div>

    <!-- TABEL RANKING -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-6">
        <div class="bg-gray-50 px-5 py-3 border-b border-gray-200">
            <div class="flex items-center justify-between">
            <h3 class="font-semibold text-gray-800 text-sm flex items-center gap-2">
                <span class="material-icons text-emerald-600 text-sm">ranking</span>
                Ranking Seluruh Mahasiswa
                <span class="text-[10px] font-normal text-gray-400">
                ({{ selectedKelas ? `Kelas ${getKelasNama(selectedKelas)}` : 'Gabungan Semua Kelas' }} - Total: {{ hasilPerhitungan.length }} mahasiswa)
                </span>
            </h3>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 text-[10px] font-semibold text-gray-500 uppercase">
                <th class="px-4 py-2 text-center w-12">Rank</th>
                <th class="px-4 py-2 text-left">NIM</th>
                <th class="px-4 py-2 text-left">Nama Mahasiswa</th>
                <th v-if="!selectedKelas" class="px-4 py-2 text-left">Kelas</th>
                <th class="px-4 py-2 text-center w-24">Nilai V</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <tr v-for="(item, index) in hasilPerhitungan" :key="item.id" :class="index < 3 ? 'bg-amber-50/20' : 'hover:bg-gray-50'">
                <td class="px-4 py-2 text-center font-semibold text-gray-500">{{ index + 1 }}</td>
                <td class="px-4 py-2 font-medium text-gray-700">{{ item.nim || '-' }}</td>
                <td class="px-4 py-2 font-medium text-gray-800">{{ item.nama }}</td>
                <td v-if="!selectedKelas" class="px-4 py-2 font-medium text-gray-600">{{ item.kelas || '-' }}</td>
                <td class="px-4 py-2 text-center">
                    <span class="font-bold" :class="index < 3 ? 'text-amber-600' : 'text-gray-700'">
                    {{ (item.preferensi * 100).toFixed(2) }}%
                    </span>
                </td>
                </tr>
            </tbody>
            </table>
        </div>
    </div>

      <!-- TOMBOL EXPORT -->
      <div class="flex flex-wrap gap-3 justify-end mb-6">
        <button @click="exportToExcel('top3')" class="border border-emerald-600 text-emerald-600 hover:bg-emerald-50 px-4 py-2 rounded-xl text-xs font-medium flex items-center gap-1 transition">
          <span class="material-icons text-sm">download</span> Export Top 3
        </button>
        <button @click="exportToExcel('top5')" class="border border-emerald-600 text-emerald-600 hover:bg-emerald-50 px-4 py-2 rounded-xl text-xs font-medium flex items-center gap-1 transition">
          <span class="material-icons text-sm">download</span> Export Top 5
        </button>
        <button @click="exportToExcel('top10')" class="border border-emerald-600 text-emerald-600 hover:bg-emerald-50 px-4 py-2 rounded-xl text-xs font-medium flex items-center gap-1 transition">
          <span class="material-icons text-sm">download</span> Export Top 10
        </button>
        <button @click="exportToExcel('all')" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-xl text-xs font-medium flex items-center gap-1 transition shadow-sm">
          <span class="material-icons text-sm">download</span> Export Semua
        </button>
      </div>

      <!-- DETAIL PERHITUNGAN LENGKAP -->
      <details class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden" open>
        <summary class="px-5 py-3 bg-gray-50 cursor-pointer font-semibold text-gray-700 text-sm hover:bg-gray-100 transition flex items-center gap-2">
          <span class="material-icons text-emerald-600">menu_book</span>
          Lihat Detail Lengkap Perhitungan TOPSIS
        </summary>
        <div class="p-4 space-y-4 text-xs">

          <!-- Langkah 1 -->
          <div>
            <p class="font-bold text-gray-700 text-sm mb-2">1. Matriks Keputusan (X)</p>
            <div class="overflow-x-auto">
              <table class="min-w-full border-collapse text-xs">
                <thead>
                  <tr class="bg-gray-100">
                    <th class="border p-2">Alternatif</th>
                    <th v-for="(k, idx) in dataKriteria" :key="idx" class="border p-2">{{ k.nama }}</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(mhs, i) in filteredMahasiswa" :key="i">
                    <td class="border p-2 font-medium">{{ mhs.nama }}</td>
                    <td v-for="j in dataKriteria.length" :key="j" class="border p-2 text-center">{{ matriksKeputusan[i]?.[j-1]?.toFixed(2) || '-' }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Langkah 2 -->
          <div>
            <p class="font-bold text-gray-700 text-sm mb-2">2. Matriks Normalisasi (R)</p>
            <div class="overflow-x-auto">
              <table class="min-w-full border-collapse text-xs">
                <thead>
                  <tr class="bg-gray-100">
                    <th class="border p-2">Alternatif</th>
                    <th v-for="(k, idx) in dataKriteria" :key="idx" class="border p-2">{{ k.nama }}</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(mhs, i) in filteredMahasiswa" :key="i">
                    <td class="border p-2 font-medium">{{ mhs.nama }}</td>
                    <td v-for="j in dataKriteria.length" :key="j" class="border p-2 text-center">{{ matriksNormalisasi[i]?.[j-1]?.toFixed(6) || '-' }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Langkah 3 -->
          <div>
            <p class="font-bold text-gray-700 text-sm mb-2">3. Matriks Normalisasi Terbobot (Y)</p>
            <div class="overflow-x-auto">
              <table class="min-w-full border-collapse text-xs">
                <thead>
                  <tr class="bg-gray-100">
                    <th class="border p-2">Alternatif</th>
                    <th v-for="(k, idx) in dataKriteria" :key="idx" class="border p-2">{{ k.nama }}</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(mhs, i) in filteredMahasiswa" :key="i">
                    <td class="border p-2 font-medium">{{ mhs.nama }}</td>
                    <td v-for="j in dataKriteria.length" :key="j" class="border p-2 text-center">{{ matriksTerbobot[i]?.[j-1]?.toFixed(6) || '-' }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Langkah 4 -->
          <div>
            <p class="font-bold text-gray-700 text-sm mb-2">4. Solusi Ideal Positif (A+) dan Negatif (A-)</p>
            <div class="grid grid-cols-2 gap-4">
              <div class="bg-green-50 p-3 rounded">
                <p class="font-semibold text-green-800 mb-2">A+ (Ideal Positif)</p>
                <div v-for="(k, idx) in dataKriteria" :key="idx" class="flex justify-between py-1 border-b border-green-100">
                  <span>{{ k.nama }}</span>
                  <span class="font-mono">{{ idealPositifValue[idx]?.toFixed(6) || '-' }}</span>
                </div>
              </div>
              <div class="bg-red-50 p-3 rounded">
                <p class="font-semibold text-red-800 mb-2">A- (Ideal Negatif)</p>
                <div v-for="(k, idx) in dataKriteria" :key="idx" class="flex justify-between py-1 border-b border-red-100">
                  <span>{{ k.nama }}</span>
                  <span class="font-mono">{{ idealNegatifValue[idx]?.toFixed(6) || '-' }}</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Langkah 5 -->
          <div>
            <p class="font-bold text-gray-700 text-sm mb-2">5. Jarak ke Solusi Ideal (D+ dan D-)</p>
            <div class="overflow-x-auto">
              <table class="min-w-full border-collapse text-xs">
                <thead>
                  <tr class="bg-gray-100">
                    <th class="border p-2">Alternatif</th>
                    <th class="border p-2">D+ (Jarak ke A+)</th>
                    <th class="border p-2">D- (Jarak ke A-)</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(mhs, i) in filteredMahasiswa" :key="i">
                    <td class="border p-2 font-medium">{{ mhs.nama }}</td>
                    <td class="border p-2 text-center">{{ jarakDPlus[i]?.toFixed(6) || '-' }}</td>
                    <td class="border p-2 text-center">{{ jarakDMinus[i]?.toFixed(6) || '-' }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Langkah 6 -->
          <div>
            <p class="font-bold text-gray-700 text-sm mb-2">6. Nilai Preferensi (V)</p>
            <div class="overflow-x-auto">
              <table class="min-w-full border-collapse text-xs">
                <thead>
                  <tr class="bg-gray-100">
                    <th class="border p-2">Alternatif</th>
                    <th class="border p-2">Nilai Preferensi (V)</th>
                    <th class="border p-2">Ranking</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, idx) in hasilPerhitungan" :key="idx">
                    <td class="border p-2 font-medium">{{ item.nama }}</td>
                    <td class="border p-2 text-center font-bold">{{ (item.preferensi * 100).toFixed(4) }}%</td>
                    <td class="border p-2 text-center">{{ idx + 1 }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

        </div>
      </details>

    </div>

    <!-- Empty State -->
    <div v-if="(hasilPerhitungan.length === 0 || (showFilterChangeWarning && !isSavedResultMatchingFilter)) && !loading && isDataValid" class="bg-white rounded-xl border-2 border-dashed border-gray-200 p-12 text-center">
      <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
        <span class="material-icons text-3xl text-gray-400">calculate</span>
      </div>
      <h3 class="font-semibold text-gray-700">{{ showFilterChangeWarning && !isSavedResultMatchingFilter ? 'Filter Berubah' : 'Belum Ada Perhitungan' }}</h3>
      <p class="text-sm text-gray-400">{{ showFilterChangeWarning && !isSavedResultMatchingFilter ? 'Belum ada hasil perhitungan untuk filter ini. Silakan klik tombol "Mulai Perhitungan TOPSIS".' : 'Klik tombol "Mulai Perhitungan TOPSIS" untuk memulai.' }}</p>
    </div>

    <!-- TOAST -->
    <div v-if="toast.show" class="fixed bottom-6 right-6 z-50">
      <div :class="['flex items-center gap-2 px-4 py-3 rounded-lg shadow-lg border-l-4 min-w-[260px]', toast.type === 'success' ? 'bg-white border-emerald-500' : toast.type === 'error' ? 'bg-white border-red-500' : 'bg-white border-blue-500']">
        <div :class="['w-8 h-8 rounded-full flex items-center justify-center', toast.type === 'success' ? 'bg-emerald-100 text-emerald-600' : toast.type === 'error' ? 'bg-red-100 text-red-600' : 'bg-blue-100 text-blue-600']">
          <span class="material-icons text-sm">{{ toast.type === 'success' ? 'check' : toast.type === 'error' ? 'close' : 'info' }}</span>
        </div>
        <div class="flex-1">
          <p class="font-semibold text-gray-800 text-sm">{{ toast.title }}</p>
          <p class="text-xs text-gray-500">{{ toast.message }}</p>
        </div>
        <button @click="toast.show = false" class="text-gray-400"><span class="material-icons text-sm">close</span></button>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import * as XLSX from 'xlsx';

const emit = defineEmits(['update-hasil']);

const props = defineProps({
  dataMahasiswa: { type: Array, default: () => [] },
  dataKriteria: { type: Array, default: () => [] },
  dataPenilaian: { type: Array, default: () => [] }
});

// State
const loading = ref(false);
const hasilPerhitungan = ref([]);
const matriksKeputusan = ref([]);
const matriksNormalisasi = ref([]);
const matriksTerbobot = ref([]);
const idealPositifValue = ref([]);
const idealNegatifValue = ref([]);
const jarakDPlus = ref([]);
const jarakDMinus = ref([]);
const toast = ref({ show: false, type: 'success', title: '', message: '' });
const lastCalculationTime = ref('');
const showFilterChangeWarning = ref(false);
const savedFilterKey = ref('');

// Data master
const fakultasList = ref([]);
const prodiList = ref([]);
const angkatanList = ref([]);
const semesterList = ref([]);
const kelasList = ref([]);
const penilaianPerSemester = ref([]);

// Data kelas default
const defaultKelas = [
  { id: 1, nama: "Reguler - C01", kode: "C01", kelompok: "Reguler" },
  { id: 2, nama: "Reguler - C02", kode: "C02", kelompok: "Reguler" },
  { id: 3, nama: "Reguler - B01", kode: "B01", kelompok: "Reguler" },
  { id: 4, nama: "Karyawan - K01", kode: "K01", kelompok: "Karyawan" }
];

// Filter
const selectedFakultas = ref(3);
const selectedProdi = ref(8);
const selectedAngkatan = ref(4);
const selectedSemester = ref(4);
const selectedKelas = ref('');

// Total Data Penilaian untuk Info Panel
const totalDataPenilaian = computed(() => {
  let count = 0;
  props.dataMahasiswa.forEach(mahasiswa => {
    const nilaiSiswa = penilaianPerSemester.value.find(
      p => p.mahasiswa_id === mahasiswa.id && p.semester_id === selectedSemester.value
    );
    if (nilaiSiswa && nilaiSiswa.nilai) {
      count += props.dataKriteria.length;
    }
  });
  return count;
});

const getCurrentFilterKey = () => {
  return `${selectedFakultas.value}_${selectedProdi.value}_${selectedAngkatan.value}_${selectedSemester.value}_${selectedKelas.value}`;
};

const isSavedResultMatchingFilter = computed(() => {
  return hasilPerhitungan.value.length > 0 && savedFilterKey.value === getCurrentFilterKey();
});

const filteredProdi = computed(() => {
  return prodiList.value.filter(p => p.fakultas_id === selectedFakultas.value);
});

const getKelasNama = (kelasId) => {
  if (!kelasId) return '-';
  const kelas = kelasList.value.find(k => k.id === kelasId);
  return kelas ? kelas.nama : '-';
};

const saveCalculationToLocalStorage = () => {
  try {
    const currentFilterKey = getCurrentFilterKey();
    const dataToSave = {
      hasilPerhitungan: hasilPerhitungan.value,
      matriksKeputusan: matriksKeputusan.value,
      matriksNormalisasi: matriksNormalisasi.value,
      matriksTerbobot: matriksTerbobot.value,
      idealPositifValue: idealPositifValue.value,
      idealNegatifValue: idealNegatifValue.value,
      jarakDPlus: jarakDPlus.value,
      jarakDMinus: jarakDMinus.value,
      lastCalculationTime: lastCalculationTime.value,
      filterKey: currentFilterKey
    };
    localStorage.setItem('topsis_hasil_perhitungan', JSON.stringify(dataToSave));
    savedFilterKey.value = currentFilterKey;
    showFilterChangeWarning.value = false;
  } catch (e) {
    console.error('Gagal menyimpan:', e);
  }
};

const loadCalculationFromLocalStorage = () => {
  try {
    const savedData = localStorage.getItem('topsis_hasil_perhitungan');
    if (savedData) {
      const data = JSON.parse(savedData);
      const currentFilterKey = getCurrentFilterKey();

      if (data.filterKey === currentFilterKey) {
        hasilPerhitungan.value = data.hasilPerhitungan || [];
        matriksKeputusan.value = data.matriksKeputusan || [];
        matriksNormalisasi.value = data.matriksNormalisasi || [];
        matriksTerbobot.value = data.matriksTerbobot || [];
        idealPositifValue.value = data.idealPositifValue || [];
        idealNegatifValue.value = data.idealNegatifValue || [];
        jarakDPlus.value = data.jarakDPlus || [];
        jarakDMinus.value = data.jarakDMinus || [];
        lastCalculationTime.value = data.lastCalculationTime || '';
        savedFilterKey.value = data.filterKey;
        showFilterChangeWarning.value = false;

        if (hasilPerhitungan.value.length > 0) {
          console.log('✅ Memuat hasil perhitungan tersimpan untuk filter ini');
        }
      } else {
        showFilterChangeWarning.value = true;
        console.log('⚠️ Filter berbeda, tidak ada hasil tersimpan');
      }
    }
  } catch (e) {
    console.error('Gagal memuat:', e);
  }
};

const clearSavedResult = () => {
  try {
    localStorage.removeItem('topsis_hasil_perhitungan');
    hasilPerhitungan.value = [];
    matriksKeputusan.value = [];
    matriksNormalisasi.value = [];
    matriksTerbobot.value = [];
    idealPositifValue.value = [];
    idealNegatifValue.value = [];
    jarakDPlus.value = [];
    jarakDMinus.value = [];
    lastCalculationTime.value = '';
    savedFilterKey.value = '';
    showFilterChangeWarning.value = true;
    showToast('success', 'Berhasil Dihapus', 'Hasil perhitungan tersimpan telah dihapus');
  } catch (e) {
    console.error('Gagal menghapus:', e);
  }
};

const onFilterChange = async () => {
  const currentFilterKey = getCurrentFilterKey();
  console.log('Filter berubah ke:', currentFilterKey);
  await loadCalculationFromLocalStorage();
};

const clearFilterWarning = () => {
  showFilterChangeWarning.value = false;
};

const onFakultasChange = () => {
  if (filteredProdi.value.length > 0) {
    selectedProdi.value = filteredProdi.value[0].id;
  }
  onFilterChange();
};

const filteredMahasiswa = computed(() => {
  let result = props.dataMahasiswa.filter(s =>
    s.fakultas_id === selectedFakultas.value &&
    s.prodi_id === selectedProdi.value &&
    s.angkatan_id === selectedAngkatan.value &&
    (selectedKelas.value ? s.kelas_id === selectedKelas.value : true)
  );
  result = result.filter(siswa => {
    return penilaianPerSemester.value.some(
      p => p.mahasiswa_id === siswa.id && p.semester_id === selectedSemester.value
    );
  });
  return result;
});

const isDataValid = computed(() => {
  if (filteredMahasiswa.value.length < 2) return false;
  if (!props.dataKriteria || props.dataKriteria.length < 2) return false;
  const totalBobot = props.dataKriteria.reduce((sum, k) => sum + (parseFloat(k.bobot) || 0), 0);
  if (Math.abs(totalBobot - 1) > 0.01) return false;
  return true;
});

const validationMessage = computed(() => {
  if (filteredMahasiswa.value.length < 2) return 'Minimal 2 mahasiswa untuk perbandingan (sesuai filter)';
  if (!props.dataKriteria || props.dataKriteria.length < 2) return 'Minimal 2 kriteria untuk perhitungan';
  const totalBobot = props.dataKriteria.reduce((sum, k) => sum + (parseFloat(k.bobot) || 0), 0);
  if (Math.abs(totalBobot - 1) > 0.01) return `Total bobot harus 1.00 (saat ini ${totalBobot.toFixed(2)})`;
  return 'Data siap dihitung';
});

const showToast = (type, title, message) => {
  toast.value = { show: true, type, title, message };
  setTimeout(() => { toast.value.show = false; }, 4000);
};

const getNilai = (mahasiswaId, kriteriaId) => {
  const penilaian = penilaianPerSemester.value.find(p =>
    p.mahasiswa_id === mahasiswaId && p.semester_id === selectedSemester.value
  );
  return penilaian?.nilai?.[kriteriaId] || 0;
};

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

  const savedPenilaian = localStorage.getItem('penilaian');
  if (savedPenilaian) {
    penilaianPerSemester.value = JSON.parse(savedPenilaian);
    console.log('✅ Perhitungan: Loaded penilaian:', penilaianPerSemester.value.length);
  } else {
    console.log('⚠️ Perhitungan: Tidak ada data penilaian di localStorage');
  }

  if (fakultasList.value.length === 0) {
    fakultasList.value = [
      { id: 1, nama: "Fakultas Agama Islam" },
      { id: 2, nama: "Fakultas Ilmu Pendidikan" },
      { id: 3, nama: "Fakultas Sains dan Teknologi" }
    ];
  }
  if (prodiList.value.length === 0) {
    prodiList.value = [{ id: 8, nama: "Informatika", fakultas_id: 3 }];
  }
  if (angkatanList.value.length === 0) {
    for (let tahun = 2020; tahun <= 2027; tahun++) {
      angkatanList.value.push({ id: tahun - 2019, tahun: tahun });
    }
  }
  if (semesterList.value.length === 0) {
    for (let i = 1; i <= 8; i++) {
      semesterList.value.push({ id: i, nama: `Semester ${i}` });
    }
  }
};

const exportToExcel = (type) => {
  if (!hasilPerhitungan.value.length) {
    showToast('error', 'Tidak Ada Data', 'Tidak ada data untuk diexport');
    return;
  }

  let title = '';
  let data = [];
  let fileName = '';

  switch(type) {
    case 'top3':
      title = 'TOP 3 MAHASISWA TERBAIK';
      fileName = `Perhitungan_TOPSIS_Top3_${new Date().toISOString().split('T')[0]}`;
      data = hasilPerhitungan.value.slice(0, 3).map((item, idx) => {
        const row = {
          'Rank': idx + 1,
          'NIM': item.nim || '-',
          'Nama Mahasiswa': item.nama,
          'Kelas': item.kelas || '-',
          'Nilai V (%)': (item.preferensi * 100).toFixed(2) + '%'
        };
        props.dataKriteria.forEach(k => {
          row[k.nama] = getNilai(item.id, k.id);
        });
        return row;
      });
      break;
    case 'top5':
      title = 'TOP 5 MAHASISWA TERBAIK';
      fileName = `Perhitungan_TOPSIS_Top5_${new Date().toISOString().split('T')[0]}`;
      data = hasilPerhitungan.value.slice(0, 5).map((item, idx) => {
        const row = {
          'Rank': idx + 1,
          'NIM': item.nim || '-',
          'Nama Mahasiswa': item.nama,
          'Kelas': item.kelas || '-',
          'Nilai V (%)': (item.preferensi * 100).toFixed(2) + '%'
        };
        props.dataKriteria.forEach(k => {
          row[k.nama] = getNilai(item.id, k.id);
        });
        return row;
      });
      break;
    case 'top10':
      title = 'TOP 10 MAHASISWA TERBAIK';
      fileName = `Perhitungan_TOPSIS_Top10_${new Date().toISOString().split('T')[0]}`;
      data = hasilPerhitungan.value.slice(0, 10).map((item, idx) => {
        const row = {
          'Rank': idx + 1,
          'NIM': item.nim || '-',
          'Nama Mahasiswa': item.nama,
          'Kelas': item.kelas || '-',
          'Nilai V (%)': (item.preferensi * 100).toFixed(2) + '%'
        };
        props.dataKriteria.forEach(k => {
          row[k.nama] = getNilai(item.id, k.id);
        });
        return row;
      });
      break;
    case 'all':
      title = 'SELURUH DATA MAHASISWA';
      fileName = `Perhitungan_TOPSIS_Semua_${new Date().toISOString().split('T')[0]}`;
      data = hasilPerhitungan.value.map((item, idx) => {
        const row = {
          'Rank': idx + 1,
          'NIM': item.nim || '-',
          'Nama Mahasiswa': item.nama,
          'Kelas': item.kelas || '-',
          'Nilai V (%)': (item.preferensi * 100).toFixed(2) + '%'
        };
        props.dataKriteria.forEach(k => {
          row[k.nama] = getNilai(item.id, k.id);
        });
        return row;
      });
      break;
    default:
      return;
  }

  const ws = XLSX.utils.json_to_sheet(data);
  const wb = XLSX.utils.book_new();
  XLSX.utils.book_append_sheet(wb, ws, title);
  XLSX.writeFile(wb, `${fileName}.xlsx`);

  showToast('success', 'Export Berhasil', `File ${title} telah diunduh`);
};

const hitungTOPSIS = async () => {
  if (!isDataValid.value) {
    showToast('error', 'Data Tidak Valid', validationMessage.value);
    return;
  }

  loading.value = true;
  await new Promise(resolve => setTimeout(resolve, 500));

  try {
    const m = filteredMahasiswa.value.length;
    const n = props.dataKriteria.length;

    const X = [];
    for (let i = 0; i < m; i++) {
      X[i] = [];
      for (let j = 0; j < n; j++) {
        X[i][j] = getNilai(filteredMahasiswa.value[i].id, props.dataKriteria[j].id);
      }
    }
    matriksKeputusan.value = X;

    const R = [];
    for (let j = 0; j < n; j++) {
      let sumSquare = 0;
      for (let i = 0; i < m; i++) sumSquare += Math.pow(X[i][j], 2);
      const denominator = Math.sqrt(sumSquare);
      for (let i = 0; i < m; i++) {
        if (!R[i]) R[i] = [];
        R[i][j] = denominator === 0 ? 0 : X[i][j] / denominator;
      }
    }
    matriksNormalisasi.value = R;

    const Y = [];
    for (let i = 0; i < m; i++) {
      Y[i] = [];
      for (let j = 0; j < n; j++) {
        const bobot = parseFloat(props.dataKriteria[j].bobot) || 0;
        Y[i][j] = R[i][j] * bobot;
      }
    }
    matriksTerbobot.value = Y;

    const Aplus = [], Amin = [];
    for (let j = 0; j < n; j++) {
      const tipe = props.dataKriteria[j].tipe;
      const values = Y.map(row => row[j]);
      if (tipe === 'benefit') {
        Aplus[j] = Math.max(...values);
        Amin[j] = Math.min(...values);
      } else {
        Aplus[j] = Math.min(...values);
        Amin[j] = Math.max(...values);
      }
    }
    idealPositifValue.value = Aplus;
    idealNegatifValue.value = Amin;

    const Dplus = [], Dmin = [];
    for (let i = 0; i < m; i++) {
      let sumPlus = 0, sumMinus = 0;
      for (let j = 0; j < n; j++) {
        sumPlus += Math.pow(Y[i][j] - Aplus[j], 2);
        sumMinus += Math.pow(Y[i][j] - Amin[j], 2);
      }
      Dplus[i] = Math.sqrt(sumPlus);
      Dmin[i] = Math.sqrt(sumMinus);
    }
    jarakDPlus.value = Dplus;
    jarakDMinus.value = Dmin;

    const V = [];
    for (let i = 0; i < m; i++) {
      V[i] = Dmin[i] / (Dplus[i] + Dmin[i]);
    }

    const hasil = filteredMahasiswa.value.map((mhs, index) => ({
      id: mhs.id,
      nim: mhs.nim || `M${index + 1}`,
      nama: mhs.nama,
      kelas: getKelasNama(mhs.kelas_id),
      kelas_id: mhs.kelas_id,
      preferensi: V[index]
    }));
    hasil.sort((a, b) => b.preferensi - a.preferensi);
    hasilPerhitungan.value = hasil;

    // SIMPAN KE RIWAYAT PERHITUNGAN
    const now = new Date();
    const waktuPerhitungan = `${now.toLocaleDateString('id-ID')} ${now.toLocaleTimeString('id-ID')}`;

    const filterInfo = {
      fakultasId: selectedFakultas.value,
      prodiId: selectedProdi.value,
      angkatanId: selectedAngkatan.value,
      semesterId: selectedSemester.value,
      kelasId: selectedKelas.value || null,
      fakultasNama: fakultasList.value.find(f => f.id === selectedFakultas.value)?.nama || '-',
      prodiNama: prodiList.value.find(p => p.id === selectedProdi.value)?.nama || '-',
      angkatanTahun: angkatanList.value.find(a => a.id === selectedAngkatan.value)?.tahun || '-',
      semesterNama: semesterList.value.find(s => s.id === selectedSemester.value)?.nama || '-',
      kelasNama: selectedKelas.value ? kelasList.value.find(k => k.id === selectedKelas.value)?.nama || '-' : 'Semua Kelas'
    };

    const newPerhitungan = {
      id: Date.now(),
      waktu: waktuPerhitungan,
      filter: filterInfo,
      hasil: hasil,
      matriksKeputusan: matriksKeputusan.value,
      matriksNormalisasi: matriksNormalisasi.value,
      matriksTerbobot: matriksTerbobot.value,
      idealPositif: idealPositifValue.value,
      idealNegatif: idealNegatifValue.value,
      jarakPositif: jarakDPlus.value,
      jarakNegatif: jarakDMinus.value
    };

    let riwayatPerhitungan = [];
    const savedRiwayat = localStorage.getItem('topsis_riwayat_perhitungan');
    if (savedRiwayat) {
      riwayatPerhitungan = JSON.parse(savedRiwayat);
    }

    const existingIndex = riwayatPerhitungan.findIndex(r =>
      r.filter.fakultasId === filterInfo.fakultasId &&
      r.filter.prodiId === filterInfo.prodiId &&
      r.filter.angkatanId === filterInfo.angkatanId &&
      r.filter.semesterId === filterInfo.semesterId &&
      r.filter.kelasId === filterInfo.kelasId
    );

    if (existingIndex !== -1) {
      riwayatPerhitungan[existingIndex] = newPerhitungan;
      showToast('info', 'Update', `Data perhitungan untuk ${filterInfo.prodiNama} - ${filterInfo.semesterNama} telah diperbarui`);
    } else {
      riwayatPerhitungan.push(newPerhitungan);
      showToast('success', 'Perhitungan Tersimpan', `Hasil untuk ${filterInfo.prodiNama} - ${filterInfo.semesterNama} telah disimpan`);
    }

    localStorage.setItem('topsis_riwayat_perhitungan', JSON.stringify(riwayatPerhitungan));
    localStorage.setItem('topsis_hasil_laporan', JSON.stringify(newPerhitungan));

    saveCalculationToLocalStorage();

    emit('update-hasil', hasil);
    showToast('success', 'Perhitungan Selesai', `Mahasiswa Terbaik: ${hasil[0].nama} (${(hasil[0].preferensi * 100).toFixed(2)}%)`);

  } catch (error) {
    console.error(error);
    showToast('error', 'Terjadi Kesalahan', error.message);
  } finally {
    loading.value = false;
  }
};

const hitungTOPSISOtomatis = () => {
  if (isDataValid.value) hitungTOPSIS();
  else showToast('error', 'Data Belum Lengkap', validationMessage.value);
};

watch([selectedFakultas, selectedProdi, selectedAngkatan, selectedSemester, selectedKelas], () => {
  onFilterChange();
}, { deep: true });

watch(() => props.dataMahasiswa, () => {
  loadCalculationFromLocalStorage();
}, { deep: true });

onMounted(() => {
  loadMasterData();
  loadCalculationFromLocalStorage();
});

defineExpose({ hitungTOPSIS, hitungTOPSISOtomatis, clearSavedResult });
</script>

<style scoped>
@keyframes spin {
  to { transform: rotate(360deg); }
}
.animate-spin {
  animation: spin 1s linear infinite;
}
details summary {
  list-style: none;
  cursor: pointer;
}
details summary::-webkit-details-marker {
  display: none;
}
</style>
