<template>
  <div class="flex items-center justify-between text-sm text-zinc-600">
    <p class="font-medium">
      Página {{ pagination.current_page }} de {{ pagination.last_page }}
    </p>
    <div class="flex items-center gap-2">
      <button
        :disabled="pagination.current_page <= 1"
        @click="$emit('page-changed', pagination.current_page - 1)"
        class="flex items-center gap-1 px-3 py-1.5 rounded-lg border border-zinc-200 bg-white hover:bg-zinc-50 disabled:opacity-40 disabled:cursor-not-allowed transition-colors font-medium"
      >
        <ChevronLeftIcon class="w-4 h-4" />
        Anterior
      </button>

      <template v-for="page in visiblePages" :key="page">
        <span v-if="page === '...'" class="px-2 text-zinc-400 select-none">…</span>
        <button
          v-else
          @click="$emit('page-changed', page)"
          class="w-8 h-8 rounded-lg border font-semibold transition-colors"
          :class="page === pagination.current_page
            ? 'bg-indigo-600 text-white border-indigo-600 shadow-sm'
            : 'border-zinc-200 bg-white hover:bg-zinc-50 text-zinc-700'"
        >
          {{ page }}
        </button>
      </template>

      <button
        :disabled="pagination.current_page >= pagination.last_page"
        @click="$emit('page-changed', pagination.current_page + 1)"
        class="flex items-center gap-1 px-3 py-1.5 rounded-lg border border-zinc-200 bg-white hover:bg-zinc-50 disabled:opacity-40 disabled:cursor-not-allowed transition-colors font-medium"
      >
        Próxima
        <ChevronRightIcon class="w-4 h-4" />
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { ChevronLeft as ChevronLeftIcon, ChevronRight as ChevronRightIcon } from 'lucide-vue-next';

const props = defineProps<{
  pagination: {
    current_page: number;
    last_page: number;
    total: number;
  };
}>();

defineEmits<{
  (e: 'page-changed', page: number): void;
}>();

const visiblePages = computed(() => {
  const current = props.pagination.current_page;
  const last = props.pagination.last_page;
  const pages: (number | string)[] = [];

  if (last <= 7) {
    for (let i = 1; i <= last; i++) pages.push(i);
    return pages;
  }

  pages.push(1);
  if (current > 4) pages.push('...');

  const start = Math.max(2, current - 1);
  const end = Math.min(last - 1, current + 1);
  for (let i = start; i <= end; i++) pages.push(i);

  if (current < last - 3) pages.push('...');
  pages.push(last);

  return pages;
});
</script>
