<template>
  <div class="min-h-screen flex items-center justify-center bg-zinc-950 px-4 relative overflow-hidden">
    <!-- Energetic background blobs -->
    <div class="absolute w-[500px] h-[500px] bg-indigo-600 rounded-full mix-blend-multiply filter blur-[128px] opacity-40 top-0 left-0 animate-blob"></div>
    <div class="absolute w-[500px] h-[500px] bg-rose-600 rounded-full mix-blend-multiply filter blur-[128px] opacity-40 bottom-0 right-0 animate-blob animation-delay-2000"></div>

    <div class="max-w-md w-full relative z-10 backdrop-blur-xl bg-zinc-900/60 p-8 rounded-3xl border border-zinc-800 shadow-2xl">
      <div class="text-center mb-8">
        <h1 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-rose-400 tracking-tight">Recuperar Senha</h1>
        <p class="text-sm text-zinc-400 mt-2">Informe seu e-mail para receber um link de redefinição de senha.</p>
      </div>

      <div v-if="successMessage" class="mb-6 bg-emerald-500/10 border border-emerald-500/50 text-emerald-400 text-sm p-4 rounded-xl text-center">
        {{ successMessage }}
      </div>

      <form v-else @submit.prevent="handleForgotPassword" class="space-y-6">
        <div>
          <label class="block text-sm font-medium text-zinc-300 mb-2">E-mail</label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-zinc-500">
              <MailIcon class="w-5 h-5 bg-transparent" />
            </div>
            <input 
              v-model="email" 
              type="email" 
              required
              class="block w-full pl-10 pr-3 py-3 border border-zinc-700 rounded-xl bg-zinc-800/50 text-zinc-100 placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all shadow-inner"
              placeholder="seu@email.com"
            >
          </div>
          <p v-if="errorMessage" class="mt-2 text-xs text-rose-500">{{ errorMessage }}</p>
        </div>

        <button 
          type="submit" 
          :disabled="isLoading"
          class="w-full flex justify-center items-center py-3.5 px-4 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white bg-gradient-to-r from-indigo-600 to-rose-600 hover:from-indigo-500 hover:to-rose-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 focus:ring-offset-zinc-900 transition-all transform hover:scale-[1.02] active:scale-[0.98] disabled:opacity-70 disabled:cursor-not-allowed disabled:transform-none"
        >
          <span v-if="isLoading" class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin mr-2"></span>
          {{ isLoading ? 'Enviando...' : 'Enviar Link de Recuperação' }}
        </button>
      </form>

      <div class="mt-6 text-center">
        <router-link to="/login" class="text-sm font-medium text-indigo-400 hover:text-indigo-300 transition-colors">
          Voltar para o Login
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { Mail as MailIcon } from 'lucide-vue-next';
import api from '../../services/api';

const email = ref('');
const isLoading = ref(false);
const errorMessage = ref('');
const successMessage = ref('');

const handleForgotPassword = async () => {
    if (!email.value) return;
    
    isLoading.value = true;
    errorMessage.value = '';
    
    try {
        await api.get('/sanctum/csrf-cookie', { baseURL: '/' });
        const response = await api.post('/forgot-password', { email: email.value });
        successMessage.value = 'Se o e-mail existir no sistema, você receberá um link de recuperação em instantes.';
    } catch (error: any) {
        if (error.response && error.response.status === 422) {
             errorMessage.value = error.response.data.message || 'E-mail inválido ou não encontrado.';
        } else {
             errorMessage.value = 'Ocorreu um erro ao conectar com o servidor.';
        }
    } finally {
        isLoading.value = false;
    }
};
</script>

<style scoped>
@keyframes blob {
  0% { transform: translate(0px, 0px) scale(1); }
  33% { transform: translate(30px, -50px) scale(1.1); }
  66% { transform: translate(-20px, 20px) scale(0.9); }
  100% { transform: translate(0px, 0px) scale(1); }
}
.animate-blob {
  animation: blob 7s infinite;
}
.animation-delay-2000 {
  animation-delay: 2s;
}
</style>
