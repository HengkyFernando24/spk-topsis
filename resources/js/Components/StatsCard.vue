<template>
  <div :class="getCardBgClass()" class="rounded-2xl p-6 relative overflow-hidden group cursor-pointer transition-all duration-300 hover:shadow-xl hover:-translate-y-1 shadow-sm">
    <!-- Background Icon Decoration -->
    <div class="absolute -right-6 -top-6 opacity-10 group-hover:scale-125 group-hover:-rotate-12 transition-all duration-500 ease-out">
      <span class="material-symbols-outlined !text-[8rem]">{{ bgIcon }}</span>
    </div>

    <div class="relative z-10">
      <div class="flex justify-between items-start mb-4">
        <!-- Icon Container -->
        <div :class="getIconBgClass()" class="w-12 h-12 rounded-xl flex items-center justify-center transition-all duration-300 group-hover:scale-110 group-hover:rotate-6 shadow-sm">
          <span :class="getIconTextClass()" class="material-symbols-outlined text-xl">{{ icon }}</span>
        </div>

        <!-- Badge -->
        <span v-if="badge" :class="getBadgeClass()" class="text-[10px] font-black px-2 py-1 rounded-full shadow-sm">
          {{ badge }}
        </span>
      </div>

      <!-- Title -->
      <p class="text-white/80 text-[11px] font-semibold uppercase tracking-wider mb-1">{{ title }}</p>

      <!-- Value -->
      <h3 class="text-3xl font-black text-white tracking-tight">{{ value }}</h3>

      <!-- Trend -->
      <div v-if="trend" class="mt-3 flex items-center gap-2">
        <span :class="trendColor === 'up' ? 'text-white/90' : trendColor === 'down' ? 'text-white/70' : 'text-white/70'" class="text-[10px] font-semibold">
          <span v-if="trendColor === 'up'" class="material-symbols-outlined text-xs align-middle">trending_up</span>
          <span v-else-if="trendColor === 'down'" class="material-symbols-outlined text-xs align-middle">trending_down</span>
          {{ trend }}
        </span>
        <span class="text-[10px] text-white/60">dari periode sebelumnya</span>
      </div>
    </div>

    <!-- Shimmer Effect -->
    <div class="absolute inset-0 -translate-x-full bg-gradient-to-r from-transparent via-white/20 to-transparent group-hover:animate-[shimmer_1.5s_infinite] pointer-events-none"></div>
  </div>
</template>

<script setup>
const props = defineProps({
  title: { type: String, default: '' },
  value: { type: [String, Number], default: 0 },
  icon: { type: String, default: 'analytics' },
  bgIcon: { type: String, default: 'analytics' },
  color: { type: String, default: 'emerald' },
  badge: { type: String, default: '' },
  trend: { type: String, default: '' },
  trendColor: { type: String, default: 'neutral' }
});

// Mapping warna untuk background card utama (WARNA BACKGROUND)
const cardBgColors = {
  blue: 'bg-gradient-to-br from-blue-500 to-blue-700',
  amber: 'bg-gradient-to-br from-amber-500 to-amber-700',
  emerald: 'bg-gradient-to-br from-emerald-500 to-emerald-700',
  purple: 'bg-gradient-to-br from-purple-500 to-purple-700',
  red: 'bg-gradient-to-br from-red-500 to-red-700',
  green: 'bg-gradient-to-br from-green-500 to-green-700',
  orange: 'bg-gradient-to-br from-orange-500 to-orange-700',
  slate: 'bg-gradient-to-br from-slate-500 to-slate-700'
};

// Mapping warna untuk background icon
const iconBgColors = {
  blue: 'bg-white/20',
  amber: 'bg-white/20',
  emerald: 'bg-white/20',
  purple: 'bg-white/20',
  red: 'bg-white/20',
  green: 'bg-white/20',
  orange: 'bg-white/20',
  slate: 'bg-white/20'
};

// Mapping warna untuk text icon
const iconTextColors = {
  blue: 'text-white',
  amber: 'text-white',
  emerald: 'text-white',
  purple: 'text-white',
  red: 'text-white',
  green: 'text-white',
  orange: 'text-white',
  slate: 'text-white'
};

// Mapping warna untuk badge
const badgeColors = {
  blue: 'bg-white text-blue-600',
  amber: 'bg-white text-amber-600',
  emerald: 'bg-white text-emerald-600',
  purple: 'bg-white text-purple-600',
  red: 'bg-white text-red-600',
  green: 'bg-white text-green-600',
  orange: 'bg-white text-orange-600',
  slate: 'bg-white text-slate-600'
};

const getCardBgClass = () => {
  return cardBgColors[props.color] || cardBgColors.emerald;
};

const getIconBgClass = () => {
  return iconBgColors[props.color] || iconBgColors.emerald;
};

const getIconTextClass = () => {
  return iconTextColors[props.color] || iconTextColors.emerald;
};

const getBadgeClass = () => {
  return badgeColors[props.color] || badgeColors.emerald;
};
</script>

<style scoped>
@keyframes shimmer {
  100% { transform: translateX(100%); }
}
</style>
