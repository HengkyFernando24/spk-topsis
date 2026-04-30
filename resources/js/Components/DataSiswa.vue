<template>
    <div>
        <div
            v-if="!isFormVisible"
            class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700"
        >
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex items-center gap-5">
                    <div class="w-2 h-12 bg-emerald-600 rounded-full shadow-sm shadow-emerald-100"></div>
                    <div>
                        <h3 class="font-black text-2xl text-slate-800 tracking-tight">
                            Manajemen Data Siswa
                        </h3>
                        <p class="text-sm text-slate-400 font-medium mt-1">
                            {{
                                students.length > 0
                                    ? "Terdapat " + students.length + " siswa terdaftar."
                                    : "Belum ada siswa yang ditambahkan."
                            }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <div class="relative group">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-emerald-600 transition-colors">search</span>
                        <input
                            v-model="searchQuery"
                            type="text"
                            placeholder="Cari nama..."
                            class="pl-12 pr-6 py-3.5 bg-white border border-slate-100 rounded-2xl w-full md:w-64 text-sm font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 transition-all shadow-sm"
                        />
                    </div>
                    <button
                        @click="bukaForm"
                        class="flex items-center gap-2 px-6 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl text-sm font-black tracking-wide transition-all shadow-lg shadow-emerald-100 uppercase active:scale-95"
                    >
                        <span class="material-symbols-outlined !text-xl">person_add</span>
                        <span>Tambah Siswa</span>
                    </button>
                </div>
            </div>

            <div class="bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.04)] border border-slate-50 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50/80">
                            <tr>
                                <th class="px-10 py-6 text-[11px] font-black text-slate-400 uppercase tracking-[0.15em]">Siswa</th>
                                <th class="px-10 py-6 text-[11px] font-black text-slate-400 uppercase tracking-[0.15em]">NISN</th>
                                <th class="px-10 py-6 text-[11px] font-black text-slate-400 uppercase tracking-[0.15em] text-center">Kelas</th>
                                <th class="px-10 py-6 text-[11px] font-black text-slate-400 uppercase tracking-[0.15em] text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <tr
                                v-for="(student, index) in filteredStudents"
                                :key="index"
                                class="hover:bg-emerald-50/30 transition-colors group"
                            >
                                <td class="px-10 py-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-700 font-black text-xs shadow-sm">
                                            {{ student.nama.charAt(0).toUpperCase() }}
                                        </div>
                                        <span class="font-bold text-slate-700">{{ student.nama }}</span>
                                    </div>
                                </td>
                                <td class="px-10 py-6 text-slate-400 font-mono text-sm tracking-tight">{{ student.nisn }}</td>
                                <td class="px-10 py-6 text-center">
                                    <span class="px-4 py-1.5 rounded-xl bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase tracking-wider">
                                        {{ student.kelas }}
                                    </span>
                                </td>
                                <td class="px-10 py-6 text-right">
                                    <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button
                                            @click="hapusSiswa(index)"
                                            class="p-2.5 bg-rose-50 text-rose-500 hover:bg-rose-100 rounded-xl transition-all active:scale-90"
                                        >
                                            <span class="material-symbols-outlined !text-xl">delete</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr v-if="filteredStudents.length === 0">
                                <td colspan="4" class="px-10 py-32 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-24 h-24 bg-slate-50 rounded-[2rem] flex items-center justify-center mb-6 rotate-3">
                                            <span class="material-symbols-outlined !text-5xl text-slate-200">group_off</span>
                                        </div>
                                        <p class="text-slate-500 font-black uppercase tracking-[0.2em] text-xs">Data Tidak Ditemukan</p>
                                        <p class="text-slate-400 text-xs mt-2 max-w-[200px] leading-relaxed">Belum ada siswa yang cocok atau terdaftar.</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <TambahSiswa
            v-else
            @close="isFormVisible = false"
            @submit="simpanSiswaBaru"
        />
    </div>
</template>

<script setup>
import { ref, computed } from "vue";
import TambahSiswa from './TambahSiswa.vue'; 

// --- STATE ---
const isFormVisible = ref(false); // Pengganti isModalOpen
const searchQuery = ref("");
const students = ref([
    { nama: "Budi Santoso", nisn: "0012345678", kelas: "XII-A" },
    { nama: "Siti Aminah", nisn: "0087654321", kelas: "XII-B" },
]);

// --- FUNGSI ---
const bukaForm = () => {
    isFormVisible.value = true;
};

// Fungsi ini akan menerima data yang dilempar (emit) dari file TambahSiswa.vue
const simpanSiswaBaru = (dataBaru) => {
    students.value.push({
        nama: dataBaru.nama,
        nisn: dataBaru.nisn || "-",
        kelas: dataBaru.kelas || "TBA",
    });
    // Tutup form dan kembali ke tabel setelah simpan
    isFormVisible.value = false; 
};

const hapusSiswa = (index) => {
    if (confirm("Apakah Anda yakin ingin menghapus data siswa ini?")) {
        students.value.splice(index, 1);
    }
};

const filteredStudents = computed(() => {
    return students.value.filter((s) =>
        s.nama.toLowerCase().includes(searchQuery.value.toLowerCase()),
    );
});
</script>