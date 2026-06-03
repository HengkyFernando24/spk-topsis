<!-- DataSiswa.vue - Versi Final (Tanpa Konversi IPK) -->
<template>
  <div class="p-4 md:p-6 max-w-7xl mx-auto font-['Inter'] antialiased text-gray-900">
    <!-- Header -->
    <div class="mb-6">
      <div class="flex items-center gap-3 mb-2">
        <div class="w-1.5 h-8 bg-emerald-500 rounded-full"></div>
        <h2 class="text-2xl font-bold text-emerald-900">Manajemen Data Mahasiswa</h2>
      </div>
      <p class="text-gray-500 text-sm">
        Kelola data mahasiswa dan nilai kriteria untuk perhitungan algoritma <span class="font-bold text-emerald-700">TOPSIS</span>
      </p>
    </div>

    <!-- FILTER DATA -->
    <div class="bg-white rounded-xl p-4 mb-6 shadow-sm border border-gray-100">
      <div class="flex items-center gap-2 mb-3">
        <span class="material-icons text-emerald-600 text-sm">filter_alt</span>
        <h4 class="font-semibold text-gray-700 text-sm">Filter Data</h4>
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
          <select v-model="selectedProdi" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-emerald-500 focus:outline-none">
            <option v-for="p in filteredProdi" :key="p.id" :value="p.id">{{ p.nama }}</option>
          </select>
        </div>
        <div>
          <label class="block text-[10px] font-bold text-gray-500 mb-1">Angkatan</label>
          <select v-model="selectedAngkatan" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-emerald-500 focus:outline-none">
            <option v-for="a in angkatanList" :key="a.id" :value="a.id">{{ a.tahun }}</option>
          </select>
        </div>
        <div>
          <label class="block text-[10px] font-bold text-gray-500 mb-1">Semester</label>
          <select v-model="selectedSemester" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-emerald-500 focus:outline-none">
            <option v-for="s in semesterList" :key="s.id" :value="s.id">{{ s.nama }}</option>
          </select>
        </div>
        <div>
          <label class="block text-[10px] font-bold text-gray-500 mb-1">Kelas</label>
          <select v-model="selectedKelas" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-emerald-500 focus:outline-none">
            <option value="">-- Semua Kelas --</option>
            <option v-for="k in kelasList" :key="k.id" :value="k.id">{{ k.nama }}</option>
          </select>
        </div>
      </div>
      <div class="mt-3 text-right">
        <span class="text-[10px] text-gray-400">Menampilkan {{ filteredSiswa.length }} dari {{ dataSiswa.length }} mahasiswa</span>
      </div>
    </div>

    <!-- Tombol Aksi -->
    <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
      <div class="flex gap-3">
        <button @click="goToTambahSiswa" class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-xl flex items-center gap-2 transition-all text-sm font-semibold shadow-sm">
          <span class="material-icons text-base">person_add</span>
          Tambah Data mahasiswa
        </button>
        <button @click="triggerFileImport" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl flex items-center gap-2 transition-all text-sm font-semibold shadow-sm">
          <span class="material-icons text-base">file_upload</span> Import Excel
        </button>
        <input type="file" ref="fileInput" @change="handleFileImport" accept=".xlsx, .xls, .csv" class="hidden">
      </div>
      <div class="flex items-center gap-4 text-sm text-gray-500 bg-gray-50 px-4 py-2 rounded-xl">
        <div class="flex items-center gap-1">
          <span class="material-icons text-emerald-500 text-sm">groups</span>
          <span class="font-medium">{{ filteredSiswa.length }}</span> <span>Mahasiswa</span>
        </div>
        <div class="w-px h-4 bg-gray-300"></div>
        <div class="flex items-center gap-1">
          <span class="material-icons text-purple-500 text-sm">fact_check</span>
          <span class="font-medium">{{ dataKriteria.length }}</span> <span>Kriteria</span>
        </div>
      </div>
    </div>

    <!-- Alert jika belum ada kriteria -->
    <div v-if="dataKriteria.length === 0" class="mb-6 p-4 bg-amber-50 rounded-xl border-l-4 border-l-amber-500">
      <div class="flex items-start gap-3">
        <span class="material-icons text-amber-500">warning</span>
        <div>
          <p class="font-semibold text-amber-900 text-sm">Belum Ada Kriteria</p>
          <p class="text-xs text-amber-700">Silakan tambahkan kriteria terlebih dahulu di menu Manajemen Kriteria.</p>
        </div>
      </div>
    </div>

    <!-- Panduan Import Excel -->
    <div v-if="showImportGuide" class="mb-6 p-4 bg-blue-50 rounded-xl border-l-4 border-l-blue-500">
      <div class="flex items-start justify-between">
        <div class="flex items-start gap-3">
          <span class="material-icons text-blue-500">info</span>
          <div>
            <p class="font-semibold text-blue-900 text-sm">Panduan Import Data Excel</p>
            <div class="text-xs text-blue-700 mt-2 space-y-1">
              <p>📋 Format file: <strong>.xlsx, .xls, .csv</strong></p>
              <p>📊 Kolom yang harus ada: <strong>NIM, Nama, Kelas</strong> dan <strong v-for="k in dataKriteria" :key="k.id"> {{ k.nama }}</strong></p>
              <p>💡 <strong>Tips:</strong> Download template Excel terlebih dahulu.</p>
            </div>
          </div>
        </div>
        <button @click="showImportGuide = false" class="text-blue-400 hover:text-blue-600"><span class="material-icons">close</span></button>
      </div>
      <div class="mt-3 flex gap-3">
        <button @click="downloadTemplate" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-medium flex items-center gap-1">
          <span class="material-icons text-sm">download</span> Download Template
        </button>
        <button @click="showImportGuide = false" class="px-3 py-1.5 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-xs font-medium">Tutup</button>
      </div>
    </div>

    <!-- Tabel Data Siswa -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
      <div class="bg-gray-50 px-5 py-3 border-b border-gray-200">
        <h3 class="font-semibold text-gray-700">Daftar Mahasiswa</h3>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="bg-gray-50 border-b border-gray-200 text-[11px] font-semibold text-gray-500 uppercase">
              <th class="px-4 py-3 text-center w-12">No</th>
              <th class="px-4 py-3 text-left">NIM</th>
              <th class="px-4 py-3 text-left">Nama Mahasiswa</th>
              <th class="px-4 py-3 text-left">Kelas</th>
              <th v-for="kriteria in dataKriteria" :key="kriteria.id" class="px-3 py-3 text-center min-w-[80px]">{{ kriteria.nama }}</th>
              <th class="px-4 py-3 text-center w-24">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(siswa, index) in filteredSiswa" :key="siswa.id" class="hover:bg-gray-50 transition-colors">
              <td class="px-4 py-3 text-center text-gray-500 font-medium">{{ index + 1 }}</td>
              <td class="px-4 py-3 font-mono text-gray-700 text-sm">{{ siswa.nim || '-' }}</td>
              <td class="px-4 py-3 font-medium text-gray-800">{{ siswa.nama }}</td>
              <td class="px-4 py-3 font-medium text-gray-600">{{ getKelasNama(siswa.kelas_id) }}</td>
              <td v-for="kriteria in dataKriteria" :key="kriteria.id" class="px-3 py-2 text-center">
                <input type="number" step="any" :value="getNilaiSiswaBySemester(siswa.id, selectedSemester, kriteria.id)" @input="updateNilaiSemester(siswa.id, selectedSemester, kriteria.id, $event.target.value)" class="w-20 px-2 py-1.5 border border-gray-300 rounded-md text-center text-sm focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500 transition">
              </td>
              <td class="px-4 py-3 text-center">
                <div class="flex items-center justify-center gap-1">
                  <button @click="editSiswa(siswa)" class="p-1.5 text-amber-600 hover:bg-amber-50 rounded-md transition" title="Edit"><span class="material-icons text-sm">edit</span></button>
                  <button @click="confirmDelete(siswa)" class="p-1.5 text-red-500 hover:bg-red-50 rounded-md transition" title="Hapus"><span class="material-icons text-sm">delete</span></button>
                </div>
              </td>
            </tr>
            <tr v-if="filteredSiswa.length === 0">
              <td :colspan="4 + dataKriteria.length" class="px-4 py-12 text-center text-gray-400">
                <div class="flex flex-col items-center gap-2">
                  <span class="material-icons text-4xl">school</span>
                  <p>Tidak ada data mahasiswa untuk filter ini</p>
                  <div class="flex gap-2 mt-1">
                    <button @click="goToTambahSiswa" class="text-emerald-600 text-sm font-medium">+ Tambah manual</button>
                    <button @click="triggerFileImport" class="text-blue-600 text-sm font-medium">📂 Import Excel</button>
                  </div>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- MODAL DELETE -->
    <Teleport to="body">
      <Transition name="modal">
        <div v-if="deleteModalVisible" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50" @click.self="deleteModalVisible = false">
          <div class="bg-white rounded-xl shadow-xl max-w-sm w-full p-5">
            <div class="text-center">
              <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3"><span class="material-icons text-red-500">warning</span></div>
              <h3 class="font-bold text-lg text-gray-800 mb-1">Hapus Data mahasiswa?</h3>
              <p class="text-gray-500 text-sm">Yakin ingin menghapus <span class="font-semibold">{{ selectedSiswa?.nama }}</span>?</p>
              <p class="text-xs text-red-400 mt-1">Semua nilai akan terhapus!</p>
            </div>
            <div class="flex justify-end gap-2 mt-5 pt-3 border-t border-gray-100">
              <button @click="deleteModalVisible = false" class="px-4 py-2 rounded-lg border border-gray-200 text-gray-600 text-sm font-medium hover:bg-gray-50">Batal</button>
              <button @click="deleteSiswa" class="px-4 py-2 rounded-lg bg-red-600 text-white text-sm font-medium hover:bg-red-700">Hapus</button>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>

    <!-- TOAST -->
    <Transition name="toast">
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
    </Transition>
  </div>
