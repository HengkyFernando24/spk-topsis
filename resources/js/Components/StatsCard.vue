<template>
  <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
    <div class="flex items-center justify-between">
      <div>
        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">{{ title }}</p>
        <h3 class="text-3xl font-black text-slate-800">{{ value }}</h3>
      </div>
      <div :class="`w-12 h-12 rounded-xl flex items-center justify-center bg-${color}-50 text-${color}-600` transition-transform hover:rotate-12">
        <slot name="icon">
           <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
        </slot>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps(['title', 'value', 'color']);
</script>