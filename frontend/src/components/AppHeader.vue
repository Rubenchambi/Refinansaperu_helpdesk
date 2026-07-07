<template>
  <header class="bg-white border-b border-slate-200 h-16 flex items-center justify-end px-8 shadow-sm relative z-50">
    <div class="flex items-center gap-4">
      
      <!-- Menú de Usuario -->
      <div class="relative" ref="dropdownRef">
        <button 
          @click="toggleDropdown"
          class="flex items-center gap-2 px-2 py-1.5 rounded-lg hover:bg-slate-100 transition-colors cursor-pointer"
        >
          <!-- Avatar con iniciales -->
          <div 
            class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-white"
            :class="avatarColor"
          >
            {{ userInitials }}
          </div>
          
          <!-- Flecha -->
          <svg 
            xmlns="http://www.w3.org/2000/svg" 
            fill="none" 
            viewBox="0 0 24 24" 
            stroke-width="2" 
            stroke="currentColor" 
            class="w-4 h-4 text-slate-400 transition-transform duration-200"
            :class="{ 'rotate-180': isOpen }"
          >
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
          </svg>
        </button>

        <!-- Dropdown Panel -->
        <Transition
          enter-active-class="transition ease-out duration-150"
          enter-from-class="opacity-0 scale-95"
          enter-to-class="opacity-100 scale-100"
          leave-active-class="transition ease-in duration-100"
          leave-from-class="opacity-100 scale-100"
          leave-to-class="opacity-0 scale-95"
        >
          <div 
            v-if="isOpen"
            class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-xl border border-slate-200 py-1.5"
          >
            <!-- Info del usuario -->
            <div class="px-4 py-3 border-b border-slate-100">
              <p class="text-sm font-semibold text-slate-900">Bienvenido {{ userName }}</p>
              <p class="text-sm font-semibold text-slate-900">{{ userRol }}</p>
              <p class="text-xs text-slate-500 truncate">{{ authStore.user?.email || 'Sin email' }}</p>
            </div>

            <!-- Menú items -->
            <div class="py-1">
              <button 
                @click="goToProfile"
                class="w-full flex items-center gap-3 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors text-left"
              >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-slate-400">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                </svg>
                Tu perfil
              </button>

              <button 
                @click="goToSettings"
                class="w-full flex items-center gap-3 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors text-left"
              >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-slate-400">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Configuración
              </button>
            </div>

            <!-- Separador -->
            <div class="border-t border-slate-100 my-1"></div>

            <!-- Salir -->
            <button 
              @click="confirmLogout"
              class="w-full flex items-center gap-3 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors text-left"
            >
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-red-500">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
              </svg>
              Salir
            </button>
          </div>
        </Transition>
      </div>
    </div>
  </header>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import Swal from 'sweetalert2';

const router = useRouter();
const authStore = useAuthStore();

const isOpen = ref(false);
const dropdownRef = ref(null);

// ===== COMPUTED PARA MOSTRAR DATOS DEL USUARIO =====
const userName = computed(() => authStore.user?.nombre || 'Usuario');
const userRol = computed(() => authStore.user?.rol?.nombre || 'Sin rol');
const userInitials = computed(() => {
  const name = authStore.user?.nombre || 'US';
  return name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
});

// Color del avatar según el rol (puedes adaptarlo)
const avatarColor = computed(() => {
  const rol = authStore.user?.rol?.nombre;
  if (rol === 'Administrador') return 'bg-blue-600';
  if (rol === 'Técnico') return 'bg-green-600';
  if (rol === 'Cliente') return 'bg-purple-600';
  return 'bg-slate-600';
});

// ===== FUNCIONES DEL DROPDOWN =====
const toggleDropdown = () => {
  isOpen.value = !isOpen.value;
};

const closeDropdown = (e) => {
  if (dropdownRef.value && !dropdownRef.value.contains(e.target)) {
    isOpen.value = false;
  }
};

onMounted(() => document.addEventListener('click', closeDropdown));
onUnmounted(() => document.removeEventListener('click', closeDropdown));

// ===== ACCIONES =====
const goToProfile = () => {
  isOpen.value = false;
  router.push('/perfil');
};

const goToSettings = () => {
  isOpen.value = false;
  router.push('/configuracion');
};

const confirmLogout = () => {
  isOpen.value = false;
  
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
      // Limpia el store y redirige al login
      authStore.logout();
      router.push('/login');
    }
  });
};
</script>