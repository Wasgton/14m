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
      <div v-if="Object.keys(errors).length > 0" class="mb-6 bg-rose-50 border border-rose-200 p-4 rounded-xl text-sm text-rose-600">
           Existem erros no formulário que precisam ser corrigidos.
      </div>
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">
        <!-- Logo (Coluna da Esquerda) -->
        <div class="lg:col-span-4 flex flex-col items-center">
            <h3 class="text-sm font-semibold text-zinc-700 mb-4 text-center w-full">Logo do Parceiro</h3>
            <div 
                class="relative flex justify-center w-full max-w-[280px] aspect-video sm:aspect-square md:aspect-video rounded-2xl border-4 border-zinc-100 hover:border-indigo-100 transition-colors shadow-sm cursor-pointer group flex-shrink-0 bg-zinc-50 overflow-hidden" 
                @click="triggerFileInput"
            >
                <img v-if="previewUrl" :src="previewUrl" class="w-full h-full object-contain p-4 bg-zinc-800" />
                <div v-else class="w-full h-full flex flex-col items-center justify-center text-zinc-400">
                    <ImageIcon class="w-10 h-10 mb-2" />
                    <span class="text-xs font-semibold text-zinc-500">Upload</span>
                </div>
                
                <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-sm font-bold backdrop-blur-[2px]">
                    Trocar Logo
                </div>
                <input id="partner-file-upload" ref="fileInput" name="partner-file-upload" type="file" class="sr-only" accept="image/*,.svg" @change="handleFileUpload">
            </div>
            <div v-if="fileName && !previewUrl" class="mt-4 text-xs text-indigo-600 font-medium text-center w-full truncate px-4">
                {{ fileName }}
            </div>
            <p v-if="errors.logo" class="mt-2 text-xs text-rose-500 text-center w-full">{{ errors.logo[0] }}</p>
            <p class="mt-4 text-[11px] text-zinc-500 text-center px-4">PNG, JPG ou SVG com fundo transparente recomendado.</p>
        </div>

        <!-- Campos de Texto (Coluna da Direita) -->
        <div class="lg:col-span-8 space-y-6 flex flex-col justify-center">
            <!-- Nome do Parceiro -->
            <div>
              <label class="block text-sm font-semibold text-zinc-700 mb-2">Nome do Parceiro</label>
              <input 
                type="text" 
                v-model="form.name"
                required
                placeholder="Ex: Coca-Cola"
                class="block w-full px-4 py-3 border border-zinc-200 rounded-xl bg-zinc-50/50 text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all"
                :class="{'border-rose-400 focus:ring-rose-500/50 focus:border-rose-500': errors.name}"
              >
              <p v-if="errors.name" class="mt-1 text-xs text-rose-500">{{ errors.name[0] }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Ordem de Exibição -->
                <div>
                  <label class="block text-sm font-semibold text-zinc-700 mb-2">Ordem de Exibição</label>
                  <input 
                    type="number" 
                    v-model="form.order"
                    min="0"
                    class="block w-full px-4 py-3 border border-zinc-200 rounded-xl bg-zinc-50/50 text-zinc-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all"
                    :class="{'border-rose-400 focus:ring-rose-500/50 focus:border-rose-500': errors.order}"
                  >
                  <p v-if="errors.order" class="mt-1 text-xs text-rose-500">{{ errors.order[0] }}</p>
                </div>
                
                <!-- Status -->
                <div>
                  <label class="block text-sm font-semibold text-zinc-700 mb-2">Status</label>
                  <select 
                    v-model="form.is_active"
                    class="block w-full px-4 py-3 border border-zinc-200 rounded-xl bg-zinc-50/50 text-zinc-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all"
                    :class="{'border-rose-400 focus:ring-rose-500/50 focus:border-rose-500': errors.is_active}"
                  >
                    <option :value="true">Ativo</option>
                    <option :value="false">Inativo</option>
                  </select>
                  <p v-if="errors.is_active" class="mt-1 text-xs text-rose-500">{{ errors.is_active[0] }}</p>
                </div>
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
import Swal from 'sweetalert2';

const router = useRouter();
const route = useRoute();

const isEditing = ref(false);
const fileInput = ref<HTMLInputElement | null>(null);
const fileName = ref('');
const previewUrl = ref('');
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
             previewUrl.value = partner.logo_url;
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
    const file = target.files[0];
    fileName.value = file.name;
    previewUrl.value = URL.createObjectURL(file);
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
            errors.value = error.response.data.errors || {};
            
            const errorMessages = Object.values(errors.value)
                .flat()
                .map(msg => `<li>${msg}</li>`)
                .join('');
            
            Swal.fire({
                title: 'Atenção, erro de validação!',
                html: `<ul style="text-align: left; padding-left: 20px;">${errorMessages}</ul>`,
                icon: 'warning',
                confirmButtonColor: '#4f46e5'
            });
        } else {
            console.error('Failed to save partner:', error);
            Swal.fire({ title: 'Erro!', text: 'Erro ao salvar o parceiro.', icon: 'error', confirmButtonColor: '#4f46e5' });
        }
    } finally {
        isSubmitting.value = false;
    }
};
</script>
