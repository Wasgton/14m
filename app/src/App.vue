<script setup lang="ts">
import AppHeader from './components/AppHeader.vue'
import Hero from './components/Hero.vue'
import Events from './components/Events.vue'
import Artists from './components/Artists.vue'
import Portfolio from './components/Portfolio.vue'
import Partners from './components/Partners.vue'
import AppFooter from './components/AppFooter.vue'
</script>

<template>
  <div class="min-h-screen bg-zinc-950 text-slate-50 font-sans selection:bg-fuchsia-500 selection:text-white flex flex-col">
    <AppHeader />
    
    <main class="flex-grow">
      <Hero />
      <Events />
      <Artists />
      <Portfolio />
      <Partners />
    </main>

    <AppFooter />
  </div>
</template>

<style>
html {
  scroll-behavior: smooth;
}

body {
  background-color: #09090b; /* zinc-950 */
  color: #f8fafc; /* slate-50 */
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}
</style>
