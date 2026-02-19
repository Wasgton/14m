<script setup lang="ts">
</script>

<template>
  <footer class="bg-zinc-950 pt-24 pb-12 border-t border-zinc-900">
    <div class="max-w-7xl mx-auto px-6">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-20 text-center md:text-left">
        <div class="md:col-span-2 flex flex-col items-center md:items-start">
          <a href="#" class="text-4xl font-black tracking-tighter uppercase mb-6 inline-block">
            Ktorze <span class="text-fuchsia-500">M</span>
          </a>
          <p class="text-zinc-400 max-w-sm text-lg leading-relaxed">
            Produção de eventos especializada em festivais de música. Criando momentos inesquecíveis em Lauro de Freitas, Bahia. Vamos fazer história juntos.
          </p>
        </div>
        
        <div class="flex flex-col items-center md:items-start">
          <h4 class="font-bold text-xl mb-6 uppercase tracking-widest text-white">Explorar</h4>
          <ul class="space-y-4">
            <li><a href="#events" class="text-zinc-400 hover:text-fuchsia-400 transition-colors uppercase text-sm tracking-wide font-medium">Próximos Eventos</a></li>
            <li><a href="#artists" class="text-zinc-400 hover:text-fuchsia-400 transition-colors uppercase text-sm tracking-wide font-medium">Artistas</a></li>
            <li><a href="#portfolio" class="text-zinc-400 hover:text-fuchsia-400 transition-colors uppercase text-sm tracking-wide font-medium">Portfólio</a></li>
            <li><a href="#partners" class="text-zinc-400 hover:text-fuchsia-400 transition-colors uppercase text-sm tracking-wide font-medium">Parceiros</a></li>
          </ul>
        </div>
        
        <div class="flex flex-col items-center md:items-start">
          <h4 class="font-bold text-xl mb-6 uppercase tracking-widest text-white">Conectar</h4>
          <div class="flex space-x-4">
            <!-- Instagram -->
            <a href="https://instagram.com/14mproducoes" target="_blank" class="w-12 h-12 rounded-full bg-zinc-900 flex items-center justify-center text-zinc-400 hover:bg-gradient-to-tr hover:from-orange-500 hover:via-fuchsia-600 hover:to-indigo-500 hover:text-white transition-all duration-300 hover:scale-110">
              <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="20" x="2" y="2" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/></svg>
            </a>
            <!-- Twitter / X -->
            <a href="#" class="w-12 h-12 rounded-full bg-zinc-900 flex items-center justify-center text-zinc-400 hover:text-white transition-all duration-300 hover:bg-black hover:scale-110">
              <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"/></svg>
            </a>
            <!-- Facebook -->
            <a href="#" class="w-12 h-12 rounded-full bg-zinc-900 flex items-center justify-center text-zinc-400 hover:text-white transition-all duration-300 hover:bg-blue-600 hover:scale-110">
              <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
            </a>
          </div>
        </div>
      </div>
      
      <div class="pt-10 border-t border-zinc-900 flex flex-col md:flex-row items-center justify-between gap-6 text-sm text-zinc-600 font-medium">
        <p>&copy; 2026 KTORZE M Produções. Todos os direitos reservados.</p>
        <p>CNPJ: --.---.---/----.-- | Lauro de Freitas, BA</p>
      </div>
    </div>
  </footer>
</template>
