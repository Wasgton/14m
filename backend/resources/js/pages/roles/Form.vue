<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between mb-8">
      <div>
        <h2 class="text-2xl font-bold text-zinc-900">{{ isEditing ? 'Editar Cargo' : 'Novo Cargo' }}</h2>
        <p class="text-sm text-zinc-500 mt-1">Defina o nome do cargo e associe as permissões de acesso ao sistema correspondentes.</p>
      </div>
      <button 
        @click="goBack"
        class="flex items-center space-x-2 px-4 py-2 border border-zinc-200 rounded-xl text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900 transition-colors bg-white shadow-sm font-medium"
      >
        <ArrowLeftIcon class="w-4 h-4" />
        <span>Voltar</span>
      </button>
    </div>

    <form @submit.prevent="save" class="bg-white rounded-2xl shadow-sm border border-zinc-100 p-6 sm:p-8">
      
      <div v-if="Object.keys(errors).length > 0" class="mb-6 bg-rose-50 border border-rose-200 p-4 rounded-xl text-sm text-rose-600">
           Existem erros no formulário que precisam ser corrigidos.
      </div>

      <div class="mb-8">
          <label class="block text-sm font-semibold text-zinc-700 mb-2">Nome do Cargo</label>
          <input 
            type="text" 
            v-model="form.name"
            required
            :disabled="form.name === 'admin' || form.name === 'super-admin'"
            placeholder="Ex: Editor de Conteúdo"
            class="block w-full max-w-md px-4 py-3 border border-zinc-200 rounded-xl bg-zinc-50/50 text-zinc-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all disabled:opacity-60"
            :class="{'border-rose-400 focus:ring-rose-500/50 focus:border-rose-500': errors.name}"
          >
          <p v-if="errors.name" class="mt-1 text-xs text-rose-500">{{ errors.name[0] }}</p>
      </div>

      <div class="border-t border-zinc-100 pt-6">
          <label class="block text-sm font-semibold text-zinc-700 mb-4">Permissões de Acesso</label>
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
               <label v-for="perm in availablePermissions" :key="perm.id" class="flex items-center justify-between p-3 border border-zinc-200 rounded-xl cursor-pointer hover:bg-zinc-50 transition-colors" :class="{'ring-2 ring-indigo-500 border-indigo-500 bg-indigo-50/30': eventPermissions.includes(perm.name)}">
                   <span class="text-sm font-medium text-zinc-900 truncate mr-3">{{ perm.name }}</span>
                   <input type="checkbox" :value="perm.name" v-model="eventPermissions" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-zinc-300 rounded flex-shrink-0">
               </label>
          </div>
      </div>

      <div class="mt-8 pt-6 border-t border-zinc-100 flex justify-end">
        <button 
          type="button" 
          @click="goBack"
          class="mr-4 bg-white px-5 py-2.5 border border-zinc-200 rounded-xl shadow-sm text-sm font-bold text-zinc-700 hover:bg-zinc-50 transition-colors"
        >
          Cancelar
        </button>
        <button 
          type="submit" 
          :disabled="isSubmitting"
          class="flex justify-center items-center px-6 py-2.5 border border-transparent rounded-xl shadow-md text-sm font-bold text-white bg-gradient-to-r from-indigo-600 to-rose-600 hover:from-indigo-500 hover:to-rose-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all transform hover:scale-[1.02] disabled:opacity-70 disabled:scale-100"
        >
          <span v-if="isSubmitting" class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin mr-2"></span>
          {{ isEditing ? 'Atualizar Cargo' : 'Salvar Cargo' }}
        </button>
      </div>
    </form>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { ArrowLeft as ArrowLeftIcon } from 'lucide-vue-next';
import api from '../../services/api';

const router = useRouter();
const route = useRoute();

const isEditing = ref(false);
const isSubmitting = ref(false);
const errors = ref<Record<string, string[]>>({});

const availablePermissions = ref<{id: number, name: string}[]>([]);
const eventPermissions = ref<string[]>([]);

const form = reactive({
  name: '',
});

onMounted(async () => {
  // Fetch available perms
  try {
      const permsRes = await api.get('/permissions');
      availablePermissions.value = permsRes.data;
  } catch (e) {
      console.error("Failed to load permissions", e);
  }

  if (route.params.id) {
    isEditing.value = true;
    try {
        const response = await api.get(`/roles/${route.params.id}`);
        const role = response.data;
        
        form.name = role.name;
        if(role.permissions) {
             eventPermissions.value = role.permissions.map((p: any) => p.name);
        }
    } catch (error) {
        console.error('Failed to fetch role:', error);
        router.push('/roles');
    }
  }
});

const goBack = () => {
  router.push('/roles');
};

const save = async () => {
    isSubmitting.value = true;
    errors.value = {};
    
    // Construct payload
    const payload: any = {
         name: form.name,
         permissions: eventPermissions.value
    };

    try {
        if (isEditing.value) {
            await api.put(`/roles/${route.params.id}`, payload);
        } else {
            await api.post('/roles', payload);
        }
        router.push('/roles');
    } catch (error: any) {
        if (error.response && error.response.status === 422) {
            errors.value = error.response.data.errors;
        } else {
            console.error('Failed to save role:', error);
            alert('Erro ao salvar o cargo.');
        }
    } finally {
        isSubmitting.value = false;
    }
};
</script>
