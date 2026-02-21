<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { Mail as MailIcon, MapPin as MapPinIcon, Loader2 as LoaderIcon, CheckCircle2 as CheckIcon } from 'lucide-vue-next';
import api from '../../services/api';
import Swal from 'sweetalert2';

const isSubmitting = ref(false);
const formStatus = ref<'idle' | 'success' | 'error'>('idle');
const settings = ref<any>({});

onMounted(async () => {
    try {
        const response = await api.get('/settings');
        settings.value = response.data;
    } catch (e) {
        console.error('Failed to load settings:', e);
    }
});

const form = ref({
  name: '',
  email: '',
  message: ''
});

const submitForm = async () => {
    isSubmitting.value = true;
    formStatus.value = 'idle';
    
    try {
        await api.post('/contact', form.value);
        formStatus.value = 'success';
        Swal.fire({
            title: 'Enviado!',
            text: 'Sua mensagem foi enviada com sucesso.',
            icon: 'success',
            confirmButtonColor: '#d97706',
            background: '#18181b', // zinc-900
            color: '#fff'
        });
        form.value = { name: '', email: '', message: '' }; // reset
    } catch (error) {
        console.error('Falha ao enviar contato:', error);
        formStatus.value = 'error';
        Swal.fire({
            title: 'Erro!',
            text: 'Falha ao enviar mensagem. Tente novamente.',
            icon: 'error',
            confirmButtonColor: '#d97706',
            background: '#18181b',
            color: '#fff'
        });
    } finally {
        isSubmitting.value = false;
    }
};
</script>

<template>
  <div class="pt-24 min-h-screen bg-zinc-950 pb-20">
    <section class="max-w-7xl mx-auto px-6 mt-16">
      <div class="mb-16">
        <h1 class="text-5xl md:text-6xl font-black uppercase tracking-tight mb-4 text-white">Fale <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-500 to-yellow-500">Conosco</span></h1>
        <p class="text-zinc-400 text-xl max-w-2xl leading-relaxed">
          Tem alguma dúvida, proposta comercial ou apenas quer dar um alô? Preencha o formulário abaixo ou utilize nossos canais diretos.
        </p>
      </div>

      <div class="grid lg:grid-cols-12 gap-12 lg:gap-20">
        <!-- Contact Form Side -->
        <div class="lg:col-span-7 bg-zinc-900 p-8 md:p-10 rounded-[2rem] border border-zinc-800 shadow-xl">
          <form @submit.prevent="submitForm" class="space-y-6">
            <div class="grid md:grid-cols-2 gap-6">
              <div class="space-y-2">
                <label for="name" class="block text-sm font-bold text-zinc-300 uppercase tracking-widest">Nome Completo</label>
                <input 
                    id="name"
                    type="text" 
                    required 
                    v-model="form.name"
                    class="w-full bg-black border border-zinc-800 rounded-xl px-5 py-4 text-white focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-colors"
                />
              </div>
              
              <div class="space-y-2">
                <label for="email" class="block text-sm font-bold text-zinc-300 uppercase tracking-widest">E-mail</label>
                <input 
                    id="email"
                    type="email" 
                    required 
                    v-model="form.email"
                    class="w-full bg-black border border-zinc-800 rounded-xl px-5 py-4 text-white focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-colors"
                />
              </div>
            </div>

            <div class="space-y-2">
              <label for="message" class="block text-sm font-bold text-zinc-300 uppercase tracking-widest">Mensagem</label>
              <textarea 
                  id="message"
                  required 
                  rows="6"
                  v-model="form.message"
                  class="w-full bg-black border border-zinc-800 rounded-xl px-5 py-4 text-white focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-colors resize-y"
              ></textarea>
            </div>

            <button 
                type="submit" 
                :disabled="isSubmitting"
                class="w-full bg-amber-600 hover:bg-amber-500 disabled:opacity-50 disabled:cursor-not-allowed text-white font-black py-4 rounded-xl transition-colors uppercase tracking-widest text-lg flex items-center justify-center gap-3"
            >
                <LoaderIcon v-if="isSubmitting" class="w-6 h-6 animate-spin" />
                <span v-else>Enviar Mensagem</span>
            </button>
            
            <div v-if="formStatus === 'success'" class="mt-4 p-4 bg-emerald-900/40 border border-emerald-800 rounded-xl flex items-center gap-3 text-emerald-400 font-bold">
               <CheckIcon class="w-5 h-5" /> Sua mensagem foi enviada. Responderemos em breve!
            </div>
          </form>
        </div>

        <!-- Info Side -->
        <div class="lg:col-span-5 flex flex-col justify-center space-y-10">
          <div>
            <h3 class="text-2xl font-black text-white uppercase tracking-widest mb-6">Informações</h3>
            
            <div class="space-y-6">
              <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-full bg-zinc-800 flex items-center justify-center flex-shrink-0 text-amber-500">
                  <MailIcon class="w-5 h-5" />
                </div>
                <div>
                  <p class="text-white font-bold text-lg">E-mail</p>
                  <a :href="`mailto:${settings.contact_email || 'contato@14mproducoes.com.br'}`" class="text-zinc-400 hover:text-amber-500 transition-colors">
                    {{ settings.contact_email || 'contato@14mproducoes.com.br' }}
                  </a>
                </div>
              </div>

              <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-full bg-zinc-800 flex items-center justify-center flex-shrink-0 text-amber-500">
                  <MapPinIcon class="w-5 h-5" />
                </div>
                <div>
                  <p class="text-white font-bold text-lg">Endereço</p>
                  <p class="text-zinc-400">
                    <template v-if="settings.contact_address">
                        {{ settings.contact_address }}
                    </template>
                    <template v-else>
                        Lauro de Freitas, BA<br/>Brasil
                    </template>
                  </p>
                </div>
              </div>
            </div>
          </div>
          
          <div class="p-8 bg-zinc-900 border border-zinc-800 rounded-[2rem]">
            <h4 class="text-xl font-bold text-white uppercase tracking-widest mb-4">Seja um Parceiro</h4>
            <p class="text-zinc-400 mb-6">Estamos sempre abertos a grandes parcerias. Marcas e patrocinadores, utilizem o formulário para enviar sua proposta de colaboração.</p>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
