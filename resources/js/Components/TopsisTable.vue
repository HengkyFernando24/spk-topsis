<template>
    <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/40 border border-slate-200 overflow-hidden mb-12 relative transition-all duration-500">
        <div class="px-8 py-6 border-b border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4 bg-white">
            <div class="flex items-center gap-3">
                <div class="w-2 h-8 bg-gradient-to-b from-emerald-500 to-green-600 rounded-full"></div>
                <h3 class="font-black text-xl text-slate-800">Peringkat Mahasiswa Terbaik</h3>
            </div>
            <span class="text-[10px] font-black rounded-full px-4 py-1.5 uppercase tracking-widest whitespace-nowrap"
                  :class="statusBadge === 'Selesai Dihitung' ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' : 'bg-amber-100 text-amber-700 border border-amber-200'">
                {{ statusBadge }}
            </span>
        </div>

        <div class="overflow-x-auto bg-white">
            <table class="w-full min-w-max text-left text-sm">
                <thead class="bg-gradient-to-r from-emerald-950 via-green-900 to-emerald-900 text-emerald-50">
                    <tr class="uppercase text-[11px] font-black tracking-widest border-b border-emerald-800 shadow-sm whitespace-nowrap">
                        <th class="px-6 py-5 text-center w-16">Rank</th>
                        <th class="px-6 py-5 text-left">Nama Mahasiswa</th>
                        <th v-for="kriteria in dataKriteria" :key="kriteria.id"
                            class="px-6 py-5 text-center">
                            <div class="flex flex-col items-center gap-1">
                                <span :class="kriteria.tipe === 'benefit' ? 'text-emerald-300' : 'text-red-300/80'"
                                      class="text-[9px] font-bold uppercase tracking-wider">
                                    {{ kriteria.tipe === 'benefit' ? 'Benefit' : 'Cost' }}
                                </span>
                                <span class="font-bold">{{ kriteria.nama }}</span>
                                <span class="text-[9px] text-emerald-400/70">({{ kriteria.bobot * 100 }}%)</span>
                            </div>
                        </th>
                        <th class="px-6 py-5 text-center">
                            <div class="flex flex-col items-center gap-1">
                                <span class="text-yellow-300 text-[9px] font-bold uppercase tracking-wider">Preferensi</span>
                                <span class="font-bold">Nilai V</span>
                            </div>
                        </th>
                        <th class="px-6 py-5 text-center w-28">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="sortedMahasiswa.length === 0">
                        <td :colspan="3 + dataKriteria.length" class="p-12 text-center text-sm font-medium text-slate-500 bg-slate-50/50">
                            <div class="flex flex-col items-center gap-3">
                                <span class="material-icons text-4xl text-slate-300">school</span>
                                <p>Belum ada data mahasiswa untuk ditampilkan.</p>
                            </div>
                        </td>
                    </tr>
                    <tr v-for="(siswa, index) in sortedMahasiswa" :key="siswa.id"
                        class="border-b border-slate-100 transition-all duration-300"
                        :class="index === 0 ? 'bg-emerald-50/40 hover:bg-emerald-50/60' : 'hover:bg-slate-50'">
                        <td class="p-4 text-sm text-center">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center font-black text-sm mx-auto"
                                 :class="getRankClass(index)">
                                {{ index + 1 }}
                            </div>
                        </td>
                        <td class="p-4 text-sm font-bold" :class="index === 0 ? 'text-emerald-800' : 'text-slate-800'">
                            <div class="flex items-center gap-2">
                                <span v-if="index === 0" class="text-yellow-500">🏆</span>
                                {{ siswa.nama }}
                            </div>
                        </td>
                        <td v-for="kriteria in dataKriteria" :key="kriteria.id"
                            class="p-4 text-sm text-center font-mono">
                            <span :class="getNilaiClass(kriteria, index)">
                                {{ getNilaiMahasiswa(siswa.id, kriteria.id) }}
                            </span>
                        </td>
                        <td class="p-4 text-sm text-center">
                            <div class="flex items-center justify-center gap-2">
                                <div class="w-20 bg-gray-200 rounded-full h-1.5 overflow-hidden">
                                    <div class="h-full rounded-full transition-all duration-500"
                                         :class="index === 0 ? 'bg-emerald-500' : 'bg-emerald-400'"
                                         :style="{ width: `${(siswa.v || 0) * 100}%` }">
                                    </div>
                                </div>
                                <span class="font-black text-emerald-600 text-sm">
                                    {{ ((siswa.v || 0) * 100).toFixed(2) }}%
                                </span>
                            </div>
                        </td>
                        <td class="p-4 text-sm text-center">
                            <span v-if="index === 0" class="inline-flex items-center gap-1 bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-xs font-bold">
                                <span class="material-icons text-sm">stars</span> Terbaik
                            </span>
                            <span v-else class="inline-flex items-center gap-1 bg-gray-100 text-gray-500 px-3 py-1 rounded-full text-xs font-bold">
                                <span class="material-icons text-sm">filter_alt</span> Peringkat {{ index + 1 }}
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Footer Info -->
        <div class="px-8 py-4 bg-slate-50/80 border-t border-slate-100 flex justify-between items-center text-[10px] text-slate-500">
            <div class="flex items-center gap-4">
                <span class="flex items-center gap-1">
                    <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                    <span>Benefit (Semakin besar semakin baik)</span>
                </span>
                <span class="flex items-center gap-1">
                    <span class="w-3 h-3 rounded-full bg-rose-500"></span>
                    <span>Cost (Semakin kecil semakin baik)</span>
                </span>
            </div>
            <div>
                * Berdasarkan perhitungan metode TOPSIS
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    dataMahasiswa: {
        type: Array,
        default: () => []
    },
    dataKriteria: {
        type: Array,
        default: () => []
    },
    statusBadge: {
        type: String,
        default: 'Menunggu Kalkulasi'
    }
});

