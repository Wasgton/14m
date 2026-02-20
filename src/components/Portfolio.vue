<script setup lang="ts">
const items = [
  { id: 1, type: 'video', url: 'https://images.unsplash.com/photo-1470229722913-7c092fb1f144?q=80&w=2942&auto=format&fit=crop', title: 'AFTERMOVIE Forró de Responsa 2025' },
  { id: 2, type: 'photo', url: 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?q=80&w=2874&auto=format&fit=crop', title: 'Bastidores do Palco Principal' },
  { id: 3, type: 'photo', url: 'https://images.unsplash.com/photo-1524368535928-5b5e00ddc76b?q=80&w=2940&auto=format&fit=crop', title: 'Público na Arena' },
  { id: 4, type: 'photo', url: 'https://images.unsplash.com/photo-1429962714451-bb934ecdc4ec?q=80&w=2940&auto=format&fit=crop', title: 'Festa no Pôr do Sol' },
]
</script>

<template>
  <section id="portfolio" class="py-32">
    <div class="max-w-7xl mx-auto px-6">
      <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-8">
        <div>
          <h2 class="text-5xl md:text-6xl font-black uppercase tracking-tight mb-4">Portfólio</h2>
          <p class="text-zinc-400 text-xl max-w-xl">Momentos congelados no tempo. Reviva a magia dos nossos eventos passados.</p>
        </div>
        <div class="flex gap-4">
          <button class="px-8 py-3 rounded-full bg-white text-zinc-950 font-bold hover:bg-zinc-200 transition-colors uppercase tracking-widest text-sm">Tudo</button>
          <button class="px-8 py-3 rounded-full border border-zinc-700 text-white font-bold hover:bg-zinc-800 transition-colors uppercase tracking-widest text-sm">Fotos</button>
          <button class="px-8 py-3 rounded-full border border-zinc-700 text-white font-bold hover:bg-zinc-800 transition-colors uppercase tracking-widest text-sm">Vídeos</button>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 auto-rows-[300px]">
        <div v-for="(item, i) in items" :key="item.id" 
          :class="['group relative rounded-[2rem] overflow-hidden cursor-pointer bg-zinc-900', i === 0 ? 'md:col-span-2 md:row-span-2' : (i === 3 ? 'md:col-span-2' : '')]">
          
          <img :src="item.url" :alt="item.title" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110" />
          <div class="absolute inset-0 bg-gradient-to-t from-zinc-950 via-zinc-950/20 to-transparent opacity-80 group-hover:opacity-60 transition-opacity duration-500"></div>
          
          <!-- Play icon for videos -->
          <div v-if="item.type === 'video'" class="absolute inset-0 flex items-center justify-center">
            <div class="w-20 h-20 bg-amber-600/90 text-white rounded-full flex items-center justify-center backdrop-blur-md transform group-hover:scale-110 transition-transform duration-500 shadow-[0_0_30px_rgba(245,158,11,0.5)]">
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
</template>
