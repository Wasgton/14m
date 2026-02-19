<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'

const isScrolled = ref(false)

const handleScroll = () => {
  isScrolled.value = window.scrollY > 50
}

onMounted(() => {
  window.addEventListener('scroll', handleScroll)
})
onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll)
})

const navLinks = [
  { name: 'Eventos', href: '/#events' },
  { name: 'Artistas', href: '/#artists' },
  { name: 'Portfólio', href: '/portfolio' },
  { name: 'Parceiros', href: '/#partners' }
]
</script>

<template>
  <header 
    class="fixed top-0 inset-x-0 z-50 transition-all duration-300 border-b"
    :class="isScrolled ? 'bg-zinc-950/80 backdrop-blur-md border-zinc-800 py-4' : 'bg-transparent border-transparent py-6'"
  >
    <div class="max-w-7xl mx-auto px-6 flex items-center justify-between">
      <router-link to="/" class="text-3xl font-black tracking-tighter uppercase">
        Ktorze <span class="text-fuchsia-500">M</span>
      </router-link>

      <nav class="hidden md:flex items-center gap-8">
        <router-link 
          v-for="link in navLinks" 
          :key="link.name"
          :to="link.href"
          class="text-sm font-medium text-zinc-300 hover:text-white transition-colors uppercase tracking-widest"
        >
          {{ link.name }}
        </router-link>
      </nav>

      <div class="hidden md:block">
        <router-link to="/#events" class="px-7 py-3 bg-fuchsia-600 hover:bg-fuchsia-500 text-white text-sm font-bold rounded-full transition-all hover:shadow-[0_0_20px_rgba(217,70,239,0.4)]">
          Comprar Ingressos
        </router-link>
      </div>
      
      <!-- Mobile menu button (visual only for now) -->
      <button class="md:hidden text-zinc-300 hover:text-white">
        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>
      </button>
    </div>
  </header>
</template>
