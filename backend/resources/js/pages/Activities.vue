<template>
  <div>
    <div class="mb-6 flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-zinc-900">Auditoria de Logs</h1>
        <p class="text-sm text-zinc-500 mt-1">Acompanhe todas as alterações feitas no sistema recentemente.</p>
      </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-zinc-100 overflow-hidden">
      <div v-if="isLoading" class="flex justify-center p-12">
        <div class="w-8 h-8 border-4 border-indigo-600 border-t-transparent rounded-full animate-spin"></div>
      </div>
      
      <div v-else-if="activities.length === 0" class="p-12 text-center text-zinc-500 font-medium">
        Nenhuma atividade registrada ainda no sistema.
      </div>
      
      <div v-else class="divide-y divide-zinc-100">
        <div v-for="activity in activities" :key="activity.id" class="p-6 flex items-start space-x-4 hover:bg-zinc-50 transition-colors">
          <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0 mt-1">
            <ActivityIcon class="w-5 h-5 text-indigo-600" />
          </div>
          <div class="flex-1">
            <p class="font-medium text-zinc-900">{{ activity.message }}</p>
            <p class="text-sm text-zinc-500 mt-1 flex items-center gap-2">
              <span class="font-semibold text-zinc-700">{{ activity.causer }}</span>
              <span class="text-zinc-300">&bull;</span>
              <span>{{ activity.created_at }}</span>
              <span class="text-zinc-400 font-medium hidden sm:inline">({{ activity.created_at_human }})</span>
            </p>
          </div>
        </div>
      </div>
      
      <!-- Pagination Component -->
      <div v-if="pagination.total > 0" class="border-t border-zinc-100 p-4 border-b-0 bg-zinc-50/50">
        <Pagination :pagination="pagination" @page-changed="loadActivities" />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { Activity as ActivityIcon } from 'lucide-vue-next';
import api from '../services/api';
import Pagination from '../components/Pagination.vue';

const isLoading = ref(true);
const activities = ref<any[]>([]);
const pagination = ref({
  current_page: 1,
  last_page: 1,
  total: 0,
});

const loadActivities = async (page = 1) => {
  isLoading.value = true;
  try {
    const response = await api.get(`/activities?page=${page}`);
    activities.value = response.data.data;
    pagination.value = {
      current_page: response.data.current_page,
      last_page: response.data.last_page,
      total: response.data.total,
    };
  } catch (error) {
    console.error('Error loading activities:', error);
  } finally {
    isLoading.value = false;
  }
};

onMounted(() => {
  loadActivities();
});
</script>
