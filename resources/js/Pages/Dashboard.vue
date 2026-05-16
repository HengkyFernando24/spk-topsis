<template>
    <div class="flex min-h-screen bg-[#F8FAFC] font-['Plus_Jakarta_Sans'] antialiased">

        <!-- SIDEBAR -->
        <aside class="fixed left-0 top-0 h-full w-72 z-50 bg-gradient-to-b from-emerald-950 via-green-950 to-emerald-950 text-white flex flex-col py-6 shadow-2xl no-print">
            <div class="absolute top-0 left-0 w-full h-64 bg-emerald-400 opacity-5 blur-[100px] rounded-full pointer-events-none"></div>
                <div class="px-8 mb-10 flex items-center gap-4 relative z-10">
                    <!-- Logo Analytics -->
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-700 flex items-center justify-center text-white shadow-lg">
                        <span class="material-symbols-outlined !text-3xl">analytics</span>
                    </div>
                    <div>
                        <h1 class="text-xl font-black text-white tracking-tighter leading-none">Edu TOPSIS</h1>
                        <p class="text-emerald-400 text-[9px] font-bold uppercase tracking-widest mt-1 opacity-80">Sistem Penunjang Keputusan</p>
                    </div>
                </div>
            <nav class="flex-1 flex flex-col gap-2 px-4 overflow-y-auto relative z-10 custom-scrollbar">
                <p class="px-4 text-[10px] font-black text-emerald-500/40 uppercase tracking-[0.3em] mb-4">Menu Utama</p>
                <button v-for="(menu, index) in menus" :key="index" @click="handleMenuClick(menu.id)"
                    class="flex items-center px-5 py-4 group cursor-pointer transition-all duration-300 border-l-[4px] rounded-2xl text-left w-full"
                    :class="activeTab === menu.id ? 'bg-white/10 text-white border-emerald-400 shadow-xl backdrop-blur-md' : 'text-emerald-100/60 border-transparent hover:text-white hover:bg-white/5'">
                    <span class="material-symbols-outlined mr-4 text-xl">{{ menu.icon }}</span>
                    <span class="text-sm font-bold tracking-wide">{{ menu.title }}</span>
                </button>
            </nav>
            <div class="mt-auto px-4 border-t border-emerald-800/30 pt-8 relative z-10">
                <button class="w-full text-left text-rose-400 hover:bg-rose-500/10 flex items-center px-5 py-3.5 transition-all rounded-2xl">
                    <span class="material-symbols-outlined mr-4 text-xl">logout</span>
                    <span class="text-sm font-bold">Keluar Sistem</span>
                </button>
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0 md:ml-72 transition-all duration-500">

            <!-- HEADER -->
            <header class="h-24 bg-white/70 backdrop-blur-2xl border-b border-slate-200/50 flex items-center justify-between px-12 sticky top-0 z-40">
                <div>
                    <h2 class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.4em] mb-1">Administrator</h2>
                    <p class="text-2xl font-extrabold text-slate-800 tracking-tight capitalize">{{ formattedTitle }} Overview</p>
                </div>
                <div class="flex items-center gap-6">
                    <div class="w-12 h-12 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-400 hover:text-emerald-600 transition-all cursor-pointer">
                        <span class="material-symbols-outlined">notifications</span>
                    </div>
                </div>
            </header>

            <main class="p-12 space-y-12 overflow-y-auto custom-scrollbar">

                <!-- FORM TAMBAH / EDIT SISWA -->
                <div v-if="showStudentForm" class="animate-in fade-in duration-700">
                    <TambahSiswa
                        :data-kriteria="criteria"
                        :edit-data="editingStudent"
                        @close="closeStudentForm"
                        @save="saveStudent"
                    />
                </div>

                <!-- DASHBOARD TAB -->
                <div v-else-if="activeTab === 'dashboard'" class="space-y-8 animate-in fade-in duration-700">

                    <!-- Welcome Banner -->
                    <div class="bg-gradient-to-r from-emerald-600 to-emerald-800 rounded-2xl p-6 text-white relative overflow-hidden shadow-lg">
                        <div class="absolute right-0 top-0 opacity-10">
                            <span class="material-symbols-outlined text-8xl">analytics</span>
                        </div>
                        <div class="relative z-10">
                            <h2 class="text-xl font-black">Selamat Datang, Administrator!</h2>
                            <p class="text-emerald-100/80 text-sm mt-1">Sistem Pendukung Keputusan Penentuan Mahasiswa Terbaik dengan Metode TOPSIS</p>
                            <div class="flex flex-wrap gap-2 mt-4">
                                <span class="text-[10px] bg-white/20 rounded-full px-3 py-1">📊 TOPSIS Method</span>
                                <span class="text-[10px] bg-white/20 rounded-full px-3 py-1">🎯 Akurasi Tinggi</span>
                                <span class="text-[10px] bg-white/20 rounded-full px-3 py-1">⚡ Real-time Analysis</span>
                            </div>
                        </div>
                    </div>

                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
                        <StatsCard
                            v-for="(stat, i) in stats"
                            :key="i"
                            :title="stat.title"
                            :value="stat.value"
                            :icon="stat.icon"
                            :bg-icon="stat.bgIcon"
                            :color="stat.color"
                            :badge="stat.badge"
                            :trend="stat.trend"
                            :trend-color="stat.trendColor"
                        />
                    </div>

                    <!-- Chart & Activity -->
                    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                        <!-- Chart -->
                        <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                            <div class="flex justify-between items-center mb-6">
                                <div>
                                    <h3 class="text-lg font-black text-slate-800">Statistik Performa Mahasiswa</h3>
                                    <p class="text-xs text-slate-400 mt-1">Perbandingan skor preferensi (V) tertinggi</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="flex items-center gap-2">
                                        <span class="w-3 h-3 bg-emerald-500 rounded-full"></span>
                                        <span class="text-[10px] font-bold text-slate-400 uppercase">Skor V</span>
                                    </div>
                                </div>
                            </div>

                            <div class="relative h-64">
                                <div class="absolute inset-0 flex flex-col justify-between pointer-events-none z-0">
                                    <div v-for="i in 4" :key="i" class="w-full border-t border-slate-100 border-dashed"></div>
                                </div>
                                <div class="relative h-full flex items-end justify-between gap-2 z-10 pt-4">
                                    <div v-for="(bar, i) in chartData" :key="i" class="relative w-full flex flex-col items-center group">
                                        <div class="absolute -top-8 opacity-0 group-hover:opacity-100 transition-all bg-slate-800 text-white text-[10px] font-bold py-1 px-2 rounded-lg whitespace-nowrap z-20">
                                            {{ bar.value }}
                                        </div>
                                        <div class="w-full flex justify-center">
                                            <div :style="{ height: Math.max(bar.value * 100, 4) + 'px' }"
                                                 class="w-10 bg-gradient-to-t from-emerald-500 to-emerald-400 rounded-t-lg hover:from-emerald-600 hover:to-emerald-500 transition-all duration-300 cursor-pointer shadow-md">
                                            </div>
                                        </div>
                                        <span class="text-[10px] font-semibold text-slate-500 mt-3 truncate w-full text-center">
                                            {{ bar.name }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Activity -->
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-lg font-black text-slate-800">Aktivitas Terbaru</h3>
                                <span class="text-[10px] bg-emerald-100 text-emerald-600 px-2 py-0.5 rounded-full font-semibold">Live</span>
                            </div>
                            <div class="space-y-4">
                                <div v-for="(activity, idx) in recentActivities" :key="idx" class="flex items-start gap-3 group hover:bg-slate-50 p-2 rounded-xl transition-all">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center" :class="activity.bgColor">
                                        <span class="material-symbols-outlined text-sm" :class="activity.iconColor">{{ activity.icon }}</span>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-xs font-semibold text-slate-700">{{ activity.title }}</p>
                                        <p class="text-[10px] text-slate-400 mt-0.5">{{ activity.time }}</p>
                                    </div>
                                </div>
                            </div>
                            <button class="w-full mt-4 py-2.5 bg-slate-50 text-slate-500 text-[10px] font-bold uppercase tracking-wider rounded-xl hover:bg-slate-100 transition-all">
                                Lihat Semua Aktivitas
                            </button>
                        </div>
                    </div>

                    <!-- ========== TAMBAHAN: RINGKASAN NILAI PER KRITERIA ========== -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-lg font-black text-slate-800 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-emerald-600">assessment</span>
                                    Ringkasan Nilai Per Kriteria
                                </h3>
                                <p class="text-xs text-slate-400 mt-1">Rata-rata nilai mahasiswa untuk setiap kriteria</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                            <!-- IPK - Warna Biru -->
                            <div v-for="(kriteria, idx) in criteria" :key="kriteria.id"
                                class="rounded-xl p-4 border transition-all duration-300 hover:shadow-md hover:-translate-y-1"
                                :class="getKriteriaCardClass(idx)">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-[11px] font-black uppercase tracking-wider text-white">{{ kriteria.nama }}</span>
                                    <span class="text-[9px] font-bold px-2 py-0.5 rounded-full bg-white/30 text-white">
                                        {{ kriteria.tipe === 'benefit' ? 'Benefit' : 'Cost' }}
                                    </span>
                                </div>
                                <div class="flex items-baseline gap-1">
                                    <span class="text-3xl font-black text-white">{{ getRataRataNilai(kriteria.id) }}</span>
                                    <span class="text-[10px] text-white/70">/ 100</span>
                                </div>
                                <div class="mt-3 w-full bg-white/30 rounded-full h-1.5 overflow-hidden">
                                    <div class="h-full rounded-full transition-all duration-500 bg-white"
                                        :style="{ width: `${(getRataRataNilai(kriteria.id) / 100) * 100}%` }">
                                    </div>
                                </div>
                                <p class="text-[9px] text-white/70 mt-2">
                                    Bobot: {{ (kriteria.bobot * 100).toFixed(0) }}%
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- ========== TABEL DETAIL NILAI MAHASISWA - ELEGAN & PROFESIONAL ========== -->
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="bg-gray-50 px-5 py-3 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-semibold text-gray-800 text-sm flex items-center gap-2">
                            <span class="material-icons text-emerald-600 text-sm">table_chart</span>
                            Detail Nilai Mahasiswa
                            </h3>
                            <p class="text-[10px] text-gray-400 mt-0.5">Nilai mentah setiap mahasiswa untuk semua kriteria</p>
                        </div>
                        <div class="bg-emerald-50 text-emerald-700 text-[10px] font-medium px-2 py-0.5 rounded">
                            Total: {{ students.length }} Mahasiswa
                        </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200 text-[10px] font-semibold text-gray-500 uppercase">
                            <th class="px-4 py-2 text-center w-12">No</th>
                            <th class="px-4 py-2 text-left">Nama Mahasiswa</th>
                            <th v-for="kriteria in criteria" :key="kriteria.id" class="px-3 py-2 text-center min-w-[70px]">
                                {{ kriteria.nama }}
                            </th>
                            <th class="px-3 py-2 text-center w-24">Nilai V</th>
                            <th class="px-3 py-2 text-center w-24">Peringkat</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr v-for="(siswa, index) in sortedStudentsByV" :key="siswa.id"
                                class="hover:bg-gray-50 transition-colors"
                                :class="index === 0 ? 'bg-emerald-50/20' : ''">
                            <td class="px-4 py-2 text-center font-medium text-gray-500">{{ index + 1 }}</td>
                            <td class="px-4 py-2 font-medium text-gray-800">{{ siswa.nama }}</td>
                            <td v-for="kriteria in criteria" :key="kriteria.id" class="px-3 py-2 text-center text-gray-600">
                                {{ getNilaiMahasiswa(siswa.id, kriteria.id) }}
                            </td>
                            <td class="px-3 py-2 text-center">
                                <span class="font-semibold" :class="index === 0 ? 'text-emerald-600' : 'text-gray-700'">
                                {{ ((siswa.v || 0) * 100).toFixed(2) }}%
                                </span>
                            </td>
                            <td class="px-3 py-2 text-center">
                                <span v-if="index === 0" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-semibold bg-emerald-100 text-emerald-700">
                                <span class="material-icons text-xs">stars</span> Terbaik
                                </span>
                                <span v-else class="text-gray-400 text-[10px]">Peringkat {{ index + 1 }}</span>
                            </td>
                            </tr>
                            <tr v-if="students.length === 0">
                            <td :colspan="3 + criteria.length" class="px-4 py-12 text-center text-gray-400">
                                <div class="flex flex-col items-center gap-2">
                                <span class="material-icons text-4xl">school</span>
                                <p>Belum ada data mahasiswa</p>
                                <p class="text-xs">Silakan tambahkan data mahasiswa melalui menu Data Siswa</p>
                                </div>
                            </td>
                            </tr>
                        </tbody>
                        </table>
                    </div>
                    </div>

                    <!-- Topsis Table -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                        <div class="px-6 py-5 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                            <div>
                                <h3 class="font-black text-xl text-slate-800 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-emerald-600">emoji_events</span>
                                    Peringkat 5 Mahasiswa Terbaik
                                </h3>
                                <p class="text-xs text-slate-400 mt-1">Berdasarkan hasil perhitungan metode TOPSIS</p>
                            </div>
                            <button class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-[10px] font-bold transition-all flex items-center gap-1 shadow-md">
                                <span class="material-symbols-outlined text-sm">download</span>
                                Export PDF
                            </button>
                        </div>
                        <TopsisTable v-if="rankedStudents.length > 0 && rankedStudents[0]?.v !== undefined" :data-mahasiswa="rankedStudents" />
                        <div v-else-if="rankedStudents.length > 0 && rankedStudents[0]?.v === undefined" class="p-16 text-center">
                            <div class="w-20 h-20 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <span class="material-symbols-outlined text-3xl text-amber-500">calculate</span>
                            </div>
                            <p class="text-slate-600 font-medium">Belum Ada Hasil Perhitungan</p>
                            <p class="text-xs text-slate-400 mt-1">Silakan buka menu <strong class="text-emerald-600">"Proses Perhitungan"</strong> dan klik tombol "Mulai Perhitungan TOPSIS"</p>
                        </div>
                        <div v-else class="p-16 text-center">
                            <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <span class="material-symbols-outlined text-3xl text-slate-400">school</span>
                            </div>
                            <p class="text-slate-500 font-medium">Belum ada data mahasiswa</p>
                            <p class="text-xs text-slate-400 mt-1">Silakan tambahkan data mahasiswa terlebih dahulu</p>
                        </div>
                    </div>
                </div>

                <!-- MANAJEMEN KRITERIA TAB -->
                <div v-else-if="activeTab === 'manajemen-kriteria'" class="animate-in fade-in slide-in-from-bottom-6 duration-700">
                    <KriteriaManager
                        :data-kriteria="criteria"
                        @update="updateCriteria"
                        @go-home="() => activeTab = 'dashboard'"
                        @go-kriteria="() => activeTab = 'manajemen-kriteria'"
                    />
                </div>

                <!-- DATA SISWA TAB -->
                <div v-else-if="activeTab === 'data-siswa'" class="animate-in fade-in duration-700">
                    <DataSiswa
                        :students-data="students"
                        :data-kriteria="criteria"
                        @update="updateStudents"
                        @navigate-to-tambah="handleTambahSiswa"
                        @navigate-to-edit="handleEditSiswa"
                    />
                </div>

                <!-- PROSES PERHITUNGAN TAB -->
                <div v-else-if="activeTab === 'proses-perhitungan'" class="animate-in fade-in duration-700">
                    <Perhitungan
                        :data-mahasiswa="students"
                        :data-kriteria="criteria"
                        :data-penilaian="dataPenilaian"
                        ref="perhitunganComponent"
                        @update-hasil="handleHasilPerhitungan"
                    />
                </div>
                    <!-- LAPORAN TAB -->
                <div v-else-if="activeTab === 'laporan'" class="animate-in fade-in duration-700">
                <Laporan
                    :data-mahasiswa="students"
                    :data-kriteria="criteria"
                />
                </div>

            </main>
        </div>
    </div>

    <!-- TOAST NOTIFICATION -->
    <div v-if="toast.show" class="fixed bottom-6 right-6 z-50">
        <div :class="[
            'flex items-center gap-3 px-4 py-3 rounded-xl shadow-2xl border-l-4 min-w-[280px]',
            toast.type === 'success' ? 'bg-white border-emerald-500' :
            toast.type === 'error' ? 'bg-white border-red-500' :
            'bg-white border-blue-500'
        ]">
            <div :class="[
                'w-8 h-8 rounded-full flex items-center justify-center',
                toast.type === 'success' ? 'bg-emerald-100 text-emerald-600' :
                toast.type === 'error' ? 'bg-red-100 text-red-600' :
                'bg-blue-100 text-blue-600'
            ]">
                <span class="material-symbols-outlined text-sm">{{ toast.type === 'success' ? 'check' : toast.type === 'error' ? 'close' : 'info' }}</span>
            </div>
            <div class="flex-1">
                <p class="font-bold text-gray-800 text-sm">{{ toast.title }}</p>
                <p class="text-xs text-gray-500">{{ toast.message }}</p>
            </div>
            <button @click="toast.show = false" class="text-gray-400 hover:text-gray-600">
                <span class="material-symbols-outlined text-sm">close</span>
            </button>
        </div>
    </div>

