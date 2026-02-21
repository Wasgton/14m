<template>
  <div class="space-y-6">
    <div class="flex justify-between items-center bg-white p-6 rounded-2xl shadow-sm border border-zinc-100">
      <div class="flex items-center space-x-2 border border-zinc-200 rounded-xl px-4 py-2 bg-zinc-50 w-full max-w-md focus-within:ring-2 focus-within:ring-indigo-500 focus-within:border-transparent transition-all">
        <SearchIcon class="w-5 h-5 text-zinc-400" />
        <input type="text" placeholder="Buscar parceiros..." class="bg-transparent border-none focus:outline-none w-full text-zinc-800 placeholder-zinc-400" />
      </div>
      
      <router-link to="/painel/parceiros/create" class="flex items-center space-x-2 bg-gradient-to-r from-indigo-600 to-rose-600 hover:from-indigo-500 hover:to-rose-500 text-white px-5 py-2.5 rounded-xl font-bold shadow-md shadow-indigo-600/20 transform hover:scale-[1.02] transition-all">
        <PlusIcon class="w-5 h-5" />
        <span>Novo Parceiro</span>
      </router-link>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-zinc-100 overflow-hidden">
      <table class="w-full text-left border-collapse">
        <thead>
          <tr class="bg-zinc-50 border-b border-zinc-200 text-sm font-semibold text-zinc-500 uppercase tracking-wider">
            <th class="p-4 pl-6">Logo</th>
            <th class="p-4">Nome</th>
            <th class="p-4">Status</th>
            <th class="p-4 pr-6 text-right">Ações</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-zinc-100">
          <tr v-if="isLoading" class="text-center">
            <td colspan="4" class="p-4 text-zinc-500">Carregando Parceiros...</td>
          </tr>
          <tr v-else-if="partners.length === 0" class="text-center">
            <td colspan="4" class="p-4 text-zinc-500">Nenhum parceiro cadastrado.</td>
          </tr>
          <tr v-else v-for="partner in partners" :key="partner.id" class="hover:bg-zinc-50 hover:shadow-inner transition-colors group">
            <td class="p-4 pl-6">
              <div v-if="partner.logo_url" class="w-24 h-12 bg-zinc-200 rounded-lg overflow-hidden flex items-center justify-center shadow-sm group-hover:shadow-md transition-shadow">
                <img :src="partner.logo_url" class="w-full h-full object-contain p-2" />
              </div>
              <div v-else class="w-24 h-12 bg-zinc-200 rounded-lg overflow-hidden flex items-center justify-center text-zinc-400 shadow-sm group-hover:shadow-md transition-shadow">
                <ImageIcon class="w-5 h-5" />
              </div>
            </td>
            <td class="p-4 font-medium text-zinc-800">{{ partner.name }}</td>
            <td class="p-4">
              <span v-if="partner.is_active" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200 shadow-sm">
                Ativo
              </span>
              <span v-else class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-zinc-100 text-zinc-800 border border-zinc-200 shadow-sm">
                Inativo
              </span>
            </td>
            <td class="p-4 pr-6 text-right">
              <div class="flex items-center justify-end space-x-3 opacity-0 group-hover:opacity-100 transition-opacity">
                <router-link :to="`/painel/parceiros/${partner.id}/edit`" class="block text-indigo-600 hover:text-indigo-900 border border-indigo-200 hover:bg-indigo-50 p-2 rounded-lg transition-colors shadow-sm">
                  <EditIcon class="w-4 h-4" />
                </router-link>
                <button @click="deletePartner(partner.id)" class="text-rose-600 hover:text-rose-900 border border-rose-200 hover:bg-rose-50 p-2 rounded-lg transition-colors shadow-sm">
                  <TrashIcon class="w-4 h-4" />
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
      <!--
      <div class="p-4 border-t border-zinc-100 bg-zinc-50 flex items-center justify-between text-sm text-zinc-500">
        <span>Mostrando {{ partners.length }} parceiros</span>
      </div>
      -->
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { Image as ImageIcon, Search as SearchIcon, Plus as PlusIcon, Edit2 as EditIcon, Trash2 as TrashIcon } from 'lucide-vue-next';
import api from '../../../services/api';
import Swal from 'sweetalert2';

interface Partner {
  id: number;
  name: string;
  logo_url: string;
  is_active: boolean;
  order: number;
}

const partners = ref<Partner[]>([]);
const isLoading = ref(true);

const fetchPartners = async () => {
    isLoading.value = true;
    try {
        const response = await api.get('/partners');
        partners.value = response.data;
    } catch (error) {
        console.error('Failed to fetch partners:', error);
    } finally {
        isLoading.value = false;
    }
};

const deletePartner = async (id: number) => {
    const result = await Swal.fire({
        title: 'Tem certeza?',
        text: 'Você deseja excluir este parceiro?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#4f46e5',
        cancelButtonColor: '#f43f5e',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    });
    
    if (result.isConfirmed) {
        try {
            await api.delete(`/partners/${id}`);
            fetchPartners();
        } catch (error) {
            console.error('Failed to delete partner:', error);
        }
    }
}

onMounted(() => {
    fetchPartners();
});
</script>
