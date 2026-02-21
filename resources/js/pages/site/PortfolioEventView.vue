<script setup lang="ts">
import { useRoute } from 'vue-router'
import { computed, ref, onMounted, onUnmounted } from 'vue'
import api from '../../services/api'

const route = useRoute()
const eventId = route.params.id as string

const event = ref<any>(null)
const isLoading = ref(true)

const fetchEvent = async () => {
    try {
        const response = await api.get(`/events/${eventId}`)
        event.value = response.data
    } catch (e) {
        console.error('Failed to load event details', e)
    } finally {
        isLoading.value = false
    }
}

const formatDate = (dateString: string) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    if (isNaN(date.getTime())) return dateString;
    return new Intl.DateTimeFormat('pt-BR', { 
        day: '2-digit', month: 'short', year: 'numeric'
    }).format(date);
};

const activeFilter = ref<'all' | 'photo' | 'video'>('all')

const filteredMedia = computed(() => {
  if (!event.value) return []
  if (activeFilter.value === 'all') return event.value.media
  return event.value.media.filter((item: any) => item.type === activeFilter.value)
})

// Modal / Carousel logic
const selectedMediaIndex = ref<number | null>(null)

const isModalOpen = computed(() => selectedMediaIndex.value !== null)

const selectedMedia = computed<any>(() => {
  if (selectedMediaIndex.value === null) return null
  return filteredMedia.value[selectedMediaIndex.value] || null
})

const openModal = (index: number) => {
  selectedMediaIndex.value = index
  document.body.style.overflow = 'hidden' // Prevent scrolling when modal is open
}

const closeModal = () => {
  selectedMediaIndex.value = null
  document.body.style.overflow = ''
}

const nextMedia = () => {
  if (selectedMediaIndex.value === null) return
  if (selectedMediaIndex.value < filteredMedia.value.length - 1) {
    selectedMediaIndex.value++
  } else {
    selectedMediaIndex.value = 0 // loop
  }
}

const prevMedia = () => {
  if (selectedMediaIndex.value === null) return
  if (selectedMediaIndex.value > 0) {
    selectedMediaIndex.value--
  } else {
    selectedMediaIndex.value = filteredMedia.value.length - 1 // loop
  }
}

const handleKeydown = (e: KeyboardEvent) => {
  if (!isModalOpen.value) return
  if (e.key === 'Escape') closeModal()
  if (e.key === 'ArrowRight') nextMedia()
  if (e.key === 'ArrowLeft') prevMedia()
}

onMounted(() => {
  window.addEventListener('keydown', handleKeydown)
  fetchEvent()
})

onUnmounted(() => {
  window.removeEventListener('keydown', handleKeydown)
  document.body.style.overflow = ''
})
</script>

