<script setup lang="ts">
const artists = [
  { id: 1, name: 'Trio Virgulino', genre: 'Forró', image: 'https://images.unsplash.com/photo-1516280440502-62bdae18e384?q=80&w=2600&auto=format&fit=crop' },
  { id: 2, name: 'DJ Alok', genre: 'Eletrônica', image: 'https://images.unsplash.com/photo-1542204165-65bf26472b9b?q=80&w=2574&auto=format&fit=crop' },
  { id: 3, name: 'Jorge & Mateus', genre: 'Sertanejo/Pop', image: 'https://images.unsplash.com/photo-1526478806334-5fd488fceabc?q=80&w=2533&auto=format&fit=crop' },
  { id: 4, name: 'Ivete Sangalo', genre: 'Axé/Pop', image: 'https://images.unsplash.com/photo-1501386761578-eac5c94b800a?q=80&w=2574&auto=format&fit=crop' },
]
</script>

<template>
  <section id="artists" class="py-32 bg-zinc-900/50">
    <div class="max-w-7xl mx-auto px-6">
      <div class="text-center mb-20">
        <h2 class="text-5xl md:text-6xl font-black uppercase tracking-tight mb-6">Nossos <span class="text-amber-500">Artistas</span></h2>
        <p class="text-zinc-400 text-xl max-w-2xl mx-auto">Trabalhamos com os melhores talentos do país para proporcionar experiências inesquecíveis no palco.</p>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8">
        <div v-for="artist in artists" :key="artist.id" class="group relative aspect-[3/4] rounded-[2rem] overflow-hidden cursor-pointer shadow-xl">
          <img :src="artist.image" :alt="artist.name" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 grayscale group-hover:grayscale-0" />
          <div class="absolute inset-0 bg-gradient-to-t from-zinc-950 via-zinc-950/20 to-transparent opacity-90 group-hover:opacity-100 transition-opacity duration-500"></div>
          
          <div class="absolute inset-0 border-2 border-transparent group-hover:border-amber-500/30 rounded-[2rem] transition-colors duration-500 z-20 pointer-events-none"></div>

          <div class="absolute bottom-0 left-0 w-full p-8 text-center transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500 z-10">
            <span class="inline-block px-4 py-1.5 rounded-full bg-amber-600/90 backdrop-blur-sm text-white font-bold text-xs uppercase tracking-widest mb-3">{{ artist.genre }}</span>
            <h3 class="text-2xl md:text-3xl font-black text-white px-2">{{ artist.name }}</h3>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
