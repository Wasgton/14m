<script setup lang="ts">
const partners = [
  { id: 1, name: 'Sponsor A', url: 'https://ui-avatars.com/api/?name=Sponsor+A&background=0D8ABC&color=fff&size=120' },
  { id: 2, name: 'Partner B', url: 'https://ui-avatars.com/api/?name=Partner+B&background=FF8A00&color=fff&size=120' },
  { id: 3, name: 'Sponsor C', url: 'https://ui-avatars.com/api/?name=Sponsor+C&background=10B981&color=fff&size=120' },
  { id: 4, name: 'Partner D', url: 'https://ui-avatars.com/api/?name=Partner+D&background=8B5CF6&color=fff&size=120' },
  { id: 5, name: 'Sponsor E', url: 'https://ui-avatars.com/api/?name=Sponsor+E&background=EC4899&color=fff&size=120' },
  { id: 6, name: 'Partner F', url: 'https://ui-avatars.com/api/?name=Partner+F&background=F43F5E&color=fff&size=120' },
]
</script>

<template>
  <section id="partners" class="py-32 bg-zinc-900 border-t border-zinc-800">
    <div class="max-w-7xl mx-auto px-6 text-center">
      <h2 class="text-4xl md:text-5xl font-black uppercase tracking-tight mb-20 text-zinc-600">Marcas Parceiras</h2>
      
      <div class="flex flex-wrap justify-center items-center gap-12 md:gap-20 opacity-60">
        <a v-for="partner in partners" :key="partner.id" href="#" class="block grayscale hover:grayscale-0 transition-all duration-500 hover:scale-110 hover:opacity-100">
          <img :src="partner.url" :alt="partner.name" class="w-20 h-20 md:w-28 md:h-28 rounded-full bg-zinc-800 object-cover shadow-xl" />
        </a>
      </div>
    </div>
  </section>
</template>
