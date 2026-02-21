<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between mb-8">
      <div>
        <h2 class="text-2xl font-bold text-zinc-900">{{ isEditing ? 'Editar Usuário' : 'Novo Usuário' }}</h2>
        <p class="text-sm text-zinc-500 mt-1">Defina as credenciais e cargos (roles) deste usuário.</p>
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

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <!-- Nome -->
        <div>
          <label class="block text-sm font-semibold text-zinc-700 mb-2">Nome Completo</label>
          <input 
            type="text" 
            v-model="form.name"
            required
            class="block w-full px-4 py-3 border border-zinc-200 rounded-xl bg-zinc-50/50 text-zinc-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all"
            :class="{'border-rose-400 focus:ring-rose-500/50 focus:border-rose-500': errors.name}"
          >
          <p v-if="errors.name" class="mt-1 text-xs text-rose-500">{{ errors.name[0] }}</p>
        </div>

        <!-- E-mail -->
        <div>
          <label class="block text-sm font-semibold text-zinc-700 mb-2">E-mail</label>
          <input 
            type="email" 
            v-model="form.email"
            required
            class="block w-full px-4 py-3 border border-zinc-200 rounded-xl bg-zinc-50/50 text-zinc-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all"
            :class="{'border-rose-400 focus:ring-rose-500/50 focus:border-rose-500': errors.email}"
          >
          <p v-if="errors.email" class="mt-1 text-xs text-rose-500">{{ errors.email[0] }}</p>
        </div>
        
        <!-- Senha -->
        <div>
          <label class="block text-sm font-semibold text-zinc-700 mb-2">
            {{ isEditing ? 'Nova Senha (deixe em branco para não alterar)' : 'Senha' }}
          </label>
          <input 
            type="password" 
            v-model="form.password"
            :required="!isEditing"
            class="block w-full px-4 py-3 border border-zinc-200 rounded-xl bg-zinc-50/50 text-zinc-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all"
            :class="{'border-rose-400 focus:ring-rose-500/50 focus:border-rose-500': errors.password}"
          >
          <p v-if="errors.password" class="mt-1 text-xs text-rose-500">{{ errors.password[0] }}</p>
        </div>

      </div>

      <div class="mt-8 border-t border-zinc-100 pt-6">
          <label class="block text-sm font-semibold text-zinc-700 mb-4">Cargos (Roles)</label>
          <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
               <label v-for="role in availableRoles" :key="role.id" class="flex items-center space-x-3 p-3 border border-zinc-200 rounded-xl cursor-pointer hover:bg-zinc-50 transition-colors" :class="{'ring-2 ring-indigo-500 border-indigo-500 bg-indigo-50/30': eventRoles.includes(role.name)}">
                   <input type="checkbox" :value="role.name" v-model="eventRoles" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-zinc-300 rounded">
                   <span class="text-sm font-medium text-zinc-900">{{ role.name }}</span>
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
          {{ isEditing ? 'Atualizar Usuário' : 'Salvar Usuário' }}
        </button>
      </div>
    </form>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { ArrowLeft as ArrowLeftIcon } from 'lucide-vue-next';
import api from '../../../services/api';

const router = useRouter();
const route = useRoute();

const isEditing = ref(false);
const isSubmitting = ref(false);
const errors = ref<Record<string, string[]>>({});

const availableRoles = ref<{id: number, name: string}[]>([]);
const eventRoles = ref<string[]>([]);

const form = reactive({
  name: '',
  email: '',
  password: '',
});

onMounted(async () => {
  // Fetch available roles
  try {
      const rolesRes = await api.get('/roles');
      availableRoles.value = rolesRes.data;
  } catch (e) {
      console.error("Failed to load roles", e);
  }

  if (route.params.id) {
    isEditing.value = true;
    try {
        const response = await api.get(`/users/${route.params.id}`);
        const user = response.data;
        
        form.name = user.name;
        form.email = user.email;
        if(user.roles) {
             eventRoles.value = user.roles.map((r: any) => r.name);
        }
    } catch (error) {
        console.error('Failed to fetch user:', error);
        router.push('/painel/users');
    }
  }
});

const goBack = () => {
  router.push('/painel/users');
};

const save = async () => {
    isSubmitting.value = true;
    errors.value = {};
    
    // Construct payload
    const payload: any = {
         name: form.name,
         email: form.email,
         roles: eventRoles.value
    };

    if (form.password) {
         payload.password = form.password;
    }

    try {
        if (isEditing.value) {
            await api.put(`/users/${route.params.id}`, payload);
        } else {
            await api.post('/users', payload);
        }
        router.push('/painel/users');
    } catch (error: any) {
        if (error.response && error.response.status === 422) {
            errors.value = error.response.data.errors;
        } else {
            console.error('Failed to save user:', error);
            alert('Erro ao salvar o usuário.');
        }
    } finally {
        isSubmitting.value = false;
    }
};
</script>