</template>

<script setup>
import { ref, onMounted, watch, computed } from 'vue';
import * as XLSX from 'xlsx';

const props = defineProps({
  studentsData: { type: Array, default: () => [] },
  dataKriteria: { type: Array, default: () => [] }
});

const emit = defineEmits(['update', 'navigate-to-tambah', 'navigate-to-edit']);

// Data utama
const dataSiswa = ref([]);
const penilaianPerSemester = ref([]);
const kelasList = ref([]);

// Filter state
const selectedFakultas = ref(3);
const selectedProdi = ref(8);
const selectedAngkatan = ref(4);
const selectedSemester = ref(4);
const selectedKelas = ref('');
const fakultasList = ref([]);
const prodiList = ref([]);
const angkatanList = ref([]);
const semesterList = ref([]);

// UI state
const deleteModalVisible = ref(false);
const selectedSiswa = ref(null);
const showImportGuide = ref(true);
const fileInput = ref(null);
const toast = ref({ show: false, type: 'success', title: '', message: '' });

// Data kelas default
const defaultKelas = [
  { id: 1, nama: "Reguler - C01", kode: "C01", kelompok: "Reguler" },
  { id: 2, nama: "Reguler - C02", kode: "C02", kelompok: "Reguler" },
  { id: 3, nama: "Reguler - B01", kode: "B01", kelompok: "Reguler" },
  { id: 4, nama: "Karyawan - K01", kode: "K01", kelompok: "Karyawan" }
];

