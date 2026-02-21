<template>
  <div class="space-y-6">
    <div class="flex justify-between items-center bg-white p-6 rounded-2xl shadow-sm border border-zinc-100">
      <div class="flex items-center space-x-2 border border-zinc-200 rounded-xl px-4 py-2 bg-zinc-50 w-full max-w-md focus-within:ring-2 focus-within:ring-indigo-500 focus-within:border-transparent transition-all">
        <SearchIcon class="w-5 h-5 text-zinc-400" />
        <input type="text" placeholder="Buscar banners..." class="bg-transparent border-none focus:outline-none w-full text-zinc-800 placeholder-zinc-400" />
      </div>
      
      <router-link to="/banners/create" class="flex items-center space-x-2 bg-gradient-to-r from-indigo-600 to-rose-600 hover:from-indigo-500 hover:to-rose-500 text-white px-5 py-2.5 rounded-xl font-bold shadow-md shadow-indigo-600/20 transform hover:scale-[1.02] transition-all">
        <PlusIcon class="w-5 h-5" />
        <span>Novo Banner</span>
      </router-link>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-zinc-100 overflow-hidden">
      <table class="w-full text-left border-collapse">
        <thead>
          <tr class="bg-zinc-50 border-b border-zinc-200 text-sm font-semibold text-zinc-500 uppercase tracking-wider">
            <th class="p-4 pl-6">Imagem</th>
            <th class="p-4">Título</th>
            <th class="p-4">Status</th>
            <th class="p-4">Data Início</th>
            <th class="p-4">Data Fim</th>
            <th class="p-4 pr-6 text-right">Ações</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-zinc-100">
          <tr v-if="isLoading" class="text-center">
            <td colspan="6" class="p-4 text-zinc-500">Carregando Banners...</td>
          </tr>
          <tr v-else-if="banners.length === 0" class="text-center">
            <td colspan="6" class="p-4 text-zinc-500">Nenhum banner cadastrado.</td>
          </tr>
          <tr v-else v-for="banner in banners" :key="banner.id" class="hover:bg-zinc-50 hover:shadow-inner transition-colors group">
            <td class="p-4 pl-6">
              <div v-if="banner.image_url" class="w-24 h-12 bg-zinc-200 rounded-lg overflow-hidden flex items-center justify-center shadow-sm group-hover:shadow-md transition-shadow">
                <img :src="banner.image_url" class="w-full h-full object-cover" />
              </div>
              <div v-else class="w-24 h-12 bg-zinc-200 rounded-lg overflow-hidden flex items-center justify-center text-zinc-400 shadow-sm group-hover:shadow-md transition-shadow">
                <ImageIcon class="w-5 h-5" />
              </div>
            </td>
            <td class="p-4 font-medium text-zinc-800">{{ banner.title }}</td>
            <td class="p-4">
              <span v-if="banner.is_active" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200 shadow-sm">
                Ativo
              </span>
              <span v-else class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-zinc-100 text-zinc-800 border border-zinc-200 shadow-sm">
                Inativo
              </span>
            </td>
            <td class="p-4 text-zinc-500 text-sm">{{ banner.start_date || '-' }}</td>
            <td class="p-4 text-zinc-500 text-sm">{{ banner.end_date || '-' }}</td>
            <td class="p-4 pr-6 text-right">
              <div class="flex items-center justify-end space-x-3 opacity-0 group-hover:opacity-100 transition-opacity">
                <router-link :to="`/banners/${banner.id}/edit`" class="block text-indigo-600 hover:text-indigo-900 border border-indigo-200 hover:bg-indigo-50 p-2 rounded-lg transition-colors shadow-sm">
                  <EditIcon class="w-4 h-4" />
                </router-link>
                <button @click="deleteBanner(banner.id)" class="text-rose-600 hover:text-rose-900 border border-rose-200 hover:bg-rose-50 p-2 rounded-lg transition-colors shadow-sm">
                  <TrashIcon class="w-4 h-4" />
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
      <div class="p-4 border-t border-zinc-100 bg-zinc-50 flex items-center justify-between text-sm text-zinc-500">
        <span>Mostrando {{ banners.length }} banners</span>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { Image as ImageIcon, Search as SearchIcon, Plus as PlusIcon, Edit2 as EditIcon, Trash2 as TrashIcon } from 'lucide-vue-next';
import api from '../../services/api';

interface Banner {
  id: number;
  title: string;
  image_url: string;
  is_active: boolean;
  start_date?: string;
  end_date?: string;
}

const banners = ref<Banner[]>([]);
const isLoading = ref(true);

const fetchBanners = async () => {
    isLoading.value = true;
    try {
        const response = await api.get('/banners');
        banners.value = response.data;
    } catch (error) {
        console.error('Failed to fetch banners:', error);
    } finally {
        isLoading.value = false;
    }
};

const deleteBanner = async (id: number) => {
    if (confirm('Tem certeza que deseja excluir este banner?')) {
        try {
            await api.delete(`/banners/${id}`);
            fetchBanners();
        } catch (error) {
            console.error('Failed to delete banner:', error);
        }
    }
}

onMounted(() => {
    fetchBanners();
});
</script>
