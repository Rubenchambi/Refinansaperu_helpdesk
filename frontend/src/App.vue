<template>
  <div class="flex min-h-screen bg-slate-50">
    <aside v-if="$route.path !== '/login'" class="w-64 bg-slate-900 text-white flex flex-col">
      <div class="p-6 text-xl font-bold border-b border-slate-800">SISTICKET</div>
      
      <nav class="flex-1 mt-4">
        <router-link to="/" class="flex items-center py-3 px-6 hover:bg-slate-800 transition-colors">
          <span>Dashboard</span>
        </router-link>
        <router-link to="/crear-ticket" class="flex items-center py-3 px-6 hover:bg-slate-800 transition-colors">
          <span>Crear Ticket</span>
        </router-link>
        
        <div class="mt-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Administración</div>
        
        <router-link to="/clientes" class="flex items-center py-3 px-6 hover:bg-slate-800 transition-colors">
          <span>Clientes</span>
        </router-link>
        <router-link to="/usuarios" class="flex items-center py-3 px-6 hover:bg-slate-800 transition-colors">
          <span>Usuarios</span>
        </router-link>
        <router-link to="/tipos-caso" class="flex items-center py-3 px-6 hover:bg-slate-800 transition-colors">
          <span>Tipos de Caso</span>
        </router-link>
        <router-link to="/productos" class="flex items-center py-3 px-6 hover:bg-slate-800 transition-colors">
          <span>Productos/Servicios</span>
        </router-link>
        <router-link to="/seguridad" class="flex items-center py-3 px-6 hover:bg-slate-800 transition-colors">
          <span>Copia de Seguridad</span>
        </router-link>
        <router-link to="/reset" class="flex items-center py-3 px-6 text-red-400 hover:bg-slate-800 transition-colors">
          <span>Resetear Sistema</span>
        </router-link>
      </nav>

      <div class="p-6 border-t border-slate-800 text-sm">
        <p class="font-bold">Benvenido : {{ authStore.user?.nombre || 'Invitado' }}</p>
        <p class="text-slate-500 text-xs">Administrador</p>
      </div>
    </aside>

    <main class="flex-1 flex flex-col">
      <header v-if="$route.path !== '/login'" class="bg-white border-b border-slate-200 h-16 flex items-center justify-end px-8 shadow-sm">
        <div class="flex items-center gap-4">
          <span class="text-sm font-semibold text-slate-700">
          Benvenido :  {{ authStore.user?.nombre || 'Usuario' }}
          </span>
          <button @click="handleLogout" class="text-sm text-red-600 hover:text-red-800 font-medium cursor-pointer">
            Salir
          </button>
        </div>
      </header>

      <router-view />
    </main>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth'; // Importamos el store
import Swal from 'sweetalert2';

const router = useRouter();
const authStore = useAuthStore(); // Usamos el store

const handleLogout = () => {
  Swal.fire({
    title: '¿Cerrar sesión?',
    text: "Vas a salir del sistema.",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#2563eb',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Sí, salir'
  }).then((result) => {
    if (result.isConfirmed) {
      // Limpiamos todo
      localStorage.removeItem('token');
      localStorage.removeItem('user');
      authStore.user = null;
      authStore.token = null;
      router.push('/login');
    }
  });
};
</script>