</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import TopsisTable from '../Components/TopsisTable.vue';
import KriteriaManager from '../Components/KriteriaManager.vue';
import DataSiswa from '../Components/DataSiswa.vue';
import Perhitungan from '../Components/Perhitungan.vue';
import TambahSiswa from '../Components/TambahSiswa.vue';
import StatsCard from '../Components/StatsCard.vue';
import Laporan from '../Components/Laporan.vue';

// ========== WARNA UNTUK SETIAP CARD KRITERIA ==========
const getKriteriaCardClass = (index) => {
    const colors = [
        'bg-gradient-to-br from-blue-500 to-blue-700',     // IPK
        'bg-gradient-to-br from-teal-500 to-teal-700',     // Kehadiran
        'bg-gradient-to-br from-emerald-500 to-emerald-700', // Prestasi
        'bg-gradient-to-br from-amber-500 to-amber-700',   // Keterlambatan
        'bg-gradient-to-br from-purple-500 to-purple-700'  // Tugas
    ];
    return colors[index % colors.length];
};

// ========== STATE MANAGEMENT ==========
const activeTab = ref('dashboard');
const students = ref([]);
const criteria = ref([]);
const dataPenilaian = ref([]);
const showStudentForm = ref(false);
const editingStudent = ref(null);
const perhitunganComponent = ref(null);

