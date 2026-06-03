<!-- TopsisTable.vue - Versi Minimalis -->
<template>
  <div class="overflow-x-auto">
    <table class="w-full">
      <thead>
        <tr class="border-b border-gray-100">
          <th class="px-6 py-3 text-left text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Rank</th>
          <th class="px-6 py-3 text-left text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Nama Mahasiswa</th>
          <th class="px-6 py-3 text-right text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Nilai V</th>
          <th class="px-6 py-3 text-center text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Status</th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="(student, index) in dataMahasiswa"
          :key="student.id"
          class="border-b border-gray-50 hover:bg-gray-50/50 transition-all duration-200"
        >
          <td class="px-6 py-3">
            <span class="font-bold" :class="index === 0 ? 'text-amber-600 text-lg' : 'text-gray-500'">
              {{ index + 1 }}
            </span>
          </td>
          <td class="px-6 py-3">
            <span class="font-medium text-gray-800">{{ student.nama }}</span>
            <p class="text-[9px] text-gray-400">{{ student.nim }}</p>
          </td>
          <td class="px-6 py-3 text-right">
            <span class="font-bold" :class="index === 0 ? 'text-amber-600 text-lg' : 'text-gray-700'">
              {{ ((student.v || 0) * 100).toFixed(2) }}%
            </span>
          </td>
          <td class="px-6 py-3 text-center">
            <span
              class="text-[9px] font-medium"
              :class="index === 0 ? 'text-amber-600' : index === 1 ? 'text-gray-500' : index === 2 ? 'text-orange-500' : 'text-emerald-500'"
            >
              {{ getBadgeText(index) }}
            </span>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
const props = defineProps({
  dataMahasiswa: { type: Array, default: () => [] }
});

const getBadgeText = (index) => {
  if (index === 0) return '🏆 Mahasiswa Terbaik';
  if (index === 1) return 'Runner Up';
  if (index === 2) return 'Third Place';
  return `Peringkat ${index + 1}`;
};
</script>
