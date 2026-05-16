<template>
  <div class="p-4 md:p-8 max-w-7xl mx-auto font-['Inter'] antialiased text-gray-900">
    <!-- Header Section - Disederhanakan -->
    <div class="mb-8 animate-in fade-in slide-in-from-top-4 duration-500">
      <div class="flex items-center gap-3 mb-2">
        <div class="w-1.5 h-8 bg-emerald-500 rounded-full"></div>
        <h2 class="text-3xl font-black text-emerald-900 tracking-tight">Tambah Kriteria Baru</h2>
      </div>
      <p class="text-gray-500 text-sm max-w-2xl leading-relaxed">
        Definisikan parameter penilaian baru untuk sistem pendukung keputusan.
      </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
      <!-- Main Form Card -->
      <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-200 shadow-xl overflow-hidden transition-all duration-300 hover:shadow-2xl">
        <!-- Card Header -->
        <div class="bg-gradient-to-r from-emerald-950 to-emerald-900 px-6 py-5">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-emerald-800 flex items-center justify-center text-emerald-400 border border-emerald-700">
              <span class="material-icons">assignment</span>
            </div>
            <div>
              <h3 class="font-black text-white text-base tracking-wide">Formulir Data Kriteria</h3>
              <p class="text-emerald-400/70 text-xs mt-0.5">Isi data kriteria dengan lengkap dan akurat</p>
            </div>
          </div>
        </div>

        <!-- Form Body -->
        <div class="p-8">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <!-- Kode Kriteria -->
            <div class="space-y-2">
              <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest flex items-center gap-2">
                <span class="material-icons text-sm">tag</span>
                Kode Kriteria
              </label>
              <div class="relative group">
                <input
                  v-model="form.code"
                  type="text"
                  placeholder="Contoh: C1, KRT01"
                  class="w-full bg-gray-50 border-2 border-gray-200 rounded-xl px-5 py-3.5 text-sm font-semibold text-gray-900 focus:border-emerald-500 focus:bg-white focus:outline-none transition-all duration-300 group-hover:border-emerald-300"
                >
                <div class="absolute inset-y-0 right-4 flex items-center text-gray-300 pointer-events-none">
                  <span class="material-icons text-lg">qr_code</span>
                </div>
              </div>
              <p class="text-[10px] text-gray-400 italic">Gunakan kode unik sebagai identitas kriteria.</p>
            </div>

            <!-- Nama Kriteria -->
            <div class="space-y-2">
              <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest flex items-center gap-2">
                <span class="material-icons text-sm">description</span>
                Nama Kriteria
              </label>
              <div class="relative group">
                <input
                  v-model="form.name"
                  type="text"
                  placeholder="Contoh: Nilai Rata-rata"
                  class="w-full bg-gray-50 border-2 border-gray-200 rounded-xl px-5 py-3.5 text-sm font-semibold text-gray-900 focus:border-emerald-500 focus:bg-white focus:outline-none transition-all duration-300 group-hover:border-emerald-300"
                >
                <div class="absolute inset-y-0 right-4 flex items-center text-gray-300 pointer-events-none">
                  <span class="material-icons text-lg">text_fields</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Tipe Kriteria (Benefit / Cost) -->
          <div class="space-y-4 mb-10">
            <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest flex items-center gap-2">
              <span class="material-icons text-sm">trending_up</span>
              Tipe Kriteria
            </label>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <!-- Benefit Option -->
              <div
                @click="form.type = 'benefit'"
                class="relative cursor-pointer p-5 rounded-2xl border-2 transition-all duration-300 group transform hover:scale-105"
                :class="form.type === 'benefit' ? 'bg-emerald-50 border-emerald-500 shadow-lg' : 'bg-white border-gray-200 hover:border-emerald-300 hover:shadow-md'"
              >
                <div class="flex items-center gap-4">
                  <div class="w-12 h-12 rounded-xl flex items-center justify-center transition-all duration-300"
                       :class="form.type === 'benefit' ? 'bg-emerald-500 text-white' : 'bg-emerald-100 text-emerald-600 group-hover:bg-emerald-200'">
                    <span class="material-icons text-2xl">trending_up</span>
                  </div>
                  <div>
                    <h4 class="font-bold text-gray-900 text-sm">Benefit</h4>
                    <p class="text-[10px] text-gray-500 mt-0.5 leading-relaxed">Semakin besar nilai, semakin baik</p>
                  </div>
                  <div v-if="form.type === 'benefit'" class="ml-auto">
                    <span class="material-icons text-emerald-500 animate-bounce">check_circle</span>
                  </div>
                </div>
              </div>

              <!-- Cost Option -->
              <div
                @click="form.type = 'cost'"
                class="relative cursor-pointer p-5 rounded-2xl border-2 transition-all duration-300 group transform hover:scale-105"
                :class="form.type === 'cost' ? 'bg-rose-50 border-rose-500 shadow-lg' : 'bg-white border-gray-200 hover:border-rose-300 hover:shadow-md'"
              >
                <div class="flex items-center gap-4">
                  <div class="w-12 h-12 rounded-xl flex items-center justify-center transition-all duration-300"
                       :class="form.type === 'cost' ? 'bg-rose-500 text-white' : 'bg-rose-100 text-rose-600 group-hover:bg-rose-200'">
                    <span class="material-icons text-2xl">trending_down</span>
                  </div>
                  <div>
                    <h4 class="font-bold text-gray-900 text-sm">Cost</h4>
                    <p class="text-[10px] text-gray-500 mt-0.5 leading-relaxed">Semakin kecil nilai, semakin baik</p>
                  </div>
                  <div v-if="form.type === 'cost'" class="ml-auto">
                    <span class="material-icons text-rose-500 animate-bounce">check_circle</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Nilai Bobot (Importance) -->
          <div class="space-y-6">
            <div class="flex justify-between items-end">
              <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest flex items-center gap-2">
                <span class="material-icons text-sm">balance</span>
                Nilai Bobot (Importance)
              </label>
              <div class="bg-gradient-to-r from-emerald-100 to-emerald-50 text-emerald-800 font-extrabold text-sm px-4 py-2 rounded-xl border border-emerald-200 shadow-inner transition-all duration-300 transform hover:scale-105">
                {{ (form.weight * 100).toFixed(0) }}%
              </div>
            </div>

            <div class="px-2 relative">
              <input
                v-model.number="form.weight"
                type="range"
                min="0"
                max="1"
                step="0.01"
                class="w-full h-2 bg-gray-100 rounded-lg appearance-none cursor-pointer"
                :class="form.type === 'benefit' ? 'accent-emerald-500' : 'accent-rose-500'"
              >
              <div class="flex justify-between mt-3">
                <span class="text-[9px] font-bold text-gray-400 uppercase tracking-wider">0%</span>
                <span class="text-[9px] font-bold text-gray-400 uppercase tracking-wider">25%</span>
                <span class="text-[9px] font-bold text-gray-400 uppercase tracking-wider">50%</span>
                <span class="text-[9px] font-bold text-gray-400 uppercase tracking-wider">75%</span>
                <span class="text-[9px] font-bold text-gray-400 uppercase tracking-wider">100%</span>
              </div>
            </div>

            <div class="bg-blue-50/50 rounded-xl p-4 border border-blue-100">
              <div class="flex items-start gap-2">
                <span class="material-icons text-blue-500 text-sm">info</span>
                <p class="text-[10px] text-gray-600 leading-relaxed">
                  <strong class="font-bold text-gray-800">Tips:</strong> Tentukan seberapa penting kriteria ini terhadap hasil akhir perhitungan.
                  Pastikan total seluruh bobot kriteria berjumlah <strong class="text-emerald-700">1.00 (100%)</strong>.
                </p>
              </div>
            </div>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="px-8 py-6 bg-gray-50/80 border-t border-gray-100 flex flex-col sm:flex-row justify-end gap-3">
          <button
            @click="$emit('batal')"
            class="px-8 py-3 rounded-xl border-2 border-gray-200 font-bold text-gray-600 text-xs uppercase tracking-widest hover:bg-white hover:border-emerald-300 hover:text-emerald-700 transition-all duration-300 active:scale-95"
          >
            Batal
          </button>
          <button
            @click="handleSubmit"
            class="px-8 py-3 rounded-xl bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white font-bold text-xs uppercase tracking-widest flex items-center justify-center gap-2 transition-all duration-300 shadow-md hover:shadow-xl active:scale-95 group"
          >
            <span class="material-icons text-base group-hover:rotate-12 transition-transform duration-300">save</span>
            Simpan Kriteria
          </button>
        </div>
      </div>

      <!-- Sidebar Information / Guide -->
      <div class="space-y-6">
        <!-- Filling Guide -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-lg p-6 overflow-hidden relative transition-all duration-300 hover:shadow-xl animate-in fade-in slide-in-from-bottom-4 duration-500" style="animation-delay: 0.1s">
          <div class="absolute top-0 right-0 p-4">
            <span class="material-icons text-emerald-50/50 text-6xl select-none">menu_book</span>
          </div>

          <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center text-white shadow-lg">
              <span class="material-icons text-lg">lightbulb</span>
            </div>
            <h4 class="font-black text-gray-800 text-sm">Panduan Pengisian</h4>
          </div>

          <ul class="space-y-5">
            <li v-for="(item, index) in guides" :key="index" class="flex gap-3 group">
              <div class="flex-shrink-0 w-7 h-7 rounded-lg bg-emerald-100 text-emerald-700 text-[11px] font-black flex items-center justify-center border border-emerald-200 transition-all duration-300 group-hover:bg-emerald-600 group-hover:text-white group-hover:scale-110">
                {{ index + 1 }}
              </div>
              <p class="text-xs text-gray-600 leading-relaxed">
                <span class="font-black text-gray-800">{{ item.title }}</span><br>
                <span class="text-[11px] text-gray-500">{{ item.text }}</span>
              </p>
            </li>
          </ul>
        </div>

        <!-- Info Card / Illustration -->
        <div class="bg-gradient-to-br from-emerald-950 to-emerald-900 rounded-2xl shadow-xl overflow-hidden group transition-all duration-500 hover:scale-105 animate-in fade-in slide-in-from-bottom-4 duration-500" style="animation-delay: 0.2s">
          <div class="relative h-48 overflow-hidden">
            <!-- Abstract Background -->
            <div class="absolute inset-0 opacity-20" :style="{ backgroundImage: 'radial-gradient(circle, #34d399 2px, transparent 2px)', backgroundSize: '24px 24px' }"></div>

            <!-- Floating Elements -->
            <div class="absolute inset-0 flex items-center justify-center">
              <div class="relative w-32 h-32">
                <div class="absolute inset-0 bg-emerald-500/20 rounded-full animate-ping"></div>
                <div class="absolute inset-2 bg-emerald-400/30 rounded-full animate-pulse"></div>
                <div class="absolute inset-4 bg-emerald-500 rounded-full flex items-center justify-center shadow-2xl">
                  <span class="material-icons text-4xl text-white">analytics</span>
                </div>
              </div>
            </div>

            <!-- Decorative Bars -->
            <div class="absolute bottom-0 left-0 right-0 h-20 bg-gradient-to-t from-emerald-950 to-transparent"></div>
          </div>

          <div class="p-6 relative z-10">
            <p class="text-[11px] text-emerald-300/90 leading-relaxed">
              <strong class="text-emerald-400 font-black">✨ Analisis Algoritma TOPSIS</strong><br>
              Sistem akan secara otomatis menyesuaikan perhitungan dengan kriteria baru yang Anda simpan. Pastikan data yang dimasukkan akurat untuk hasil yang optimal.
            </p>
          </div>
        </div>

        <!-- Quick Stats Preview -->
        <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-lg transition-all duration-300 hover:shadow-xl animate-in fade-in slide-in-from-bottom-4 duration-500" style="animation-delay: 0.3s">
          <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2">
              <span class="material-icons text-emerald-600 text-sm">speed</span>
              <h5 class="font-bold text-gray-700 text-xs uppercase tracking-wider">Preview Bobot</h5>
            </div>
            <div class="text-[10px] font-black text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg">
              {{ (form.weight * 100).toFixed(0) }}%
            </div>
          </div>
          <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden">
            <div class="h-full rounded-full transition-all duration-700 ease-out"
                 :class="form.type === 'benefit' ? 'bg-gradient-to-r from-emerald-500 to-emerald-400' : 'bg-gradient-to-r from-rose-500 to-rose-400'"
                 :style="{ width: `${form.weight * 100}%` }">
            </div>
          </div>
          <p class="text-[10px] text-gray-400 mt-3 text-center">
            Bobot akan digunakan dalam normalisasi matriks keputusan
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive } from 'vue';