// Toast Notification
const toast = ref({ show: false, type: 'success', title: '', message: '' });

const showToast = (type, title, message) => {
    toast.value = { show: true, type, title, message };
    setTimeout(() => { toast.value.show = false; }, 3000);
};

// Recent Activities
const recentActivities = ref([
    { icon: 'person_add', title: 'Data siswa baru ditambahkan', time: 'baru saja', bgColor: 'bg-emerald-50', iconColor: 'text-emerald-600' },
    { icon: 'edit_note', title: 'Kriteria diperbarui', time: 'baru saja', bgColor: 'bg-blue-50', iconColor: 'text-blue-600' },
    { icon: 'calculate', title: 'Perhitungan TOPSIS dilakukan', time: 'baru saja', bgColor: 'bg-purple-50', iconColor: 'text-purple-600' },
    { icon: 'file_upload', title: 'Import data excel', time: 'baru saja', bgColor: 'bg-green-50', iconColor: 'text-green-600' }
]);

// ========== DATA KRITERIA DEFAULT (5 KRITERIA FIXS) ==========
const defaultKriteria = [
    { id: 1, kode: 'C1', nama: 'IPK', tipe: 'benefit', bobot: 0.30 },
    { id: 2, kode: 'C2', nama: 'Kehadiran', tipe: 'benefit', bobot: 0.20 },
    { id: 3, kode: 'C3', nama: 'Prestasi', tipe: 'benefit', bobot: 0.15 },
    { id: 4, kode: 'C4', nama: 'Keterlambatan', tipe: 'cost', bobot: 0.10 },
    { id: 5, kode: 'C5', nama: 'Tugas', tipe: 'benefit', bobot: 0.25 }
];

