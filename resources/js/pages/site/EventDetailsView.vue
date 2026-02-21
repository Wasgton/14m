<script setup lang="ts">
import { useRoute } from 'vue-router'
import { ref, onMounted, onUnmounted } from 'vue'
import api from '../../services/api'

interface EventMedia {
    media_url: string;
    type: string;
    cover_image: boolean;
    order: number;
}

interface EventArtist {
    id: number;
    name: string;
    pivot?: {
        display_order: number;
    }
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
    lineup?: EventArtist[];
}

const route = useRoute()
const eventId = route.params.id

const event = ref<Event | null>(null)
const currentMediaIndex = ref(0)
const isLoading = ref(true)

const isModalOpen = ref(false)

const openModal = () => {
  isModalOpen.value = true
  document.body.style.overflow = 'hidden'
}

const closeModal = () => {
  isModalOpen.value = false
  document.body.style.overflow = ''
}

const handleKeydown = (e: KeyboardEvent) => {
  if (!isModalOpen.value) return
  if (e.key === 'Escape') closeModal()
  if (e.key === 'ArrowRight') nextMedia()
  if (e.key === 'ArrowLeft') prevMedia()
}

onMounted(async () => {
    window.addEventListener('keydown', handleKeydown)
    try {
        const response = await api.get(`/events/${eventId}`);
        event.value = response.data;
    } catch(err) {
        console.error(err);
    } finally {
        isLoading.value = false;
    }
})

onUnmounted(() => {
    window.removeEventListener('keydown', handleKeydown)
    document.body.style.overflow = ''
})

const nextMedia = () => {
  if (!event.value || !event.value.media) return
  if (currentMediaIndex.value < event.value.media.length - 1) {
    currentMediaIndex.value++
  } else {
    currentMediaIndex.value = 0
  }
}

const prevMedia = () => {
  if (!event.value || !event.value.media) return
  if (currentMediaIndex.value > 0) {
    currentMediaIndex.value--
  } else {
    currentMediaIndex.value = event.value.media.length - 1
  }
}

