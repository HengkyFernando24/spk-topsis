<template>
    <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/40 border border-slate-200 overflow-hidden mb-12 relative transition-all duration-500">
        <div class="px-8 py-6 border-b border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4 bg-white">
            <div class="flex items-center gap-3">
                <div class="w-2 h-8 bg-gradient-to-b from-emerald-500 to-green-600 rounded-full"></div>
                <h3 class="font-black text-xl text-slate-800">Matriks Data Mahasiswa</h3>
            </div>
            <span class="text-[10px] font-black bg-slate-100 text-slate-500 px-4 py-1.5 rounded-full border border-slate-200 uppercase tracking-widest whitespace-nowrap">
                {{ statusBadge }}
            </span>
        </div>
        
        <div class="overflow-x-auto bg-white">
            <table class="w-full min-w-max text-left text-sm">
                <thead class="bg-gradient-to-r from-emerald-950 via-green-900 to-emerald-900 text-emerald-50">
                    <tr class="uppercase text-[11px] font-black tracking-widest border-b border-emerald-800 shadow-sm whitespace-nowrap">
                        <th class="px-6 py-5 text-center">No</th>
                        <th class="px-6 py-5">Nama Lengkap</th>
                        <th class="px-6 py-5 text-center"><div class="flex flex-col"><span class="text-emerald-300 text-[9px]">Benefit</span><span>IPK (C1)</span></div></th>
                        <th class="px-6 py-5 text-center"><div class="flex flex-col"><span class="text-emerald-300 text-[9px]">Benefit</span><span>Hadir (C2)</span></div></th>
                        <th class="px-6 py-5 text-center"><div class="flex flex-col"><span class="text-emerald-300 text-[9px]">Benefit</span><span>Prestasi (C3)</span></div></th>
                        <th class="px-6 py-5 text-center"><div class="flex flex-col"><span class="text-red-300/80 text-[9px]">Cost</span><span>Telat (C4)</span></div></th>
                        <th class="px-6 py-5 text-center"><div class="flex flex-col"><span class="text-emerald-300 text-[9px]">Benefit</span><span>Tugas (C5)</span></div></th>
                        <th class="px-6 py-5 text-center">Nilai V</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="dataMahasiswa.length === 0">
                        <td colspan="8" class="p-8 text-center text-sm font-medium text-slate-500 bg-slate-50/50">
                            Belum ada data mahasiswa untuk ditampilkan.
                        </td>
                    </tr>
                    <tr v-for="(siswa, index) in dataMahasiswa" :key="siswa.id" class="border-b border-slate-100 hover:bg-slate-50 transition-colors whitespace-nowrap">
                        <td class="p-4 text-sm text-slate-600 text-center">{{ index + 1 }}</td>
                        <td class="p-4 text-sm font-bold text-slate-800">{{ siswa.nama }}</td>
                        <td class="p-4 text-sm text-slate-600 text-center">{{ siswa.c1 }}</td>
                        <td class="p-4 text-sm text-slate-600 text-center">{{ siswa.c2 }}</td>
                        <td class="p-4 text-sm text-slate-600 text-center">{{ siswa.c3 }}</td>
                        <td class="p-4 text-sm text-slate-600 text-center">{{ siswa.c4 }}</td>
                        <td class="p-4 text-sm text-slate-600 text-center">{{ siswa.c5 }}</td>
                        <td class="p-4 text-sm font-black text-emerald-600 text-center">
                            {{ siswa.v ? siswa.v.toFixed(4) : '0.0000' }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
import { defineProps } from 'vue';

const props = defineProps({
    dataMahasiswa: {
        type: Array,
        default: () => []
    },
    statusBadge: {
        type: String,
        default: 'Menunggu Kalkulasi'
    }
});
</script>