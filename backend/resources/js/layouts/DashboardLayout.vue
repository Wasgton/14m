<template>
  <div class="min-h-screen bg-zinc-50 flex">
    <!-- Mobile Overlay -->
    <transition name="fade">
      <div 
        v-if="!isSidebarCollapsed" 
        @click="isSidebarCollapsed = true" 
        class="fixed inset-0 bg-zinc-900/60 backdrop-blur-sm z-40 sm:hidden"
      ></div>
    </transition>

    <!-- Sidebar -->
    <aside 
      class="bg-zinc-950 text-zinc-300 flex flex-col shadow-2xl z-50 transition-all duration-300 fixed sm:sticky sm:top-0 h-screen" 
      :class="[
        isSidebarCollapsed ? '-translate-x-full sm:translate-x-0 sm:w-20' : 'translate-x-0 w-64'
      ]"
    >
      <div class="flex items-center justify-center h-20 border-b border-zinc-800 relative shrink-0">
        <h1 v-if="!isSidebarCollapsed" class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-rose-400 whitespace-nowrap">KTORZE M</h1>
        <h1 v-else class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-rose-400">K</h1>
      </div>
      
      <nav class="flex-1 overflow-y-auto py-5 space-y-6" :class="isSidebarCollapsed ? 'px-2' : 'px-4'">
        <div v-for="group in menuGroups" :key="group.name" class="space-y-1">
          <div v-if="group.showTitle && !isSidebarCollapsed" class="px-3 pb-1 pt-2 text-[11px] font-bold text-zinc-500 uppercase tracking-wider">
            {{ group.name }}
          </div>
          <template v-for="item in group.items" :key="item.name">
            <router-link 
              v-if="!item.permission || hasPermission(item.permission)"
              :to="item.to" 
              @click="closeMobileSidebar"
              class="flex items-center space-x-3 py-2.5 rounded-xl transition-all duration-200 group relative"
              :class="[$route.path === item.to ? 'bg-indigo-600/10 text-indigo-400' : 'hover:bg-zinc-900 text-zinc-400 hover:text-zinc-100', isSidebarCollapsed ? 'justify-center px-0' : 'px-4']"
              :title="isSidebarCollapsed ? item.name : ''"
            >
              <div v-if="$route.path === item.to" class="absolute left-0 w-1 h-7 bg-indigo-500 rounded-r-full"></div>
              <component :is="item.icon" class="w-[18px] h-[18px] transition-transform group-hover:scale-110 flex-shrink-0" :class="[$route.path === item.to ? 'text-indigo-400' : 'text-zinc-500 group-hover:text-zinc-300']" />
              <span v-if="!isSidebarCollapsed" class="text-[13px] font-medium whitespace-nowrap">{{ item.name }}</span>
            </router-link>
          </template>
        </div>
      </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col min-w-0 relative">
      <!-- Top header for mobile and general layout -->
      <header class="h-20 bg-white border-b border-zinc-200 flex items-center justify-between px-4 sm:px-8 shadow-sm z-30 sticky top-0 transition-all duration-300">
        <div class="flex items-center space-x-3 sm:space-x-4">
          <button @click="toggleSidebar" class="p-2 -ml-2 text-zinc-500 hover:text-indigo-600 hover:bg-zinc-100 rounded-xl transition-colors">
            <MenuIcon class="w-6 h-6" />
          </button>
          <h2 class="text-lg sm:text-xl font-bold text-zinc-800 tracking-tight truncate max-w-[140px] sm:max-w-none">{{ currentRouteName }}</h2>
        </div>
        
        <div class="flex items-center space-x-4">
          <button class="p-2 text-zinc-400 hover:text-indigo-600 hover:bg-zinc-100 rounded-full transition-colors relative">
            <BellIcon class="w-5 h-5" />
            <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-rose-500 rounded-full border-2 border-white"></span>
          </button>
          
          <div class="relative pl-4 border-l border-zinc-200">
            <button @click="toggleUserMenu" @blur="closeUserMenuDelay" class="flex items-center space-x-3 text-left focus:outline-none hover:bg-zinc-50 p-1.5 rounded-xl transition-colors">
              <div class="text-right hidden sm:block">
                <p class="text-sm font-bold text-zinc-800 leading-none">{{ user?.name || 'Admin User' }}</p>
                <p class="text-xs text-zinc-500 mt-1">{{ user?.email || 'admin@ktorzem.com' }}</p>
              </div>
              <div class="flex items-center space-x-2">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-rose-500 border-2 border-white shadow-md flex items-center justify-center text-white font-bold">
                  {{ user?.name ? user.name.charAt(0).toUpperCase() : 'K' }}
                </div>
                <ChevronDownIcon class="w-4 h-4 text-zinc-400 hidden sm:block transition-transform duration-200" :class="{ 'rotate-180': isUserMenuOpen }" />
              </div>
            </button>

            <!-- Dropdown Menu -->
            <transition name="dropdown">
              <div v-if="isUserMenuOpen" class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl py-2 border border-zinc-100/80 z-50 origin-top-right">
                <div class="px-4 py-3 border-b border-zinc-50 sm:hidden">
                  <p class="text-sm font-bold text-zinc-800">{{ user?.name || 'Admin User' }}</p>
                  <p class="text-xs text-zinc-500 mt-0.5 truncate">{{ user?.email || 'admin@ktorzem.com' }}</p>
                </div>
                
                <router-link to="/users" @click="isUserMenuOpen = false" class="flex items-center space-x-2 px-4 py-2.5 text-sm text-zinc-700 hover:bg-zinc-50 hover:text-indigo-600 transition-colors">
                  <UserIcon class="w-4 h-4" />
                  <span class="font-medium">Meu Perfil</span>
                </router-link>
                
                <button @click="logout" class="w-full flex items-center space-x-2 px-4 py-2.5 text-sm text-rose-600 hover:bg-rose-50 transition-colors border-t border-zinc-50">
                  <LogOutIcon class="w-4 h-4" />
                  <span class="font-medium">Sair do Sistema</span>
                </button>
              </div>
            </transition>
          </div>
        </div>
      </header>
      
      <!-- Content Area -->
      <div class="flex-1 overflow-x-hidden overflow-y-auto bg-zinc-50 p-6 sm:p-8">
        <div class="max-w-7xl mx-auto z-10 relative">
          <router-view v-slot="{ Component }">
            <transition name="fade" mode="out-in">
              <component :is="Component" />
            </transition>
          </router-view>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { 
  Menu as MenuIcon,
  LayoutDashboard as DashboardIcon, 
  Image as ImageIcon, 
  Calendar as CalendarIcon, 
  Users as UsersIcon,
  LogOut as LogOutIcon,
  Bell as BellIcon,
  Mic2 as Mic2Icon,
  Shield as ShieldIcon,
  Key as KeyIcon,
  User as UserIcon,
  ChevronDown as ChevronDownIcon,
  Settings as SettingsIcon
} from 'lucide-vue-next';
import { useAuth } from '../composables/useAuth';

