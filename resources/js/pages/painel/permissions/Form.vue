<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between mb-8">
      <div>
        <h2 class="text-2xl font-bold text-zinc-900">{{ isEditing ? 'Editar Permissão' : 'Nova Permissão' }}</h2>
        <p class="text-sm text-zinc-500 mt-1">Crie chaves de permissão que o código valida no backend e frontend.</p>
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
          <label class="block text-sm font-semibold text-zinc-700 mb-2">Chave da Permissão (ex: `events.create`)</label>
          <input 
            type="text" 
            v-model="form.name"
            required
            placeholder="Ex: edit articles"
            class="block w-full max-w-md px-4 py-3 border border-zinc-200 rounded-xl bg-zinc-50/50 font-mono text-zinc-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all"
            :class="{'border-rose-400 focus:ring-rose-500/50 focus:border-rose-500': errors.name}"
          >
          <p class="mt-2 text-xs text-amber-600 font-medium">Atenção: alterar o nome de uma permissão pode quebrar regras no código que a utilizam estaticamente.</p>
          <p v-if="errors.name" class="mt-1 text-xs text-rose-500">{{ errors.name[0] }}</p>
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
          {{ isEditing ? 'Atualizar Permissão' : 'Salvar Permissão' }}
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

const form = reactive({
  name: '',
});

onMounted(async () => {
  if (route.params.id) {
    isEditing.value = true;
    try {
        const response = await api.get(`/permissions/${route.params.id}`);
        form.name = response.data.name;
    } catch (error) {
        console.error('Failed to fetch permission:', error);
        router.push('/painel/permissions');
    }
  }
});

const goBack = () => {
  router.push('/painel/permissions');
};

const save = async () => {
    isSubmitting.value = true;
    errors.value = {};
    
    // Construct payload
    const payload: any = {
         name: form.name
    };

    try {
        if (isEditing.value) {
            await api.put(`/permissions/${route.params.id}`, payload);
        } else {
            await api.post('/permissions', payload);
        }
        router.push('/painel/permissions');
    } catch (error: any) {
        if (error.response && error.response.status === 422) {
            errors.value = error.response.data.errors;
        } else {
            console.error('Failed to save permission:', error);
            alert('Erro ao salvar a permissão.');
        }
    } finally {
        isSubmitting.value = false;
    }
};
</script>
