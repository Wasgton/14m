<script setup lang="ts">
import AppHeader from './components/AppHeader.vue'
import AppFooter from './components/AppFooter.vue'
</script>

<template>
  <div class="min-h-screen bg-zinc-950 text-slate-50 font-sans selection:bg-amber-500 selection:text-white flex flex-col">
    <AppHeader />
    
    <main class="flex-grow">
      <router-view />
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
