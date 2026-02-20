<script setup lang="ts">
const partners = [
  { id: 1, name: 'Heineken', url: 'https://upload.wikimedia.org/wikipedia/commons/2/25/Heineken_logo.svg' },
  { id: 2, name: 'Ambev', url: 'https://upload.wikimedia.org/wikipedia/commons/4/4b/Ambev_logo_2021.svg' },
  { id: 3, name: 'Coca-Cola', url: 'https://upload.wikimedia.org/wikipedia/commons/c/ce/Coca-Cola_logo.svg' },
  { id: 4, name: 'Red Bull', url: 'https://upload.wikimedia.org/wikipedia/en/f/f5/RedBullEnergyDrink.svg' },
  { id: 5, name: 'Monster Energy', url: 'https://upload.wikimedia.org/wikipedia/commons/4/45/Monster_Energy_logo.svg' },
  { id: 6, name: 'Absolut Vodka', url: 'https://upload.wikimedia.org/wikipedia/commons/3/30/Absolut_Vodka_logo.svg' }
]
</script>

<template>
  <section id="partners" class="py-32 bg-zinc-900 border-t border-zinc-800">
    <div class="max-w-7xl mx-auto px-6 text-center">
      <h2 class="text-4xl md:text-5xl font-black uppercase tracking-tight mb-20 text-zinc-600">Marcas Parceiras</h2>
      
      <div class="flex flex-wrap justify-center items-center gap-16 md:gap-24">
        <a v-for="partner in partners" :key="partner.id" href="#" class="block group">
          <img 
            :src="partner.url" 
            :alt="partner.name" 
            class="h-10 md:h-14 lg:h-16 w-auto object-contain filter brightness-0 invert opacity-40 group-hover:brightness-100 group-hover:invert-0 group-hover:opacity-100 transition-all duration-500 transform group-hover:scale-110" 
          />
        </a>
      </div>
    </div>
  </section>
</template>
