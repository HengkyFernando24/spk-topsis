<!-- Perhitungan.vue - VERSI ELEGAN & PROFESIONAL -->
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

    <!-- Info Panel -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
      <div class="bg-blue-50 rounded-xl p-3 text-center">
        <p class="text-[10px] font-semibold text-blue-600 uppercase">Mahasiswa</p>
        <p class="text-xl font-bold text-blue-700">{{ dataMahasiswa?.length || 0 }}</p>
      </div>
      <div class="bg-emerald-50 rounded-xl p-3 text-center">
        <p class="text-[10px] font-semibold text-emerald-600 uppercase">Kriteria</p>
        <p class="text-xl font-bold text-emerald-700">{{ dataKriteria?.length || 0 }}</p>
      </div>
      <div class="bg-purple-50 rounded-xl p-3 text-center">
        <p class="text-[10px] font-semibold text-purple-600 uppercase">Data Penilaian</p>
        <p class="text-xl font-bold text-purple-700">{{ dataPenilaian?.length || 0 }}</p>
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
      <button @click="hitungTOPSIS" :disabled="!isDataValid"
              class="bg-emerald-600 hover:bg-emerald-700 disabled:bg-gray-300 text-white px-5 py-2 rounded-xl text-sm font-semibold flex items-center gap-2 transition shadow-sm">
        <span class="material-icons text-base">calculate</span>
        Mulai Perhitungan TOPSIS
      </button>
      <button @click="hitungTOPSISOtomatis"
              class="border border-blue-600 text-blue-600 hover:bg-blue-50 px-5 py-2 rounded-xl text-sm font-semibold flex items-center gap-2 transition">
        <span class="material-icons text-base">auto_awesome</span>
        Hitung Otomatis
      </button>
      <button v-if="hasilPerhitungan.length > 0" @click="exportToExcel"
              class="border border-emerald-600 text-emerald-600 hover:bg-emerald-50 px-5 py-2 rounded-xl text-sm font-semibold flex items-center gap-2 transition">
        <span class="material-icons text-base">download</span>
        Export Excel
      </button>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="flex items-center justify-center py-16">
      <div class="text-center">
        <div class="w-12 h-12 border-4 border-emerald-200 border-t-emerald-600 rounded-full animate-spin mx-auto mb-3"></div>
        <p class="text-gray-500 text-sm">Sedang menghitung TOPSIS...</p>
      </div>
    </div>

    <!-- HASIL PERHITUNGAN -->
    <div v-if="hasilPerhitungan.length > 0 && !loading" class="space-y-6">

      <!-- RANKING TOP 3 - DENGAN ICON PIALA -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Juara 2 -->
        <div v-if="hasilPerhitungan[1]"
             class="bg-white rounded-xl border border-gray-200 p-5 text-center hover:shadow-md transition-shadow">
          <div class="text-4xl mb-2">🥈</div>
          <div class="text-gray-500 text-sm font-semibold">Runner Up</div>
          <div class="mt-2">
            <h3 class="font-bold text-gray-800">{{ hasilPerhitungan[1].nama }}</h3>
          </div>
          <div class="mt-3">
            <span class="text-2xl font-bold text-gray-700">{{ (hasilPerhitungan[1].preferensi * 100).toFixed(2) }}%</span>
            <p class="text-[10px] text-gray-400 mt-0.5">Nilai Preferensi</p>
          </div>
        </div>

        <!-- Juara 1 -->
        <div v-if="hasilPerhitungan[0]"
             class="bg-gradient-to-r from-amber-50 to-yellow-100 rounded-xl border border-amber-200 p-5 text-center shadow-md">
          <div class="text-5xl mb-2">🏆🥇</div>
          <div class="text-amber-600 text-sm font-bold">MAHASISWA TERBAIK</div>
          <div class="mt-2">
            <h3 class="font-bold text-amber-800 text-lg">{{ hasilPerhitungan[0].nama }}</h3>
          </div>
          <div class="mt-3">
            <span class="text-3xl font-bold text-amber-700">{{ (hasilPerhitungan[0].preferensi * 100).toFixed(2) }}%</span>
            <p class="text-[10px] text-amber-500 mt-0.5">Nilai Preferensi Tertinggi</p>
          </div>
        </div>

        <!-- Juara 3 -->
        <div v-if="hasilPerhitungan[2]"
             class="bg-white rounded-xl border border-gray-200 p-5 text-center hover:shadow-md transition-shadow">
          <div class="text-4xl mb-2">🥉</div>
          <div class="text-gray-500 text-sm font-semibold">Third Place</div>
          <div class="mt-2">
            <h3 class="font-bold text-gray-800">{{ hasilPerhitungan[2].nama }}</h3>
          </div>
          <div class="mt-3">
            <span class="text-2xl font-bold text-gray-700">{{ (hasilPerhitungan[2].preferensi * 100).toFixed(2) }}%</span>
            <p class="text-[10px] text-gray-400 mt-0.5">Nilai Preferensi</p>
          </div>
        </div>
      </div>

      <!-- TABEL RANKING LENGKAP -->
      <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="bg-gray-50 px-5 py-3 border-b border-gray-200">
          <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <h3 class="font-semibold text-gray-800 text-sm flex items-center gap-2">
              <span class="material-icons text-emerald-600 text-sm">ranking</span>
              Ranking Seluruh Mahasiswa
            </h3>
            <span class="bg-emerald-50 text-emerald-700 text-[10px] font-medium px-2 py-0.5 rounded">Total: {{ hasilPerhitungan.length }} Mahasiswa</span>
          </div>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full text-sm">
            <thead>
              <tr class="bg-gray-50 border-b border-gray-200 text-[10px] font-semibold text-gray-500 uppercase">
                <th class="px-4 py-2 text-center w-12">Rank</th>
                <th class="px-4 py-2 text-left">Nama Mahasiswa</th>
                <th class="px-4 py-2 text-center w-24">Nilai V</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
              <tr v-for="(item, index) in hasilPerhitungan" :key="item.id"
                  :class="index === 0 ? 'bg-amber-50/20' : 'hover:bg-gray-50'">
                <td class="px-4 py-2 text-center font-semibold text-gray-500">{{ index + 1 }}</td>
                <td class="px-4 py-2 font-medium text-gray-800">{{ item.nama }}</td>
                <td class="px-4 py-2 text-center">
                  <span class="font-bold" :class="index === 0 ? 'text-amber-600' : 'text-gray-700'">
                    {{ (item.preferensi * 100).toFixed(2) }}%
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- DETAIL LANGKAH PERHITUNGAN TOPSIS -->
      <details class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <summary class="px-5 py-3 bg-gray-50 cursor-pointer font-semibold text-gray-700 text-sm hover:bg-gray-100 transition flex items-center gap-2">
          <span class="material-icons text-emerald-600">menu_book</span>
          Lihat Detail Perhitungan Metode TOPSIS (7 Langkah)
        </summary>
        <div class="p-5 space-y-6">

          <!-- Langkah 1: Matriks Keputusan -->
          <div>
            <h4 class="font-semibold text-gray-800 text-sm mb-2 flex items-center gap-2">
              <span class="w-5 h-5 bg-emerald-500 text-white rounded-full flex items-center justify-center text-[10px] font-bold">1</span>
              Matriks Keputusan (X)
            </h4>
            <p class="text-[10px] text-gray-500 mb-2">X<sub>ij</sub> = Nilai alternatif ke-i pada kriteria ke-j</p>
            <div class="overflow-x-auto">
              <table class="min-w-full border-collapse text-xs">
                <thead>
                  <tr class="bg-gray-100">
                    <th class="border p-2 text-left">Alternatif</th>
                    <th v-for="(k, idx) in dataKriteria" :key="idx" class="border p-2 text-center">{{ k.nama }}</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(mhs, i) in dataMahasiswa" :key="i">
                    <td class="border p-2 font-medium">{{ mhs.nama }}</td>
                    <td v-for="j in dataKriteria.length" :key="j" class="border p-2 text-center font-mono">
                      {{ matriksKeputusan[i]?.[j-1]?.toFixed(2) || '-' }}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Langkah 2: Normalisasi Matriks -->
          <div v-if="matriksNormalisasi.length > 0">
            <h4 class="font-semibold text-gray-800 text-sm mb-2 flex items-center gap-2">
              <span class="w-5 h-5 bg-emerald-500 text-white rounded-full flex items-center justify-center text-[10px] font-bold">2</span>
              Normalisasi Matriks (R)
            </h4>
            <p class="text-[10px] text-gray-500 mb-2">R<sub>ij</sub> = X<sub>ij</sub> / √(Σ X<sub>ij</sub>²)</p>
            <div class="overflow-x-auto">
              <table class="min-w-full border-collapse text-xs">
                <thead>
                  <tr class="bg-gray-100">
                    <th class="border p-2 text-left">Alternatif</th>
                    <th v-for="(k, idx) in dataKriteria" :key="idx" class="border p-2 text-center">{{ k.nama }}</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(mhs, i) in dataMahasiswa" :key="i">
                    <td class="border p-2 font-medium">{{ mhs.nama }}</td>
                    <td v-for="(val, j) in matriksNormalisasi[i] || []" :key="j" class="border p-2 text-center font-mono">
                      {{ val?.toFixed(6) || '-' }}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Langkah 3: Matriks Normalisasi Terbobot -->
          <div v-if="matriksTerbobot.length > 0">
            <h4 class="font-semibold text-gray-800 text-sm mb-2 flex items-center gap-2">
              <span class="w-5 h-5 bg-emerald-500 text-white rounded-full flex items-center justify-center text-[10px] font-bold">3</span>
              Matriks Normalisasi Terbobot (Y)
            </h4>
            <p class="text-[10px] text-gray-500 mb-2">Y<sub>ij</sub> = R<sub>ij</sub> × W<sub>j</sub></p>
            <div class="overflow-x-auto">
              <table class="min-w-full border-collapse text-xs">
                <thead>
                  <tr class="bg-gray-100">
                    <th class="border p-2 text-left">Alternatif</th>
                    <th v-for="(k, idx) in dataKriteria" :key="idx" class="border p-2 text-center">{{ k.nama }}</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(mhs, i) in dataMahasiswa" :key="i">
                    <td class="border p-2 font-medium">{{ mhs.nama }}</td>
                    <td v-for="(val, j) in matriksTerbobot[i] || []" :key="j" class="border p-2 text-center font-mono">
                      {{ val?.toFixed(6) || '-' }}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Langkah 4: Solusi Ideal -->
          <div>
            <h4 class="font-semibold text-gray-800 text-sm mb-2 flex items-center gap-2">
              <span class="w-5 h-5 bg-emerald-500 text-white rounded-full flex items-center justify-center text-[10px] font-bold">4</span>
              Solusi Ideal Positif (A+) & Negatif (A-)
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
              <div class="bg-green-50 p-3 rounded-lg border border-green-200">
                <p class="font-semibold text-green-800 text-xs">A+ (Ideal Positif)</p>
                <div class="mt-1 space-y-0.5">
                  <div v-for="(k, idx) in dataKriteria" :key="idx" class="flex justify-between text-[10px]">
                    <span>{{ k.nama }} ({{ k.tipe }})</span>
                    <span class="font-mono">{{ idealPositifValue[idx]?.toFixed(6) || '-' }}</span>
                  </div>
                </div>
              </div>
              <div class="bg-rose-50 p-3 rounded-lg border border-rose-200">
                <p class="font-semibold text-rose-800 text-xs">A- (Ideal Negatif)</p>
                <div class="mt-1 space-y-0.5">
                  <div v-for="(k, idx) in dataKriteria" :key="idx" class="flex justify-between text-[10px]">
                    <span>{{ k.nama }} ({{ k.tipe }})</span>
                    <span class="font-mono">{{ idealNegatifValue[idx]?.toFixed(6) || '-' }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Langkah 5: Jarak -->
          <div>
            <h4 class="font-semibold text-gray-800 text-sm mb-2 flex items-center gap-2">
              <span class="w-5 h-5 bg-emerald-500 text-white rounded-full flex items-center justify-center text-[10px] font-bold">5</span>
              Jarak Solusi Ideal (D+ dan D-)
            </h4>
            <div class="overflow-x-auto">
              <table class="min-w-full border-collapse text-xs">
                <thead>
                  <tr class="bg-gray-100">
                    <th class="border p-2">Alternatif</th>
                    <th class="border p-2">D+ (Jarak ke Ideal Positif)</th>
                    <th class="border p-2">D- (Jarak ke Ideal Negatif)</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(mhs, i) in dataMahasiswa" :key="i">
                    <td class="border p-2 font-medium">{{ mhs.nama }}</td>
                    <td class="border p-2 text-center font-mono">{{ jarakDPlus[i]?.toFixed(6) || '-' }}</td>
                    <td class="border p-2 text-center font-mono">{{ jarakDMinus[i]?.toFixed(6) || '-' }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Langkah 6: Preferensi -->
          <div>
            <h4 class="font-semibold text-gray-800 text-sm mb-2 flex items-center gap-2">
              <span class="w-5 h-5 bg-emerald-500 text-white rounded-full flex items-center justify-center text-[10px] font-bold">6</span>
              Nilai Preferensi (V<sub>i</sub>)
            </h4>
            <div class="overflow-x-auto">
              <table class="min-w-full border-collapse text-xs">
                <thead>
                  <tr class="bg-gray-100">
                    <th class="border p-2">Alternatif</th>
                    <th class="border p-2">Nilai Preferensi (V<sub>i</sub>)</th>
                    <th class="border p-2">Ranking</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, idx) in hasilPerhitungan" :key="idx">
                    <td class="border p-2 font-medium">{{ item.nama }}</td>
                    <td class="border p-2 text-center font-bold text-emerald-600">{{ (item.preferensi * 100).toFixed(2) }}%</td>
                    <td class="border p-2 text-center font-bold">{{ idx + 1 }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Langkah 7: Hasil Akhir -->
          <div class="bg-amber-50 p-4 rounded-lg border border-amber-200">
            <h4 class="font-semibold text-amber-800 text-sm mb-2 flex items-center gap-2">
              <span class="w-5 h-5 bg-amber-500 text-white rounded-full flex items-center justify-center text-[10px] font-bold">7</span>
              🏆 Hasil Akhir - Perankingan Mahasiswa Terbaik
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-2 mt-2">
              <div v-for="(item, idx) in hasilPerhitungan.slice(0, 3)" :key="idx"
                   class="text-center p-2 rounded-lg"
                   :class="idx === 0 ? 'bg-amber-100' : idx === 1 ? 'bg-gray-100' : 'bg-orange-100'">
                <span class="font-bold text-lg">{{ idx === 0 ? '🏆' : idx === 1 ? '🥈' : '🥉' }} #{{ idx + 1 }}</span>
                <p class="font-semibold text-sm">{{ item.nama }}</p>
                <p class="text-xs font-bold">{{ (item.preferensi * 100).toFixed(2) }}%</p>
              </div>
            </div>
          </div>

        </div>
      </details>
    </div>

    <!-- Empty State -->
    <div v-if="hasilPerhitungan.length === 0 && !loading && isDataValid" class="bg-white rounded-xl border-2 border-dashed border-gray-200 p-12 text-center">
      <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
        <span class="material-icons text-3xl text-gray-400">calculate</span>
      </div>
      <h3 class="font-semibold text-gray-700">Belum Ada Perhitungan</h3>
      <p class="text-sm text-gray-400">Klik tombol "Mulai Perhitungan TOPSIS" untuk memulai.</p>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

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

const showToast = (type, title, message) => {
  toast.value = { show: true, type, title, message };
  setTimeout(() => { toast.value.show = false; }, 4000);
};

// Computed
const kelengkapanData = computed(() => {
  if (!props.dataMahasiswa?.length || !props.dataKriteria?.length) return 0;
  const target = props.dataMahasiswa.length * props.dataKriteria.length;
  const current = props.dataPenilaian?.length || 0;
  return Math.min(100, Math.floor((current / target) * 100));
});

const totalBobotValue = computed(() => {
  return props.dataKriteria.reduce((sum, k) => sum + (parseFloat(k.bobot) || 0), 0);
});

const totalBobotValid = computed(() => Math.abs(totalBobotValue.value - 1) < 0.01);

const isDataValid = computed(() => {
  if (!props.dataMahasiswa || props.dataMahasiswa.length < 2) return false;
  if (!props.dataKriteria || props.dataKriteria.length < 2) return false;
  if (!totalBobotValid.value) return false;
  const target = props.dataMahasiswa.length * props.dataKriteria.length;
  if ((props.dataPenilaian?.length || 0) < target) return false;
  return true;
});

const validationMessage = computed(() => {
  if (!props.dataMahasiswa || props.dataMahasiswa.length < 2) return 'Minimal 2 mahasiswa untuk perbandingan';
  if (!props.dataKriteria || props.dataKriteria.length < 2) return 'Minimal 2 kriteria untuk perhitungan';
  if (!totalBobotValid.value) return `Total bobot harus 1.00 (saat ini ${totalBobotValue.value.toFixed(2)})`;
  const target = props.dataMahasiswa.length * props.dataKriteria.length;
  if ((props.dataPenilaian?.length || 0) < target) {
    return `Data penilaian kurang: butuh ${target}, tersedia ${props.dataPenilaian?.length || 0}`;
  }
  return 'Data siap dihitung';
});

const getNilai = (mahasiswaId, kriteriaId) => {
  const penilaian = props.dataPenilaian.find(p => p.mahasiswaId === mahasiswaId && p.kriteriaId === kriteriaId);
  return penilaian ? parseFloat(penilaian.nilai) : 0;
};

// Main TOPSIS Calculation
const hitungTOPSIS = async () => {
  if (!isDataValid.value) {
    showToast('error', 'Data Tidak Valid', validationMessage.value);
    return;
  }

  loading.value = true;
  await new Promise(resolve => setTimeout(resolve, 500));

  try {
    const m = props.dataMahasiswa.length;
    const n = props.dataKriteria.length;

    // Step 1: Matriks Keputusan
    const X = [];
    for (let i = 0; i < m; i++) {
      X[i] = [];
      for (let j = 0; j < n; j++) {
        X[i][j] = getNilai(props.dataMahasiswa[i].id, props.dataKriteria[j].id);
      }
    }
    matriksKeputusan.value = X;

    // Step 2: Normalisasi
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

    // Step 3: Matriks Terbobot
    const Y = [];
    for (let i = 0; i < m; i++) {
      Y[i] = [];
      for (let j = 0; j < n; j++) {
        const bobot = parseFloat(props.dataKriteria[j].bobot) || 0;
        Y[i][j] = R[i][j] * bobot;
      }
    }
    matriksTerbobot.value = Y;

    // Step 4: Ideal Positif & Negatif
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

    // Step 5: Jarak
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

    // Step 6: Preferensi
    const V = [];
    for (let i = 0; i < m; i++) {
      V[i] = Dmin[i] / (Dplus[i] + Dmin[i]);
    }

    // Step 7: Ranking
    const hasil = props.dataMahasiswa.map((mhs, index) => ({
      id: mhs.id,
      nim: mhs.nim || `M${index + 1}`,
      nama: mhs.nama,
      v: V[index],
      preferensi: V[index]
    }));
    hasil.sort((a, b) => b.preferensi - a.preferensi);
    hasilPerhitungan.value = hasil;

    emit('update-hasil', hasil);
    showToast('success', '✅ Perhitungan Selesai', `Mahasiswa Terbaik: ${hasil[0].nama} (${(hasil[0].preferensi * 100).toFixed(2)}%)`);

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

const exportToExcel = () => {
  showToast('info', 'Fitur Export', 'Fitur export Excel akan segera hadir!');
};

defineExpose({ hitungTOPSIS, hitungTOPSISOtomatis });
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
