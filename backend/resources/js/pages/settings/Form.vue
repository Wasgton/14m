<template>
  <div class="space-y-6">
    <div class="mb-8">
      <h2 class="text-2xl font-bold text-zinc-900">Configurações Gerais</h2>
      <p class="text-sm text-zinc-500 mt-1">Atualize informações do projeto, contatos e logotipo.</p>
    </div>

    <form @submit.prevent="save" class="bg-white rounded-2xl shadow-sm border border-zinc-100 p-6 sm:p-8">
      
      <div v-if="successMessage" class="mb-6 bg-emerald-50 border border-emerald-200 p-4 rounded-xl text-sm text-emerald-600">
           {{ successMessage }}
      </div>
      <div v-if="errorMessage" class="mb-6 bg-rose-50 border border-rose-200 p-4 rounded-xl text-sm text-rose-600">
           {{ errorMessage }}
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Logo Section -->
        <div class="col-span-1 border border-zinc-200 rounded-2xl p-6 bg-zinc-50 flex flex-col items-center justify-center text-center">
            <label class="block text-sm font-semibold text-zinc-700 mb-4 w-full text-left">Logotipo Principal</label>
            <div class="relative w-40 h-40 rounded-full border-4 border-white shadow-lg overflow-hidden bg-zinc-100 flex items-center justify-center mb-4 group cursor-pointer" @click="(logoInput as HTMLInputElement).click()">
                <img v-if="logoPreview || form.logo_url" :src="logoPreview || form.logo_url" class="w-full h-full object-cover">
                <div v-else class="text-zinc-400 font-medium font-mono text-sm">Sem Logo</div>
                <div class="absolute inset-0 bg-black/50 hidden group-hover:flex items-center justify-center transition-all">
                    <span class="text-white font-bold text-sm">Alterar Foto</span>
                </div>
            </div>
            <input type="file" ref="logoInput" class="hidden" accept="image/*" @change="onLogoChange">
            <p class="text-xs text-zinc-500 max-w-[200px]">Clique na imagem para enviar uma nova foto (JPEG, PNG). Recomendado: 500x500px.</p>
        </div>

        <!-- Info Section -->
        <div class="col-span-1 lg:col-span-2 space-y-6">
            <div>
              <label class="block text-sm font-semibold text-zinc-700 mb-2">Nome do Projeto</label>
              <input type="text" v-model="form.project_name" placeholder="Ex: KTORZE M" class="block w-full px-4 py-3 border border-zinc-200 rounded-xl bg-zinc-50/50 text-zinc-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-semibold text-zinc-700 mb-2">E-mail de Contato</label>
                  <input type="email" v-model="form.contact_email" placeholder="contato@empresa.com" class="block w-full px-4 py-3 border border-zinc-200 rounded-xl bg-zinc-50/50 text-zinc-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-zinc-700 mb-2">Telefone / WhatsApp</label>
                  <input type="text" v-model="form.contact_phone" placeholder="(11) 99999-9999" class="block w-full px-4 py-3 border border-zinc-200 rounded-xl bg-zinc-50/50 text-zinc-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all">
                </div>
            </div>
            
            <div>
              <label class="block text-sm font-semibold text-zinc-700 mb-2">Endereço Fiscal (Opcional)</label>
              <input type="text" v-model="form.contact_address" placeholder="Av. Principal, 1000 - Centro" class="block w-full px-4 py-3 border border-zinc-200 rounded-xl bg-zinc-50/50 text-zinc-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-semibold text-zinc-700 mb-2">Facebook URL</label>
                  <input type="url" v-model="form.facebook_url" placeholder="https://facebook.com/pagina" class="block w-full px-4 py-3 border border-zinc-200 rounded-xl bg-zinc-50/50 text-zinc-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all">
                </div>
                <div>
                  <label class="block text-sm font-semibold text-zinc-700 mb-2">Instagram URL</label>
                  <input type="url" v-model="form.instagram_url" placeholder="https://instagram.com/perfil" class="block w-full px-4 py-3 border border-zinc-200 rounded-xl bg-zinc-50/50 text-zinc-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all">
                </div>
            </div>
        </div>
      </div>

      <div class="mt-8 pt-6 border-t border-zinc-100 flex justify-end">
        <button 
          type="submit" 
          :disabled="isSubmitting"
          class="flex justify-center items-center px-6 py-2.5 border border-transparent rounded-xl shadow-md text-sm font-bold text-white bg-gradient-to-r from-indigo-600 to-rose-600 hover:from-indigo-500 hover:to-rose-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all transform hover:scale-[1.02] disabled:opacity-70 disabled:scale-100"
        >
          <span v-if="isSubmitting" class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin mr-2"></span>
          Salvar Configurações
        </button>
      </div>
    </form>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue';
import api from '../../services/api';

const isSubmitting = ref(false);
const successMessage = ref('');
const errorMessage = ref('');

const logoInput = ref<HTMLInputElement | null>(null);
const logoPreview = ref<string | null>(null);
const logoFile = ref<File | null>(null);

const form = reactive({
  project_name: '',
  contact_email: '',
  contact_phone: '',
  contact_address: '',
  facebook_url: '',
  instagram_url: '',
  logo_url: ''
});

onMounted(async () => {
    try {
        const response = await api.get('/settings');
        const settings = response.data;
        
        form.project_name = settings.project_name || '';
        form.contact_email = settings.contact_email || '';
        form.contact_phone = settings.contact_phone || '';
        form.contact_address = settings.contact_address || '';
        form.facebook_url = settings.facebook_url || '';
        form.instagram_url = settings.instagram_url || '';
        form.logo_url = settings.logo || '';
    } catch (e) {
        console.error("Failed to fetch settings", e);
    }
});

const onLogoChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        logoFile.value = target.files[0];
        logoPreview.value = URL.createObjectURL(target.files[0]);
    }
};

const save = async () => {
    isSubmitting.value = true;
    successMessage.value = '';
    errorMessage.value = '';
    
    const formData = new FormData();
    formData.append('_method', 'POST'); // Explicit request
    formData.append('project_name', form.project_name || '');
    formData.append('contact_email', form.contact_email || '');
    formData.append('contact_phone', form.contact_phone || '');
    formData.append('contact_address', form.contact_address || '');
    formData.append('facebook_url', form.facebook_url || '');
    formData.append('instagram_url', form.instagram_url || '');
    
    if (logoFile.value) {
         formData.append('logo', logoFile.value);
    }
    
    try {
        await api.post('/settings', formData, {
             headers: { 'Content-Type': 'multipart/form-data' }
        });
        successMessage.value = 'Configurações atualizadas com sucesso!';
    } catch (error: any) {
        if (error.response && error.response.status === 403) {
             errorMessage.value = error.response.data.message;
        } else {
             errorMessage.value = 'Erro ao salvar as configurações.';
             console.error('Failed to save settings:', error);
        }
    } finally {
        isSubmitting.value = false;
        
        // Hide success message automatically
        setTimeout(() => {
            successMessage.value = '';
        }, 4000);
    }
};
</script>