// ========== FUNGSI NAVIGASI MENU ==========
const handleMenuClick = (menuId) => {
    if (showStudentForm.value) {
        showStudentForm.value = false;
        editingStudent.value = null;
    }
    activeTab.value = menuId;
};

// ========== FUNGSI UNTUK FORM SISWA ==========
const handleTambahSiswa = () => {
    editingStudent.value = null;
    showStudentForm.value = true;
    activeTab.value = null;
};

const handleEditSiswa = (siswa) => {
    editingStudent.value = siswa;
    showStudentForm.value = true;
    activeTab.value = null;
};

const closeStudentForm = () => {
    showStudentForm.value = false;
    editingStudent.value = null;
    activeTab.value = 'data-siswa';
};

const saveStudent = (newSiswa) => {
    if (editingStudent.value) {
        const index = students.value.findIndex(s => s.id === newSiswa.id);
        if (index !== -1) {
            students.value[index] = newSiswa;
        }
        showToast('success', 'Berhasil', 'Data siswa berhasil diperbarui!');
    } else {
        students.value.push(newSiswa);
        showToast('success', 'Berhasil', 'Data siswa berhasil ditambahkan!');
    }
    generateDataPenilaian();
    localStorage.setItem('mahasiswa', JSON.stringify(students.value));
    closeStudentForm();
};