// Urutkan mahasiswa berdasarkan nilai V (tertinggi ke terendah)
const sortedMahasiswa = computed(() => {
    return [...props.dataMahasiswa].sort((a, b) => (b.v || 0) - (a.v || 0));
});

// Fungsi untuk mendapatkan nilai mahasiswa per kriteria
const getNilaiMahasiswa = (siswaId, kriteriaId) => {
    const siswa = props.dataMahasiswa.find(s => s.id === siswaId);
    if (siswa && siswa.nilai && siswa.nilai[kriteriaId] !== undefined) {
        const nilai = siswa.nilai[kriteriaId];
        // Format nilai: jika desimal, tampilkan 2 angka di belakang koma
        return typeof nilai === 'number' ? nilai.toFixed(2) : nilai;
    }
    return '-';
};

// Kelas untuk styling rank
const getRankClass = (index) => {
    if (index === 0) return 'bg-emerald-500 text-white shadow-md';
    if (index === 1) return 'bg-gray-400 text-white';
    if (index === 2) return 'bg-orange-400 text-white';
    return 'bg-gray-100 text-gray-600';
};

// Kelas untuk styling nilai berdasarkan tipe kriteria dan peringkat
const getNilaiClass = (kriteria, index) => {
    const baseClass = 'font-medium';
    if (index === 0) return `${baseClass} text-emerald-700 font-bold`;
    return `${baseClass} text-slate-600`;
};
</script>

<style scoped>
/* Custom scrollbar untuk tabel */
.overflow-x-auto::-webkit-scrollbar {
    height: 6px;
}
.overflow-x-auto::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}
.overflow-x-auto::-webkit-scrollbar-thumb {
    background: #10b981;
    border-radius: 10px;
}
.overflow-x-auto::-webkit-scrollbar-thumb:hover {
    background: #059669;
}
</style>