const route = useRoute();
const router = useRouter();
const { user, logout: performLogout, hasPermission } = useAuth();

const isSidebarCollapsed = ref(window.innerWidth < 640);
const isUserMenuOpen = ref(false);

const toggleSidebar = () => {
  isSidebarCollapsed.value = !isSidebarCollapsed.value;
};

const closeMobileSidebar = () => {
  if (window.innerWidth < 640) {
    isSidebarCollapsed.value = true;
  }
};

const toggleUserMenu = () => {
  isUserMenuOpen.value = !isUserMenuOpen.value;
};

const closeUserMenuDelay = () => {
  // Use a slight timeout so clicks on dropdown buttons register before the blur closes the menu
  setTimeout(() => {
    isUserMenuOpen.value = false;
  }, 200);
};

interface MenuItem {
  name: string;
  to: string;
  icon: any;
  permission?: string;
}

interface MenuGroup {
  name: string;
  showTitle: boolean;
  items: MenuItem[];
}

const menuGroups: MenuGroup[] = [
  {
    name: 'Geral',
    showTitle: false,
    items: [
      { name: 'Dashboard', to: '/', icon: DashboardIcon },
    ]
  },
  {
    name: 'Recursos',
    showTitle: true,
    items: [
      { name: 'Banners', to: '/banners', icon: ImageIcon, permission: 'view.banners' },
      { name: 'Eventos', to: '/eventos', icon: CalendarIcon, permission: 'view.events' },
      { name: 'Parceiros', to: '/parceiros', icon: UsersIcon, permission: 'view.partners' },
      { name: 'Artistas', to: '/artistas', icon: Mic2Icon, permission: 'view.artists' },
    ]
  },
  {
    name: 'Administrativo',
    showTitle: true,
    items: [
      { name: 'Usuários', to: '/users', icon: UserIcon, permission: 'view.users' },
      { name: 'Cargos', to: '/roles', icon: ShieldIcon, permission: 'view.roles' },
      { name: 'Permissões', to: '/permissions', icon: KeyIcon, permission: 'view.permissions' },
      { name: 'Configurações', to: '/settings', icon: SettingsIcon, permission: 'view.settings' },
    ]
  }
];

const currentRouteName = computed(() => {
  return route.name || 'Visão Geral';
});

const logout = async () => {
  await performLogout();
  router.push('/login');
};
</script>

<style>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease, transform 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
  transform: translateY(10px);
}
</style>