// Props diterima dari parent
const props = defineProps({
    jumlahKriteria: { type: Number, default: 0 }
});

// Emits yang sesuai dengan parent (KriteriaManager.vue)
const emit = defineEmits(['simpan', 'batal']);

// Form State dengan auto-generate kode
const form = reactive({
  code: `C${props.jumlahKriteria + 1}`, // Auto-generate kode berdasarkan jumlah kriteria
  name: '',
  type: 'benefit',
  weight: 0.5
});

// Guide Content
const guides = [
  { title: 'Kode Kriteria', text: 'Gunakan kode yang unik dan mudah diingat, contoh: C1, KRT01, PRESTASI' },
  { title: 'Tipe Benefit', text: 'Pilih jika kriteria bersifat positif (prestasi, nilai, kehadiran)' },
  { title: 'Tipe Cost', text: 'Pilih jika kriteria bersifat negatif (jarak, biaya, pelanggaran)' },
  { title: 'Nilai Bobot', text: 'Sesuaikan dengan tingkat kepentingan kriteria (total semua kriteria = 1.0)' }
];

// Methods
const handleSubmit = () => {
  // Validasi input
  if (!form.code || !form.name) {
    alert('⚠️ Harap lengkapi Kode dan Nama Kriteria terlebih dahulu!');
    return;
  }

  // Validasi bobot
  if (form.weight <= 0 || form.weight > 1) {
    alert('⚠️ Bobot harus antara 0.01 sampai 1.00!');
    return;
  }

  // Kirim data ke parent
  emit('simpan', {
    code: form.code,
    name: form.name,
    type: form.type,
    weight: form.weight
  });
};
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

