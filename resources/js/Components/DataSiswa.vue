<template>
    <div class="space-y-8 animate-in fade-in duration-700">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-5">
                <div class="w-2 h-12 bg-emerald-500 rounded-full"></div>
                <div>
                    <h3 class="font-black text-2xl text-slate-800 tracking-tight">Manajemen Data Siswa</h3>
                    <p class="text-sm text-slate-400 font-medium mt-1">
                        {{ students.length > 0 ? 'Terdapat ' + students.length + ' siswa terdaftar.' : 'Belum ada siswa yang ditambahkan.' }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <div class="relative group">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-emerald-500 transition-colors">search</span>
                    <input 
                        v-model="searchQuery"
                        type="text" 
                        placeholder="Cari nama..." 
                        class="pl-12 pr-6 py-3.5 bg-white border border-slate-100 rounded-2xl w-full md:w-64 text-sm font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 transition-all shadow-sm"
                    />
                </div>
                <button @click="tambahSiswaManual" class="flex items-center gap-2 px-6 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl text-sm font-black tracking-wide transition-all shadow-lg shadow-emerald-200 uppercase">
                    <span class="material-symbols-outlined !text-xl">person_add</span>
                    <span>Tambah Siswa</span>
                </button>
            </div>
        </div>

        <div class="bg-white rounded-[3rem] shadow-[0_30px_60px_rgba(0,0,0,0.03)] border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50/50">
                        <tr>
                            <th class="px-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Siswa</th>
                            <th class="px-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">NISN</th>
                            <th class="px-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Kelas</th>
                            <th class="px-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr v-for="(student, index) in filteredStudents" :key="index" class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-10 py-6 font-bold text-slate-700">{{ student.nama }}</td>
                            <td class="px-10 py-6 text-slate-400 font-mono text-sm">{{ student.nisn }}</td>
                            <td class="px-10 py-6 text-center">
                                <span class="px-4 py-1.5 rounded-full bg-slate-100 text-slate-600 text-[11px] font-black uppercase">{{ student.kelas }}</span>
                            </td>
                            <td class="px-10 py-6 text-right">
                                <button @click="hapusSiswa(index)" class="p-2.5 bg-rose-50 text-rose-600 hover:bg-rose-100 rounded-xl transition-all">
                                    <span class="material-symbols-outlined !text-xl">delete</span>
                                </button>
                            </td>
                        </tr>
                        <tr v-if="filteredStudents.length === 0">
                            <td colspan="4" class="px-10 py-32 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                        <span class="material-symbols-outlined !text-4xl text-slate-200">person_off</span>
                                    </div>
                                    <p class="text-slate-400 font-bold uppercase tracking-[0.2em] text-xs">Belum ada data siswa, blay!</p>
                                    <p class="text-slate-300 text-[10px] mt-2">Klik tombol "Tambah Siswa" untuk mengisi secara manual.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const searchQuery = ref('');
// LIST KOSONG: Biar lo bisa isi sendiri blay
const students = ref([]);

// Fungsi buat nambah data manual (pake prompt biar simpel)
const tambahSiswaManual = () => {
    const nama = prompt("Masukkan Nama Siswa:");
    if (!nama) return;
    const nisn = prompt("Masukkan NISN:");
    const kelas = prompt("Masukkan Kelas (Contoh: XII-A):");

    students.value.push({
        nama: nama,
        nisn: nisn || '-',
        kelas: kelas || 'TBA'
    });
};

const hapusSiswa = (index) => {
    if(confirm('Yakin mau hapus data ini, blay?')) {
        students.value.splice(index, 1);
    }
};

const filteredStudents = computed(() => {
    return students.value.filter(s => 
        s.nama.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});
</script>