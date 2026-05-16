<!-- DataSiswa.vue - Versi Tabel Rapi -->
<template>
  <div class="p-4 md:p-6 max-w-7xl mx-auto font-['Inter'] antialiased text-gray-900">
    <!-- Header -->
    <div class="mb-6">
      <div class="flex items-center gap-3 mb-2">
        <div class="w-1.5 h-8 bg-emerald-500 rounded-full"></div>
        <h2 class="text-2xl font-bold text-emerald-900">Manajemen Data Siswa</h2>
      </div>
      <p class="text-gray-500 text-sm">
        Kelola data mahasiswa dan nilai kriteria untuk perhitungan algoritma <span class="font-bold text-emerald-700">TOPSIS</span>
      </p>
    </div>

    <!-- Tombol Aksi -->
    <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
      <div class="flex gap-3">
        <button @click="goToTambahSiswa"
                class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-xl flex items-center gap-2 transition-all text-sm font-semibold shadow-sm">
          <span class="material-icons text-base">person_add</span>
          Tambah Data Siswa
        </button>
        <button @click="triggerFileImport"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl flex items-center gap-2 transition-all text-sm font-semibold shadow-sm">
          <span class="material-icons text-base">file_upload</span>
          Import Excel
        </button>
        <input type="file" ref="fileInput" @change="handleFileImport" accept=".xlsx, .xls, .csv" class="hidden">
      </div>
      <div class="flex items-center gap-4 text-sm text-gray-500 bg-gray-50 px-4 py-2 rounded-xl">
        <div class="flex items-center gap-1">
          <span class="material-icons text-emerald-500 text-sm">groups</span>
          <span class="font-medium">{{ dataSiswa.length }}</span>
          <span>Mahasiswa</span>
        </div>
        <div class="w-px h-4 bg-gray-300"></div>
        <div class="flex items-center gap-1">
          <span class="material-icons text-purple-500 text-sm">fact_check</span>
          <span class="font-medium">{{ dataKriteria.length }}</span>
          <span>Kriteria</span>
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
              <p>📊 Kolom yang harus ada: <strong>Nama</strong> dan <strong v-for="k in dataKriteria" :key="k.id"> {{ k.nama }}</strong></p>
              <p>💡 <strong>Tips:</strong> Download template Excel terlebih dahulu.</p>
            </div>
          </div>
        </div>
        <button @click="showImportGuide = false" class="text-blue-400 hover:text-blue-600">
          <span class="material-icons">close</span>
        </button>
      </div>
      <div class="mt-3 flex gap-3">
        <button @click="downloadTemplate" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-medium flex items-center gap-1">
          <span class="material-icons text-sm">download</span> Download Template
        </button>
        <button @click="showImportGuide = false" class="px-3 py-1.5 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-xs font-medium">Tutup</button>
      </div>
    </div>

    <!-- Tabel Data Siswa - TAMPILAN RAPI -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
      <div class="bg-gray-50 px-5 py-3 border-b border-gray-200">
        <h3 class="font-semibold text-gray-700">Daftar Mahasiswa</h3>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="bg-gray-50 border-b border-gray-200 text-[11px] font-semibold text-gray-500 uppercase">
              <th class="px-4 py-3 text-center w-12">No</th>
              <th class="px-4 py-3 text-left">Nama Mahasiswa</th>
              <th v-for="kriteria in dataKriteria" :key="kriteria.id" class="px-3 py-3 text-center min-w-[80px]">
                {{ kriteria.nama }}
              </th>
              <th class="px-4 py-3 text-center w-24">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            <tr v-for="(siswa, index) in dataSiswa" :key="siswa.id" class="hover:bg-gray-50 transition-colors">
              <td class="px-4 py-3 text-center text-gray-500 font-medium">{{ index + 1 }}</td>
              <td class="px-4 py-3 font-medium text-gray-800">{{ siswa.nama }}</td>
              <td v-for="kriteria in dataKriteria" :key="kriteria.id" class="px-3 py-2 text-center">
                <input type="number"
                       step="any"
                       :value="getNilaiSiswa(siswa.id, kriteria.id)"
                       @input="updateNilai(siswa.id, kriteria.id, $event.target.value)"
                       class="w-20 px-2 py-1.5 border border-gray-300 rounded-md text-center text-sm focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500 transition">
              </td>
              <td class="px-4 py-3 text-center">
                <div class="flex items-center justify-center gap-1">
                  <button @click="editSiswa(siswa)" class="p-1.5 text-amber-600 hover:bg-amber-50 rounded-md transition" title="Edit">
                    <span class="material-icons text-sm">edit</span>
                  </button>
                  <button @click="confirmDelete(siswa)" class="p-1.5 text-red-500 hover:bg-red-50 rounded-md transition" title="Hapus">
                    <span class="material-icons text-sm">delete</span>
                  </button>
                </div>
              </td>
            </tr>
            <tr v-if="dataSiswa.length === 0">
              <td :colspan="2 + dataKriteria.length" class="px-4 py-12 text-center text-gray-400">
                <div class="flex flex-col items-center gap-2">
                  <span class="material-icons text-4xl">school</span>
                  <p>Belum ada data siswa</p>
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
              <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                <span class="material-icons text-red-500">warning</span>
              </div>
              <h3 class="font-bold text-lg text-gray-800 mb-1">Hapus Data Siswa?</h3>
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
import { ref, onMounted, watch } from 'vue';
import * as XLSX from 'xlsx';

