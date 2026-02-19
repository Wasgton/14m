<script setup lang="ts">
const events = [
  {
    id: 1,
    name: 'Forró de Responsa',
    date: '14 de Outubro, 2026',
    location: 'Arena Ktorze, Lauro de Freitas',
    description: 'O icônico evento de forró tradicional onde tudo começou. Junte-se a nós para uma noite de muita dança, música boa e energia inesquecível.',
    price: 'R$ 60,00',
    image: 'https://images.unsplash.com/photo-1533174000276-8090333ccdf8?q=80&w=2938&auto=format&fit=crop'
  },
  {
    id: 2,
    name: 'Summer Vibes Festival',
    date: '05 de Dezembro, 2026',
    location: 'Praia de Ipitanga',
    description: 'Um grande festival de música à beira-mar com os melhores artistas de música pop e eletrônica da temporada.',
    price: 'R$ 120,00',
    image: 'https://images.unsplash.com/photo-1459749411175-04bf5292ceea?q=80&w=2940&auto=format&fit=crop'
  }
]
</script>

<template>
  <section id="events" class="py-32 relative bg-zinc-950">
    <div class="max-w-7xl mx-auto px-6">
      <div class="flex flex-col md:flex-row items-start md:items-end justify-between mb-16 gap-6">
        <div>
          <h2 class="text-5xl md:text-6xl font-black uppercase tracking-tight mb-4">Próximos<br/><span class="text-transparent bg-clip-text bg-gradient-to-r from-fuchsia-500 to-indigo-500">Eventos</span></h2>
          <p class="text-zinc-400 text-xl max-w-xl">Garanta seus ingressos antes que esgotem e junte-se à experiência KTORZE M.</p>
        </div>
        <a href="#" class="hidden md:flex items-center text-fuchsia-400 hover:text-fuchsia-300 font-bold transition-colors text-lg uppercase tracking-wide group">
          Ver Todos os Eventos
          <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 w-6 h-6 transform group-hover:translate-x-1 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
        </a>
      </div>

      <div class="grid lg:grid-cols-2 gap-10">
        <article v-for="event in events" :key="event.id" class="group relative rounded-[2rem] overflow-hidden bg-zinc-900 border border-zinc-800 transition-all hover:border-zinc-700 hover:shadow-2xl flex flex-col sm:flex-row">
          <div class="sm:w-2/5 relative overflow-hidden min-h-[300px] sm:min-h-full">
            <img :src="event.image" :alt="event.name" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" />
            <div class="absolute inset-0 bg-gradient-to-r from-zinc-900/0 to-zinc-900 sm:block hidden"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-zinc-900 to-transparent sm:hidden block"></div>
            
            <div class="absolute top-4 left-4 z-20 bg-zinc-950/80 backdrop-blur-md px-4 py-2 rounded-full border border-zinc-800">
              <span class="font-bold text-fuchsia-400">{{ event.price }}</span>
            </div>
          </div>
          
          <div class="p-8 sm:w-3/5 relative flex flex-col justify-center">
            <div class="inline-flex items-center gap-2 mb-4 text-sm font-bold text-white bg-zinc-800/80 backdrop-blur px-4 py-2 rounded-full self-start">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-fuchsia-500"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
              {{ event.date }}
            </div>
            
            <h3 class="text-3xl font-black mb-3">{{ event.name }}</h3>
            <p class="text-zinc-400 mb-8 line-clamp-3 text-lg">{{ event.description }}</p>
            
            <div class="flex flex-col xl:flex-row items-start xl:items-center justify-between gap-4 mt-auto">
              <div class="flex items-center gap-2 text-zinc-400 font-medium">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-zinc-500"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                {{ event.location }}
              </div>
              <button class="w-full xl:w-auto bg-white hover:bg-zinc-200 text-zinc-950 font-bold py-3 px-8 rounded-full transition-colors uppercase tracking-wide">
                Comprar
              </button>
            </div>
          </div>
        </article>
      </div>
    </div>
  </section>
</template>
