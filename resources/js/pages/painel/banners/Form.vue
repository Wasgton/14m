<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between mb-8">
      <div>
        <h2 class="text-2xl font-bold text-zinc-900">{{ isEditing ? 'Editar Banner' : 'Novo Banner' }}</h2>
        <p class="text-sm text-zinc-500 mt-1">Preencha os dados abaixo para {{ isEditing ? 'atualizar' : 'cadastrar' }} o banner.</p>
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
        
        <!-- Título -->
        <div class="col-span-1 md:col-span-2">
          <label class="block text-sm font-semibold text-zinc-700 mb-2">Título do Banner</label>
          <input 
            type="text" 
            v-model="form.title"
            required
            placeholder="Ex: Festival de Verão 2026"
            class="block w-full px-4 py-3 border border-zinc-200 rounded-xl bg-zinc-50/50 text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all"
            :class="{'border-rose-400 focus:ring-rose-500/50 focus:border-rose-500': errors.title}"
          >
          <p v-if="errors.title" class="mt-1 text-xs text-rose-500">{{ errors.title[0] }}</p>
        </div>

        <!-- Subtítulo -->
        <div class="col-span-1 md:col-span-2">
          <label class="block text-sm font-semibold text-zinc-700 mb-2">Subtítulo do Banner</label>
          <input 
            type="text" 
            v-model="form.subtitle"
            placeholder="Ex: De 12 a 15 de Janeiro"
            class="block w-full px-4 py-3 border border-zinc-200 rounded-xl bg-zinc-50/50 text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all"
            :class="{'border-rose-400 focus:ring-rose-500/50 focus:border-rose-500': errors.subtitle}"
          >
          <p v-if="errors.subtitle" class="mt-1 text-xs text-rose-500">{{ errors.subtitle[0] }}</p>
        </div>

        <!-- Texto do Botão -->
        <div>
          <label class="block text-sm font-semibold text-zinc-700 mb-2">Texto do Botão (Opcional)</label>
          <input 
            type="text" 
            v-model="form.button_text"
            placeholder="Ex: Compre Agora"
            class="block w-full px-4 py-3 border border-zinc-200 rounded-xl bg-zinc-50/50 text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all"
            :class="{'border-rose-400 focus:ring-rose-500/50 focus:border-rose-500': errors.button_text}"
          >
          <p v-if="errors.button_text" class="mt-1 text-xs text-rose-500">{{ errors.button_text[0] }}</p>
        </div>

        <!-- Link do Botão -->
        <div>
          <label class="block text-sm font-semibold text-zinc-700 mb-2">Link do Botão (Opcional)</label>
          <input 
            type="text" 
            v-model.trim="form.button_link"
            placeholder="https://..."
            class="block w-full px-4 py-3 border border-zinc-200 rounded-xl bg-zinc-50/50 text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all"
            :class="{'border-rose-400 focus:ring-rose-500/50 focus:border-rose-500': errors.button_link}"
          >
          <p v-if="errors.button_link" class="mt-1 text-xs text-rose-500">{{ errors.button_link[0] }}</p>
        </div>

        <!-- Ordem -->
        <div>
          <label class="block text-sm font-semibold text-zinc-700 mb-2">Ordem de Exibição</label>
          <input 
            type="number" 
            v-model="form.order"
            class="block w-full px-4 py-3 border border-zinc-200 rounded-xl bg-zinc-50/50 text-zinc-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all"
            :class="{'border-rose-400 focus:ring-rose-500/50 focus:border-rose-500': errors.order}"
          >
          <p v-if="errors.order" class="mt-1 text-xs text-rose-500">{{ errors.order[0] }}</p>
        </div>
        
        <!-- Status -->
        <div>
          <label class="block text-sm font-semibold text-zinc-700 mb-2">Status</label>
          <select 
            v-model="form.status"
            class="block w-full px-4 py-3 border border-zinc-200 rounded-xl bg-zinc-50/50 text-zinc-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all"
            :class="{'border-rose-400 focus:ring-rose-500/50 focus:border-rose-500': errors.status}"
          >
            <option value="active">Ativo</option>
            <option value="inactive">Inativo</option>
          </select>
          <p v-if="errors.status" class="mt-1 text-xs text-rose-500">{{ errors.status[0] }}</p>
        </div>

        <!-- Imagem -->
        <div class="col-span-1 md:col-span-2">
          <label class="block text-sm font-semibold text-zinc-700 mb-2">Imagem do Banner</label>
          <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-zinc-300 border-dashed rounded-xl hover:bg-zinc-50 transition-colors cursor-pointer" @click="triggerFileInput">
            <div class="space-y-1 text-center">
              <ImageIcon class="mx-auto h-12 w-12 text-zinc-400" />
              <div class="flex text-sm text-zinc-600 justify-center">
                <label for="file-upload" class="relative cursor-pointer rounded-md font-semibold text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2">
                  <span>Fazer upload de uma imagem</span>
                  <input id="file-upload" ref="fileInput" name="file-upload" type="file" class="sr-only" accept="image/*" @change="handleFileUpload">
                </label>
              </div>
              <p class="text-xs text-zinc-500">PNG, JPG, GIF até 10MB</p>
            </div>
          </div>
          <div v-if="fileName" class="mt-2 text-sm text-indigo-600 font-medium">
            Arquivo selecionado: {{ fileName }}
          </div>
          <p v-if="errors.image" class="mt-1 text-xs text-rose-500">{{ errors.image[0] }}</p>
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
          {{ isEditing ? 'Atualizar Banner' : 'Salvar Banner' }}
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
const isSubmitting = ref(false);
const errors = ref<Record<string, string[]>>({});

const form = reactive({
  title: '',
  subtitle: '',
  button_text: '',
  button_link: '',
  order: 0,
  status: 'active',
});

onMounted(async () => {
  if (route.params.id) {
    isEditing.value = true;
    try {
        const response = await api.get(`/banners/${route.params.id}`);
        const banner = response.data;
        form.title = banner.title || '';
        form.subtitle = banner.subtitle || '';
        form.button_text = banner.button_text || '';
        form.button_link = banner.button_link || '';
        form.order = banner.order || 0;
        form.status = banner.is_active ? 'active' : 'inactive';
        if (banner.image_url) {
             fileName.value = 'Imagem atual carregada';
        }
    } catch (error) {
        console.error('Failed to fetch banner:', error);
        router.push('/painel/banners');
    }
  }
});

const goBack = () => {
  router.push('/painel/banners');
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
    formData.append('title', form.title);
    formData.append('is_active', form.status === 'active' ? '1' : '0');
    if (form.subtitle) formData.append('subtitle', form.subtitle);
    if (form.button_text) formData.append('button_text', form.button_text);
    if (form.button_link) formData.append('button_link', form.button_link);
    formData.append('order', String(form.order));
    
    if (fileInput.value?.files?.length) {
        formData.append('image', fileInput.value.files[0]);
    }

    if (isEditing.value) {
        formData.append('_method', 'PUT'); // Laravel spoofing for multipart full puts
    }

    try {
        if (isEditing.value) {
            await api.post(`/banners/${route.params.id}`, formData, {
                 headers: { 'Content-Type': 'multipart/form-data' }
            });
        } else {
            await api.post('/banners', formData, {
                 headers: { 'Content-Type': 'multipart/form-data' }
            });
        }
        router.push('/painel/banners');
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
            console.error('Failed to save banner:', error);
            Swal.fire({ title: 'Erro!', text: 'Erro ao salvar o banner.', icon: 'error', confirmButtonColor: '#4f46e5' });
        }
    } finally {
        isSubmitting.value = false;
    }
};
</script>
