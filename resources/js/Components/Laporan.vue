<template>
  <div class="p-4 md:p-6 max-w-7xl mx-auto font-['Inter'] antialiased text-gray-900 bg-transparent">

    <!-- Header tanpa tombol -->
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

    <!-- Tombol Download di baris terpisah -->
    <div class="flex justify-end mb-6">
      <div class="relative" ref="dropdownRef">
        <button @click="toggleDropdown" class="flex items-center gap-2 px-5 py-2.5 bg-emerald-600 text-white rounded-lg font-bold text-xs uppercase tracking-wider hover:bg-emerald-700 transition-all duration-300 shadow-md hover:shadow-lg active:scale-95 group">
          <span class="material-icons text-lg transition-transform duration-300 group-hover:rotate-12">download</span>
          Unduh Laporan
          <span class="material-icons text-sm transition-transform duration-300" :class="dropdownOpen ? 'rotate-180' : ''">expand_more</span>
        </button>

        <!-- Dropdown Menu -->
        <Transition name="dropdown">
          <div v-if="dropdownOpen" class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-100 z-50 overflow-hidden animate-fadeInDown">
            <div class="py-2">
              <button @click="exportReport('top5')" class="w-full px-4 py-2.5 text-left text-sm hover:bg-emerald-50 transition-all duration-200 flex items-center gap-3 group">
                <span class="material-icons text-emerald-500 text-sm transition-transform duration-200 group-hover:scale-110">looks_one</span>
                <span>Top 5 Mahasiswa Terbaik</span>
              </button>
              <button @click="exportReport('top10')" class="w-full px-4 py-2.5 text-left text-sm hover:bg-emerald-50 transition-all duration-200 flex items-center gap-3 group">
                <span class="material-icons text-emerald-500 text-sm transition-transform duration-200 group-hover:scale-110">looks_two</span>
                <span>Top 10 Mahasiswa Terbaik</span>
              </button>
              <button @click="exportReport('all')" class="w-full px-4 py-2.5 text-left text-sm hover:bg-emerald-50 transition-all duration-200 flex items-center gap-3 group">
                <span class="material-icons text-emerald-500 text-sm transition-transform duration-200 group-hover:scale-110">table_rows</span>
                <span>Seluruh Data Mahasiswa</span>
              </button>
              <div class="border-t border-gray-100 my-1"></div>
              <button @click="exportReport('complete')" class="w-full px-4 py-2.5 text-left text-sm hover:bg-emerald-50 transition-all duration-200 flex items-center gap-3 group">
                <span class="material-icons text-emerald-500 text-sm transition-transform duration-200 group-hover:scale-110">description</span>
                <span>Laporan Lengkap (PDF)</span>
              </button>
            </div>
          </div>
        </Transition>
      </div>
    </div>

    <!-- Alert jika belum ada data -->
    <Transition name="alert">
      <div v-if="studentsWithV.length === 0" class="mb-6 p-5 bg-amber-50 rounded-xl border-l-4 border-l-amber-500 animate-fadeInUp">
        <div class="flex items-start gap-3">
          <span class="material-icons text-amber-500 animate-pulse-slow">warning</span>
          <div>
            <p class="font-bold text-amber-900 text-sm">Belum Ada Data Perhitungan</p>
            <p class="text-xs text-amber-700">Silakan lakukan perhitungan TOPSIS terlebih dahulu di menu "Proses Perhitungan".</p>
          </div>
        </div>
      </div>
    </Transition>

    <!-- KONTEN LAPORAN -->
    <div v-if="studentsWithV.length > 0" id="reportContainer" class="space-y-6">

      <!-- Statistik Ringkas dengan Animasi Stagger -->
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div v-for="(stat, idx) in statsData" :key="idx"
             class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-0.5 animate-fadeInUp"
             :style="{ animationDelay: `${idx * 0.1}s` }">
          <p class="text-[10px] font-bold text-gray-400 uppercase">{{ stat.label }}</p>
          <p class="text-2xl font-black text-emerald-700">{{ stat.value }}</p>
          <div class="mt-2 h-0.5 w-full bg-emerald-100 rounded-full overflow-hidden">
            <div class="h-full bg-emerald-500 rounded-full animate-slideIn" :style="{ width: stat.percentage + '%' }"></div>
          </div>
        </div>
      </div>

      <!-- Top 5 Mahasiswa dengan Animasi -->
      <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden animate-fadeInUp" style="animation-delay: 0.2s">
        <div class="bg-emerald-50 px-5 py-3 border-b border-emerald-100">
          <h3 class="font-bold text-emerald-800 text-sm flex items-center gap-2">
            <span class="material-icons text-emerald-600 text-sm animate-pulse-slow">military_tech</span>
            🏆 Top 5 Mahasiswa Terbaik
          </h3>
        </div>
        <div class="p-5 space-y-4">
          <div v-for="(student, index) in top5Students" :key="student.id"
               class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0 group animate-fadeInLeft hover:bg-gray-50 rounded-lg transition-all duration-300 px-3"
               :style="{ animationDelay: `${index * 0.1}s` }">
            <div class="flex items-center gap-4">
              <span class="text-base font-bold text-gray-400 w-8">{{ index + 1 }}</span>
              <div>
                <span class="font-semibold text-gray-800 group-hover:text-emerald-700 transition-colors">{{ student.nama }}</span>
              </div>
            </div>
            <div class="flex items-center gap-4">
              <div class="w-32 h-2 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full bg-emerald-500 rounded-full animate-slideIn" :style="{ width: ((student.v || 0) * 100) + '%' }"></div>
              </div>
              <span class="text-sm font-bold text-emerald-600 min-w-[60px] text-right">{{ ((student.v || 0) * 100).toFixed(2) }}%</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Tabel Seluruh Data -->
      <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="bg-gray-50 px-5 py-3 border-b border-gray-100">
          <h3 class="font-bold text-gray-700 text-sm flex items-center gap-2">
            <span class="material-icons text-gray-500 text-sm">table_rows</span>
            Seluruh Data Mahasiswa
          </h3>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full text-sm">
            <thead class="bg-gray-50">
              <tr class="text-[10px] font-bold text-gray-400 uppercase">
                <th class="px-4 py-3 text-left w-16">Rank</th>
                <th class="px-4 py-3 text-left">Nama</th>
                <th class="px-4 py-3 text-center w-16">IPK</th>
                <th class="px-4 py-3 text-center w-16">Hadir</th>
                <th class="px-4 py-3 text-center w-16">Prestasi</th>
                <th class="px-4 py-3 text-center w-16">Telat</th>
                <th class="px-4 py-3 text-center w-16">Tugas</th>
                <th class="px-4 py-3 text-center w-20">Nilai V</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
              <tr v-for="(item, index) in paginatedData" :key="item.id" class="hover:bg-gray-50 transition-all duration-200">
                <td class="px-4 py-2 text-center font-bold text-gray-500">{{ item.rank }}</td>
                <td class="px-4 py-2 font-medium text-gray-800">{{ item.nama }}</td>
                <td class="px-4 py-2 text-center text-gray-600">{{ getNilaiMahasiswa(item.id, 1) }}</td>
                <td class="px-4 py-2 text-center text-gray-600">{{ getNilaiMahasiswa(item.id, 2) }}</td>
                <td class="px-4 py-2 text-center text-gray-600">{{ getNilaiMahasiswa(item.id, 3) }}</td>
                <td class="px-4 py-2 text-center text-gray-600">{{ getNilaiMahasiswa(item.id, 4) }}</td>
                <td class="px-4 py-2 text-center text-gray-600">{{ getNilaiMahasiswa(item.id, 5) }}</td>
                <td class="px-4 py-2 text-center font-semibold text-emerald-600">{{ ((item.v || 0) * 100).toFixed(2) }}%</td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div class="px-4 py-3 border-t border-gray-100 flex justify-between items-center">
          <p class="text-[10px] text-gray-400">Hal {{ currentPage }} dari {{ totalPages }}</p>
          <div class="flex gap-1">
            <button @click="prevPage" :disabled="currentPage === 1" class="w-7 h-7 flex items-center justify-center rounded-md text-gray-400 hover:bg-gray-100 hover:text-emerald-600 transition-all duration-200 disabled:opacity-40 disabled:hover:bg-transparent disabled:hover:text-gray-400">
              <span class="material-icons text-sm">chevron_left</span>
            </button>
            <button @click="nextPage" :disabled="currentPage === totalPages" class="w-7 h-7 flex items-center justify-center rounded-md text-gray-400 hover:bg-gray-100 hover:text-emerald-600 transition-all duration-200 disabled:opacity-40 disabled:hover:bg-transparent disabled:hover:text-gray-400">
              <span class="material-icons text-sm">chevron_right</span>
            </button>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import * as XLSX from 'xlsx';
