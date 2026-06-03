<!-- TambahDataMahasiswa.vue - FINAL VERSION dengan Fitur Kelas -->
<template>
  <div class="min-h-screen bg-gray-50 py-6 px-4 md:px-8 font-['Inter'] antialiased">
    <div class="max-w-5xl mx-auto">

      <!-- Header dengan Tombol Kembali -->
      <div class="flex items-center gap-4 mb-6">
        <button @click="kembaliKeDataMahasiswa" class="p-2 bg-white rounded-xl shadow-sm hover:bg-gray-100 transition-all group">
          <span class="material-icons text-gray-600 group-hover:text-emerald-600">arrow_back</span>
        </button>
        <div>
          <div class="flex items-center gap-3 mb-1">
            <div class="w-1.5 h-8 bg-emerald-500 rounded-full"></div>
            <h2 class="text-2xl font-bold text-emerald-900">
              {{ isEditMode ? 'Edit Data Mahasiswa' : 'Tambah Data Mahasiswa' }}
            </h2>
          </div>
          <p class="text-gray-500 text-sm ml-4">
            {{ isEditMode ? 'Perbarui informasi data mahasiswa.' : 'Isi data mahasiswa dengan lengkap.' }}
          </p>
        </div>
      </div>

      <!-- Form Card -->
      <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">

        <!-- Card Header -->
        <div class="bg-gradient-to-r from-emerald-950 to-emerald-900 px-6 py-4">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-emerald-800 flex items-center justify-center text-emerald-400">
              <span class="material-icons">assignment_ind</span>
            </div>
            <div>
              <h3 class="font-bold text-white text-base">Formulir Data Mahasiswa</h3>
              <p class="text-emerald-400/70 text-xs mt-0.5">Isi data dengan lengkap dan akurat</p>
            </div>
          </div>
        </div>

        <!-- Form Body -->
        <div class="p-6 space-y-5">

          <!-- Nama Mahasiswa -->
          <div>
            <label class="block text-xs font-bold text-gray-700 mb-1">
              Nama Mahasiswa <span class="text-red-500">*</span>
            </label>
            <input v-model="form.nama" type="text"
                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:border-emerald-500 focus:bg-white focus:outline-none transition-all"
                   placeholder="Masukkan nama lengkap mahasiswa">
          </div>

          <!-- NIM -->
          <div>
            <label class="block text-xs font-bold text-gray-700 mb-1">
              NIM <span class="text-red-500">*</span>
            </label>
            <input v-model="form.nim" type="text"
                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:border-emerald-500 focus:bg-white focus:outline-none transition-all"
                   placeholder="Masukkan NIM mahasiswa">
          </div>

          <!-- 2 Kolom: Fakultas & Prodi -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
              <label class="block text-xs font-bold text-gray-700 mb-1">Fakultas</label>
              <select v-model="form.fakultas_id" @change="onFakultasChange"
                      class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:border-emerald-500 focus:outline-none transition-all">
                <option v-for="f in fakultasList" :key="f.id" :value="f.id">{{ f.nama }}</option>
              </select>
            </div>
            <div>
              <label class="block text-xs font-bold text-gray-700 mb-1">Program Studi</label>
              <select v-model="form.prodi_id"
                      class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:border-emerald-500 focus:outline-none transition-all">
                <option v-for="p in filteredProdi" :key="p.id" :value="p.id">{{ p.nama }}</option>
              </select>
            </div>
          </div>

          <!-- 3 Kolom: Angkatan, Semester, Kelas -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div>
              <label class="block text-xs font-bold text-gray-700 mb-1">Angkatan</label>
              <select v-model="form.angkatan_id"
                      class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:border-emerald-500 focus:outline-none transition-all">
                <option v-for="a in angkatanList" :key="a.id" :value="a.id">{{ a.tahun }}</option>
              </select>
            </div>
            <div>
              <label class="block text-xs font-bold text-gray-700 mb-1">Semester</label>
              <select v-model="form.semester_id"
                      class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:border-emerald-500 focus:outline-none transition-all">
                <option v-for="s in semesterList" :key="s.id" :value="s.id">{{ s.nama }}</option>
              </select>
            </div>
            <div>
              <label class="block text-xs font-bold text-gray-700 mb-1">Kelas <span class="text-red-500">*</span></label>
              <select v-model="form.kelas_id"
                      class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:border-emerald-500 focus:outline-none transition-all">
                <option v-for="k in kelasList" :key="k.id" :value="k.id">{{ k.nama }}</option>
              </select>
            </div>
          </div>

          <!-- Nilai Kriteria - 5 Kolom Landscape -->
          <div class="border-t border-gray-100 pt-4">
            <label class="block text-xs font-bold text-gray-700 mb-3">Nilai Kriteria</label>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
              <div v-for="k in dataKriteria" :key="k.id" class="bg-gray-50 rounded-xl p-3 border border-gray-100 text-center">
                <label class="block text-sm font-bold text-gray-700 mb-2">{{ k.nama }}</label>
                <input type="number" step="any" v-model="form.nilai[k.id]"
                       class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-center focus:border-emerald-500 focus:outline-none transition-all"
                       :placeholder="`Nilai`">
                <p class="text-[10px] text-gray-400 mt-1">{{ k.tipe === 'benefit' ? '➕ Benefit' : '➖ Cost' }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="px-6 py-5 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
          <button @click="batal" class="px-6 py-2.5 rounded-xl border border-gray-300 text-gray-600 font-medium text-sm hover:bg-gray-100 transition-all">
            Batal
          </button>
          <button @click="simpan" class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-sm flex items-center gap-2 transition-all shadow-sm">
            <span class="material-icons text-sm">save</span>
            {{ isEditMode ? 'Simpan Perubahan' : 'Simpan Data' }}
          </button>
        </div>
      </div>

      <!-- Informasi Tambahan -->
      <div class="mt-6 p-4 bg-emerald-50 rounded-xl border border-emerald-100">
        <div class="flex items-center gap-2">
          <span class="material-icons text-emerald-600 text-sm">info</span>
          <p class="text-[11px] text-emerald-800">
            <strong class="font-bold">Catatan:</strong> Data nilai akan disimpan untuk semester yang dipilih.
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';

const props = defineProps({
  dataKriteria: { type: Array, default: () => [] },
  editData: { type: Object, default: null }
});

const emit = defineEmits(['close', 'save']);

// State
const isEditMode = ref(false);
const fakultasList = ref([]);
const prodiList = ref([]);
const angkatanList = ref([]);
const semesterList = ref([]);
const kelasList = ref([]);

// Data kelas default
const defaultKelas = [
  { id: 1, nama: "Reguler - C01", kode: "C01", kelompok: "Reguler" },
  { id: 2, nama: "Reguler - C02", kode: "C02", kelompok: "Reguler" },
  { id: 3, nama: "Reguler - B01", kode: "B01", kelompok: "Reguler" },
  { id: 4, nama: "Karyawan - K01", kode: "K01", kelompok: "Karyawan" }
];

// Form data
const form = ref({
  id: null,
  nim: '',
  nama: '',
  fakultas_id: 3,
  prodi_id: 8,
  angkatan_id: 4,
  semester_id: 4,
  kelas_id: 1,
  nilai: {}
});

// Computed
const filteredProdi = computed(() => {
  return prodiList.value.filter(p => p.fakultas_id === form.value.fakultas_id);
});

// Methods
const onFakultasChange = () => {
  if (filteredProdi.value.length > 0) {
    form.value.prodi_id = filteredProdi.value[0].id;
  }
};

const batal = () => {
    emit('close');
};

const kembaliKeDataMahasiswa = () => {
    emit('close');
};

const simpan = () => {
  // Validasi
  if (!form.value.nama.trim()) {
    alert('Nama mahasiswa harus diisi!');
    return;
  }

  if (!form.value.nim.trim()) {
    alert('NIM mahasiswa harus diisi!');
    return;
  }

  for (const k of props.dataKriteria) {
    const nilai = form.value.nilai[k.id];
    if (nilai === undefined || nilai === null || nilai === '') {
      alert(`Nilai untuk kriteria ${k.nama} harus diisi!`);
      return;
    }
  }

  // Siapkan data untuk disimpan
  const dataToSave = {
    id: form.value.id || Date.now(),
    nim: form.value.nim,
    nama: form.value.nama,
    fakultas_id: form.value.fakultas_id,
    prodi_id: form.value.prodi_id,
    angkatan_id: form.value.angkatan_id,
    semester_id: form.value.semester_id,
    kelas_id: form.value.kelas_id,
    nilai: { ...form.value.nilai }
  };

  emit('save', dataToSave);
};

// Load data kelas
const loadKelasData = () => {
  if (!localStorage.getItem('kelas')) {
    localStorage.setItem('kelas', JSON.stringify(defaultKelas));
  }
  kelasList.value = JSON.parse(localStorage.getItem('kelas') || '[]');
};

const loadMasterData = () => {
  // Load Fakultas
  const savedFakultas = localStorage.getItem('fakultas');
  if (savedFakultas) {
    fakultasList.value = JSON.parse(savedFakultas);
  } else {
    fakultasList.value = [
      { id: 1, nama: "Fakultas Agama Islam", kode: "FAI" },
      { id: 2, nama: "Fakultas Ilmu Pendidikan", kode: "FIP" },
      { id: 3, nama: "Fakultas Sains dan Teknologi", kode: "FST" }
    ];
  }

  // Load Prodi
  const savedProdi = localStorage.getItem('prodi');
  if (savedProdi) {
    prodiList.value = JSON.parse(savedProdi);
  } else {
    prodiList.value = [
      { id: 8, nama: "Informatika", kode: "IF", fakultas_id: 3 }
    ];
  }

  // Load Angkatan
  const savedAngkatan = localStorage.getItem('angkatan');
  if (savedAngkatan) {
    angkatanList.value = JSON.parse(savedAngkatan);
  } else {
    for (let tahun = 2020; tahun <= 2027; tahun++) {
      angkatanList.value.push({ id: tahun - 2019, tahun: tahun });
    }
  }

  // Load Semester
  const savedSemester = localStorage.getItem('semester');
  if (savedSemester) {
    semesterList.value = JSON.parse(savedSemester);
  } else {
    for (let i = 1; i <= 8; i++) {
      semesterList.value.push({ id: i, nama: `Semester ${i}`, urutan: i });
    }
  }
};

const initForm = () => {
  // Inisialisasi nilai kriteria
  props.dataKriteria.forEach(k => {
    form.value.nilai[k.id] = '';
  });

  if (props.editData) {
    isEditMode.value = true;
    form.value.id = props.editData.id;
    form.value.nim = props.editData.nim || '';
    form.value.nama = props.editData.nama || '';
    form.value.fakultas_id = props.editData.fakultas_id || 3;
    form.value.prodi_id = props.editData.prodi_id || 8;
    form.value.angkatan_id = props.editData.angkatan_id || 4;
    form.value.semester_id = props.editData.semester_id || 4;
    form.value.kelas_id = props.editData.kelas_id || 1;

    // Load nilai dari props jika ada
    if (props.editData.nilai) {
      Object.keys(props.editData.nilai).forEach(kId => {
        form.value.nilai[kId] = props.editData.nilai[kId];
      });
    }
  } else {
    isEditMode.value = false;
    form.value.id = null;
    form.value.nim = '';
    form.value.nama = '';
    form.value.fakultas_id = 3;
    form.value.prodi_id = 8;
    form.value.angkatan_id = 4;
    form.value.semester_id = 4;
    form.value.kelas_id = 1;
  }
};

onMounted(() => {
  loadKelasData();
  loadMasterData();
  initForm();
});
</script>

<style scoped>
input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button {
  opacity: 0.3;
}
</style>
