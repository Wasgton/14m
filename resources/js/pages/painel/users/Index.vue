<template>
  <div class="space-y-6">
    <div class="flex justify-between items-center bg-white p-6 rounded-2xl shadow-sm border border-zinc-100">
      <div class="flex items-center space-x-2 border border-zinc-200 rounded-xl px-4 py-2 bg-zinc-50 w-full max-w-md focus-within:ring-2 focus-within:ring-indigo-500 focus-within:border-transparent transition-all">
        <SearchIcon class="w-5 h-5 text-zinc-400" />
        <input type="text" placeholder="Buscar usuários..." class="bg-transparent border-none focus:outline-none w-full text-zinc-800 placeholder-zinc-400" />
      </div>
      
      <router-link to="/painel/users/create" class="flex items-center space-x-2 bg-gradient-to-r from-indigo-600 to-rose-600 hover:from-indigo-500 hover:to-rose-500 text-white px-5 py-2.5 rounded-xl font-bold shadow-md shadow-indigo-600/20 transform hover:scale-[1.02] transition-all">
        <PlusIcon class="w-5 h-5" />
        <span>Novo Usuário</span>
      </router-link>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-zinc-100 overflow-hidden">
      <table class="w-full text-left border-collapse">
        <thead>
          <tr class="bg-zinc-50 border-b border-zinc-200 text-sm font-semibold text-zinc-500 uppercase tracking-wider">
            <th class="p-4 pl-6 text-center w-16">Avatar</th>
            <th class="p-4">Nome</th>
            <th class="p-4">E-mail</th>
            <th class="p-4">Cargos (Roles)</th>
            <th class="p-4 pr-6 text-right">Ações</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-zinc-100">
          <tr v-if="isLoading" class="text-center">
            <td colspan="5" class="p-4 text-zinc-500">Carregando usuários...</td>
          </tr>
          <tr v-else-if="users.length === 0" class="text-center">
            <td colspan="5" class="p-4 text-zinc-500">Nenhum usuário encontrado.</td>
          </tr>
          <tr v-else v-for="user in users" :key="user.id" class="hover:bg-zinc-50 hover:shadow-inner transition-colors group">
            <td class="p-4 pl-6 flex justify-center">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-rose-500 flex items-center justify-center text-white font-bold shadow-sm">
                    {{ user.name.charAt(0).toUpperCase() }}
                </div>
            </td>
            <td class="p-4 font-medium text-zinc-800">{{ user.name }}</td>
            <td class="p-4 text-zinc-500">{{ user.email }}</td>
            <td class="p-4">
                <div class="flex flex-wrap gap-2">
                    <span v-for="role in user.roles" :key="role.id" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800 border border-indigo-200">
                      {{ role.name }}
                    </span>
                    <span v-if="!user.roles || user.roles.length === 0" class="text-xs text-zinc-400 font-medium">Sem cargo</span>
                </div>
            </td>
            <td class="p-4 pr-6 text-right">
              <div class="flex items-center justify-end space-x-3 opacity-0 group-hover:opacity-100 transition-opacity">
                <router-link :to="`/painel/users/${user.id}/edit`" class="block text-indigo-600 hover:text-indigo-900 border border-indigo-200 hover:bg-indigo-50 p-2 rounded-lg transition-colors shadow-sm">
                  <EditIcon class="w-4 h-4" />
                </router-link>
                <button @click="deleteUser(user.id)" class="text-rose-600 hover:text-rose-900 border border-rose-200 hover:bg-rose-50 p-2 rounded-lg transition-colors shadow-sm">
                  <TrashIcon class="w-4 h-4" />
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
      <div class="p-4 border-t border-zinc-100 bg-zinc-50 flex items-center justify-between text-sm text-zinc-500">
        <span>Mostrando {{ users.length }} usuários</span>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { Search as SearchIcon, Plus as PlusIcon, Edit2 as EditIcon, Trash2 as TrashIcon } from 'lucide-vue-next';
import api from '../../../services/api';
import Swal from 'sweetalert2';

interface UserData {
  id: number;
  name: string;
  email: string;
  roles: { id: number; name: string }[];
}

const users = ref<UserData[]>([]);
const isLoading = ref(true);

const fetchUsers = async () => {
    isLoading.value = true;
    try {
        const response = await api.get('/users');
        users.value = response.data;
    } catch (error) {
        console.error('Failed to fetch users:', error);
    } finally {
        isLoading.value = false;
    }
};

const deleteUser = async (id: number) => {
    const result = await Swal.fire({
        title: 'Tem certeza?',
        text: 'Você deseja excluir este usuário?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#4f46e5',
        cancelButtonColor: '#f43f5e',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    });
    
    if (result.isConfirmed) {
        try {
            await api.delete(`/users/${id}`);
            fetchUsers();
        } catch (error: any) {
             if (error.response && error.response.status === 403) {
                  Swal.fire({ title: 'Atenção!', text: error.response.data.message, icon: 'warning', confirmButtonColor: '#4f46e5' });
             } else {
                  console.error('Failed to delete user:', error);
             }
        }
    }
}

onMounted(() => {
    fetchUsers();
});
</script>