// ========== FUNGSI UNTUK MENERIMA HASIL PERHITUNGAN TOPSIS ==========
const handleHasilPerhitungan = (hasil) => {
    const updatedStudents = students.value.map(siswa => {
        const hasilPerhitungan = hasil.find(h => h.id === siswa.id);
        if (hasilPerhitungan) {
            return {
                ...siswa,
                v: hasilPerhitungan.v
            };
        }
        return siswa;
    });

    students.value = updatedStudents;
    localStorage.setItem('mahasiswa', JSON.stringify(updatedStudents));
    generateDataPenilaian();
    showToast('success', 'Berhasil', 'Hasil perhitungan TOPSIS telah disimpan ke Dashboard!');
};

// ========== FUNGSI UNTUK MENGOLAH DATA PENILAIAN ==========
const generateDataPenilaian = () => {
    const penilaian = [];
    students.value.forEach(mahasiswa => {
        if (mahasiswa.nilai && typeof mahasiswa.nilai === 'object') {
            criteria.value.forEach(kriteria => {
                const nilai = mahasiswa.nilai[kriteria.id];
                if (nilai !== undefined && nilai !== null) {
                    penilaian.push({
                        mahasiswaId: mahasiswa.id,
                        kriteriaId: kriteria.id,
                        nilai: parseFloat(nilai)
                    });
                }
            });
        }
    });
    dataPenilaian.value = penilaian;
};

