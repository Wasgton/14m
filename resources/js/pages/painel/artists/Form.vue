<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between mb-8">
      <div>
        <h2 class="text-2xl font-bold text-zinc-900">{{ isEditing ? 'Editar Artista' : 'Novo Artista' }}</h2>
        <p class="text-sm text-zinc-500 mt-1">Preencha os dados abaixo para {{ isEditing ? 'atualizar' : 'cadastrar' }} o artista.</p>
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
        <!-- Foto (Coluna da Esquerda) -->
        <div class="lg:col-span-4 flex flex-col items-center">
            <h3 class="text-sm font-semibold text-zinc-700 mb-4 text-center w-full">Foto de Perfil</h3>
            <div 
                class="relative flex justify-center w-48 h-48 sm:w-56 sm:h-56 rounded-full border-4 border-zinc-100 hover:border-indigo-100 transition-colors shadow-sm cursor-pointer group flex-shrink-0 bg-zinc-50 overflow-hidden" 
                @click="triggerFileInput"
            >
                <img v-if="previewUrl" :src="previewUrl" class="w-full h-full object-cover" />
                <div v-else class="w-full h-full flex flex-col items-center justify-center text-zinc-400">
                    <ImageIcon class="w-10 h-10 mb-2" />
                    <span class="text-xs font-semibold text-zinc-500">Upload</span>
                </div>
                
                <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-sm font-bold backdrop-blur-[2px]">
                    Trocar Foto
                </div>
                <input id="artist-file-upload" ref="fileInput" name="artist-file-upload" type="file" class="sr-only" accept="image/*" @change="handleFileUpload">
            </div>
            <div v-if="fileName && !previewUrl" class="mt-4 text-xs text-indigo-600 font-medium text-center w-full truncate px-4">
                {{ fileName }}
            </div>
            <p v-if="errors.image" class="mt-2 text-xs text-rose-500 text-center w-full">{{ errors.image[0] }}</p>
            <p class="mt-4 text-[11px] text-zinc-500 text-center px-4">Use formatos JPG ou PNG e o tamanho ideal que foque no rosto/busto do artista.</p>
        </div>

        <!-- Campos de Texto (Coluna da Direita) -->
        <div class="lg:col-span-8 space-y-6 flex flex-col justify-center">
            <!-- Nome do Artista -->
            <div>
              <label class="block text-sm font-semibold text-zinc-700 mb-2">Nome do Artista</label>
              <input 
                type="text" 
                v-model="form.name"
                required
                placeholder="Ex: DJ Alok"
                class="block w-full px-4 py-3 border border-zinc-200 rounded-xl bg-zinc-50/50 text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all"
                :class="{'border-rose-400 focus:ring-rose-500/50 focus:border-rose-500': errors.name}"
              >
              <p v-if="errors.name" class="mt-1 text-xs text-rose-500">{{ errors.name[0] }}</p>
            </div>

            <!-- Genero -->
            <div>
              <label class="block text-sm font-semibold text-zinc-700 mb-2">Gênero Musical (Opcional)</label>
              <input 
                type="text" 
                v-model="form.genre"
                placeholder="Ex: Eletrônica"
                class="block w-full px-4 py-3 border border-zinc-200 rounded-xl bg-zinc-50/50 text-zinc-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all"
                :class="{'border-rose-400 focus:ring-rose-500/50 focus:border-rose-500': errors.genre}"
              >
              <p v-if="errors.genre" class="mt-1 text-xs text-rose-500">{{ errors.genre[0] }}</p>
            </div>

            <!-- Instagram -->
            <div>
              <label class="block text-sm font-semibold text-zinc-700 mb-2">Instagram (URL ou @)</label>
              <input 
                type="text" 
                v-model.trim="form.instagram_url"
                placeholder="Ex: @usuario ou https://instagram.com/..."
                class="block w-full px-4 py-3 border border-zinc-200 rounded-xl bg-zinc-50/50 text-zinc-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all"
                :class="{'border-rose-400 focus:ring-rose-500/50 focus:border-rose-500': errors.instagram_url}"
              >
              <p v-if="errors.instagram_url" class="mt-1 text-xs text-rose-500">{{ errors.instagram_url[0] }}</p>
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
          {{ isEditing ? 'Atualizar Artista' : 'Salvar Artista' }}
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
  genre: '',
  instagram_url: '',
});

onMounted(async () => {
  if (route.params.id) {
    isEditing.value = true;
    try {
        const response = await api.get(`/artists/${route.params.id}`);
        const artist = response.data;
        form.name = artist.name;
        form.genre = artist.genre || '';
        form.instagram_url = artist.instagram_url || '';
        if (artist.image_url) {
             previewUrl.value = artist.image_url;
             fileName.value = 'Foto atual carregada';
        }
    } catch (error) {
        console.error('Failed to fetch artist:', error);
        router.push('/painel/artistas');
    }
  }
});

const goBack = () => {
  router.push('/painel/artistas');
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
    if (form.genre) formData.append('genre', form.genre);
    if (form.instagram_url) formData.append('instagram_url', form.instagram_url);
    
    if (fileInput.value?.files?.length) {
        formData.append('image', fileInput.value.files[0]);
    }

    if (isEditing.value) {
        formData.append('_method', 'PUT'); // Laravel spoofing for multipart full puts
    }

    try {
        if (isEditing.value) {
            await api.post(`/artists/${route.params.id}`, formData, {
                 headers: { 'Content-Type': 'multipart/form-data' }
            });
        } else {
            await api.post('/artists', formData, {
                 headers: { 'Content-Type': 'multipart/form-data' }
            });
        }
        router.push('/painel/artistas');
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
            console.error('Failed to save artist:', error);
            Swal.fire({ title: 'Erro!', text: 'Erro ao salvar o artista.', icon: 'error', confirmButtonColor: '#4f46e5' });
        }
    } finally {
        isSubmitting.value = false;
    }
};
</script>
