<template>
  <div class="min-h-screen flex items-center justify-center bg-zinc-950 px-4 relative overflow-hidden">
    <!-- Energetic background blobs -->
    <div class="absolute w-[500px] h-[500px] bg-rose-600 rounded-full mix-blend-multiply filter blur-[128px] opacity-40 top-0 right-0 animate-blob"></div>
    <div class="absolute w-[500px] h-[500px] bg-indigo-600 rounded-full mix-blend-multiply filter blur-[128px] opacity-40 bottom-0 left-0 animate-blob animation-delay-2000"></div>

    <div class="max-w-md w-full relative z-10 backdrop-blur-xl bg-zinc-900/60 p-8 rounded-3xl border border-zinc-800 shadow-2xl">
      <div class="text-center mb-8">
        <h1 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-rose-400 tracking-tight">Nova Senha</h1>
        <p class="text-sm text-zinc-400 mt-2">Crie e confirme a sua nova senha de acesso.</p>
      </div>

      <div v-if="successMessage" class="mb-6 bg-emerald-500/10 border border-emerald-500/50 text-emerald-400 text-sm p-4 rounded-xl text-center">
        {{ successMessage }}
        <div class="mt-4">
          <router-link to="/login" class="underline hover:text-emerald-300">Ir para o Login</router-link>
        </div>
      </div>

      <form v-else @submit.prevent="handleResetPassword" class="space-y-6">
        <div>
          <label class="block text-sm font-medium text-zinc-300 mb-2">E-mail</label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-zinc-500">
              <MailIcon class="w-5 h-5 bg-transparent" />
            </div>
            <input 
              v-model="email" 
              type="email" 
              readonly
              class="block w-full pl-10 pr-3 py-3 border border-zinc-700 rounded-xl bg-zinc-800/80 text-zinc-400 focus:outline-none transition-all cursor-not-allowed"
            >
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-zinc-300 mb-2">Nova Senha</label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-zinc-500">
              <LockIcon class="w-5 h-5 bg-transparent" />
            </div>
            <input 
              v-model="password" 
              type="password" 
              required
              class="block w-full pl-10 pr-3 py-3 border border-zinc-700 rounded-xl bg-zinc-800/50 text-zinc-100 placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all shadow-inner"
              placeholder="••••••••"
            >
          </div>
          <p v-if="errors.password" class="mt-2 text-xs text-rose-500">{{ errors.password[0] }}</p>
        </div>

        <div>
          <label class="block text-sm font-medium text-zinc-300 mb-2">Confirmar Nova Senha</label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-zinc-500">
              <LockIcon class="w-5 h-5 bg-transparent" />
            </div>
            <input 
              v-model="password_confirmation" 
              type="password" 
              required
              class="block w-full pl-10 pr-3 py-3 border border-zinc-700 rounded-xl bg-zinc-800/50 text-zinc-100 placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all shadow-inner"
              placeholder="••••••••"
            >
          </div>
        </div>
        
        <p v-if="errors.email" class="text-xs text-rose-500 text-center">{{ errors.email[0] }}</p>

        <button 
          type="submit" 
          :disabled="isLoading"
          class="w-full flex justify-center items-center py-3.5 px-4 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white bg-gradient-to-r from-indigo-600 to-rose-600 hover:from-indigo-500 hover:to-rose-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 focus:ring-offset-zinc-900 transition-all transform hover:scale-[1.02] active:scale-[0.98] disabled:opacity-70 disabled:cursor-not-allowed disabled:transform-none"
        >
          <span v-if="isLoading" class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin mr-2"></span>
          {{ isLoading ? 'Redefinindo...' : 'Salvar Nova Senha' }}
        </button>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { Mail as MailIcon, Lock as LockIcon } from 'lucide-vue-next';
import api from '../../services/api';

const route = useRoute();

const email = ref('');
const password = ref('');
const password_confirmation = ref('');
const token = ref('');

const isLoading = ref(false);
const errors = ref<Record<string, string[]>>({});
const successMessage = ref('');

onMounted(() => {
    // Collect data from URL
    token.value = route.params.token as string || '';
    email.value = (route.query.email as string) || '';
});

const handleResetPassword = async () => {
    if (!password.value || !password_confirmation.value) return;
    
    isLoading.value = true;
    errors.value = {};
    
    try {
        await api.get('/sanctum/csrf-cookie', { baseURL: '/' });
        await api.post('/reset-password', { 
            token: token.value,
            email: email.value,
            password: password.value,
            password_confirmation: password_confirmation.value
        });
        
        successMessage.value = 'Sua senha foi redefinida com sucesso. Você já pode fazer login.';
    } catch (error: any) {
        if (error.response && error.response.status === 422) {
             errors.value = error.response.data.errors || {};
        } else {
             errors.value = { email: ['Ocorreu um erro ou o link expirou.'] };
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