<template>
  <div class="pt-24 min-h-screen">
    <section v-if="event" class="py-16">
      <div class="max-w-7xl mx-auto px-6">
        <div class="mb-12">
          <router-link to="/portfolio" class="inline-flex items-center text-zinc-400 hover:text-amber-400 transition-colors uppercase tracking-widest text-sm font-bold mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="m15 18-6-6 6-6"/></svg>
            Voltar para Portfólio
          </router-link>
          <h1 class="text-5xl md:text-6xl font-black uppercase tracking-tight mb-4">{{ event.name }}</h1>
          <div class="flex items-center gap-4 text-zinc-400 text-lg mb-6">
            <span>{{ formatDate(event.date) }}</span>
          </div>
          <p class="text-zinc-300 text-xl max-w-2xl editor-content" v-html="event.description"></p>

          <div v-if="event.lineup && event.lineup.length > 0" class="mt-12">
            <h3 class="text-2xl font-black uppercase tracking-widest text-white mb-6 flex items-center gap-3">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-amber-500"><path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/></svg>
              Line Up
            </h3>
            <div class="flex flex-wrap gap-3">
              <span v-for="artist in event.lineup" :key="artist.id" class="px-5 py-2.5 bg-zinc-900 border border-zinc-800 text-zinc-300 font-bold rounded-full text-sm tracking-wide shadow-sm">
                {{ artist.name }}
              </span>
            </div>
          </div>
        </div>

        <div class="flex gap-4 mb-10 overflow-x-auto pb-4 pt-1">
          <button 
            @click="activeFilter = 'all'"
            :class="['px-8 py-3 rounded-full font-bold transition-all uppercase tracking-widest text-sm whitespace-nowrap', 
                     activeFilter === 'all' ? 'bg-white text-zinc-950 shadow-[0_0_15px_rgba(255,255,255,0.3)]' : 'border border-zinc-700 text-white hover:bg-zinc-800']"
          >
            Tudo
          </button>
          <button 
            @click="activeFilter = 'photo'"
            :class="['px-8 py-3 rounded-full font-bold transition-all uppercase tracking-widest text-sm whitespace-nowrap', 
                     activeFilter === 'photo' ? 'bg-amber-600 text-white shadow-[0_0_15px_rgba(245,158,11,0.5)] border-transparent' : 'border border-zinc-700 text-white hover:bg-zinc-800']"
          >
            Fotos
          </button>
          <button 
            @click="activeFilter = 'video'"
            :class="['px-8 py-3 rounded-full font-bold transition-all uppercase tracking-widest text-sm whitespace-nowrap', 
                     activeFilter === 'video' ? 'bg-blue-600 text-white shadow-[0_0_15px_rgba(37,99,235,0.5)] border-transparent' : 'border border-zinc-700 text-white hover:bg-zinc-800']"
          >
            Vídeos
          </button>
        </div>

        <div v-if="filteredMedia.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 auto-rows-[300px]">
          <div v-for="(item, i) in filteredMedia" :key="item.id" 
            @click="openModal(Number(i))"
            :class="['group relative rounded-[2rem] overflow-hidden cursor-pointer bg-zinc-900', i === 0 && filteredMedia.length > 1 ? 'md:col-span-2 md:row-span-2' : (i === 3 ? 'md:col-span-2' : '')]">
            
            <img v-if="item.type === 'photo' || item.type === 'image'" :src="item.media_url" :alt="event.name" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110" />
            
            <div v-if="item.type === 'video'" class="absolute inset-0 w-full h-full bg-zinc-900 flex items-center justify-center overflow-hidden">
                <img :src="item.media_url" :alt="event.name" class="w-full h-full object-cover opacity-60 transition-transform duration-1000 group-hover:scale-110" />
            </div>

            <div class="absolute inset-0 bg-gradient-to-t from-zinc-950 via-zinc-950/20 to-transparent opacity-80 group-hover:opacity-60 transition-opacity duration-500"></div>
            
            <!-- Play icon for videos -->
            <div v-if="item.type === 'video'" class="absolute inset-0 flex items-center justify-center">
              <div class="w-20 h-20 bg-amber-600/90 text-white rounded-full flex items-center justify-center backdrop-blur-md transform group-hover:scale-110 transition-transform duration-500 shadow-[0_0_30px_rgba(245,158,11,0.5)]">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="currentColor" stroke="none" class="ml-2"><polygon points="5 3 19 12 5 21 5 3"/></svg>
              </div>
            </div>

            <div class="absolute bottom-0 left-0 right-0 p-8 transform translate-y-2 group-hover:translate-y-0 transition-transform duration-500">
              <div class="flex items-center gap-3 mb-3">
                <span v-if="item.type === 'video'" class="px-3 py-1 bg-red-500/80 backdrop-blur-sm text-xs font-bold uppercase tracking-widest rounded text-white shadow-[0_0_10px_rgba(239,68,68,0.3)]">Vídeo</span>
                <span v-else class="px-3 py-1 bg-zinc-700/80 backdrop-blur-sm text-xs font-bold uppercase tracking-widest rounded text-white">Foto</span>
              </div>
              <h3 class="text-2xl font-black text-white leading-tight">{{ event.name }}</h3>
            </div>
          </div>
        </div>
        <div v-else class="py-20 text-center">
          <p class="text-zinc-500 text-xl font-medium">Nenhuma mídia encontrada para este filtro.</p>
        </div>
      </div>
    </section>
    <section v-else-if="isLoading" class="py-32 text-center text-xl text-zinc-500">
      Carregando...
    </section>
    <section v-else class="py-32 text-center">
      <h2 class="text-4xl font-bold">Evento não encontrado no Portfólio</h2>
      <router-link to="/portfolio" class="mt-8 inline-block px-8 py-3 bg-amber-600 text-white rounded-full">Voltar</router-link>
    </section>

    <!-- Modal Carousel -->
    <div v-if="isModalOpen && selectedMedia" class="fixed inset-0 z-[100] flex items-center justify-center bg-zinc-950/95 backdrop-blur-xl">
      <!-- Close Button -->
      <button @click="closeModal" class="absolute top-6 right-6 p-3 bg-zinc-800/50 hover:bg-zinc-700 text-white rounded-full transition-colors z-10 backdrop-blur-md">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
      </button>

      <!-- Navigation Prev -->
      <button @click="prevMedia" v-if="filteredMedia.length > 1" class="absolute left-6 p-4 bg-zinc-800/50 hover:bg-zinc-700 text-white rounded-full transition-colors z-10 backdrop-blur-md transform hover:scale-110">
        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
      </button>

      <!-- Main Content -->
      <div class="relative w-full h-full max-w-6xl max-h-[85vh] flex flex-col items-center justify-center p-4">
        <div class="relative w-full h-full flex items-center justify-center rounded-2xl overflow-hidden bg-black shadow-[0_0_50px_rgba(0,0,0,0.5)]">
          <!-- Show Image -->
          <img v-if="selectedMedia.type === 'photo' || selectedMedia.type === 'image'" :src="selectedMedia.media_url" :alt="event.name" class="max-w-full max-h-full object-contain" />
          
          <!-- Or Show Video -->
          <div v-if="selectedMedia.type === 'video'" class="relative w-full h-full flex items-center justify-center bg-zinc-900">
            <img :src="selectedMedia.media_url" :alt="event.name" class="absolute inset-0 w-full h-full object-contain opacity-50 blur-sm" />
            <img :src="selectedMedia.media_url" :alt="event.name" class="max-w-full max-h-full object-contain z-10" />
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
            <span v-if="selectedMedia.type === 'video'" class="px-3 py-1 bg-red-500 text-xs font-bold uppercase tracking-widest rounded text-white shadow-[0_0_10px_rgba(239,68,68,0.5)]">Vídeo</span>
            <span v-else class="px-3 py-1 bg-zinc-700 text-xs font-bold uppercase tracking-widest rounded text-white">Foto</span>
            <span class="text-zinc-500 font-medium text-sm">{{ selectedMediaIndex! + 1 }} de {{ filteredMedia.length }}</span>
          </div>
          <h3 class="text-2xl md:text-3xl font-bold text-white">{{ event.name }}</h3>
        </div>
      </div>

      <!-- Navigation Next -->
      <button @click="nextMedia" v-if="filteredMedia.length > 1" class="absolute right-6 p-4 bg-zinc-800/50 hover:bg-zinc-700 text-white rounded-full transition-colors z-10 backdrop-blur-md transform hover:scale-110">
        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
      </button>
    </div>
  </div>
</template>
