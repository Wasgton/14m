<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'

const isScrolled = ref(false)
const isMobileMenuOpen = ref(false)

const handleScroll = () => {
  isScrolled.value = window.scrollY > 50
}

const toggleMobileMenu = () => {
  isMobileMenuOpen.value = !isMobileMenuOpen.value
  if (isMobileMenuOpen.value) {
    document.body.style.overflow = 'hidden'
  } else {
    document.body.style.overflow = ''
  }
}

const closeMobileMenu = () => {
  isMobileMenuOpen.value = false
  document.body.style.overflow = ''
}

onMounted(() => {
  window.addEventListener('scroll', handleScroll)
})
onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll)
})

const navLinks = [
  { name: 'Eventos', href: '/eventos' },
  { name: 'Artistas', href: '/#artists' },
  { name: 'Portfólio', href: '/portfolio' },
  { name: 'Parceiros', href: '/#partners' },
  { name: 'Contato', href: '/contato' }
]
</script>

<template>
  <header 
    class="fixed top-0 inset-x-0 z-50 transition-all duration-300 border-b"
    :class="isScrolled || isMobileMenuOpen ? 'bg-zinc-950/80 backdrop-blur-md border-zinc-800 py-4' : 'bg-transparent border-transparent py-6'"
  >
    <div class="max-w-7xl mx-auto px-6 flex items-center justify-between relative min-h-[60px] md:min-h-0">
      <!-- Invisible spacer to perfectly balance the right-side hamburger menu in flex -->
      <div class="w-10 md:hidden"></div>
      
      <router-link to="/" class="flex items-center absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 md:static md:translate-x-0 md:translate-y-0" @click="closeMobileMenu">
        <img src="../../assets/logo-ktorze.png" alt="Ktorze M" class="h-28 sm:h-32 md:h-24 lg:h-28 w-auto object-contain mix-blend-screen scale-110" />
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
        <router-link to="/eventos" class="px-7 py-3 bg-amber-600 hover:bg-amber-500 text-white text-sm font-bold rounded-full transition-all hover:shadow-[0_0_20px_rgba(245,158,11,0.4)]">
          Comprar Ingressos
        </router-link>
      </div>
      
      <!-- Mobile menu button -->
      <button @click="toggleMobileMenu" class="md:hidden text-zinc-300 hover:text-white z-50 relative p-2 focus:outline-none">
        <svg v-if="!isMobileMenuOpen" xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>
        <svg v-else xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>

    <!-- Mobile Menu Fullscreen Overlay -->
    <transition
      enter-active-class="transition-all duration-500 ease-out"
      enter-from-class="opacity-0 -translate-y-4"
      enter-to-class="opacity-100 translate-y-0"
      leave-active-class="transition-all duration-300 ease-in"
      leave-from-class="opacity-100 translate-y-0"
      leave-to-class="opacity-0 -translate-y-4"
    >
      <div v-if="isMobileMenuOpen" class="fixed inset-0 h-[100dvh] bg-zinc-950/98 backdrop-blur-xl z-40 md:hidden flex flex-col pt-32 pb-10 px-6 overflow-hidden">
        <nav class="flex flex-col gap-10 items-center flex-grow justify-center -mt-10">
          <router-link 
            v-for="(link, index) in navLinks" 
            :key="link.name"
            :to="link.href"
            @click="closeMobileMenu"
            class="text-4xl sm:text-5xl font-black text-zinc-300 hover:text-white transition-colors uppercase tracking-tight text-center animate-fade-in-up active:scale-95 duration-200"
            :style="`animation-delay: ${index * 100}ms`"
          >
            {{ link.name }}
          </router-link>
        </nav>

        <div class="mt-auto w-full animate-fade-in-up" :style="`animation-delay: ${navLinks.length * 100}ms`">
          <router-link 
            to="/eventos" 
            @click="closeMobileMenu"
            class="w-full flex items-center justify-center py-5 bg-amber-600 hover:bg-amber-500 text-white text-xl font-black uppercase tracking-widest rounded-full transition-all shadow-[0_0_20px_rgba(245,158,11,0.4)] active:scale-95 duration-200"
          >
            Comprar Ingressos
          </router-link>
        </div>
      </div>
    </transition>
  </header>
</template>

<style scoped>
.animate-fade-in-up {
  opacity: 0;
  animation: fadeInUp 0.5s ease-out forwards;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>