// ========== FUNGSI UPDATE DATA ==========
const updateStudents = (newData) => {
    students.value = newData;
    generateDataPenilaian();
    localStorage.setItem('mahasiswa', JSON.stringify(newData));
    showToast('success', 'Berhasil', 'Data siswa berhasil disimpan!');
};

const updateCriteria = (newData) => {
    criteria.value = newData;
    generateDataPenilaian();
    localStorage.setItem('kriteria', JSON.stringify(newData));
    showToast('success', 'Berhasil', 'Data kriteria berhasil disimpan!');
};

// ========== FUNGSI UNTUK MENAMPILKAN NILAI PERKRITERIA ==========
const getNilaiMahasiswa = (siswaId, kriteriaId) => {
    const siswa = students.value.find(s => s.id === siswaId);
    if (siswa && siswa.nilai && siswa.nilai[kriteriaId] !== undefined) {
        return siswa.nilai[kriteriaId];
    }
    return '-';
};

const getRataRataNilai = (kriteriaId) => {
    const nilaiSemua = students.value
        .map(s => s.nilai && s.nilai[kriteriaId])
        .filter(n => n !== undefined && n !== null);

    if (nilaiSemua.length === 0) return 0;
    const total = nilaiSemua.reduce((sum, n) => sum + parseFloat(n), 0);
    return (total / nilaiSemua.length).toFixed(2);
};

