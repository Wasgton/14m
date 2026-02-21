<script setup lang="ts">
import { ref, onMounted } from 'vue';
import api from '../../services/api';

interface EventMedia {
    media_url: string;
    type: string;
    cover_image: boolean;
    order: number;
}

interface Event {
    id: number;
    name: string;
    date: string;
    location: string;
    description: string;
    price: number | string;
    status: string;
    ticket_link?: string;
    media: EventMedia[];
}

const upcomingEvents = ref<Event[]>([]);

onMounted(async () => {
    try {
        const response = await api.get('/events');
        upcomingEvents.value = response.data
            .filter((e: Event) => ['upcoming', 'próximo', 'proximo'].includes(e.status?.toLowerCase()))
            .slice(0, 2);
    } catch(err) {
        console.error('Failed to load events', err);
    }
});

const getEventImage = (event: Event) => {
    if (event.media && event.media.length > 0) {
        const cover = event.media.find(m => m.cover_image);
        if (cover) return cover.media_url;
        return event.media[0].media_url;
    }
    return 'https://images.unsplash.com/photo-1533174000276-8090333ccdf8?q=80&w=2938&auto=format&fit=crop';
};

const formatPrice = (priceInCents: number | string) => {
    if (!priceInCents) return 'R$ 0,00';
    const num = typeof priceInCents === 'string' ? parseInt(priceInCents, 10) : priceInCents;
    if (isNaN(num)) return String(priceInCents);
    return new Intl.NumberFormat('pt-BR', { 
        style: 'currency', 
        currency: 'BRL' 
    }).format(num / 100);
};

const formatDate = (dateString: string) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    if (isNaN(date.getTime())) return dateString;
    return new Intl.DateTimeFormat('pt-BR', { 
        day: '2-digit', 
        month: 'short', 
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    }).format(date).replace(',', ' - ');
};
</script>

<template>
  <section id="events" class="py-32 relative bg-zinc-950">
    <div class="max-w-7xl mx-auto px-6">
      <div class="flex flex-col md:flex-row items-start md:items-end justify-between mb-16 gap-6">
        <div>
          <h2 class="text-5xl md:text-6xl font-black uppercase tracking-tight mb-4">Próximos<br/><span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-500 to-yellow-500">Eventos</span></h2>
          <p class="text-zinc-400 text-xl max-w-xl">Garanta seus ingressos antes que esgotem e junte-se à experiência KTORZE M.</p>
        </div>
        <router-link to="/eventos" class="hidden md:flex items-center text-amber-400 hover:text-amber-300 font-bold transition-colors text-lg uppercase tracking-wide group">
          Ver Todos os Eventos
          <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 w-6 h-6 transform group-hover:translate-x-1 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
        </router-link>
      </div>

      <div class="grid lg:grid-cols-2 gap-10">
        <router-link :to="`/eventos/${event.id}`" v-for="event in upcomingEvents" :key="event.id" class="group relative rounded-[2rem] overflow-hidden bg-zinc-900 border border-zinc-800 transition-all hover:border-zinc-700 hover:shadow-2xl flex flex-col sm:flex-row cursor-pointer">
          <div class="sm:w-2/5 relative overflow-hidden min-h-[300px] sm:min-h-full">
            <img :src="getEventImage(event)" :alt="event.name" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" />
            <div class="absolute inset-0 bg-gradient-to-r from-zinc-900/0 to-zinc-900 sm:block hidden"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-zinc-900 to-transparent sm:hidden block"></div>
            
            <div class="absolute top-4 left-4 z-20 bg-zinc-950/80 backdrop-blur-md px-4 py-2 rounded-full border border-zinc-800">
              <span class="font-bold text-amber-400">{{ formatPrice(event.price) }}</span>
            </div>
          </div>
          
          <div class="p-8 sm:w-3/5 relative flex flex-col justify-center">
            <div class="inline-flex items-center gap-2 mb-4 text-sm font-bold text-white bg-zinc-800/80 backdrop-blur px-4 py-2 rounded-full self-start">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-amber-500"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
              {{ formatDate(event.date) }}
            </div>
            
            <h3 class="text-3xl font-black mb-3">{{ event.name }}</h3>
            <div class="text-zinc-400 mb-8 line-clamp-3 text-lg editor-content" v-html="event.description"></div>
            
            <div class="flex flex-col mt-auto pt-4 gap-4 w-full overflow-hidden border-t border-zinc-800/50">
              <div class="flex items-center gap-2 text-zinc-400 font-medium w-full">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-zinc-500 flex-shrink-0"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                <span class="truncate" :title="event.location">{{ event.location }}</span>
              </div>
              <a v-if="event.ticket_link" :href="event.ticket_link" target="_blank" @click.stop class="w-full bg-white hover:bg-zinc-200 text-zinc-950 font-bold py-3 px-8 rounded-full transition-all uppercase tracking-wide hover:shadow-[0_0_20px_rgba(255,255,255,0.3)] text-center">
                Comprar Ingressos
              </a>
            </div>
          </div>
        </router-link>
      </div>
    </div>
  </section>
</template>
