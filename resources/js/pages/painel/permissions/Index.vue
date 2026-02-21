<template>
  <div class="space-y-6">
    <div class="flex justify-between items-center bg-white p-6 rounded-2xl shadow-sm border border-zinc-100">
      <div class="flex items-center space-x-2 border border-zinc-200 rounded-xl px-4 py-2 bg-zinc-50 w-full max-w-md focus-within:ring-2 focus-within:ring-indigo-500 focus-within:border-transparent transition-all">
        <SearchIcon class="w-5 h-5 text-zinc-400" />
        <input type="text" placeholder="Buscar permissões..." class="bg-transparent border-none focus:outline-none w-full text-zinc-800 placeholder-zinc-400" />
      </div>
      
      <router-link to="/painel/permissions/create" class="flex items-center space-x-2 bg-gradient-to-r from-indigo-600 to-rose-600 hover:from-indigo-500 hover:to-rose-500 text-white px-5 py-2.5 rounded-xl font-bold shadow-md shadow-indigo-600/20 transform hover:scale-[1.02] transition-all">
        <PlusIcon class="w-5 h-5" />
        <span>Nova Permissão</span>
      </router-link>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-zinc-100 overflow-hidden">
      <table class="w-full text-left border-collapse">
        <thead>
          <tr class="bg-zinc-50 border-b border-zinc-200 text-sm font-semibold text-zinc-500 uppercase tracking-wider">
            <th class="p-4 pl-6 text-center w-16">ID</th>
            <th class="p-4">Chave da Permissão (Key)</th>
            <th class="p-4 pr-6 text-right">Ações</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-zinc-100">
          <tr v-if="isLoading" class="text-center">
            <td colspan="3" class="p-4 text-zinc-500">Carregando permissões...</td>
          </tr>
          <tr v-else-if="permissions.length === 0" class="text-center">
            <td colspan="3" class="p-4 text-zinc-500">Nenhuma permissão encontrada.</td>
          </tr>
          <tr v-else v-for="permission in permissions" :key="permission.id" class="hover:bg-zinc-50 hover:shadow-inner transition-colors group">
            <td class="p-4 pl-6 text-center text-sm font-medium text-zinc-400">#{{ permission.id }}</td>
            <td class="p-4 font-mono text-zinc-800 text-sm font-semibold">{{ permission.name }}</td>
            <td class="p-4 pr-6 text-right">
              <div class="flex items-center justify-end space-x-3 opacity-0 group-hover:opacity-100 transition-opacity">
                <router-link :to="`/painel/permissions/${permission.id}/edit`" class="block text-indigo-600 hover:text-indigo-900 border border-indigo-200 hover:bg-indigo-50 p-2 rounded-lg transition-colors shadow-sm">
                  <EditIcon class="w-4 h-4" />
                </router-link>
                <button @click="deletePermission(permission.id)" class="text-rose-600 hover:text-rose-900 border border-rose-200 hover:bg-rose-50 p-2 rounded-lg transition-colors shadow-sm">
                  <TrashIcon class="w-4 h-4" />
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { Search as SearchIcon, Plus as PlusIcon, Edit2 as EditIcon, Trash2 as TrashIcon } from 'lucide-vue-next';
import api from '../../../services/api';

interface PermissionData {
  id: number;
  name: string;
}

const permissions = ref<PermissionData[]>([]);
const isLoading = ref(true);

const fetchPermissions = async () => {
    isLoading.value = true;
    try {
        const response = await api.get('/permissions');
        permissions.value = response.data;
    } catch (error) {
        console.error('Failed to fetch permissions:', error);
    } finally {
        isLoading.value = false;
    }
};

const deletePermission = async (id: number) => {
    if (confirm('Tem certeza que deseja excluir esta permissão? Essa ação pode quebrar dependências de código (Desejável apenas para Devs).')) {
        try {
            await api.delete(`/permissions/${id}`);
            fetchPermissions();
        } catch (error: any) {
             console.error('Failed to delete permission:', error);
        }
    }
}

onMounted(() => {
    fetchPermissions();
});
</script>