const showToast = (type, title, message) => {
  toast.value = { show: true, type, title, message };
  setTimeout(() => { toast.value.show = false; }, 4000);
};

// Computed
const filteredProdi = computed(() => {
  return prodiList.value.filter(p => p.fakultas_id === selectedFakultas.value);
});

const filteredSiswa = computed(() => {
  let result = dataSiswa.value.filter(s =>
    s.fakultas_id === selectedFakultas.value &&
    s.prodi_id === selectedProdi.value &&
    s.angkatan_id === selectedAngkatan.value &&
    (selectedKelas.value ? s.kelas_id === parseInt(selectedKelas.value) : true)
  );

  result = result.filter(siswa => {
    return penilaianPerSemester.value.some(p => p.mahasiswa_id === siswa.id && p.semester_id === selectedSemester.value);
  });

  return result;
});

// Helper functions
const getKelasNama = (kelasId) => {
  if (!kelasId) return '-';
  const kelas = kelasList.value.find(k => k.id === parseInt(kelasId));
  return kelas ? kelas.nama : '-';
};

const getNilaiSiswaBySemester = (siswaId, semesterId, kriteriaId) => {
  const penilaian = penilaianPerSemester.value.find(p => p.mahasiswa_id === siswaId && p.semester_id === semesterId);
  if (penilaian && penilaian.nilai && penilaian.nilai[kriteriaId] !== undefined) {
    return penilaian.nilai[kriteriaId];
  }
  return '';
};

