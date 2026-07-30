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
        <!-- 1. DASHBOARD: Visible para TODOS -->
        <router-link to="/" class="flex items-center gap-3 py-3 px-6 hover:bg-slate-800 transition-colors" :class="sidebarCollapsed ? 'justify-center px-3' : ''">
          <HomeIcon class="w-5 h-5 shrink-0" />
          <span v-if="!sidebarCollapsed">Dashboard</span>
        </router-link>

        <!-- 2. TICKETS: Visible para TODOS -->
        <router-link to="/tickets" class="flex items-center gap-3 py-3 px-6 hover:bg-slate-800 transition-colors" :class="sidebarCollapsed ? 'justify-center px-3' : ''">
          <TicketIcon class="w-5 h-5 shrink-0" />
          <span v-if="!sidebarCollapsed">Tickets</span>
        </router-link>
        
        <!-- SECCIÓN DE ADMINISTRACIÓN (Solo visible si el rol puede ver al menos un módulo de administración) -->
        <template v-if="puedeVerSeccionAdmin">
          <div v-if="!sidebarCollapsed" class="mt-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Administración</div>
          <div v-else class="mt-4 mx-auto w-8 h-px bg-slate-700"></div>
          
          <!-- Clientes: Admin (1), Técnico (2), Supervisor (5) -->
          <router-link v-if="puedeVerClientes" to="/clientes" class="flex items-center gap-3 py-3 px-6 hover:bg-slate-800 transition-colors" :class="sidebarCollapsed ? 'justify-center px-3' : ''">
            <UsersIcon class="w-5 h-5 shrink-0" />
            <span v-if="!sidebarCollapsed">Clientes</span>
          </router-link>
          
          <!-- Usuarios: Admin (1), Técnico (2), Supervisor (5) -->
          <router-link v-if="puedeVerUsuarios" to="/usuarios" class="flex items-center gap-3 py-3 px-6 hover:bg-slate-800 transition-colors" :class="sidebarCollapsed ? 'justify-center px-3' : ''">
            <UserGroupIcon class="w-5 h-5 shrink-0" />
            <span v-if="!sidebarCollapsed">Usuarios</span>
          </router-link>
          
          <!-- Tipos de Caso: Admin (1), Técnico (2), Supervisor (5) -->
          <router-link v-if="puedeVerTiposCasos" to="/tipos-casos" class="flex items-center gap-3 py-3 px-6 hover:bg-slate-800 transition-colors" :class="sidebarCollapsed ? 'justify-center px-3' : ''">
            <TagIcon class="w-5 h-5 shrink-0" />
            <span v-if="!sidebarCollapsed">Tipos de Caso</span>
          </router-link>

          <!-- Administración General, Productos, Seguridad, Reset: Solo Admin (1) y Técnico (2) -->
          <router-link v-if="puedeVerAdminTotal" to="/administracion" class="flex items-center gap-3 py-3 px-6 hover:bg-slate-800 transition-colors" :class="sidebarCollapsed ? 'justify-center px-3' : ''">
            <Cog6ToothIcon class="w-5 h-5 shrink-0" />
            <span v-if="!sidebarCollapsed">Administración</span>
          </router-link>
          
          <router-link v-if="puedeVerAdminTotal" to="/productos" class="flex items-center gap-3 py-3 px-6 hover:bg-slate-800 transition-colors" :class="sidebarCollapsed ? 'justify-center px-3' : ''">
            <CubeIcon class="w-5 h-5 shrink-0" />
            <span v-if="!sidebarCollapsed">Productos/Servicios</span>
          </router-link>
          
          <router-link v-if="puedeVerAdminTotal" to="/seguridad" class="flex items-center gap-3 py-3 px-6 hover:bg-slate-800 transition-colors" :class="sidebarCollapsed ? 'justify-center px-3' : ''">
            <ShieldCheckIcon class="w-5 h-5 shrink-0" />
            <span v-if="!sidebarCollapsed">Copia de Seguridad</span>
          </router-link>
          
          <router-link v-if="puedeVerAdminTotal" to="/reset" class="flex items-center gap-3 py-3 px-6 text-red-400 hover:bg-slate-800 transition-colors" :class="sidebarCollapsed ? 'justify-center px-3' : ''">
            <ArrowPathIcon class="w-5 h-5 shrink-0" />
            <span v-if="!sidebarCollapsed">Resetear Sistema</span>
          </router-link>
        </template>
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
import { computed, ref, onMounted, onUnmounted } from 'vue'; 
import AppHeader from '@/components/AppHeader.vue';
import { useAuthStore } from '@/stores/auth';
import api from '@/services/api';
import Swal from 'sweetalert2';

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

