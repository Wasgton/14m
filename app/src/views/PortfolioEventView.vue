<script setup lang="ts">
import { useRoute } from 'vue-router'
import { computed } from 'vue'
import { portfolioEvents } from '../data/portfolio'

const route = useRoute()
const eventId = route.params.id as string

const event = computed(() => {
  return portfolioEvents.find(e => e.id === eventId)
})
</script>

<template>
  <div class="pt-24 min-h-screen">
    <section v-if="event" class="py-16">
      <div class="max-w-7xl mx-auto px-6">
        <div class="mb-12">
          <router-link to="/portfolio" class="inline-flex items-center text-zinc-400 hover:text-fuchsia-400 transition-colors uppercase tracking-widest text-sm font-bold mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="m15 18-6-6 6-6"/></svg>
            Voltar para Portfólio
          </router-link>
          <h1 class="text-5xl md:text-6xl font-black uppercase tracking-tight mb-4">{{ event.title }}</h1>
          <div class="flex items-center gap-4 text-zinc-400 text-lg mb-6">
            <span>{{ event.date }}</span>
          </div>
          <p class="text-zinc-300 text-xl max-w-2xl">{{ event.description }}</p>
        </div>

        <div class="flex gap-4 mb-10">
          <button class="px-8 py-3 rounded-full bg-white text-zinc-950 font-bold hover:bg-zinc-200 transition-colors uppercase tracking-widest text-sm">Tudo</button>
          <button class="px-8 py-3 rounded-full border border-zinc-700 text-white font-bold hover:bg-zinc-800 transition-colors uppercase tracking-widest text-sm">Fotos</button>
          <button class="px-8 py-3 rounded-full border border-zinc-700 text-white font-bold hover:bg-zinc-800 transition-colors uppercase tracking-widest text-sm">Vídeos</button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 auto-rows-[300px]">
          <div v-for="(item, i) in event.media" :key="item.id" 
            :class="['group relative rounded-[2rem] overflow-hidden cursor-pointer bg-zinc-900', i === 0 ? 'md:col-span-2 md:row-span-2' : (i === 3 ? 'md:col-span-2' : '')]">
            
            <img :src="item.url" :alt="item.title" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110" />
            <div class="absolute inset-0 bg-gradient-to-t from-zinc-950 via-zinc-950/20 to-transparent opacity-80 group-hover:opacity-60 transition-opacity duration-500"></div>
            
            <!-- Play icon for videos -->
            <div v-if="item.type === 'video'" class="absolute inset-0 flex items-center justify-center">
              <div class="w-20 h-20 bg-fuchsia-600/90 text-white rounded-full flex items-center justify-center backdrop-blur-md transform group-hover:scale-110 transition-transform duration-500 shadow-[0_0_30px_rgba(217,70,239,0.5)]">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="currentColor" stroke="none" class="ml-2"><polygon points="5 3 19 12 5 21 5 3"/></svg>
              </div>
            </div>

            <div class="absolute bottom-0 left-0 right-0 p-8 transform translate-y-2 group-hover:translate-y-0 transition-transform duration-500">
              <div class="flex items-center gap-3 mb-3">
                <span v-if="item.type === 'video'" class="px-3 py-1 bg-red-500/80 backdrop-blur-sm text-xs font-bold uppercase tracking-widest rounded text-white">Vídeo</span>
                <span v-else class="px-3 py-1 bg-zinc-700/80 backdrop-blur-sm text-xs font-bold uppercase tracking-widest rounded text-white">Foto</span>
              </div>
              <h3 class="text-2xl font-black text-white leading-tight">{{ item.title }}</h3>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section v-else class="py-32 text-center">
      <h2 class="text-4xl font-bold">Evento não encontrado</h2>
      <router-link to="/portfolio" class="mt-8 inline-block px-8 py-3 bg-fuchsia-600 text-white rounded-full">Voltar</router-link>
    </section>
  </div>
</template>