import html2pdf from 'html2pdf.js';

// Props
const props = defineProps({
  dataMahasiswa: { type: Array, default: () => [] },
  dataKriteria: { type: Array, default: () => [] }
});

// State
const dropdownOpen = ref(false);
const currentPage = ref(1);
const itemsPerPage = ref(10);
const dropdownRef = ref(null);

// Close dropdown when clicking outside
const handleClickOutside = (event) => {
  if (dropdownRef.value && !dropdownRef.value.contains(event.target)) {
    dropdownOpen.value = false;
  }
};

onMounted(() => {
  document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
});

const toggleDropdown = () => {
  dropdownOpen.value = !dropdownOpen.value;
};

// Computed
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

const studentsWithV = computed(() => {
  return props.dataMahasiswa.filter(s => s.v !== undefined && s.v !== null);
});

const criteria = computed(() => props.dataKriteria);

const sortedStudents = computed(() => {
  return [...studentsWithV.value].sort((a, b) => (b.v || 0) - (a.v || 0));
});

const top5Students = computed(() => sortedStudents.value.slice(0, 5));
const top10Students = computed(() => sortedStudents.value.slice(0, 10));

const highestV = computed(() => {
  if (studentsWithV.value.length === 0) return '-';
  const maxV = Math.max(...studentsWithV.value.map(s => s.v || 0));
  return (maxV * 100).toFixed(2);
});

