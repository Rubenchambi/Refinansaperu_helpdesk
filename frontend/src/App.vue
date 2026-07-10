<template>
  <div class="flex min-h-screen bg-slate-50">
    <!-- Sidebar -->
    <aside 
      :class="[
        'bg-slate-900 text-white flex flex-col transition-all duration-300 ease-in-out',
        sidebarCollapsed ? 'w-16' : 'w-64'
      ]"
      v-if="$route.path !== '/login'"
    >
      <!-- Header del Sidebar con botón de colapsar -->
      <div class="flex items-center justify-between p-4 border-b border-slate-800" :class="sidebarCollapsed ? 'px-3' : 'px-6'">
        <span v-if="!sidebarCollapsed" class="text-xl font-bold">SISTICKET</span>
        <button 
          @click="toggleSidebar"
          class="p-1 rounded hover:bg-slate-800 transition-colors"
          :class="sidebarCollapsed ? 'mx-auto' : ''"
          title="Colapsar sidebar"
        >
          <Bars3BottomLeftIcon v-if="!sidebarCollapsed" class="w-5 h-5" />
          <Bars3Icon v-else class="w-5 h-5" />
        </button>
      </div>
      
      <nav class="flex-1 mt-4">
        <router-link to="/" class="flex items-center gap-3 py-3 px-6 hover:bg-slate-800 transition-colors" :class="sidebarCollapsed ? 'justify-center px-3' : ''">
          <HomeIcon class="w-5 h-5 shrink-0" />
          <span v-if="!sidebarCollapsed">Dashboard</span>
        </router-link>

        <router-link to="/tickets" class="flex items-center gap-3 py-3 px-6 hover:bg-slate-800 transition-colors" :class="sidebarCollapsed ? 'justify-center px-3' : ''">
          <TicketIcon class="w-5 h-5 shrink-0" />
          <span v-if="!sidebarCollapsed">Tickets</span>
        </router-link>
        
        <div v-if="!sidebarCollapsed" class="mt-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Administración</div>
        <div v-else class="mt-4 mx-auto w-8 h-px bg-slate-700"></div>
        
        <router-link to="/clientes" class="flex items-center gap-3 py-3 px-6 hover:bg-slate-800 transition-colors" :class="sidebarCollapsed ? 'justify-center px-3' : ''">
          <UsersIcon class="w-5 h-5 shrink-0" />
          <span v-if="!sidebarCollapsed">Clientes</span>
        </router-link>
        
        <router-link to="/usuarios" class="flex items-center gap-3 py-3 px-6 hover:bg-slate-800 transition-colors" :class="sidebarCollapsed ? 'justify-center px-3' : ''">
          <UserGroupIcon class="w-5 h-5 shrink-0" />
          <span v-if="!sidebarCollapsed">Usuarios</span>
        </router-link>
        
        <router-link to="/tipos-casos" class="flex items-center gap-3 py-3 px-6 hover:bg-slate-800 transition-colors" :class="sidebarCollapsed ? 'justify-center px-3' : ''">
          <TagIcon class="w-5 h-5 shrink-0" />
          <span v-if="!sidebarCollapsed">Tipos de Caso</span>
        </router-link>

        <router-link to="/administracion" class="flex items-center gap-3 py-3 px-6 hover:bg-slate-800 transition-colors" :class="sidebarCollapsed ? 'justify-center px-3' : ''">
          <Cog6ToothIcon class="w-5 h-5 shrink-0" />
          <span v-if="!sidebarCollapsed">Administración</span>
        </router-link>
        
        <router-link to="/productos" class="flex items-center gap-3 py-3 px-6 hover:bg-slate-800 transition-colors" :class="sidebarCollapsed ? 'justify-center px-3' : ''">
          <CubeIcon class="w-5 h-5 shrink-0" />
          <span v-if="!sidebarCollapsed">Productos/Servicios</span>
        </router-link>
        
        <router-link to="/seguridad" class="flex items-center gap-3 py-3 px-6 hover:bg-slate-800 transition-colors" :class="sidebarCollapsed ? 'justify-center px-3' : ''">
          <ShieldCheckIcon class="w-5 h-5 shrink-0" />
          <span v-if="!sidebarCollapsed">Copia de Seguridad</span>
        </router-link>
        
        <router-link to="/reset" class="flex items-center gap-3 py-3 px-6 text-red-400 hover:bg-slate-800 transition-colors" :class="sidebarCollapsed ? 'justify-center px-3' : ''">
          <ArrowPathIcon class="w-5 h-5 shrink-0" />
          <span v-if="!sidebarCollapsed">Resetear Sistema</span>
        </router-link>
      </nav>

      <!-- Saludo en el Sidebar (Inferior) -->
      <div class="p-4 border-t border-slate-800 text-sm" :class="sidebarCollapsed ? 'px-3 text-center' : 'px-6'">
        <p v-if="!sidebarCollapsed" class="font-bold">Bienvenido : {{ userNombre }}</p>
        <p v-if="!sidebarCollapsed" class="text-slate-500 text-xs">{{ userRol }}</p>
        <UserCircleIcon v-else class="w-6 h-6 mx-auto text-slate-400" />
      </div>
    </aside>

    <!-- Contenido principal -->
    <main class="flex-1 flex flex-col">
      <!-- Header -->
      <AppHeader />

      <router-view />
    </main>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue'; 
import AppHeader from '@/components/AppHeader.vue';
import { useAuthStore } from '@/stores/auth';

// Heroicons
import {
  HomeIcon,
  TicketIcon,
  UsersIcon,
  UserGroupIcon,
  TagIcon,
  CubeIcon,
  ShieldCheckIcon,
  ArrowPathIcon,
  UserCircleIcon,
  Bars3Icon,
  Bars3BottomLeftIcon,
  Cog6ToothIcon
} from '@heroicons/vue/24/outline';

const authStore = useAuthStore();
const userNombre = computed(() => authStore.user?.nombre || 'Invitado');
const userRol = computed(() => authStore.user?.rol?.nombre || 'Sin rol');

// Estado del sidebar
const sidebarCollapsed = ref(false);

const toggleSidebar = () => {
  sidebarCollapsed.value = !sidebarCollapsed.value;
};
</script>