const props = defineProps({
  studentsData: { type: Array, default: () => [] },
  dataKriteria: { type: Array, default: () => [] }
});

const emit = defineEmits(['update', 'navigate-to-tambah', 'navigate-to-edit']);

const dataSiswa = ref([]);
const dataNilai = ref([]);
const deleteModalVisible = ref(false);
const selectedSiswa = ref(null);
const showImportGuide = ref(true);
const fileInput = ref(null);
const toast = ref({ show: false, type: 'success', title: '', message: '' });

const showToast = (type, title, message) => {
  toast.value = { show: true, type, title, message };
  setTimeout(() => { toast.value.show = false; }, 4000);
};

const getNilaiSiswa = (siswaId, kriteriaId) => {
  const nilai = dataNilai.value.find(n => n.siswaId === siswaId && n.kriteriaId === kriteriaId);
  return nilai ? nilai.nilai : '';
};

const updateNilai = (siswaId, kriteriaId, value) => {
  const existingIndex = dataNilai.value.findIndex(n => n.siswaId === siswaId && n.kriteriaId === kriteriaId);
  if (existingIndex !== -1) {
    dataNilai.value[existingIndex].nilai = parseFloat(value) || 0;
  } else {
    dataNilai.value.push({ siswaId, kriteriaId, nilai: parseFloat(value) || 0 });
  }
  updateParentData();
};

const updateParentData = () => {
  const updatedStudents = dataSiswa.value.map(siswa => ({
    id: siswa.id,
    nama: siswa.nama,
    nilai: {}
  }));
  dataNilai.value.forEach(nilai => {
    const siswa = updatedStudents.find(s => s.id === nilai.siswaId);
    if (siswa) siswa.nilai[nilai.kriteriaId] = nilai.nilai;
  });
  emit('update', updatedStudents);
};

const goToTambahSiswa = () => emit('navigate-to-tambah');
const editSiswa = (siswa) => emit('navigate-to-edit', siswa);

const confirmDelete = (siswa) => {
  selectedSiswa.value = siswa;
  deleteModalVisible.value = true;
};

const deleteSiswa = () => {
  if (selectedSiswa.value) {
    dataSiswa.value = dataSiswa.value.filter(s => s.id !== selectedSiswa.value.id);
    dataNilai.value = dataNilai.value.filter(n => n.siswaId !== selectedSiswa.value.id);
    updateParentData();
    showToast('success', 'Berhasil', `Data ${selectedSiswa.value.nama} dihapus!`);
  }
  deleteModalVisible.value = false;
  selectedSiswa.value = null;
};

