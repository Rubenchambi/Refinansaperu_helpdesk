<template>
  <header class="bg-white border-b border-slate-200 h-16 flex items-center justify-end px-8 shadow-sm">
    <div class="flex items-center gap-4">
      <!-- Nombre del usuario dinámico -->
      <span class="text-sm font-semibold text-slate-700">
        {{ userName }}
      </span>
      
      <!-- Iniciales del usuario -->
      <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-xs font-bold text-slate-600">
        {{ userInitials }}
      </div>
      
      <!-- Botón de Salir -->
      <button 
        @click="handleLogout" 
        class="ml-4 text-sm text-red-600 hover:text-red-800 font-medium cursor-pointer"
      >
        Salir
      </button>
    </div>
  </header>
</template>

<script setup>
import { computed } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import Swal from 'sweetalert2';

const router = useRouter();
const authStore = useAuthStore();

// CORRECCIÓN: Cambiamos .name por .nombre
const userName = computed(() => authStore.user?.nombre || 'Usuario');

// CORRECCIÓN: Cambiamos .name por .nombre
const userInitials = computed(() => {
  const name = authStore.user?.nombre || 'US';
  return name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
});

const handleLogout = () => {
  Swal.fire({
    title: '¿Cerrar sesión?',
    text: "Tu sesión actual finalizará.",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#2563eb',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Sí, salir'
  }).then((result) => {
    if (result.isConfirmed) {
      localStorage.removeItem('token');
      localStorage.removeItem('user');
      authStore.token = null;
      authStore.user = null;
      router.push('/login');
    }
  });
};
</script>