// Obtenemos el ID del rol del usuario logueado (manejando posibles estructuras de datos)
const rolId = computed(() => {
  return authStore.user?.rolId ?? authStore.user?.rol?.id ?? 0;
});

// --- REGLAS DE PERMISOS POR MÓDULO ---
const puedeVerUsuarios = computed(() => [1, 2, 5].includes(rolId.value));
const puedeVerClientes = computed(() => [1, 2].includes(rolId.value));
const puedeVerTiposCasos = computed(() => [1, 2, 5].includes(rolId.value));
const puedeVerAdminTotal = computed(() => [1, 2].includes(rolId.value));
const puedeVerSeccionAdmin = computed(() => [1, 2, 5].includes(rolId.value));

// Estado del sidebar
const sidebarCollapsed = ref(false);

const toggleSidebar = () => {
  sidebarCollapsed.value = !sidebarCollapsed.value;
};

// --- SISTEMA DE NOTIFICACIONES GLOBALES EN SEGUNDO PLANO ---
let intervaloGlobalNotificaciones = null;

const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 5000,
  timerProgressBar: true
});

// Mapas globales actualizados para rastrear estados, técnicos y cantidad de notas
const ticketsAnteriorMap = ref(new Map());
const tecnicoAnteriorMap = ref(new Map());
const bitacorasCountMap = ref(new Map()); // <--- Nuevo mapa para las notas

const verificarAlertasGlobales = async () => {
  if (!authStore.user) return;

  try {
    const { data } = await api.get('/tickets?all=true');
    const listaTickets = data.data || data;

    listaTickets.forEach(ticketActual => {
      const estadoAnterior = ticketsAnteriorMap.value.get(ticketActual.id);
      const tecnicoAnterior = tecnicoAnteriorMap.value.get(ticketActual.id);
      const cantidadNotasAnterior = bitacorasCountMap.value.get(ticketActual.id);
      
      // Contamos cuántas notas/bitácoras tiene actualmente el ticket (asumiendo que viene un array 'bitacoras')
      const cantidadNotasActual = ticketActual.bitacoras ? ticketActual.bitacoras.length : 0;

      // Si es la primera vez que lo vemos, solo memorizamos todo sin alertar
      if (estadoAnterior === undefined) {
        ticketsAnteriorMap.value.set(ticketActual.id, ticketActual.estadoTicket);
        tecnicoAnteriorMap.value.set(ticketActual.id, ticketActual.tecnicoAsignadoId);
        bitacorasCountMap.value.set(ticketActual.id, cantidadNotasActual);
        return;
      }

      // 1. SI CAMBIÓ A RESUELTO (Aviso para el técnico)
      if (estadoAnterior !== ticketActual.estadoTicket && ticketActual.estadoTicket === 'Resuelto') {
        Toast.fire({
          icon: 'success',
          title: `¡Ticket Nro : ${ticketActual.codigo_ticket} Resuelto!`,
          text: 'El usuario dio el visto bueno. Ya puedes finalizarlo.'
        });
      }

      // 2. SI FUE ASIGNADO A UN TÉCNICO (Aviso de nuevo caso)
      if (!tecnicoAnterior && ticketActual.tecnicoAsignadoId) {
        Toast.fire({
          icon: 'info',
          title: `¡Nuevo ticket asignado! (Nro : ${ticketActual.codigo_ticket})`,
          text: `Te han asignado el caso: "${ticketActual.asunto}"`
        });
      }

      // 3. 📝 SI SE ENVIÓ UNA NUEVA NOTA / BITÁCORA
      if (cantidadNotasAnterior !== undefined && cantidadNotasActual > cantidadNotasAnterior) {
        Toast.fire({
          icon: 'warning',
          title: `Nueva nota en ticket Nro : ${ticketActual.codigo_ticket}`,
          text: 'El técnico ha dejado un mensaje para revisión.'
        });
      }

      // Actualizamos todos los mapas con los valores actuales
      ticketsAnteriorMap.value.set(ticketActual.id, ticketActual.estadoTicket);
      tecnicoAnteriorMap.value.set(ticketActual.id, ticketActual.tecnicoAsignadoId);
      bitacorasCountMap.value.set(ticketActual.id, cantidadNotasActual);
    });

  } catch (error) {
    console.error("❌ Error en notificaciones globales:", error);
  }
};
onMounted(() => {
  verificarAlertasGlobales();
  // Consulta global cada 12 segundos
  intervaloGlobalNotificaciones = setInterval(verificarAlertasGlobales, 12000);
});

onUnmounted(() => {
  clearInterval(intervaloGlobalNotificaciones);
});
</script>