const setMedia = (index: number) => {
  currentMediaIndex.value = index
}

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
  <div class="pt-24 min-h-screen bg-zinc-950">
    <section v-if="isLoading" class="max-w-7xl mx-auto px-6 py-32 text-center text-white text-xl">
      Carregando...
    </section>
    <section v-else-if="event" class="max-w-7xl mx-auto px-6 py-16">
      <div class="mb-10">
        <router-link to="/eventos" class="inline-flex items-center text-zinc-400 hover:text-amber-400 transition-colors uppercase tracking-widest text-sm font-bold">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="m15 18-6-6 6-6"/></svg>
          Voltar para Eventos
        </router-link>
      </div>

      <div class="grid lg:grid-cols-2 gap-12 lg:gap-20 items-stretch">
        <!-- Image/Carousel Side -->
        <div class="relative w-full h-[500px] lg:h-auto lg:min-h-[600px] rounded-[2rem] overflow-hidden bg-black border border-zinc-800 shadow-2xl flex items-center justify-center group">
          <div v-if="event.media && event.media.length > 0" class="w-full h-full relative">
            <!-- Media Display -->
            <transition name="fade" mode="out-in">
              <div :key="currentMediaIndex" class="absolute inset-0 w-full h-full flex items-center justify-center cursor-pointer" @click="openModal">
                <img v-if="event?.media?.[currentMediaIndex]?.type === 'photo' || event?.media?.[currentMediaIndex]?.type === 'image'" :src="event?.media?.[currentMediaIndex]?.media_url" :alt="event.name" class="w-full h-full object-cover" />
                
                <div v-if="event?.media?.[currentMediaIndex]?.type === 'video'" class="relative w-full h-full flex items-center justify-center bg-zinc-900">
                  <img :src="event?.media?.[currentMediaIndex]?.media_url" :alt="event.name" class="absolute inset-0 w-full h-full object-cover opacity-60" />
                  <div class="absolute inset-0 flex items-center justify-center z-10">
                    <div class="w-20 h-20 bg-amber-600/90 text-white rounded-full flex items-center justify-center backdrop-blur-md shadow-[0_0_30px_rgba(245,158,11,0.5)]">
                      <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="currentColor" stroke="none" class="ml-2"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                    </div>
                  </div>
                </div>
              </div>
            </transition>
            
            <div class="absolute inset-0 bg-gradient-to-t from-zinc-950 via-transparent to-transparent opacity-80 pointer-events-none"></div>
            

            <!-- Carousel Controls -->
            <div v-if="event.media.length > 1">
              <!-- Left/Right Buttons -->
              <button @click.stop="prevMedia" class="absolute left-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-black/50 hover:bg-black/80 backdrop-blur flex items-center justify-center text-white rounded-full opacity-0 group-hover:opacity-100 transition-all duration-300 transform -translate-x-4 group-hover:translate-x-0 z-20">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
              </button>
              <button @click.stop="nextMedia" class="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-black/50 hover:bg-black/80 backdrop-blur flex items-center justify-center text-white rounded-full opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-4 group-hover:translate-x-0 z-20">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
              </button>

              <!-- Bottom Indicators -->
              <div class="absolute bottom-6 left-0 right-0 z-20 flex justify-center gap-2">
                <button 
                  v-for="(_, idx) in event.media" 
                  :key="idx" 
                  @click="setMedia(idx)"
                  :class="['w-2.5 h-2.5 rounded-full transition-all duration-300', currentMediaIndex === idx ? 'bg-amber-500 w-8' : 'bg-white/50 hover:bg-white/80']"
                  :aria-label="`Ir para mídia ${idx + 1}`"
                ></button>
              </div>

              <!-- Media Type Badge -->
              <div class="absolute top-6 right-6 z-20 flex items-center gap-2">
                <span v-if="event?.media?.[currentMediaIndex]?.type === 'video'" class="px-3 py-1 bg-red-500/80 backdrop-blur-sm text-xs font-bold uppercase tracking-widest rounded text-white shadow-[0_0_10px_rgba(239,68,68,0.3)]">Vídeo</span>
                <span v-else class="px-3 py-1 bg-zinc-700/80 backdrop-blur-sm text-xs font-bold uppercase tracking-widest rounded text-white">Foto</span>
                <span class="px-3 py-1 bg-black/60 backdrop-blur text-xs font-bold rounded text-white">{{ currentMediaIndex + 1 }} / {{ event?.media?.length }}</span>
              </div>
            </div>
          </div>
          <div v-else-if="event.media && event.media.length > 0" class="relative w-full h-full">
            <img :src="event.media[0].media_url" :alt="event.name" class="absolute inset-0 w-full h-full object-cover" />
            <div class="absolute inset-0 bg-gradient-to-t from-zinc-950 via-transparent to-transparent opacity-80 pointer-events-none"></div>
          </div>
          <div v-else class="relative w-full h-full bg-zinc-900 flex items-center justify-center text-zinc-500 text-lg">
            Sem mídia vinculada
          </div>
        </div>

        <!-- Info Side -->
        <div class="flex flex-col justify-center py-6">
          <div class="inline-flex items-center gap-2 mb-6 text-sm font-bold text-white bg-zinc-800/80 border border-zinc-700 backdrop-blur px-5 py-2.5 rounded-full self-start shadow-[0_0_15px_rgba(0,0,0,0.5)]">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-amber-500"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            {{ formatDate(event.date) }}
          </div>
          
          <h1 class="text-5xl lg:text-6xl font-black uppercase tracking-tight mb-6 leading-tight">{{ event.name }}</h1>
          
          <div class="flex items-center gap-3 text-zinc-300 font-medium text-lg mb-8">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-amber-500 flex-shrink-0"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
            <span>{{ event.location }}</span>
          </div>

          <div class="text-zinc-400 text-lg leading-relaxed mb-10 editor-content" v-html="event.description"></div>

          <div v-if="event.lineup && event.lineup.length > 0" class="mb-12">
            <h3 class="text-2xl font-black uppercase tracking-widest text-white mb-6 flex items-center gap-3">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-yellow-500"><path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/></svg>
              Line Up
            </h3>
            <div class="flex flex-wrap gap-3">
              <span v-for="artist in event.lineup" :key="artist.id" class="px-5 py-2.5 bg-zinc-900 border border-zinc-800 text-zinc-300 font-bold rounded-full text-sm tracking-wide">
                {{ artist.name }}
              </span>
            </div>
          </div>
          
          <div class="pt-8 border-t border-zinc-800 mt-auto flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
            <div class="text-left w-full sm:w-auto">
              <span class="block text-zinc-400 text-sm font-bold uppercase tracking-widest mb-1">Ingressos a partir de</span>
              <span class="text-4xl font-black text-amber-400">{{ formatPrice(event.price) }}</span>
            </div>
            <a v-if="event.ticket_link" :href="event.ticket_link" target="_blank" class="w-full sm:w-auto bg-amber-600 hover:bg-amber-500 text-white font-black py-4 px-10 rounded-full transition-all uppercase tracking-widest text-lg hover:shadow-[0_0_30px_rgba(245,158,11,0.5)] hover:scale-105 active:scale-95 text-center">
              Comprar Ingressos
            </a>
          </div>
        </div>
      </div>
    </section>
    
    <section v-else class="py-32 text-center max-w-7xl mx-auto px-6">
      <h2 class="text-5xl font-black mb-6">Evento não encontrado</h2>
      <p class="text-zinc-400 text-xl mb-10">O evento que você está procurando não existe ou já foi finalizado.</p>
      <router-link to="/eventos" class="inline-block px-10 py-4 bg-amber-600 text-white font-bold uppercase tracking-widest rounded-full hover:bg-amber-500 transition-colors">Ver Todos os Eventos</router-link>
    </section>

    <!-- Modal Carousel -->
    <div v-if="isModalOpen && event?.media?.[currentMediaIndex]" class="fixed inset-0 z-[100] flex items-center justify-center bg-zinc-950/95 backdrop-blur-xl">
      <!-- Close Button -->
      <button @click="closeModal" class="absolute top-6 right-6 p-3 bg-zinc-800/50 hover:bg-zinc-700 text-white rounded-full transition-colors z-10 backdrop-blur-md">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
      </button>

      <!-- Navigation Prev -->
      <button @click.stop="prevMedia" v-if="event.media.length > 1" class="absolute left-6 p-4 bg-zinc-800/50 hover:bg-zinc-700 text-white rounded-full transition-colors z-10 backdrop-blur-md transform hover:scale-110">
        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
      </button>

      <!-- Main Content -->
      <div class="relative w-full h-full max-w-6xl max-h-[85vh] flex flex-col items-center justify-center p-4">
        <div class="relative w-full h-full flex items-center justify-center rounded-2xl overflow-hidden bg-black shadow-[0_0_50px_rgba(0,0,0,0.5)]">
          <!-- Show Image -->
          <img v-if="event.media[currentMediaIndex].type === 'photo' || event.media[currentMediaIndex].type === 'image'" :src="event.media[currentMediaIndex].media_url" :alt="event.name" class="max-w-full max-h-full object-contain" />
          
          <!-- Or Show Video -->
          <div v-if="event.media[currentMediaIndex].type === 'video'" class="relative w-full h-full flex items-center justify-center bg-zinc-900">
            <img :src="event.media[currentMediaIndex].media_url" :alt="event.name" class="absolute inset-0 w-full h-full object-contain opacity-50 blur-sm" />
            <img :src="event.media[currentMediaIndex].media_url" :alt="event.name" class="max-w-full max-h-full object-contain z-10" />
            <div class="absolute inset-0 flex items-center justify-center z-20 pointer-events-none">
              <div class="w-24 h-24 bg-amber-600/90 text-white rounded-full flex items-center justify-center backdrop-blur-md shadow-[0_0_40px_rgba(245,158,11,0.6)]">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="currentColor" stroke="none" class="ml-2"><polygon points="5 3 19 12 5 21 5 3"/></svg>
              </div>
            </div>
          </div>
        </div>

        <!-- Meta info -->
        <div class="mt-6 text-center w-full">
          <div class="flex items-center justify-center gap-3 mb-2">
            <span v-if="event.media[currentMediaIndex].type === 'video'" class="px-3 py-1 bg-red-500 text-xs font-bold uppercase tracking-widest rounded text-white shadow-[0_0_10px_rgba(239,68,68,0.5)]">Vídeo</span>
            <span v-else class="px-3 py-1 bg-zinc-700 text-xs font-bold uppercase tracking-widest rounded text-white">Foto</span>
            <span class="text-zinc-500 font-medium text-sm">{{ currentMediaIndex + 1 }} de {{ event.media.length }}</span>
          </div>
          <h3 class="text-2xl md:text-3xl font-bold text-white">{{ event.name }}</h3>
        </div>
      </div>

      <!-- Navigation Next -->
      <button @click.stop="nextMedia" v-if="event.media.length > 1" class="absolute right-6 p-4 bg-zinc-800/50 hover:bg-zinc-700 text-white rounded-full transition-colors z-10 backdrop-blur-md transform hover:scale-110">
        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
      </button>
    </div>
  </div>
</template>