// ========== MAHASISWA TERURUT BERDASARKAN NILAI V ==========
const sortedStudentsByV = computed(() => {
    return [...students.value].sort((a, b) => (b.v || 0) - (a.v || 0));
});

// ========== RANKING UNTUK TABEL DASHBOARD ==========
const rankedStudents = computed(() => {
    return [...students.value]
        .filter(s => s.v !== undefined && s.v !== null)
        .sort((a, b) => (parseFloat(b.v) || 0) - (parseFloat(a.v) || 0))
        .slice(0, 5);
});

// ========== COMPUTED PROPERTIES ==========
const formattedTitle = computed(() => {
    if (showStudentForm.value) return editingStudent.value ? 'Edit Data Siswa' : 'Tambah Data Siswa';
    const titles = { 'dashboard': 'Dashboard', 'manajemen-kriteria': 'Manajemen Kriteria', 'data-siswa': 'Data Siswa', 'proses-perhitungan': 'SPK TOPSIS' };
    return titles[activeTab.value] || activeTab.value;
});

const chartData = computed(() => {
    if (rankedStudents.value.length === 0) return [{ name: 'N/A', value: 0 }];
    return rankedStudents.value.slice(0, 7).map(s => ({
        name: s.nama?.split(' ')[0] || s.nama || 'Unknown',
        value: parseFloat(s.v) || 0
    }));
});

// ========== STATS DENGAN FORMAT UNTUK STATSCARD ==========
const stats = computed(() => {
    const siswaDenganV = students.value.filter(s => s.v !== undefined && s.v !== null);
    const avgV = siswaDenganV.length > 0
        ? (siswaDenganV.reduce((acc, s) => acc + (parseFloat(s.v) || 0), 0) / siswaDenganV.length).toFixed(2)
        : '0.00';

    return [
        {
            title: 'Total Mahasiswa',
            value: students.value.length,
            icon: 'school',
            bgIcon: 'groups',
            color: 'blue',
            badge: `${students.value.length} Orang`,
            trend: `${students.value.length} Mahasiswa`,
            trendColor: 'up'
        },
        {
            title: 'Kriteria Aktif',
            value: criteria.value.length,
            icon: 'fact_check',
            bgIcon: 'fact_check',
            color: 'amber',
            badge: `${criteria.value.length} Kriteria`,
            trend: `${criteria.value.length} Kriteria`,
            trendColor: 'neutral'
        },
        {
            title: 'Rata-rata Skor (V)',
            value: avgV,
            icon: 'analytics',
            bgIcon: 'monitoring',
            color: 'emerald',
            badge: 'TOPSIS',
            trend: 'Metode TOPSIS',
            trendColor: 'up'
        },
        {
            title: 'Status Sistem',
            value: 'Online',
            icon: 'check_circle',
            bgIcon: 'verified',
            color: 'purple',
            badge: 'Live',
            trend: 'Aktif',
            trendColor: 'up'
        }
    ];
});

// ========== LIFECYCLE ==========
onMounted(() => {
    const savedStudents = localStorage.getItem('mahasiswa');
    const savedCriteria = localStorage.getItem('kriteria');

    if (savedStudents) {
        students.value = JSON.parse(savedStudents);
    }

    if (savedCriteria) {
        criteria.value = JSON.parse(savedCriteria);
    } else {
        criteria.value = defaultKriteria;
        localStorage.setItem('kriteria', JSON.stringify(criteria.value));
    }

    generateDataPenilaian();
});

// ========== MENU ITEMS ==========
const menus = [
    { id: 'dashboard', title: 'Dashboard', icon: 'dashboard' },
    { id: 'manajemen-kriteria', title: 'Manajemen Kriteria', icon: 'list_alt' },
    { id: 'data-siswa', title: 'Data Siswa', icon: 'groups' },
    { id: 'proses-perhitungan', title: 'Proses Perhitungan', icon: 'calculate' },
    { id: 'laporan', title: 'Laporan', icon: 'description' },
];
</script>

<style>
@keyframes shimmer {
    100% { transform: translateX(100%); }
}
.custom-scrollbar::-webkit-scrollbar { width: 5px; }
.custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 20px; }
.custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
.animate-in { animation: fade-in 0.5s ease-out; }
@keyframes fade-in { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>