@keyframes bounce {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-5px); }
}

@keyframes pulse {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.5; }
}

@keyframes ping {
  75%, 100% { transform: scale(2); opacity: 0; }
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

.animate-bounce {
  animation: bounce 1s ease-in-out;
}

.animate-pulse {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

.animate-ping {
  animation: ping 1.5s cubic-bezier(0, 0, 0.2, 1) infinite;
}

.duration-500 {
  animation-duration: 500ms;
}

.duration-700 {
  animation-duration: 700ms;
}

/* Range Input Custom Styles */
input[type=range] {
  -webkit-appearance: none;
  background: transparent;
}

input[type=range]::-webkit-slider-thumb {
  -webkit-appearance: none;
  height: 20px;
  width: 20px;
  border-radius: 50%;
  background: #10b981;
  cursor: pointer;
  box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
  margin-top: -8px;
  transition: all 0.3s ease;
}

input[type=range]::-webkit-slider-thumb:hover {
  transform: scale(1.2);
  background: #059669;
}

input[type=range]::-webkit-slider-runnable-track {
  width: 100%;
  height: 4px;
  cursor: pointer;
  background: #e5e7eb;
  border-radius: 4px;
}

input[type=range]:focus {
  outline: none;
}

/* Custom Scrollbar */
::-webkit-scrollbar {
  width: 8px;
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
