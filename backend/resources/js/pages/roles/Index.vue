<template>
  <div class="space-y-6">
    <div class="flex justify-between items-center bg-white p-6 rounded-2xl shadow-sm border border-zinc-100">
      <div class="flex items-center space-x-2 border border-zinc-200 rounded-xl px-4 py-2 bg-zinc-50 w-full max-w-md focus-within:ring-2 focus-within:ring-indigo-500 focus-within:border-transparent transition-all">
        <SearchIcon class="w-5 h-5 text-zinc-400" />
        <input type="text" placeholder="Buscar cargos..." class="bg-transparent border-none focus:outline-none w-full text-zinc-800 placeholder-zinc-400" />
      </div>
      
      <router-link to="/roles/create" class="flex items-center space-x-2 bg-gradient-to-r from-indigo-600 to-rose-600 hover:from-indigo-500 hover:to-rose-500 text-white px-5 py-2.5 rounded-xl font-bold shadow-md shadow-indigo-600/20 transform hover:scale-[1.02] transition-all">
        <PlusIcon class="w-5 h-5" />
        <span>Novo Cargo</span>
      </router-link>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-zinc-100 overflow-hidden">
      <table class="w-full text-left border-collapse">
        <thead>
          <tr class="bg-zinc-50 border-b border-zinc-200 text-sm font-semibold text-zinc-500 uppercase tracking-wider">
            <th class="p-4 pl-6 text-center w-16">ID</th>
            <th class="p-4">Nome do Cargo</th>
            <th class="p-4">Qtd. Permissões Associadas</th>
            <th class="p-4 pr-6 text-right">Ações</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-zinc-100">
          <tr v-if="isLoading" class="text-center">
            <td colspan="4" class="p-4 text-zinc-500">Carregando cargos...</td>
          </tr>
          <tr v-else-if="roles.length === 0" class="text-center">
            <td colspan="4" class="p-4 text-zinc-500">Nenhum cargo encontrado.</td>
          </tr>
          <tr v-else v-for="role in roles" :key="role.id" class="hover:bg-zinc-50 hover:shadow-inner transition-colors group">
            <td class="p-4 pl-6 text-center text-sm font-medium text-zinc-400">#{{ role.id }}</td>
            <td class="p-4 font-bold text-zinc-800">{{ role.name }}</td>
            <td class="p-4">
                <span class="inline-flex items-center justify-center px-2 py-1 bg-zinc-100 text-zinc-700 text-xs font-bold rounded-md border border-zinc-200">
                     {{ role.permissions?.length || 0 }} permissões
                </span>
            </td>
            <td class="p-4 pr-6 text-right">
              <div class="flex items-center justify-end space-x-3 opacity-0 group-hover:opacity-100 transition-opacity">
                <router-link :to="`/roles/${role.id}/edit`" class="block text-indigo-600 hover:text-indigo-900 border border-indigo-200 hover:bg-indigo-50 p-2 rounded-lg transition-colors shadow-sm">
                  <EditIcon class="w-4 h-4" />
                </router-link>
                <button v-if="role.name !== 'admin' && role.name !== 'super-admin'" @click="deleteRole(role.id)" class="text-rose-600 hover:text-rose-900 border border-rose-200 hover:bg-rose-50 p-2 rounded-lg transition-colors shadow-sm">
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
import api from '../../services/api';

interface RoleData {
  id: number;
  name: string;
  permissions: any[];
}

const roles = ref<RoleData[]>([]);
const isLoading = ref(true);

const fetchRoles = async () => {
    isLoading.value = true;
    try {
        const response = await api.get('/roles');
        roles.value = response.data;
    } catch (error) {
        console.error('Failed to fetch roles:', error);
    } finally {
        isLoading.value = false;
    }
};

const deleteRole = async (id: number) => {
    if (confirm('Tem certeza que deseja excluir este cargo?')) {
        try {
            await api.delete(`/roles/${id}`);
            fetchRoles();
        } catch (error: any) {
             if (error.response && error.response.status === 403) {
                  alert(error.response.data.message);
             } else {
                  console.error('Failed to delete role:', error);
             }
        }
    }
}

onMounted(() => {
    fetchRoles();
});
</script>
