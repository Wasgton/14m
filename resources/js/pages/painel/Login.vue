<template>
  <div class="min-h-screen flex items-center justify-center bg-zinc-950 px-4 relative overflow-hidden">
    <!-- Energetic background blobs -->
    <div class="absolute w-[500px] h-[500px] bg-indigo-600 rounded-full mix-blend-multiply filter blur-[128px] opacity-40 top-0 left-0 animate-blob"></div>
    <div class="absolute w-[500px] h-[500px] bg-rose-600 rounded-full mix-blend-multiply filter blur-[128px] opacity-40 bottom-0 right-0 animate-blob animation-delay-2000"></div>
    <div class="absolute w-[500px] h-[500px] bg-amber-600 rounded-full mix-blend-multiply filter blur-[128px] opacity-40 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 animate-blob animation-delay-4000"></div>

    <div class="max-w-md w-full relative z-10 backdrop-blur-xl bg-zinc-900/60 p-8 rounded-3xl border border-zinc-800 shadow-2xl">
      <div class="text-center mb-10">
        <h1 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-rose-400 tracking-tight">KTORZE M</h1>
      </div>

      <form @submit.prevent="handleLogin" class="space-y-6">
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
        </div>

        <div>
          <label class="block text-sm font-medium text-zinc-300 mb-2">Senha</label>
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
        </div>

        <div class="flex items-center justify-between mt-4">
          <div class="flex items-center">
            <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 rounded border-zinc-700 bg-zinc-800/50 text-indigo-500 focus:ring-indigo-500/50 focus:ring-offset-zinc-900">
            <label for="remember-me" class="ml-2 block text-sm text-zinc-400">
              Lembrar-me
            </label>
          </div>

          <div class="text-sm">
            <router-link to="/painel/forgot-password" class="font-medium text-indigo-400 hover:text-indigo-300 transition-colors">
              Esqueceu a senha?
            </router-link>
          </div>
        </div>

        <div v-if="errorMessage" class="mb-4 bg-rose-500/10 border border-rose-500/50 text-rose-400 text-sm p-3 rounded-lg text-center">
          {{ errorMessage }}
        </div>

        <button 
          type="submit" 
          :disabled="isLoading"
          class="w-full flex justify-center items-center py-3.5 px-4 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white bg-gradient-to-r from-indigo-600 to-rose-600 hover:from-indigo-500 hover:to-rose-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 focus:ring-offset-zinc-900 transition-all transform hover:scale-[1.02] active:scale-[0.98] disabled:opacity-70 disabled:cursor-not-allowed disabled:transform-none"
        >
          <span v-if="isLoading" class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin mr-2"></span>
          {{ isLoading ? 'Entrando...' : 'Entrar no Sistema' }}
        </button>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { Mail as MailIcon, Lock as LockIcon } from 'lucide-vue-next';
import { useAuth } from '../../composables/useAuth';

const router = useRouter();
const { login } = useAuth();

const email = ref('');
const password = ref('');
const errorMessage = ref('');
const isLoading = ref(false);

const handleLogin = async () => {
    if(!email.value || !password.value) return;
    
    isLoading.value = true;
    errorMessage.value = '';

    try {
        await login({ email: email.value, password: password.value });
        router.push('/painel');
    } catch (error: any) {
        if (error.response && error.response.status === 422) {
             errorMessage.value = 'Dados inválidos.';
        } else if (error.response && error.response.status === 401) {
             errorMessage.value = 'E-mail ou senha incorretos.';
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
.animation-delay-4000 {
  animation-delay: 4s;
}
</style>
