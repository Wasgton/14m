<script setup lang="ts">
import { portfolioEvents } from '../../data/portfolio'
</script>

<template>
  <div class="pt-24 min-h-screen mb-20">
    <section class="max-w-7xl mx-auto px-6 mt-16">
      <div class="mb-16">
        <h1 class="text-5xl md:text-6xl font-black uppercase tracking-tight mb-4 text-white">Nosso Portfólio</h1>
        <p class="text-zinc-400 text-xl max-w-2xl leading-relaxed">
          Momentos congelados no tempo. Reviva a magia, a energia e a emoção dos nossos maiores eventos passados. Clicando em cada evento, você poderá ver a cobertura completa.
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <router-link 
          v-for="event in portfolioEvents" 
          :key="event.id"
          :to="`/portfolio/${event.id}`"
          class="group relative h-[450px] rounded-[2rem] overflow-hidden bg-zinc-900 shadow-xl transition-all hover:-translate-y-2 hover:shadow-[0_20px_40px_rgba(245,158,11,0.15)]"
        >
          <img :src="event.coverImage" :alt="event.title" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110" />
          <div class="absolute inset-0 bg-gradient-to-t from-zinc-950 via-zinc-950/40 to-transparent opacity-90 group-hover:opacity-70 transition-opacity duration-500"></div>
          
          <div class="absolute bottom-0 left-0 right-0 p-8">
            <div class="flex items-center gap-3 mb-4">
              <span class="px-4 py-1.5 bg-amber-600/90 backdrop-blur-sm text-xs font-bold uppercase tracking-widest rounded text-white shadow-[0_0_15px_rgba(245,158,11,0.5)]">
                {{ event.media.length }} Mídias
              </span>
              <span class="text-zinc-300 text-sm font-medium tracking-wide">{{ event.date }}</span>
            </div>
            <h3 class="text-3xl font-black text-white leading-tight uppercase tracking-tight group-hover:text-amber-400 transition-colors duration-300">{{ event.title }}</h3>
            <p class="mt-3 text-zinc-400 line-clamp-2 leading-relaxed opacity-0 group-hover:opacity-100 transition-opacity duration-300 transform translate-y-4 group-hover:translate-y-0">{{ event.description }}</p>
          </div>
        </router-link>
      </div>
    </section>
  </div>
</template>
