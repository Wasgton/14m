<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between mb-8">
      <div>
        <h2 class="text-2xl font-bold text-zinc-900">{{ isEditing ? 'Editar Parceiro' : 'Novo Parceiro' }}</h2>
        <p class="text-sm text-zinc-500 mt-1">Preencha os dados abaixo para {{ isEditing ? 'atualizar' : 'cadastrar' }} o parceiro.</p>
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
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <!-- Nome do Parceiro -->
        <div class="col-span-1 md:col-span-2">
          <label class="block text-sm font-semibold text-zinc-700 mb-2">Nome do Parceiro</label>
          <input 
            type="text" 
            v-model="form.name"
            required
            placeholder="Ex: Coca-Cola"
            class="block w-full px-4 py-3 border border-zinc-200 rounded-xl bg-zinc-50/50 text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all"
          >
        </div>

        <!-- Ordem de Exibição -->
        <div>
          <label class="block text-sm font-semibold text-zinc-700 mb-2">Ordem de Exibição</label>
          <input 
            type="number" 
            v-model="form.order"
            min="0"
            class="block w-full px-4 py-3 border border-zinc-200 rounded-xl bg-zinc-50/50 text-zinc-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all"
          >
        </div>
        
        <!-- Status -->
        <div>
          <label class="block text-sm font-semibold text-zinc-700 mb-2">Status</label>
          <select 
            v-model="form.is_active"
            class="block w-full px-4 py-3 border border-zinc-200 rounded-xl bg-zinc-50/50 text-zinc-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all"
          >
            <option :value="true">Ativo</option>
            <option :value="false">Inativo</option>
          </select>
        </div>

        <!-- Logo -->
        <div class="col-span-1 md:col-span-2">
          <label class="block text-sm font-semibold text-zinc-700 mb-2">Logo do Parceiro</label>
          <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-zinc-300 border-dashed rounded-xl hover:bg-zinc-50 transition-colors cursor-pointer" @click="triggerFileInput">
            <div class="space-y-1 text-center">
              <ImageIcon class="mx-auto h-12 w-12 text-zinc-400" />
              <div class="flex text-sm text-zinc-600 justify-center">
                <label for="partner-file-upload" class="relative cursor-pointer rounded-md font-semibold text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2">
                  <span>Fazer upload do Logo</span>
                  <input id="partner-file-upload" ref="fileInput" name="partner-file-upload" type="file" class="sr-only" accept="image/*" @change="handleFileUpload">
                </label>
              </div>
              <p class="text-xs text-zinc-500">PNG, JPG com fundo transparente recomendado</p>
            </div>
          </div>
          <div v-if="fileName" class="mt-2 text-sm text-indigo-600 font-medium">
            Arquivo selecionado: {{ fileName }}
          </div>
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
          class="flex justify-center px-6 py-2.5 border border-transparent rounded-xl shadow-md text-sm font-bold text-white bg-gradient-to-r from-indigo-600 to-rose-600 hover:from-indigo-500 hover:to-rose-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all transform hover:scale-[1.02]"
        >
          {{ isEditing ? 'Atualizar Parceiro' : 'Salvar Parceiro' }}
        </button>
      </div>
    </form>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { ArrowLeft as ArrowLeftIcon, Image as ImageIcon } from 'lucide-vue-next';
import api from '../../../services/api';

const router = useRouter();
const route = useRoute();

const isEditing = ref(false);
const fileInput = ref<HTMLInputElement | null>(null);
const fileName = ref('');
const isSubmitting = ref(false);
const errors = ref<Record<string, string[]>>({});

const form = reactive({
  name: '',
  is_active: true,
  order: 0,
});

onMounted(async () => {
  if (route.params.id) {
    isEditing.value = true;
    try {
        const response = await api.get(`/partners/${route.params.id}`);
        const partner = response.data;
        form.name = partner.name;
        form.is_active = partner.is_active;
        form.order = partner.order || 0;
        if (partner.logo_url) {
             fileName.value = 'Logo atual carregado';
        }
    } catch (error) {
        console.error('Failed to fetch partner:', error);
        router.push('/painel/parceiros');
    }
  }
});

const goBack = () => {
  router.push('/painel/parceiros');
};

const triggerFileInput = () => {
  fileInput.value?.click();
};

const handleFileUpload = (event: Event) => {
  const target = event.target as HTMLInputElement;
  if (target.files && target.files.length > 0) {
    fileName.value = target.files[0].name;
  }
};

const save = async () => {
    isSubmitting.value = true;
    errors.value = {};
    
    const formData = new FormData();
    formData.append('name', form.name);
    formData.append('is_active', form.is_active ? '1' : '0');
    formData.append('order', form.order.toString());
    
    if (fileInput.value?.files?.length) {
        formData.append('logo', fileInput.value.files[0]);
    }

    if (isEditing.value) {
        formData.append('_method', 'PUT'); // Laravel spoofing for multipart full puts
    }

    try {
        if (isEditing.value) {
            await api.post(`/partners/${route.params.id}`, formData, {
                 headers: { 'Content-Type': 'multipart/form-data' }
            });
        } else {
            await api.post('/partners', formData, {
                 headers: { 'Content-Type': 'multipart/form-data' }
            });
        }
        router.push('/painel/parceiros');
    } catch (error: any) {
        if (error.response && error.response.status === 422) {
            errors.value = error.response.data.errors;
        } else {
            console.error('Failed to save partner:', error);
            alert('Erro ao salvar o parceiro.');
        }
    } finally {
        isSubmitting.value = false;
    }
};
</script>
