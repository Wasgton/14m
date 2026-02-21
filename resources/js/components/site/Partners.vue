<script setup lang="ts">
import { ref, onMounted } from 'vue';
import api from '../../services/api';

const partners = ref<any[]>([]);

onMounted(async () => {
    try {
        const response = await api.get('/partners');
        partners.value = response.data;
    } catch (err) {
        console.error('Failed to load partners', err);
    }
});
</script>

<template>
  <section id="partners" class="py-32 bg-zinc-900 border-t border-zinc-800">
    <div class="max-w-7xl mx-auto px-6 text-center">
      <h2 class="text-4xl md:text-5xl font-black uppercase tracking-tight mb-20 text-zinc-600">Marcas Parceiras</h2>
      
      <div class="flex flex-wrap justify-center items-center gap-16 md:gap-24">
        <div v-for="partner in partners" :key="partner.id" class="block group">
          <img 
            :src="partner.logo_url" 
            :alt="partner.name" 
            class="h-10 md:h-14 lg:h-16 w-auto object-contain filter brightness-0 invert opacity-40 group-hover:brightness-100 group-hover:invert-0 group-hover:opacity-100 transition-all duration-500 transform group-hover:scale-110" 
          />
        </div>
      </div>
    </div>
  </section>
</template>