const updateNilaiSemester = (siswaId, semesterId, kriteriaId, value) => {
  const existingIndex = penilaianPerSemester.value.findIndex(p => p.mahasiswa_id === siswaId && p.semester_id === semesterId);
  const nilaiBaru = parseFloat(value) || 0;
  if (existingIndex !== -1) {
    penilaianPerSemester.value[existingIndex].nilai[kriteriaId] = nilaiBaru;
  } else {
    const newNilai = {};
    props.dataKriteria.forEach(k => { newNilai[k.id] = 0; });
    newNilai[kriteriaId] = nilaiBaru;
    penilaianPerSemester.value.push({ mahasiswa_id: siswaId, semester_id: semesterId, nilai: newNilai });
  }
  updateParentData();
};

const updateParentData = () => {
  const updatedStudents = dataSiswa.value.map(siswa => ({
    id: siswa.id,
    nim: siswa.nim,
    nama: siswa.nama,
    fakultas_id: siswa.fakultas_id,
    prodi_id: siswa.prodi_id,
    angkatan_id: siswa.angkatan_id,
    kelas_id: siswa.kelas_id,
    v: siswa.v || 0,
    nilai: {}
  }));
  emit('update', updatedStudents);
  localStorage.setItem('penilaian', JSON.stringify(penilaianPerSemester.value));
};

// Navigasi
const goToTambahSiswa = () => {
  emit('navigate-to-tambah');
};

const editSiswa = (siswa) => {
  emit('navigate-to-edit', siswa);
};

// Delete
const confirmDelete = (siswa) => {
  selectedSiswa.value = siswa;
  deleteModalVisible.value = true;
};

const deleteSiswa = () => {
  if (selectedSiswa.value) {
    dataSiswa.value = dataSiswa.value.filter(s => s.id !== selectedSiswa.value.id);
    penilaianPerSemester.value = penilaianPerSemester.value.filter(p => p.mahasiswa_id !== selectedSiswa.value.id);
    updateParentData();
    showToast('success', 'Berhasil', `Data mahasiswa ${selectedSiswa.value.nama} dihapus!`);
  }
  deleteModalVisible.value = false;
  selectedSiswa.value = null;
};

// Filter handlers
const onFakultasChange = () => {
  if (filteredProdi.value.length > 0) {
    selectedProdi.value = filteredProdi.value[0].id;
  }
};

// Load data kelas
const loadKelasData = () => {
  let kelas = localStorage.getItem('kelas');
  if (!kelas) {
    localStorage.setItem('kelas', JSON.stringify(defaultKelas));
    kelasList.value = defaultKelas;
  } else {
    kelasList.value = JSON.parse(kelas);
  }
  console.log('✅ Kelas loaded:', kelasList.value.length);
};

// Import Excel
const triggerFileImport = () => fileInput.value.click();

const downloadTemplate = () => {
  try {
    const headers = ['NIM', 'Nama', 'Kelas', 'Fakultas', 'Prodi', 'Angkatan', 'Semester', ...props.dataKriteria.map(k => k.nama)];
    const row1 = ['A001', 'Contoh: Budi Santoso', 'Reguler - C01', 'Fakultas Sains dan Teknologi', 'Informatika', '2023', '4', ...props.dataKriteria.map(() => '85')];
    const row2 = ['A002', 'Contoh: Siti Nurhaliza', 'Reguler - C02', 'Fakultas Sains dan Teknologi', 'Informatika', '2023', '4', ...props.dataKriteria.map(() => '90')];
    const ws = XLSX.utils.aoa_to_sheet([headers, row1, row2]);
    ws['!cols'] = [{ wch: 15 }, { wch: 25 }, { wch: 20 }, { wch: 25 }, { wch: 20 }, { wch: 10 }, { wch: 10 }, ...props.dataKriteria.map(() => ({ wch: 12 }))];
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, 'Template Data Mahasiswa');
    XLSX.writeFile(wb, `Template_Data_Mahasiswa_${new Date().toISOString().split('T')[0]}.xlsx`);
    showToast('success', 'Download Berhasil', 'Template Excel telah diunduh!');
  } catch (error) {
    showToast('error', 'Gagal Download', error.message);
  }
};

