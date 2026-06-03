<template>
    <div class="flex min-h-screen bg-[#F8FAFC] font-['Plus_Jakarta_Sans'] antialiased">

        <!-- SIDEBAR -->
        <aside class="fixed left-0 top-0 h-full w-72 z-50 bg-gradient-to-b from-emerald-950 via-green-950 to-emerald-950 text-white flex flex-col py-6 shadow-2xl no-print">
            <div class="absolute top-0 left-0 w-full h-64 bg-emerald-400 opacity-5 blur-[100px] rounded-full pointer-events-none"></div>
                <div class="px-8 mb-10 flex items-center gap-4 relative z-10">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-700 flex items-center justify-center text-white shadow-lg">
                        <span class="material-symbols-outlined !text-3xl">analytics</span>
                    </div>
                    <div>
                        <h1 class="text-xl font-black text-white tracking-tighter leading-none">SPK TOPSIS</h1>
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
                <button @click="showLogoutPopup = true" class="w-full text-left text-rose-400 hover:bg-rose-500/10 flex items-center px-5 py-3.5 transition-all rounded-2xl">
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
                    <p class="text-2xl font-extrabold text-slate-800 tracking-tight capitalize">{{ formattedTitle }} </p>
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
                    <TambahDataMahasiswa
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

                    <!-- FILTER DATA -->
                    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="material-icons text-emerald-600 text-sm">filter_alt</span>
                            <h4 class="font-semibold text-gray-700 text-sm">Filter Data</h4>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                            <!-- Filter Fakultas -->
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 mb-1">Fakultas</label>
                                <select v-model="selectedFakultas" @change="onFakultasChange"
                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-emerald-500 focus:outline-none">
                                    <option v-for="f in fakultasList" :key="f.id" :value="f.id">
                                        {{ f.nama }}
                                    </option>
                                </select>
                            </div>

                            <!-- Filter Prodi -->
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 mb-1">Program Studi</label>
                                <select v-model="selectedProdi"
                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-emerald-500 focus:outline-none">
                                    <option v-for="p in filteredProdi" :key="p.id" :value="p.id">
                                        {{ p.nama }}
                                    </option>
                                </select>
                            </div>

                            <!-- Filter Angkatan -->
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 mb-1">Angkatan</label>
                                <select v-model="selectedAngkatan"
                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-emerald-500 focus:outline-none">
                                    <option v-for="a in angkatanList" :key="a.id" :value="a.id">
                                        {{ a.tahun }}
                                    </option>
                                </select>
                            </div>

                            <!-- Filter Semester -->
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 mb-1">Semester</label>
                                <select v-model="selectedSemester"
                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-emerald-500 focus:outline-none">
                                    <option v-for="s in semesterList" :key="s.id" :value="s.id">
                                        {{ s.nama }}
                                    </option>
                                </select>
                            </div>

                            <!-- Filter Kelas (BARU) -->
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 mb-1">Kelas</label>
                                <select v-model="selectedKelas"
                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-emerald-500 focus:outline-none">
                                    <option value="">-- Semua Kelas --</option>
                                    <option v-for="k in kelasList" :key="k.id" :value="k.id">
                                        {{ k.nama }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-3 text-right">
                            <span class="text-[10px] text-gray-400">
                                Menampilkan {{ filteredStudentsByGroup.length }} dari {{ students.length }} mahasiswa
                            </span>
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

                    <!-- RINGKASAN NILAI PER KRITERIA -->
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

                    <!-- TABEL DETAIL NILAI MAHASISWA -->
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
                                    Total: {{ filteredStudentsByGroup.length }} Mahasiswa
                                </div>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="bg-gray-50 border-b border-gray-200 text-[10px] font-semibold text-gray-500 uppercase">
                                        <th class="px-4 py-2 text-center w-12">No</th>
                                        <th class="px-4 py-2 text-left">NIM</th>
                                        <th class="px-4 py-2 text-left">Nama Mahasiswa</th>
                                        <th class="px-4 py-2 text-left">Kelas</th>
                                        <th v-for="kriteria in criteria" :key="kriteria.id" class="px-3 py-2 text-center min-w-[70px]">
                                            {{ kriteria.nama }}
                                        </th>
                                        <th class="px-3 py-2 text-center w-24">Nilai V</th>
                                        <th class="px-3 py-2 text-center w-24">Peringkat</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <tr v-for="(siswa, index) in filteredSortedStudents" :key="siswa.id"
                                        class="hover:bg-gray-50 transition-colors"
                                        :class="index === 0 ? 'bg-emerald-50/20' : ''">
                                        <td class="px-4 py-2 text-center font-medium text-gray-500">{{ index + 1 }}</td>
                                        <td class="px-4 py-2 font-medium text-gray-800">{{ siswa.nim || '-' }}</td>
                                        <td class="px-4 py-2 font-medium text-gray-800">{{ siswa.nama }}</td>
                                        <td class="px-4 py-2 font-medium text-gray-600">
                                            {{ getKelasNama(siswa.kelas_id) }}
                                        </td>
                                        <td v-for="kriteria in criteria" :key="kriteria.id" class="px-3 py-2 text-center text-gray-600">
                                            {{ getNilaiMahasiswaBySemester(siswa.id, selectedSemester, kriteria.id) }}
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
                                    <tr v-if="filteredStudentsByGroup.length === 0">
                                    <td :colspan="4 + criteria.length" class="px-4 py-12 text-center align-middle">
                                        <div class="flex flex-col items-center justify-center gap-2">
                                        <span class="material-icons text-4xl text-gray-400">school</span>
                                        <p class="text-gray-500 font-medium">Tidak ada data mahasiswa untuk filter ini</p>
                                        <p class="text-xs text-gray-400">Silakan ubah filter atau tambahkan data mahasiswa</p>
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
                        <TopsisTable v-if="filteredRankedStudents.length > 0 && filteredRankedStudents[0]?.v !== undefined" :data-mahasiswa="filteredRankedStudents" />
                        <div v-else-if="filteredStudentsByGroup.length > 0 && filteredRankedStudents[0]?.v === undefined" class="p-16 text-center">
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

                <!-- DATA MAHASISWA TAB -->
                <div v-else-if="activeTab === 'data-mahasiswa'" class="animate-in fade-in duration-700">
                    <DataMahasiswa
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

    <!-- LOGOUT POPUP -->
    <div v-if="showLogoutPopup" class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/40 backdrop-blur-sm animate-in fade-in duration-300">
        <div class="bg-white rounded-3xl p-8 max-w-sm w-full mx-4 shadow-2xl border border-slate-100 relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-32 h-32 bg-rose-50 rounded-full blur-2xl pointer-events-none opacity-60"></div>
            <div class="relative z-10">
                <div class="w-16 h-16 bg-rose-50 text-rose-500 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-inner border border-rose-100">
                    <span class="material-symbols-outlined !text-4xl">logout</span>
                </div>
                <h3 class="text-xl font-black text-slate-800 text-center tracking-tight mb-2">Keluar Sistem?</h3>
                <p class="text-sm text-slate-500 text-center font-medium mb-8 leading-relaxed">
                    Apakah Anda yakin ingin keluar dari SPK TOPSIS?
                </p>
                <div class="flex gap-4">
                    <button @click="showLogoutPopup = false" class="flex-1 py-3.5 bg-slate-50 text-slate-500 hover:bg-slate-100 hover:text-slate-700 rounded-xl text-xs font-black tracking-widest uppercase transition-all">
                        Batal
                    </button>
                    <button @click="prosesLogout" :disabled="isLoggingOut" class="flex-1 py-3.5 bg-rose-500 text-white hover:bg-rose-600 rounded-xl text-xs font-black tracking-widest uppercase transition-all shadow-lg hover:shadow-rose-200 flex justify-center items-center gap-2 disabled:opacity-50">
                        <span v-if="isLoggingOut" class="material-symbols-outlined animate-spin !text-lg">progress_activity</span>
                        <span v-else>Ya, Keluar</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

</template>

<script setup>
import axios from 'axios';
import { ref, computed, onMounted, watch } from 'vue';
import TopsisTable from '../Components/TopsisTable.vue';
import KriteriaManager from '../Components/KriteriaManager.vue';
import DataMahasiswa from '../Components/DataMahasiswa.vue';
import Perhitungan from '../Components/Perhitungan.vue';
import TambahDataMahasiswa from '../Components/TambahDataMahasiswa.vue';
import StatsCard from '../Components/StatsCard.vue';
import Laporan from '../Components/Laporan.vue';


// ========== LOGOUT ==========
const showLogoutPopup = ref(false);
const isLoggingOut = ref(false);

const prosesLogout = () => {
    axios.post('/logout');
    window.location.replace('/login');
};

// ========== WARNA UNTUK SETIAP CARD KRITERIA ==========
const getKriteriaCardClass = (index) => {
    const colors = [
        'bg-gradient-to-br from-blue-500 to-blue-700',
        'bg-gradient-to-br from-teal-500 to-teal-700',
        'bg-gradient-to-br from-emerald-500 to-emerald-700',
        'bg-gradient-to-br from-amber-500 to-amber-700',
        'bg-gradient-to-br from-purple-500 to-purple-700'
    ];
    return colors[index % colors.length];
};

// ========== DATA KELAS DEFAULT ==========
const defaultKelas = [
    { id: 1, nama: "Reguler - C01", kode: "C01", kelompok: "Reguler" },
    { id: 2, nama: "Reguler - C02", kode: "C02", kelompok: "Reguler" },
    { id: 3, nama: "Reguler - B01", kode: "B01", kelompok: "Reguler" },
    { id: 4, nama: "Karyawan - K01", kode: "K01", kelompok: "Karyawan" }
];

// ========== STATE MANAGEMENT ==========
const activeTab = ref('dashboard');
const students = ref([]);
const criteria = ref([]);
const dataPenilaian = ref([]);
const showStudentForm = ref(false);
const editingStudent = ref(null);
const perhitunganComponent = ref(null);
const penilaianPerSemester = ref([]);

// Data Kelas
const kelasList = ref([]);

// Toast Notification
const toast = ref({ show: false, type: 'success', title: '', message: '' });

const showToast = (type, title, message) => {
    toast.value = { show: true, type, title, message };
    setTimeout(() => { toast.value.show = false; }, 3000);
};

// Recent Activities
const recentActivities = ref([
    { icon: 'person_add', title: 'Data mahasiswa baru ditambahkan', time: 'baru saja', bgColor: 'bg-emerald-50', iconColor: 'text-emerald-600' },
    { icon: 'edit_note', title: 'Kriteria diperbarui', time: 'baru saja', bgColor: 'bg-blue-50', iconColor: 'text-blue-600' },
    { icon: 'calculate', title: 'Perhitungan TOPSIS dilakukan', time: 'baru saja', bgColor: 'bg-purple-50', iconColor: 'text-purple-600' },
    { icon: 'file_upload', title: 'Import data excel', time: 'baru saja', bgColor: 'bg-green-50', iconColor: 'text-green-600' }
]);

// ========== DATA KRITERIA DEFAULT ==========
const defaultKriteria = [
    { id: 1, kode: 'C1', nama: 'IPK', tipe: 'benefit', bobot: 0.30 },
    { id: 2, kode: 'C2', nama: 'Kehadiran', tipe: 'benefit', bobot: 0.20 },
    { id: 3, kode: 'C3', nama: 'Prestasi', tipe: 'benefit', bobot: 0.15 },
    { id: 4, kode: 'C4', nama: 'Keterlambatan', tipe: 'cost', bobot: 0.10 },
    { id: 5, kode: 'C5', nama: 'Tugas', tipe: 'benefit', bobot: 0.25 }
];

// ========== DATA MASTER UNTUK FILTER ==========
const fakultasList = ref([]);
const prodiList = ref([]);
const angkatanList = ref([]);
const semesterList = ref([]);

// Filter yang dipilih user
const selectedFakultas = ref(3);
const selectedProdi = ref(8);
const selectedAngkatan = ref(4);
const selectedSemester = ref(4);
const selectedKelas = ref(''); // Filter Kelas (BARU)

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

const closeStudentForm = () => {
    showStudentForm.value = false;
    editingStudent.value = null;
    activeTab.value = 'data-mahasiswa';
};

const handleEditSiswa = (siswa) => {
    const penilaian = penilaianPerSemester.value.find(
        p => p.mahasiswa_id === siswa.id && p.semester_id === 4
    );

    editingStudent.value = {
        ...siswa,
        semester_id: 4,
        nilai: penilaian?.nilai || {}
    };
    showStudentForm.value = true;
    activeTab.value = null;
};

const saveStudent = (newSiswa) => {
  console.log('📝 Menyimpan siswa:', newSiswa);

  // ========== PERBAIKAN: Ambil data terbaru dari localStorage ==========
  let currentStudents = JSON.parse(localStorage.getItem('mahasiswa') || '[]');
  let currentPenilaian = JSON.parse(localStorage.getItem('penilaian') || '[]');

  if (editingStudent.value) {
    // ========== MODE EDIT ==========
    const index = currentStudents.findIndex(s => s.id === newSiswa.id);
    if (index !== -1) {
      currentStudents[index] = {
        ...currentStudents[index],
        nim: newSiswa.nim,
        nama: newSiswa.nama,
        fakultas_id: newSiswa.fakultas_id,
        prodi_id: newSiswa.prodi_id,
        angkatan_id: newSiswa.angkatan_id,
        kelas_id: newSiswa.kelas_id
      };
    }

    // Update nilai
    const penilaianIndex = currentPenilaian.findIndex(
      p => p.mahasiswa_id === newSiswa.id && p.semester_id === newSiswa.semester_id
    );

    if (penilaianIndex !== -1) {
      currentPenilaian[penilaianIndex].nilai = newSiswa.nilai;
    } else {
      currentPenilaian.push({
        mahasiswa_id: newSiswa.id,
        semester_id: newSiswa.semester_id,
        nilai: newSiswa.nilai
      });
    }
    showToast('success', 'Berhasil', 'Data Mahasiswa berhasil diperbarui!');
  } else {
    // ========== MODE TAMBAH ==========
    const newId = Date.now();

    // Cek apakah NIM sudah ada
    if (currentStudents.find(s => s.nim === newSiswa.nim)) {
      showToast('error', 'Gagal', `NIM ${newSiswa.nim} sudah ada!`);
      return;
    }

    currentStudents.push({
      id: newId,
      nim: newSiswa.nim,
      nama: newSiswa.nama,
      fakultas_id: newSiswa.fakultas_id,
      prodi_id: newSiswa.prodi_id,
      angkatan_id: newSiswa.angkatan_id,
      kelas_id: newSiswa.kelas_id,
      v: 0
    });

    currentPenilaian.push({
      mahasiswa_id: newId,
      semester_id: newSiswa.semester_id,
      nilai: newSiswa.nilai
    });

    showToast('success', 'Berhasil', 'Data Mahasiswa berhasil ditambahkan!');
  }

  // ========== SIMPAN KE LOCALSTORAGE ==========
  localStorage.setItem('mahasiswa', JSON.stringify(currentStudents));
  localStorage.setItem('penilaian', JSON.stringify(currentPenilaian));

  // ========== UPDATE STATE ==========
  students.value = currentStudents;
  penilaianPerSemester.value = currentPenilaian;

  generateDataPenilaian();
  closeStudentForm();

  console.log('✅ Total mahasiswa setelah simpan:', students.value.length);
};

// ========== FUNGSI UNTUK MENERIMA HASIL PERHITUNGAN TOPSIS ==========
const handleHasilPerhitungan = (hasil) => {
    const updatedStudents = students.value.map(siswa => {
        const hasilPerhitungan = hasil.find(h => h.id === siswa.id);
        if (hasilPerhitungan) {
            return {
                ...siswa,
                v: hasilPerhitungan.v || hasilPerhitungan.preferensi || 0
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
    // Pastikan penilaianPerSemester sudah terisi
    if (penilaianPerSemester.value.length === 0) {
        const savedPenilaian = localStorage.getItem('penilaian');
        if (savedPenilaian) {
            penilaianPerSemester.value = JSON.parse(savedPenilaian);
            console.log('✅ Data penilaian dimuat dari localStorage:', penilaianPerSemester.value.length);
        }
    }

    const penilaian = [];

    // Loop semua mahasiswa
    students.value.forEach(mahasiswa => {
        // Cari nilai dari penilaianPerSemester berdasarkan semester yang dipilih
        const nilaiSiswa = penilaianPerSemester.value.find(
            p => p.mahasiswa_id === mahasiswa.id && p.semester_id === selectedSemester.value
        );

        if (nilaiSiswa && nilaiSiswa.nilai) {
            // Loop semua kriteria
            criteria.value.forEach(kriteria => {
                const nilai = nilaiSiswa.nilai[kriteria.id];
                if (nilai !== undefined && nilai !== null && !isNaN(nilai)) {
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
    console.log('✅ DataPenilaian generated:', dataPenilaian.value.length);
};

// ========== FUNGSI UPDATE DATA ==========
const updateStudents = (newData) => {
    students.value = newData;
    generateDataPenilaian();
    localStorage.setItem('mahasiswa', JSON.stringify(newData));
    showToast('success', 'Berhasil', 'Data Mahasiswa berhasil disimpan!');
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

// ========== FUNGSI GET NILAI PER SEMESTER ==========
const getNilaiMahasiswaBySemester = (mahasiswaId, semesterId, kriteriaId) => {
    const penilaian = penilaianPerSemester.value.find(
        p => p.mahasiswa_id === mahasiswaId && p.semester_id === semesterId
    );
    if (penilaian && penilaian.nilai && penilaian.nilai[kriteriaId] !== undefined) {
        return penilaian.nilai[kriteriaId];
    }
    return '-';
};

// ========== FUNGSI GET NAMA KELAS ==========
const getKelasNama = (kelasId) => {
    if (!kelasId) return '-';
    const kelas = kelasList.value.find(k => k.id === kelasId);
    return kelas ? kelas.nama : '-';
};

const getRataRataNilai = (kriteriaId) => {
    // Ambil semua penilaian di semester yang dipilih
    const penilaianDiSemester = penilaianPerSemester.value.filter(
        p => p.semester_id === selectedSemester.value
    );

    console.log('Penilaian di semester', selectedSemester.value, ':', penilaianDiSemester.length);

    // Filter mahasiswa berdasarkan filter yang dipilih
    const filteredMahasiswaIds = students.value
        .filter(s =>
            s.fakultas_id === selectedFakultas.value &&
            s.prodi_id === selectedProdi.value &&
            s.angkatan_id === selectedAngkatan.value &&
            (selectedKelas.value ? s.kelas_id === selectedKelas.value : true)
        )
        .map(s => s.id);

    console.log('Filtered mahasiswa IDs:', filteredMahasiswaIds.length);

    // Ambil nilai untuk kriteria tertentu
    const nilaiSemua = penilaianDiSemester
        .filter(p => filteredMahasiswaIds.includes(p.mahasiswa_id))
        .map(p => p.nilai && p.nilai[kriteriaId])
        .filter(n => n !== undefined && n !== null && !isNaN(n));

    console.log('Nilai untuk kriteria', kriteriaId, ':', nilaiSemua.length);

    if (nilaiSemua.length === 0) return 0;
    const total = nilaiSemua.reduce((sum, n) => sum + parseFloat(n), 0);
    return (total / nilaiSemua.length).toFixed(2);
};

// ========== FILTER DATA MASTER ==========
const filteredProdi = computed(() => {
    return prodiList.value.filter(p => p.fakultas_id === selectedFakultas.value);
});

const filteredStudentsByGroup = computed(() => {
    let result = students.value.filter(s =>
        s.fakultas_id === selectedFakultas.value &&
        s.prodi_id === selectedProdi.value &&
        s.angkatan_id === selectedAngkatan.value &&
        (selectedKelas.value ? s.kelas_id === selectedKelas.value : true)
    );

    // Filter berdasarkan semester (hanya mahasiswa yang punya data nilai di semester ini)
    result = result.filter(siswa => {
        const hasData = penilaianPerSemester.value.some(
            p => p.mahasiswa_id === siswa.id && p.semester_id === selectedSemester.value
        );
        return hasData;
    });

    console.log('Filtered students:', result.length);
    return result;
});

const filteredSortedStudents = computed(() => {
    return [...filteredStudentsByGroup.value].sort((a, b) => (b.v || 0) - (a.v || 0));
});

const filteredRankedStudents = computed(() => {
    return filteredSortedStudents.value.slice(0, 5);
});

const chartData = computed(() => {
    if (filteredRankedStudents.value.length === 0) return [{ name: 'N/A', value: 0 }];
    return filteredRankedStudents.value.slice(0, 7).map(s => ({
        name: s.nama?.split(' ')[0] || s.nama || 'Unknown',
        value: parseFloat(s.v) || 0
    }));
});

const stats = computed(() => {
    const siswaDenganV = filteredStudentsByGroup.value.filter(s => s.v !== undefined && s.v !== null);
    const avgV = siswaDenganV.length > 0
        ? (siswaDenganV.reduce((acc, s) => acc + (parseFloat(s.v) || 0), 0) / siswaDenganV.length).toFixed(2)
        : '0.00';

    return [
        {
            title: 'Total Mahasiswa',
            value: filteredStudentsByGroup.value.length,
            icon: 'school',
            bgIcon: 'groups',
            color: 'blue',
            badge: `${filteredStudentsByGroup.value.length} Orang`,
            trend: `${filteredStudentsByGroup.value.length} Mahasiswa`,
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

const onFakultasChange = () => {
    if (filteredProdi.value.length > 0) {
        selectedProdi.value = filteredProdi.value[0].id;
    }
};

// ========== FUNGSI LOAD HASIL PERHITUNGAN ==========
const loadHasilPerhitunganByFilter = () => {
    try {
        const savedRiwayat = localStorage.getItem('topsis_riwayat_perhitungan');
        if (!savedRiwayat) {
            console.log('⚠️ Dashboard: Belum ada riwayat perhitungan');
            return;
        }

        const riwayat = JSON.parse(savedRiwayat);
        if (riwayat.length === 0) {
            console.log('⚠️ Dashboard: Riwayat perhitungan kosong');
            return;
        }

        const perhitunganYangSesuai = riwayat.find(r => {
        let match = r.filter.fakultasId === selectedFakultas.value &&
                    r.filter.prodiId === selectedProdi.value &&
                    r.filter.angkatanId === selectedAngkatan.value &&
                    r.filter.semesterId === selectedSemester.value;

        if (match) {
            if (selectedKelas.value) {
            match = r.filter.kelasId === selectedKelas.value;
            } else {
            match = !r.filter.kelasId || r.filter.kelasId === null || r.filter.kelasId === '';
            }
        }

        return match;
        });


        if (perhitunganYangSesuai && perhitunganYangSesuai.hasil) {
            const updatedStudents = students.value.map(siswa => {
                const hasil = perhitunganYangSesuai.hasil.find(h => h.id === siswa.id);
                if (hasil) {
                    return {
                        ...siswa,
                        v: hasil.preferensi || hasil.v || 0
                    };
                }
                return { ...siswa, v: 0 };
            });
            students.value = updatedStudents;
            localStorage.setItem('mahasiswa', JSON.stringify(students.value));
            console.log('✅ Dashboard: Memuat hasil untuk filter',
                perhitunganYangSesuai.filter.prodiNama,
                '-', perhitunganYangSesuai.filter.semesterNama);
        } else {
            const resetStudents = students.value.map(siswa => ({
                ...siswa,
                v: 0
            }));
            students.value = resetStudents;
            localStorage.setItem('mahasiswa', JSON.stringify(students.value));
            console.log('⚠️ Dashboard: Belum ada perhitungan untuk filter yang dipilih');
        }
    } catch (e) {
        console.error('Gagal memuat hasil perhitungan:', e);
    }
};

// ========== LOAD DATA KELAS ==========
const loadKelasData = () => {
    if (!localStorage.getItem('kelas')) {
        localStorage.setItem('kelas', JSON.stringify(defaultKelas));
    }
    kelasList.value = JSON.parse(localStorage.getItem('kelas') || '[]');
};

// ========== COMPUTED PROPERTIES ==========
const formattedTitle = computed(() => {
    if (showStudentForm.value) return editingStudent.value ? 'Edit Data Mahasiswa' : 'Tambah Data Mahasiswa';
    const titles = {
        'dashboard': 'Dashboard',
        'manajemen-kriteria': 'Manajemen Kriteria',
        'data-mahasiswa': 'Data Mahasiswa',
        'proses-perhitungan': 'SPK TOPSIS',
        'laporan': 'Laporan' };
    return titles[activeTab.value] || activeTab.value;
});

// ========== WATCH UNTUK FILTER ==========
watch([selectedFakultas, selectedProdi, selectedAngkatan, selectedSemester, selectedKelas], () => {
    loadHasilPerhitunganByFilter();
});

// ========== LIFECYCLE ==========
onMounted(() => {
    // Load data kelas
    loadKelasData();

    // Load data mahasiswa
    const savedStudents = localStorage.getItem('mahasiswa');
    const savedCriteria = localStorage.getItem('kriteria');

    if (savedStudents) {
        students.value = JSON.parse(savedStudents);
        console.log('✅ Loaded students:', students.value.length);
    }

    if (savedCriteria) {
        criteria.value = JSON.parse(savedCriteria);
    } else {
        criteria.value = defaultKriteria;
        localStorage.setItem('kriteria', JSON.stringify(criteria.value));
    }

    // LOAD ATAU MIGRASI DATA PENILAIAN PER SEMESTER
    const savedPenilaian = localStorage.getItem('penilaian');
    if (savedPenilaian) {
        penilaianPerSemester.value = JSON.parse(savedPenilaian);
        console.log('✅ Loaded penilaian:', penilaianPerSemester.value.length);
    } else {
        // Migrasi data lama
        const newPenilaian = [];
        students.value.forEach(siswa => {
            if (siswa.nilai) {
                newPenilaian.push({
                    mahasiswa_id: siswa.id,
                    semester_id: 4,
                    nilai: siswa.nilai
                });
            }
        });
        penilaianPerSemester.value = newPenilaian;
        localStorage.setItem('penilaian', JSON.stringify(penilaianPerSemester.value));
        console.log('✅ Migrated penilaian:', penilaianPerSemester.value.length);
    }

    // Hapus properti 'nilai' dari students
    const updatedStudents = students.value.map(s => {
        const { nilai, ...rest } = s;
        return rest;
    });
    students.value = updatedStudents;
    localStorage.setItem('mahasiswa', JSON.stringify(students.value));

    // DATA MASTER (FAKULTAS, PRODI, ANGKATAN, SEMESTER)
    if (!localStorage.getItem('fakultas')) {
        const defaultFakultas = [
            { id: 1, nama: "Fakultas Agama Islam", kode: "FAI" },
            { id: 2, nama: "Fakultas Ilmu Pendidikan", kode: "FIP" },
            { id: 3, nama: "Fakultas Sains dan Teknologi", kode: "FST" }
        ];
        localStorage.setItem('fakultas', JSON.stringify(defaultFakultas));
    }
    fakultasList.value = JSON.parse(localStorage.getItem('fakultas') || '[]');

    if (!localStorage.getItem('prodi')) {
        const defaultProdi = [
            { id: 1, nama: "Pendidikan Agama Islam", kode: "PAI", fakultas_id: 1 },
            { id: 2, nama: "Pendidikan Guru Madrasah Ibtidaiyah", kode: "PGMI", fakultas_id: 1 },
            { id: 3, nama: "Pendidikan Fisika", kode: "PF", fakultas_id: 2 },
            { id: 4, nama: "Pendidikan Ekonomi", kode: "PE", fakultas_id: 2 },
            { id: 5, nama: "Pendidikan Bahasa Inggris", kode: "PBI", fakultas_id: 2 },
            { id: 6, nama: "Pendidikan Bahasa dan Sastra Indonesia", kode: "PBSI", fakultas_id: 2 },
            { id: 7, nama: "Pendidikan Teknologi Informasi", kode: "PTI", fakultas_id: 2 },
            { id: 8, nama: "Informatika", kode: "IF", fakultas_id: 3 },
            { id: 9, nama: "Matematika", kode: "MTK", fakultas_id: 3 },
            { id: 10, nama: "Sains Pertanian", kode: "SP", fakultas_id: 3 }
        ];
        localStorage.setItem('prodi', JSON.stringify(defaultProdi));
    }
    prodiList.value = JSON.parse(localStorage.getItem('prodi') || '[]');

    if (!localStorage.getItem('angkatan')) {
        const defaultAngkatan = [];
        for (let tahun = 2020; tahun <= 2027; tahun++) {
            defaultAngkatan.push({ id: tahun - 2019, tahun: tahun });
        }
        localStorage.setItem('angkatan', JSON.stringify(defaultAngkatan));
    }
    angkatanList.value = JSON.parse(localStorage.getItem('angkatan') || '[]');

    if (!localStorage.getItem('semester')) {
        const defaultSemester = [];
        for (let i = 1; i <= 8; i++) {
            defaultSemester.push({ id: i, nama: `Semester ${i}`, urutan: i });
        }
        localStorage.setItem('semester', JSON.stringify(defaultSemester));
    }
    semesterList.value = JSON.parse(localStorage.getItem('semester') || '[]');

    // Update data mahasiswa lama dengan properti baru
    let needUpdate = false;
    const studentsWithProps = students.value.map(s => {
        let updated = { ...s };
        if (s.fakultas_id === undefined) {
            needUpdate = true;
            updated.fakultas_id = 3;
            updated.prodi_id = 8;
            updated.angkatan_id = 4;
        }
        if (s.kelas_id === undefined) {
            needUpdate = true;
            updated.kelas_id = 1; // Default ke Reguler - C01
        }
        return updated;
    });
    if (needUpdate) {
        students.value = studentsWithProps;
        localStorage.setItem('mahasiswa', JSON.stringify(students.value));
    }

    generateDataPenilaian();

    // LOAD HASIL PERHITUNGAN
    generateDataPenilaian();
    loadHasilPerhitunganByFilter();

    console.log('Dashboard mounted - Students:', students.value.length);
    console.log('Dashboard mounted - Criteria:', criteria.value.length);
    console.log('Dashboard mounted - Penilaian:', penilaianPerSemester.value.length);
    console.log('Dashboard mounted - Kelas:', kelasList.value.length);
});

// ========== MENU ITEMS ==========
const menus = [
    { id: 'dashboard', title: 'Dashboard', icon: 'dashboard' },
    { id: 'manajemen-kriteria', title: 'Manajemen Kriteria', icon: 'list_alt' },
    { id: 'data-mahasiswa', title: 'Data Mahasiswa', icon: 'groups' },
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