const averageV = computed(() => {
  if (studentsWithV.value.length === 0) return '-';
  const avg = studentsWithV.value.reduce((sum, s) => sum + (s.v || 0), 0) / studentsWithV.value.length;
  return (avg * 100).toFixed(2);
});

const statsData = computed(() => [
  { label: 'Total Mahasiswa', value: studentsWithV.value.length, percentage: 100 },
  { label: 'Total Kriteria', value: criteria.value.length, percentage: 100 },
  { label: 'Nilai V Tertinggi', value: highestV.value + '%', percentage: parseFloat(highestV.value) || 0 },
  { label: 'Rata-rata V', value: averageV.value + '%', percentage: parseFloat(averageV.value) || 0 }
]);

const filteredStudents = computed(() => {
  return sortedStudents.value;
});

const totalPages = computed(() => Math.ceil(filteredStudents.value.length / itemsPerPage.value));

const paginatedData = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value;
  const end = start + itemsPerPage.value;
  return filteredStudents.value.slice(start, end).map((item, idx) => ({
    ...item,
    rank: start + idx + 1
  }));
});

// Helper Functions
const getNilaiMahasiswa = (siswaId, kriteriaId) => {
  const siswa = props.dataMahasiswa.find(s => s.id === siswaId);
  if (siswa && siswa.nilai && siswa.nilai[kriteriaId] !== undefined) {
    return siswa.nilai[kriteriaId];
  }
  return '-';
};

// Pagination
const prevPage = () => { if (currentPage.value > 1) currentPage.value--; };
const nextPage = () => { if (currentPage.value < totalPages.value) currentPage.value++; };

// Export Functions
const exportReport = (type) => {
  dropdownOpen.value = false;

  let title = '';
  let data = [];

  switch(type) {
    case 'top5':
      title = 'TOP 5 MAHASISWA TERBAIK';
      data = top5Students.value.map((s, i) => ({
        Rank: i + 1,
        Nama: s.nama,
        'Nilai V (%)': ((s.v || 0) * 100).toFixed(2) + '%'
      }));
      break;
    case 'top10':
      title = 'TOP 10 MAHASISWA TERBAIK';
      data = top10Students.value.map((s, i) => ({
        Rank: i + 1,
        Nama: s.nama,
        'Nilai V (%)': ((s.v || 0) * 100).toFixed(2) + '%'
      }));
      break;
    case 'all':
      title = 'SELURUH DATA MAHASISWA';
      data = sortedStudents.value.map((s, i) => ({
        Rank: i + 1,
        Nama: s.nama,
        IPK: getNilaiMahasiswa(s.id, 1),
        Kehadiran: getNilaiMahasiswa(s.id, 2),
        Prestasi: getNilaiMahasiswa(s.id, 3),
        Keterlambatan: getNilaiMahasiswa(s.id, 4),
        Tugas: getNilaiMahasiswa(s.id, 5),
        'Nilai V (%)': ((s.v || 0) * 100).toFixed(2) + '%'
      }));
      break;
    case 'complete':
      exportCompleteReport();
      return;
  }

  const ws = XLSX.utils.json_to_sheet(data);
  const wb = XLSX.utils.book_new();
  XLSX.utils.book_append_sheet(wb, ws, title);
  XLSX.writeFile(wb, `Laporan_TOPSIS_${title}_${new Date().toISOString().split('T')[0]}.xlsx`);
};

const exportCompleteReport = async () => {
  const element = document.getElementById('reportContainer');
  if (!element) return;

  const opt = {
    margin: [0.5, 0.5, 0.5, 0.5],
    filename: `Laporan_TOPSIS_Complete_${new Date().toISOString().split('T')[0]}.pdf`,
    image: { type: 'jpeg', quality: 0.98 },
    html2canvas: { scale: 2 },
    jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
  };
  await html2pdf().set(opt).from(element).save();
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/icon?family=Material+Icons');
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

::-webkit-scrollbar { width: 5px; }
::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
::-webkit-scrollbar-thumb:hover { background: #10b981; }

/* ANIMATIONS */
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

/* Animation Classes */
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

/* Dropdown Transition */
.dropdown-enter-active,
.dropdown-leave-active {
  transition: all 0.2s ease;
}
.dropdown-enter-from,
.dropdown-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}

/* Alert Transition */
.alert-enter-active,
.alert-leave-active {
  transition: all 0.3s ease;
}
.alert-enter-from,
.alert-leave-to {
  opacity: 0;
  transform: translateY(-20px);
}

/* Rotate Animation */
.rotate-180 {
  transform: rotate(180deg);
}
</style>
