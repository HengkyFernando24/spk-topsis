<!-- TambahSiswa.vue - Versi Revisi -->
<template>
  <div class="p-4 md:p-8 max-w-5xl mx-auto font-['Inter'] antialiased text-gray-900 animate-in fade-in slide-in-from-bottom-4 duration-500">

    <!-- Header -->
    <div class="mb-8">
      <div class="flex items-center gap-3 mb-2">
        <button @click="$emit('close')" class="p-2 hover:bg-gray-100 rounded-xl transition-colors">
          <span class="material-icons text-gray-500">arrow_back</span>
        </button>
        <div class="w-1.5 h-8 bg-emerald-500 rounded-full"></div>
        <h2 class="text-3xl font-black text-emerald-900 tracking-tight">
          {{ isEditMode ? 'Edit Data Mahasiswa' : 'Tambah Data Mahasiswa' }}
        </h2>
      </div>
      <p class="text-gray-500 text-sm max-w-2xl leading-relaxed ml-11">
        {{ isEditMode ? 'Perbarui informasi data mahasiswa.' : 'Input informasi detail mahasiswa baru ke dalam sistem EduDecision.' }}
      </p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
      <!-- Card Header -->
      <div class="bg-gradient-to-r from-emerald-950 to-emerald-900 px-8 py-5">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-xl bg-emerald-800 flex items-center justify-center text-emerald-400">
            <span class="material-icons">assignment_ind</span>
          </div>
          <div>
            <h3 class="font-black text-white text-lg">Formulir Data Mahasiswa</h3>
            <p class="text-emerald-400/70 text-xs mt-0.5">Isi data dengan lengkap dan akurat</p>
          </div>
        </div>
      </div>

      <!-- Form Body -->
      <div class="p-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
          <!-- NIM -->
          <div class="space-y-2">
            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-wider flex items-center gap-2">
              <span class="material-icons text-sm">badge</span>
              NIM <span class="text-red-500">*</span>
            </label>
            <input v-model="form.nim" type="text"
                   class="w-full px-5 py-3.5 bg-gray-50 border-2 border-gray-200 rounded-xl font-mono text-sm font-medium focus:border-emerald-500 focus:bg-white focus:outline-none transition-all"
                   placeholder="Contoh: 202001001">
          </div>

          <!-- Nama Lengkap -->
          <div class="space-y-2">
            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-wider flex items-center gap-2">
              <span class="material-icons text-sm">person</span>
              Nama Lengkap <span class="text-red-500">*</span>
            </label>
            <input v-model="form.nama" type="text"
                   class="w-full px-5 py-3.5 bg-gray-50 border-2 border-gray-200 rounded-xl text-sm font-medium focus:border-emerald-500 focus:bg-white focus:outline-none transition-all"
                   placeholder="Contoh: Ahmad Fauzi">
          </div>

          <!-- Email -->
          <div class="space-y-2">
            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-wider flex items-center gap-2">
              <span class="material-icons text-sm">email</span>
              Email
            </label>
            <input v-model="form.email" type="email"
                   class="w-full px-5 py-3.5 bg-gray-50 border-2 border-gray-200 rounded-xl text-sm font-medium focus:border-emerald-500 focus:bg-white focus:outline-none transition-all"
                   placeholder="contoh@email.com">
          </div>

          <!-- No Telepon -->
          <div class="space-y-2">
            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-wider flex items-center gap-2">
              <span class="material-icons text-sm">phone</span>
              No Telepon
            </label>
            <input v-model="form.telepon" type="tel"
                   class="w-full px-5 py-3.5 bg-gray-50 border-2 border-gray-200 rounded-xl text-sm font-medium focus:border-emerald-500 focus:bg-white focus:outline-none transition-all"
                   placeholder="Contoh: 08123456789">
          </div>
        </div>

        <!-- Informasi Kriteria -->
        <div class="mt-8 p-5 bg-emerald-50/50 rounded-2xl border border-emerald-100">
          <div class="flex items-center gap-2 mb-4">
            <span class="material-icons text-emerald-600">info</span>
            <h4 class="font-bold text-emerald-800 text-sm">Informasi Kriteria Penilaian</h4>
          </div>
          <p class="text-xs text-gray-600 mb-3">
            Sistem menggunakan <strong>{{ dataKriteria.length }} kriteria</strong> untuk penilaian:
          </p>
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
            <div v-for="k in dataKriteria" :key="k.id"
                 class="flex items-center justify-between bg-white rounded-lg px-3 py-2 border border-emerald-100">
              <span class="text-sm font-medium text-gray-700">{{ k.nama }}</span>
              <span class="text-[10px] font-bold px-2 py-0.5 rounded-full"
                    :class="k.tipe === 'benefit' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700'">
                {{ k.tipe === 'benefit' ? 'Benefit +' : 'Cost -' }}
              </span>
            </div>
          </div>
          <div class="mt-3 pt-3 border-t border-emerald-200">
            <div class="flex justify-between text-xs">
              <span class="text-gray-600">Total Bobot:</span>
              <span class="font-mono font-bold" :class="totalBobotValid ? 'text-emerald-600' : 'text-amber-600'">
                {{ totalBobotValue.toFixed(2) }} / 1.00
              </span>
            </div>
          </div>
        </div>

        <!-- Input Nilai Kriteria -->
        <div class="mt-8">
          <div class="flex items-center gap-2 mb-4">
            <span class="material-icons text-emerald-600">fact_check</span>
            <h4 class="font-bold text-gray-800 text-sm">Input Nilai Kriteria</h4>
            <span class="text-[10px] text-gray-400">(Isi sesuai dengan kriteria yang ditentukan)</span>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            <div v-for="kriteria in dataKriteria" :key="kriteria.id" class="space-y-2">
              <label class="block text-[11px] font-bold text-gray-700 flex items-center justify-between">
                <span>{{ kriteria.nama }}</span>
                <span class="text-[9px] text-gray-400">({{ kriteria.tipe }})</span>
              </label>
              <input type="number"
                     step="0.01"
                     v-model="form.nilai[kriteria.id]"
                     class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl text-center font-mono text-sm focus:border-emerald-500 focus:bg-white focus:outline-none transition-all"
                     :placeholder="`Nilai ${kriteria.nama}`">
              <p class="text-[9px] text-gray-400">
                {{ kriteria.tipe === 'benefit' ? 'Semakin besar semakin baik' : 'Semakin kecil semakin baik' }}
              </p>
            </div>
          </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="mt-10 pt-6 border-t border-gray-100 flex justify-end gap-4">
          <button @click="$emit('close')"
                  class="px-8 py-3 rounded-xl border-2 border-gray-200 text-gray-600 font-bold text-sm uppercase tracking-wider hover:bg-gray-50 transition-all">
            Batal
          </button>
          <button @click="simpanData"
                  class="px-8 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm uppercase tracking-wider flex items-center gap-2 transition-all shadow-md active:scale-95">
            <span class="material-icons text-base">{{ isEditMode ? 'save' : 'person_add' }}</span>
            {{ isEditMode ? 'Simpan Perubahan' : 'Tambah Mahasiswa' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';

// Props
const props = defineProps({
  dataKriteria: { type: Array, default: () => [] },
  editData: { type: Object, default: null }
});

// Emits
const emit = defineEmits(['close', 'save']);

// State
const isEditMode = ref(false);
const form = ref({
  id: null,
  nim: '',
  nama: '',
  email: '',
  telepon: '',
  nilai: {}
});

// Computed
const totalBobotValue = computed(() => {
  return props.dataKriteria.reduce((sum, k) => sum + (parseFloat(k.bobot) || 0), 0);
});

const totalBobotValid = computed(() => Math.abs(totalBobotValue.value - 1) < 0.01);

// Methods
const simpanData = () => {
  // Validasi
  if (!form.value.nim.trim()) {
    alert('NIM tidak boleh kosong!');
    return;
  }
  if (!form.value.nama.trim()) {
    alert('Nama tidak boleh kosong!');
    return;
  }

  // Validasi nilai kriteria
  for (const kriteria of props.dataKriteria) {
    const nilai = form.value.nilai[kriteria.id];
    if (nilai === undefined || nilai === null || nilai === '') {
      alert(`Nilai untuk kriteria ${kriteria.nama} harus diisi!`);
      return;
    }
  }

  // Siapkan data untuk disimpan
  const dataToSave = {
    id: form.value.id || Date.now(),
    nim: form.value.nim,
    nama: form.value.nama,
    email: form.value.email,
    telepon: form.value.telepon,
    nilai: { ...form.value.nilai }
  };

  emit('save', dataToSave);
};

// Initialize form jika edit mode
const initForm = () => {
  if (props.editData) {
    isEditMode.value = true;
    form.value = {
      id: props.editData.id,
      nim: props.editData.nim || '',
      nama: props.editData.nama || '',
      email: props.editData.email || '',
      telepon: props.editData.telepon || '',
      nilai: { ...props.editData.nilai }
    };
  } else {
    isEditMode.value = false;
    form.value = {
      id: null,
      nim: '',
      nama: '',
      email: '',
      telepon: '',
      nilai: {}
    };
    // Inisialisasi nilai kriteria dengan default
    props.dataKriteria.forEach(k => {
      form.value.nilai[k.id] = '';
    });
  }
};

onMounted(() => {
  initForm();
});
</script>

<style scoped>
@import url('https://fonts.googleapis.com/icon?family=Material+Icons');
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');

@keyframes fade-in {
  from { opacity: 0; }
  to { opacity: 1; }
}

@keyframes slide-in-from-bottom-4 {
  from { transform: translateY(1rem); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
}

.animate-in { animation-fill-mode: both; }
.fade-in { animation-name: fade-in; }
.slide-in-from-bottom-4 { animation-name: slide-in-from-bottom-4; }
.duration-500 { animation-duration: 500ms; }

/* Number input styling */
input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button {
  opacity: 0.5;
}
</style>
