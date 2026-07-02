<template>
  <div class="p-6 max-w-7xl mx-auto">
    <!-- Botón regresar -->
    <button 
      @click="goBack" 
      class="mb-6 inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors shadow-sm"
    >
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
      </svg>
      Regresar
    </button>

    <!-- Título -->
    <h1 class="text-2xl font-bold text-slate-800 mb-6 flex items-center gap-2">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7 text-blue-600">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
      </svg>
      Mi Perfil
    </h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Tarjeta de perfil principal -->
      <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <div class="flex flex-col sm:flex-row items-center gap-6">
          <div 
            class="w-24 h-24 rounded-full flex items-center justify-center text-2xl font-bold text-white shadow-lg"
            :class="avatarColor"
          >
            {{ userInitials }}
          </div>
          <div class="text-center sm:text-left">
            <p class="text-xl font-semibold text-slate-900">{{ authStore.user?.nombre || 'Usuario' }}</p>
            <p class="text-sm text-slate-500 flex items-center justify-center sm:justify-start gap-1">
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" :class="rolBadgeColor">
                {{ authStore.user?.rol?.nombre || 'Sin rol' }}
              </span>
            </p>
            <p class="text-sm text-slate-400 mt-1">{{ authStore.user?.email }}</p>
          </div>
        </div>

        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4 border-t border-slate-100 pt-4">
          <div>
            <span class="text-xs font-medium text-slate-400 uppercase">Nombre completo</span>
            <p class="text-sm text-slate-800">{{ authStore.user?.nombre || '-' }}</p>
          </div>
          <div>
            <span class="text-xs font-medium text-slate-400 uppercase">Usuario</span>
            <p class="text-sm text-slate-800">{{ authStore.user?.username || '-' }}</p>
          </div>
          <div>
            <span class="text-xs font-medium text-slate-400 uppercase">Email</span>
            <p class="text-sm text-slate-800">{{ authStore.user?.email || '-' }}</p>
          </div>
          <div>
            <span class="text-xs font-medium text-slate-400 uppercase">Teléfono</span>
            <p class="text-sm text-slate-800">{{ authStore.user?.telefono || 'No registrado' }}</p>
          </div>
          <div>
            <span class="text-xs font-medium text-slate-400 uppercase">Rol</span>
            <p class="text-sm text-slate-800">{{ authStore.user?.rol?.nombre || '-' }}</p>
          </div>
          <div>
            <span class="text-xs font-medium text-slate-400 uppercase">Fecha registro</span>
            <p class="text-sm text-slate-800">{{ formattedDate || '-' }}</p>
          </div>
        </div>
      </div>

      <!-- Tarjeta de estadísticas o información adicional -->
      <div class="lg:col-span-1 space-y-4">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
          <h3 class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-3">Estadísticas</h3>
          <div class="space-y-3">
            <div class="flex justify-between items-center">
              <span class="text-sm text-slate-600">Tickets creados</span>
              <span class="text-sm font-semibold text-slate-900">{{ stats.ticketsCreados || 0 }}</span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-sm text-slate-600">Tickets asignados</span>
              <span class="text-sm font-semibold text-slate-900">{{ stats.ticketsAsignados || 0 }}</span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-sm text-slate-600">Tickets cerrados</span>
              <span class="text-sm font-semibold text-slate-900">{{ stats.ticketsCerrados || 0 }}</span>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
          <h3 class="text-sm font-medium text-slate-400 uppercase tracking-wider mb-3">Acciones rápidas</h3>
          <div class="space-y-2">
            <button @click="goToSettings" class="w-full flex items-center gap-2 px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 rounded-lg transition-colors">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
              Ir a Configuración
            </button>
            <button @click="logout" class="w-full flex items-center gap-2 px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg transition-colors">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
              </svg>
              Cerrar sesión
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import Swal from 'sweetalert2';

const router = useRouter();
const authStore = useAuthStore();

// Iniciales
const userInitials = computed(() => {
  const name = authStore.user?.nombre || 'US';
  return name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
});

// Color del avatar
const avatarColor = computed(() => {
  const rol = authStore.user?.rol?.nombre;
  if (rol === 'Administrador') return 'bg-blue-600';
  if (rol === 'Técnico') return 'bg-green-600';
  if (rol === 'Cliente') return 'bg-purple-600';
  return 'bg-slate-600';
});

// Color de badge
const rolBadgeColor = computed(() => {
  const rol = authStore.user?.rol?.nombre;
  if (rol === 'Administrador') return 'bg-blue-100 text-blue-800';
  if (rol === 'Técnico') return 'bg-green-100 text-green-800';
  if (rol === 'Cliente') return 'bg-purple-100 text-purple-800';
  return 'bg-slate-100 text-slate-800';
});

// Fecha formateada
const formattedDate = computed(() => {
  if (!authStore.user?.created_at) return null;
  return new Date(authStore.user.created_at).toLocaleDateString('es-ES', {
    year: 'numeric', month: 'long', day: 'numeric'
  });
});

// Estadísticas (ejemplo estático, reemplazar con datos reales)
const stats = computed(() => ({
  ticketsCreados: 12,
  ticketsAsignados: 5,
  ticketsCerrados: 7
}));

// Funciones
const goBack = () => {
  router.go(-1);
};

const goToSettings = () => {
  router.push('/configuracion');
};

const logout = () => {
  Swal.fire({
    title: '¿Cerrar sesión?',
    text: '¿Estás seguro que deseas salir?',
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#dc2626',
    cancelButtonColor: '#64748b',
    confirmButtonText: 'Sí, salir',
    cancelButtonText: 'Cancelar',
    reverseButtons: true
  }).then((result) => {
    if (result.isConfirmed) {
      authStore.logout();
      router.push('/login');
    }
  });
};
</script>