const triggerFileImport = () => fileInput.value.click();

const downloadTemplate = () => {
  try {
    const headers = ['Nama', ...props.dataKriteria.map(k => k.nama)];
    const row1 = ['Contoh: Budi Santoso', ...props.dataKriteria.map(() => '85')];
    const row2 = ['Contoh: Siti Nurhaliza', ...props.dataKriteria.map(() => '90')];
    const ws = XLSX.utils.aoa_to_sheet([headers, row1, row2]);
    ws['!cols'] = [{ wch: 25 }, ...props.dataKriteria.map(() => ({ wch: 12 }))];
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, 'Template Data Siswa');
    XLSX.writeFile(wb, `Template_Data_Siswa_${new Date().toISOString().split('T')[0]}.xlsx`);
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
    if (headers[0] !== 'Nama') throw new Error('Kolom pertama harus "Nama"');

    let importedCount = 0, errorCount = 0;

    for (let i = 1; i < jsonData.length; i++) {
      const row = jsonData[i];
      if (!row || row.length === 0) continue;

      const nama = row[0]?.toString().trim();
      if (!nama) { errorCount++; continue; }
      if (dataSiswa.value.find(s => s.nama === nama)) { errorCount++; continue; }

      const newId = Date.now() + i;
      dataSiswa.value.push({ id: newId, nama });

      let hasValid = false;
      for (let j = 0; j < props.dataKriteria.length; j++) {
        const nilai = parseFloat(row[j + 1]);
        if (!isNaN(nilai) && nilai >= 0) {
          dataNilai.value.push({ siswaId: newId, kriteriaId: props.dataKriteria[j].id, nilai });
          hasValid = true;
        }
      }
      if (hasValid) importedCount++;
      else { errorCount++; dataSiswa.value.pop(); }
    }

    updateParentData();
    if (importedCount > 0) showToast('success', 'Import Berhasil', `${importedCount} data diimport${errorCount ? ` (${errorCount} gagal)` : ''}`);
    else showToast('error', 'Import Gagal', 'Tidak ada data valid');
  } catch (error) {
    showToast('error', 'Gagal Import', error.message);
  } finally {
    fileInput.value.value = '';
  }
};

const initData = () => {
  if (props.studentsData?.length) {
    dataSiswa.value = props.studentsData.map(s => ({ id: s.id, nama: s.nama }));
    const newNilai = [];
    props.studentsData.forEach(siswa => {
      if (siswa.nilai) {
        Object.keys(siswa.nilai).forEach(kriteriaId => {
          newNilai.push({ siswaId: siswa.id, kriteriaId: parseInt(kriteriaId), nilai: siswa.nilai[kriteriaId] });
        });
      }
    });
    dataNilai.value = newNilai;
  }
};

watch(() => props.studentsData, (newVal) => {
  if (newVal?.length) initData();
  else { dataSiswa.value = []; dataNilai.value = []; }
}, { deep: true, immediate: true });

onMounted(() => initData());

defineExpose({ getDataSiswa: () => dataSiswa.value, getDataNilai: () => dataNilai.value });
</script>

<style scoped>
/* Transisi modal */
.modal-enter-active, .modal-leave-active { transition: all 0.2s ease; }
.modal-enter-from, .modal-leave-to { opacity: 0; transform: scale(0.95); }

/* Transisi toast */
.toast-enter-active, .toast-leave-active { transition: all 0.3s ease; }
.toast-enter-from, .toast-leave-to { opacity: 0; transform: translateY(20px); }

/* Scrollbar */
::-webkit-scrollbar { width: 6px; height: 6px; }
::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
::-webkit-scrollbar-thumb:hover { background: #10b981; }

/* Hilangkan spinner di input number */
input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button { opacity: 0.3; }
</style>
