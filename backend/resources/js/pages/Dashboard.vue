<template>
  <div class="space-y-6">
    <!-- Keep content energetic and modern -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      <div class="bg-white rounded-2xl shadow-sm border border-zinc-100 p-6 flex items-center justify-between hover:shadow-md transition-shadow">
        <div>
          <p class="text-sm font-medium text-zinc-500 mb-1">Total de Eventos</p>
          <p class="text-3xl font-black text-zinc-900">
             <span v-if="isLoading">...</span>
             <span v-else>{{ stats.total_events }}</span>
          </p>
        </div>
        <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center">
          <CalendarIcon class="w-6 h-6 text-indigo-600" />
        </div>
      </div>
      
      <div class="bg-white rounded-2xl shadow-sm border border-zinc-100 p-6 flex items-center justify-between hover:shadow-md transition-shadow">
        <div>
          <p class="text-sm font-medium text-zinc-500 mb-1">Banners Ativos</p>
          <p class="text-3xl font-black text-zinc-900">
             <span v-if="isLoading">...</span>
             <span v-else>{{ stats.active_banners }}</span>
          </p>
        </div>
        <div class="w-12 h-12 bg-rose-50 rounded-xl flex items-center justify-center">
          <ImageIcon class="w-6 h-6 text-rose-600" />
        </div>
      </div>

      <div class="bg-white rounded-2xl shadow-sm border border-zinc-100 p-6 flex items-center justify-between hover:shadow-md transition-shadow">
        <div>
          <p class="text-sm font-medium text-zinc-500 mb-1">Parceiros Ativos</p>
          <p class="text-3xl font-black text-zinc-900">
             <span v-if="isLoading">...</span>
             <span v-else>{{ stats.active_partners }}</span>
          </p>
        </div>
        <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center">
          <UsersIcon class="w-6 h-6 text-amber-600" />
        </div>
      </div>

      <div class="bg-white rounded-2xl shadow-sm border border-zinc-100 p-6 flex items-center justify-between hover:shadow-md transition-shadow">
        <div>
          <p class="text-sm font-medium text-zinc-500 mb-1">Acessos na Semana</p>
          <p class="text-3xl font-black text-zinc-900">
             <span v-if="isLoading">...</span>
             <span v-else>{{ stats.weekly_access }}</span>
          </p>
        </div>
        <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center">
          <TrendingUpIcon class="w-6 h-6 text-emerald-600" />
        </div>
      </div>
    </div>

    <!-- Chart mock / recent activities -->
    <div class="bg-white rounded-3xl shadow-sm border border-zinc-100 p-8 shadow-inner overflow-hidden relative">
      <div class="absolute -right-20 -top-20 w-64 h-64 bg-indigo-600/5 rounded-full blur-3xl pointer-events-none"></div>
      <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-rose-600/5 rounded-full blur-3xl pointer-events-none"></div>
      
      <div class="flex items-center justify-between mb-8 relative z-10">
        <h3 class="text-lg font-bold text-zinc-900">Atividades Recentes</h3>
        <router-link to="/atividades" class="text-sm font-semibold text-indigo-600 hover:text-indigo-700 bg-indigo-50 px-4 py-2 rounded-lg transition-colors inline-block">
          Ver todas
        </router-link>
      </div>

      <div class="space-y-6 relative z-10">
        <div v-if="isLoading" class="flex justify-center py-6">
           <div class="w-6 h-6 border-2 border-indigo-600 border-t-transparent rounded-full animate-spin"></div>
        </div>
        <div v-else-if="!recentActivities.length" class="text-zinc-500 text-center py-6 font-medium">
           Nenhuma atividade registrada hoje.
        </div>
        <div v-else v-for="activity in recentActivities" :key="activity.id" class="flex items-start space-x-4 pb-6 border-b border-zinc-100 last:border-0 last:pb-0">
          <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0 mt-1">
            <ActivityIcon class="w-5 h-5 text-indigo-600" />
          </div>
          <div>
            <p class="font-medium text-zinc-900">{{ activity.message }}</p>
            <p class="text-sm text-zinc-500 mt-1">Por {{ activity.causer }} - {{ activity.created_at_human }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { Calendar as CalendarIcon, Image as ImageIcon, Users as UsersIcon, TrendingUp as TrendingUpIcon, Activity as ActivityIcon } from 'lucide-vue-next';
import api from '../services/api';

const isLoading = ref(true);
const stats = ref({
    total_events: 0,
    active_banners: 0,
    active_partners: 0,
    weekly_access: "0",
});

const recentActivities = ref<any[]>([]);

onMounted(async () => {
    try {
        const response = await api.get('/dashboard');
        stats.value = response.data.stats;
        recentActivities.value = response.data.recent_activities;
    } catch (e) {
        console.error("Erro ao puxar dados do dashboard.", e);
    } finally {
        isLoading.value = false;
    }
});
</script>
