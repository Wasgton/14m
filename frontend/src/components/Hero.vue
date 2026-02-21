<script setup lang="ts">
</script>

<template>
  <section class="relative min-h-screen flex items-center justify-center pt-20 overflow-hidden">
    <!-- Background layers -->
    <div class="absolute inset-0 z-0 bg-zinc-950">
      <div class="absolute inset-0 bg-gradient-to-b from-zinc-950/40 via-zinc-950/60 to-zinc-950 z-10"></div>
      <img src="https://lazaromedeiros.com.br/wp-content/uploads/2023/05/unnamed-3-e1684789257121.png" alt="Music Festival" class="w-full h-full object-cover opacity-60" />
    </div>

    <!-- Content -->
    <div class="relative z-10 max-w-7xl mx-auto px-6 text-center">
      <div class="inline-block mb-6 px-5 py-2 rounded-full border border-amber-500/30 bg-amber-500/10 text-amber-400 text-sm font-bold tracking-widest uppercase backdrop-blur-sm animate-pulse">
        Lauro de Freitas, Bahia
      </div>
      <h1 class="text-6xl md:text-8xl lg:text-9xl font-black tracking-tighter mb-6 uppercase leading-none">
        <span class="block text-transparent bg-clip-text bg-gradient-to-br from-white to-zinc-400">Nós Criamos</span>
        <span class="block text-transparent bg-clip-text bg-gradient-to-r from-amber-500 to-yellow-500 pb-2">Momentos</span>
      </h1>
      <p class="mt-6 text-xl md:text-2xl text-zinc-300 max-w-2xl mx-auto font-light leading-relaxed">
        A melhor experiência em produção de eventos. De encontros intimistas como o Forró de Responsa a grandes festivais de música.
      </p>
      
      <div class="mt-12 flex flex-col sm:flex-row items-center justify-center gap-6">
        <router-link to="/#events" class="w-full sm:w-auto px-10 py-5 bg-amber-600 hover:bg-amber-500 text-white font-bold rounded-full transition-all hover:scale-105 hover:shadow-[0_0_40px_rgba(245,158,11,0.5)] text-lg uppercase tracking-wide">
          Próximos Eventos
        </router-link>
        <router-link to="/portfolio" class="w-full sm:w-auto px-10 py-5 bg-zinc-800/80 hover:bg-zinc-700 text-white font-bold rounded-full border border-zinc-700 hover:border-zinc-500 transition-all backdrop-blur-sm text-lg uppercase tracking-wide">
          Assistir Aftermovies
        </router-link>
      </div>
    </div>
    
    <!-- Scroll indicator -->
    <div class="absolute bottom-10 left-1/2 -translate-x-1/2 z-10 animate-bounce">
      <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-zinc-500"><path d="M12 5v14"/><path d="m19 12-7 7-7-7"/></svg>
    </div>
  </section>
</template>
