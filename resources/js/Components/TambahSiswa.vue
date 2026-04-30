<template>
    <div class="max-w-5xl mx-auto p-6 md:p-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
        
        <div class="mb-8">
            <div class="flex items-center text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-3">
                <span @click="$emit('close')" class="hover:text-emerald-600 cursor-pointer transition-colors">Data Siswa</span>
                <span class="material-symbols-outlined mx-2 !text-sm">chevron_right</span>
                <span class="text-emerald-600">Tambah Mahasiswa</span>
            </div>
            
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-black text-slate-800 tracking-tight">Tambah Data Siswa</h1>
                    <p class="text-slate-500 text-sm font-medium mt-1.5">Input informasi detail mahasiswa baru ke dalam sistem EduDecision.</p>
                </div>
                </div>
        </div>

        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
            
            <div class="px-8 py-6 border-b border-slate-50 flex items-center gap-3 bg-slate-50/50">
                <span class="material-symbols-outlined text-emerald-600">assignment</span>
                <h2 class="text-lg font-bold text-slate-800">Formulir Registrasi</h2>
            </div>

            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                    
                    <div class="space-y-2.5">
                        <label class="text-[11px] font-black uppercase tracking-[0.15em] text-slate-500">Nama Lengkap</label>
                        <div class="relative group">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-emerald-600 transition-colors">person</span>
                            <input v-model="form.nama" type="text" placeholder="Masukkan nama sesuai identitas" class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border border-transparent rounded-xl text-sm font-bold text-slate-700 focus:bg-white focus:border-emerald-200 focus:ring-4 focus:ring-emerald-50 outline-none transition-all placeholder:font-medium placeholder:text-slate-400" />
                        </div>
                    </div>

                    <div class="space-y-2.5">
                        <label class="text-[11px] font-black uppercase tracking-[0.15em] text-slate-500">Tanggal Lahir</label>
                        <div class="relative group">
                            <input v-model="form.tanggalLahir" type="date" class="w-full px-4 py-3.5 bg-slate-50 border border-transparent rounded-xl text-sm font-bold text-slate-700 focus:bg-white focus:border-emerald-200 focus:ring-4 focus:ring-emerald-50 outline-none transition-all" />
                        </div>
                    </div>

                    <div class="space-y-2.5">
                        <label class="text-[11px] font-black uppercase tracking-[0.15em] text-slate-500">NISN</label>
                        <div class="relative group">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-emerald-600 transition-colors">badge</span>
                            <input v-model="form.nisn" type="text" placeholder="10 Digit Nomor Induk Siswa Nasional" class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border border-transparent rounded-xl text-sm font-bold text-slate-700 focus:bg-white focus:border-emerald-200 focus:ring-4 focus:ring-emerald-50 outline-none transition-all placeholder:font-medium placeholder:text-slate-400" />
                        </div>
                    </div>

                    <div class="space-y-2.5 md:row-span-2">
                        <label class="text-[11px] font-black uppercase tracking-[0.15em] text-slate-500">Alamat</label>
                        <div class="relative group h-full">
                            <span class="material-symbols-outlined absolute left-4 top-4 text-slate-400 group-focus-within:text-emerald-600 transition-colors">location_on</span>
                            <textarea v-model="form.alamat" placeholder="Alamat lengkap domisili" class="w-full h-full min-h-[120px] pl-12 pr-4 pt-4 pb-4 bg-slate-50 border border-transparent rounded-xl text-sm font-bold text-slate-700 focus:bg-white focus:border-emerald-200 focus:ring-4 focus:ring-emerald-50 outline-none transition-all resize-none placeholder:font-medium placeholder:text-slate-400"></textarea>
                        </div>
                    </div>

                    <div class="space-y-2.5">
                        <label class="text-[11px] font-black uppercase tracking-[0.15em] text-slate-500">Kelas</label>
                        <div class="relative group">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-emerald-600 transition-colors">school</span>
                            <select v-model="form.kelas" class="w-full pl-12 pr-10 py-3.5 bg-slate-50 border border-transparent rounded-xl text-sm font-bold text-slate-700 focus:bg-white focus:border-emerald-200 focus:ring-4 focus:ring-emerald-50 outline-none transition-all appearance-none cursor-pointer">
                                <option value="" disabled selected>Pilih Kelas</option>
                                <option value="XII-MIPA 1">XII-MIPA 1</option>
                                <option value="XII-MIPA 2">XII-MIPA 2</option>
                                <option value="XII-IPS 1">XII-IPS 1</option>
                            </select>
                            <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">expand_more</span>
                        </div>
                    </div>

                </div>

                <div class="mt-10 flex justify-end items-center gap-6 pt-6 border-t border-slate-50">
                    <button @click="$emit('close')" class="text-sm font-bold text-slate-500 hover:text-slate-800 transition-colors">
                        Batal
                    </button>
                    <button @click="simpanData" class="flex items-center gap-2 px-8 py-3.5 bg-[#0e603a] hover:bg-[#0a472a] text-white rounded-xl text-sm font-bold transition-all shadow-lg shadow-emerald-900/20 active:scale-95">
                        <span class="material-symbols-outlined !text-[18px]">save</span>
                        <span>Simpan Data</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="mt-12 flex justify-between items-center text-[10px] font-black text-slate-400 tracking-[0.15em] uppercase">
            <div>
                <span>EduDecision © 2024</span>
                <span class="mx-3 text-slate-300">•</span>
                <span>Institutional Security Protocol Active</span>
            </div>
            <div class="flex items-center gap-1.5 text-emerald-600">
                <span class="material-symbols-outlined !text-[14px]">lock</span>
                <span>Encrypted Connection</span>
            </div>
        </div>

    </div>
</template>

<script setup>
import { ref } from 'vue';

// Definisikan event apa saja yang bisa dilempar ke komponen parent (DataSiswa.vue)
const emit = defineEmits(['close', 'submit']);

const form = ref({
    nama: '',
    tanggalLahir: '',
    nisn: '',
    alamat: '',
    kelas: ''
});

const simpanData = () => {
    // Validasi sederhana
    if (!form.value.nama) {
        alert("Nama lengkap wajib diisi!");
        return;
    }

    // Lempar data form ke komponen DataSiswa menggunakan event 'submit'
    emit('submit', { ...form.value });
    
    // Reset form setelah simpan (opsional)
    form.value = { nama: '', tanggalLahir: '', nisn: '', alamat: '', kelas: '' };
};
</script>