const handleFileImport = async (event) => {
  const file = event.target.files[0];
  if (!file) return;

  const fileExt = file.name.substring(file.name.lastIndexOf('.')).toLowerCase();
  if (!['.xlsx', '.xls', '.csv'].includes(fileExt)) {
    showToast('error', 'Format Error', 'Upload file Excel (.xlsx, .xls, .csv)');
    fileInput.value.value = '';
    return;
  }

  try {
    const data = await file.arrayBuffer();
    const workbook = XLSX.read(data);
    const jsonData = XLSX.utils.sheet_to_json(workbook.Sheets[workbook.SheetNames[0]], { header: 1, defval: '' });

    if (!jsonData || jsonData.length < 2) throw new Error('File kosong');

    const headers = jsonData[0];
    if (headers[0] !== 'NIM') throw new Error('Kolom pertama harus "NIM"');
    if (headers[1] !== 'Nama') throw new Error('Kolom kedua harus "Nama"');
    if (headers[2] !== 'Kelas') throw new Error('Kolom ketiga harus "Kelas"');

    // Fungsi untuk mendapatkan ID kelas dari nama kelas
    const getKelasId = (namaKelas) => {
      let kelas = kelasList.value.find(k => k.nama === namaKelas);
      if (kelas) return kelas.id;
      kelas = kelasList.value.find(k => k.kode === namaKelas);
      if (kelas) return kelas.id;
      if (namaKelas.includes('C01') || namaKelas === 'Reguler C01') return 1;
      if (namaKelas.includes('C02') || namaKelas === 'Reguler C02') return 2;
      if (namaKelas.includes('B01') || namaKelas === 'Reguler B01') return 3;
      if (namaKelas.includes('Karyawan') || namaKelas === 'Karyawan - K01' || namaKelas === 'K01') return 4;
      return 1;
    };

    let importedCount = 0;
    let duplicateCount = 0;
    let errorCount = 0;

    for (let i = 1; i < jsonData.length; i++) {
      const row = jsonData[i];
      if (!row || row.length < 3) {
        errorCount++;
        continue;
      }

      const nim = row[0]?.toString().trim();
      const nama = row[1]?.toString().trim();
      const kelasNama = row[2]?.toString().trim();

      if (!nim || !nama) {
        errorCount++;
        continue;
      }

      // Cek duplikat NIM
      if (dataSiswa.value.find(s => s.nim === nim)) {
        duplicateCount++;
        continue;
      }

      const fakultasNama = row[3]?.toString().trim() || 'Fakultas Sains dan Teknologi';
      const prodiNama = row[4]?.toString().trim() || 'Informatika';
      const angkatanTahun = parseInt(row[5]) || 2023;
      const semesterId = parseInt(row[6]) || 4;

      const kelas_id = getKelasId(kelasNama);
      const fakultas_id = 3;
      const prodi_id = 8;
      const angkatan_id = angkatanList.value.find(a => a.tahun === angkatanTahun)?.id || 4;

      const newId = Date.now() + i;

      // Tambah mahasiswa
      dataSiswa.value.push({
        id: newId,
        nim: nim,
        nama: nama,
        fakultas_id: fakultas_id,
        prodi_id: prodi_id,
        angkatan_id: angkatan_id,
        kelas_id: kelas_id,
        v: 0
      });

      // Proses nilai kriteria (TANPA KONVERSI IPK)
      const nilaiBaru = {};
      let hasValid = false;

      for (let j = 0; j < props.dataKriteria.length; j++) {
        let nilai = parseFloat(row[j + 7]);
        if (isNaN(nilai)) nilai = 0;

        // KONVERSI IPK DINONAKTIFKAN - biarkan nilai asli dari Excel
        // if (props.dataKriteria[j]?.id === 1 && nilai <= 4 && nilai > 0) {
        //   nilai = (nilai / 4) * 100;
        // }

        // Konversi Keterlambatan (kriteria id 4) dari hari ke skala 0-100

        nilaiBaru[props.dataKriteria[j]?.id] = nilai;
        if (nilai > 0) hasValid = true;
      }

      penilaianPerSemester.value.push({
        mahasiswa_id: newId,
        semester_id: semesterId,
        nilai: nilaiBaru
      });

      importedCount++;
    }

    updateParentData();

    let message = `${importedCount} data berhasil diimport`;
    if (duplicateCount > 0) message += ` (${duplicateCount} duplikat dilewati)`;
    if (errorCount > 0) message += ` (${errorCount} baris error)`;

    if (importedCount > 0) {
      showToast('success', 'Import Berhasil', message);
      initData();
    } else {
      showToast('error', 'Import Gagal', 'Tidak ada data valid yang diimport');
    }

  } catch (error) {
    console.error('Import error:', error);
    showToast('error', 'Gagal Import', error.message);
  } finally {
    fileInput.value.value = '';
  }
};

