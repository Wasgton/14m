<script setup lang="ts">
import { ref, onMounted } from 'vue';
import api from '../../services/api';

const settings = ref<any>({});

onMounted(async () => {
    try {
        const response = await api.get('/settings');
        settings.value = response.data;
    } catch (e) {
        console.error('Failed to load formatting settings:', e);
    }
});
</script>

<template>
  <footer class="bg-zinc-950 pt-24 pb-12 border-t border-zinc-900">
    <div class="max-w-7xl mx-auto px-6">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-20 text-center md:text-left">
        <div class="md:col-span-2 flex flex-col items-center md:items-start">
          <router-link to="/" class="mb-6 inline-block">
            <img v-if="settings.logo" :src="settings.logo" :alt="settings.project_name || 'Ktorze M'" class="h-16 md:h-20 w-auto object-contain mix-blend-screen" />
            <img v-else src="../../assets/logo-ktorze.png" alt="Ktorze M" class="h-16 md:h-20 w-auto object-contain mix-blend-screen" />
          </router-link>
          <p class="text-zinc-400 max-w-sm text-lg leading-relaxed">
            Produção de eventos especializada em festivais de música. Criando momentos inesquecíveis em Lauro de Freitas, Bahia. Vamos fazer história juntos.
          </p>
        </div>
        
        <div class="flex flex-col items-center md:items-start">
          <h4 class="font-bold text-xl mb-6 uppercase tracking-widest text-white">Explorar</h4>
          <ul class="space-y-4">
            <li><router-link to="/#events" class="text-zinc-400 hover:text-amber-400 transition-colors uppercase text-sm tracking-wide font-medium">Próximos Eventos</router-link></li>
            <li><router-link to="/#artists" class="text-zinc-400 hover:text-amber-400 transition-colors uppercase text-sm tracking-wide font-medium">Artistas</router-link></li>
            <li><router-link to="/portfolio" class="text-zinc-400 hover:text-amber-400 transition-colors uppercase text-sm tracking-wide font-medium">Portfólio</router-link></li>
            <li><router-link to="/#partners" class="text-zinc-400 hover:text-amber-400 transition-colors uppercase text-sm tracking-wide font-medium">Parceiros</router-link></li>
            <li><router-link to="/contato" class="text-zinc-400 hover:text-amber-400 transition-colors uppercase text-sm tracking-wide font-medium">Contato</router-link></li>
          </ul>
        </div>
        
        <div class="flex flex-col items-center md:items-start">
          <h4 class="font-bold text-xl mb-6 uppercase tracking-widest text-white">Conectar</h4>
          <div class="flex space-x-4">
            <!-- Instagram -->
            <a v-if="settings.instagram_url" :href="settings.instagram_url" target="_blank" class="w-12 h-12 rounded-full bg-zinc-900 flex items-center justify-center text-zinc-400 hover:bg-gradient-to-tr hover:from-orange-500 hover:via-amber-600 hover:to-yellow-500 hover:text-white transition-all duration-300 hover:scale-110">
              <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="20" x="2" y="2" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/></svg>
            </a>
            <a v-else href="https://instagram.com/14mproducoes" target="_blank" class="w-12 h-12 rounded-full bg-zinc-900 flex items-center justify-center text-zinc-400 hover:bg-gradient-to-tr hover:from-orange-500 hover:via-amber-600 hover:to-yellow-500 hover:text-white transition-all duration-300 hover:scale-110">
              <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="20" x="2" y="2" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/></svg>
            </a>
            
            <!-- Facebook -->
            <a v-if="settings.facebook_url" :href="settings.facebook_url" target="_blank" class="w-12 h-12 rounded-full bg-zinc-900 flex items-center justify-center text-zinc-400 hover:text-white transition-all duration-300 hover:bg-blue-600 hover:scale-110">
              <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
            </a>
          </div>
        </div>
      </div>
      
      <div class="pt-10 border-t border-zinc-900 flex flex-col md:flex-row items-center justify-between gap-6 text-sm text-zinc-600 font-medium">
        <p>&copy; 2026 {{ settings.project_name || 'KTORZE M Produções' }}. Todos os direitos reservados.</p>
        <p>CNPJ: --.---.---/----.-- | {{ settings.contact_address || 'Lauro de Freitas, BA' }}</p>
      </div>
    </div>
  </footer>
</template>