// Initialize data
const initData = () => {
  const savedSiswa = localStorage.getItem('mahasiswa');
  if (savedSiswa) {
    dataSiswa.value = JSON.parse(savedSiswa);
    console.log('✅ Loaded mahasiswa from localStorage:', dataSiswa.value.length);
  } else if (props.studentsData?.length) {
    dataSiswa.value = props.studentsData.map(s => ({
      id: s.id,
      nim: s.nim,
      nama: s.nama,
      fakultas_id: s.fakultas_id || 3,
      prodi_id: s.prodi_id || 8,
      angkatan_id: s.angkatan_id || 4,
      kelas_id: s.kelas_id || 1,
      v: s.v || 0
    }));
    console.log('✅ Loaded mahasiswa from props:', dataSiswa.value.length);
  }

  const savedPenilaian = localStorage.getItem('penilaian');
  if (savedPenilaian) {
    penilaianPerSemester.value = JSON.parse(savedPenilaian);
    console.log('✅ Loaded penilaian:', penilaianPerSemester.value.length);
  }
};

// Load master data
const loadMasterData = () => {
  fakultasList.value = JSON.parse(localStorage.getItem('fakultas') || '[]');
  prodiList.value = JSON.parse(localStorage.getItem('prodi') || '[]');
  angkatanList.value = JSON.parse(localStorage.getItem('angkatan') || '[]');
  semesterList.value = JSON.parse(localStorage.getItem('semester') || '[]');

  if (fakultasList.value.length === 0) {
    fakultasList.value = [
      { id: 1, nama: "Fakultas Agama Islam", kode: "FAI" },
      { id: 2, nama: "Fakultas Ilmu Pendidikan", kode: "FIP" },
      { id: 3, nama: "Fakultas Sains dan Teknologi", kode: "FST" }
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
      semesterList.value.push({ id: i, nama: `Semester ${i}`, urutan: i });
    }
  }
};

watch(() => props.studentsData, (newVal) => {
  if (newVal?.length && dataSiswa.value.length === 0) initData();
}, { deep: true, immediate: true });

onMounted(() => {
  loadKelasData();
  loadMasterData();
  initData();
});

defineExpose({ getDataSiswa: () => dataSiswa.value, getPenilaian: () => penilaianPerSemester.value });
</script>

<style scoped>
.modal-enter-active, .modal-leave-active { transition: all 0.2s ease; }
.modal-enter-from, .modal-leave-to { opacity: 0; transform: scale(0.95); }
.toast-enter-active, .toast-leave-active { transition: all 0.3s ease; }
.toast-enter-from, .toast-leave-to { opacity: 0; transform: translateY(20px); }
::-webkit-scrollbar { width: 6px; height: 6px; }
::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
::-webkit-scrollbar-thumb:hover { background: #10b981; }
input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button { opacity: 0.3; }
</style>
