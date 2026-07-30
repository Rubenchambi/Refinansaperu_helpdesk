<template>
  <div class="p-6 max-w-4xl mx-auto">
    <button 
      @click="goBack" 
      class="mb-6 inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors shadow-sm"
    >
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
      </svg>
      Regresar
    </button>

    <h1 class="text-2xl font-bold text-slate-800 mb-6 flex items-center gap-2">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7 text-slate-600">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
      </svg>
      Configuración
    </h1>

    <div class="space-y-6">
      <!-- Cambiar contraseña -->
      <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h2 class="text-lg font-semibold text-slate-800 flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-slate-500">
            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
          </svg>
          Cambiar contraseña
        </h2>
        <form @submit.prevent="changePassword" class="mt-4 space-y-4">
          <div>
            <label class="block text-sm font-medium text-slate-700">Contraseña actual</label>
            <input 
              type="password" 
              v-model="passwordForm.current" 
              class="mt-1 block w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors" 
              required
              placeholder="Ingresa tu contraseña actual"
            >
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-slate-700">Nueva contraseña</label>
              <input 
                type="password" 
                v-model="passwordForm.new" 
                class="mt-1 block w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors" 
                required
                placeholder="Nueva contraseña"
              >
            </div>
            <div>
              <label class="block text-sm font-medium text-slate-700">Confirmar nueva contraseña</label>
              <input 
                type="password" 
                v-model="passwordForm.confirm" 
                class="mt-1 block w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors" 
                required
                placeholder="Confirma la nueva contraseña"
              >
            </div>
          </div>

          <!-- Nota informativa de requisitos -->
          <div class="text-xs text-slate-500 bg-slate-50 p-3 rounded-lg border border-slate-100 flex items-start gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-blue-500 shrink-0 mt-0.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
            </svg>
            <div>
              <span class="font-medium text-slate-700">Requisitos de seguridad:</span>
              <p class="mt-0.5">La contraseña debe contener al menos 6 caracteres (letras y números recomendados).</p>
            </div>
          </div>

          <div v-if="passwordError" class="text-sm text-red-600 bg-red-50 p-2 rounded-lg">
            {{ passwordError }}
          </div>
          <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
              <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
            </svg>
            Actualizar contraseña
          </button>
        </form>
      </div>

      <!-- Preferencias -->
<!-- Preferencias -->
      <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h2 class="text-lg font-semibold text-slate-800 flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-slate-500">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75" />
          </svg>
          Preferencias
        </h2>
        <div class="mt-4 space-y-3">
          
          <!-- Notificaciones (Deshabilitado / Próximamente) -->
          <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg border border-slate-100 opacity-75">
            <div class="flex items-center gap-3">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-slate-400">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
              </svg>
              <div>
                <span class="text-sm text-slate-700 font-medium">Recibir notificaciones por email</span>
                <span class="block text-[11px] text-amber-600 font-medium">Próximamente disponible</span>
              </div>
            </div>
            <input type="checkbox" disabled class="w-4 h-4 rounded border-slate-300 text-slate-300 cursor-not-allowed">
          </div>

          <!-- Modo Oscuro (ACTIVO Y FUNCIONAL) -->
          <label class="flex items-center justify-between p-3 hover:bg-slate-50 rounded-lg transition-colors cursor-pointer border border-transparent hover:border-slate-100">
            <div class="flex items-center gap-3">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-slate-400">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" />
              </svg>
              <div>
                <span class="text-sm text-slate-700 font-medium">Activar modo oscuro</span>
                <span class="block text-[11px] text-blue-600 font-medium">Cambia la interfaz a tonos oscuros</span>
              </div>
            </div>
            <input type="checkbox" v-model="preferences.darkMode" @change="toggleDarkMode" class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
          </label>

          <!-- Diseño Responsivo (Deshabilitado / Próximamente) -->
          <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg border border-slate-100 opacity-75">
            <div class="flex items-center gap-3">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-slate-400">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
              </svg>
              <div>
                <span class="text-sm text-slate-700 font-medium">Diseño responsivo avanzado</span>
                <span class="block text-[11px] text-amber-600 font-medium">Próximamente disponible</span>
              </div>
            </div>
            <input type="checkbox" disabled class="w-4 h-4 rounded border-slate-300 text-slate-300 cursor-not-allowed">
          </div>

          <div class="mt-4 pt-2">
            <button @click="savePreferences" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors shadow-sm">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
              </svg>
              Guardar preferencias
            </button>
          </div>
        </div>
      </div>
      <!-- Zona de peligro -->
      <div class="bg-white rounded-xl shadow-sm border border-red-200 p-6">
        <h2 class="text-lg font-semibold text-red-600 flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
          </svg>
          Zona de peligro
        </h2>
        <p class="text-sm text-slate-600 mt-2">Eliminar o desactivar tu cuenta es irreversible para este acceso operativo.</p>
        <button @click="deleteAccount" class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700 transition-colors shadow-sm">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.56 0a30.59 30.59 0 00-3.755-.385m-5.858 0a30.588 30.588 0 01-3.755-.385m16.5 0a3.25 3.25 0 00-3.25-3.25h-2.5a3.25 3.25 0 00-3.25 3.25m3.25 0h-6" />
          </svg>
          Desactivar cuenta
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import Swal from 'sweetalert2';
import api from '@/services/api';

const router = useRouter();

const passwordForm = ref({ current: '', new: '', confirm: '' });
const passwordError = ref('');

const preferences = ref({
  notifications: true,
  darkMode: false,
  responsive: false,
});

// Cargar preferencia guardada de modo oscuro al abrir la vista
onMounted(() => {
  const savedPrefs = localStorage.getItem('user_preferences');
  if (savedPrefs) {
    try {
      const parsed = JSON.parse(savedPrefs);
      preferences.value = { ...preferences.value, ...parsed };
      if (preferences.value.darkMode) {
        document.documentElement.classList.add('dark');
      }
    } catch (e) {
      console.error('Error al leer preferencias', e);
    }
  }
});

// Activar o desactivar el modo oscuro al instante
const toggleDarkMode = () => {
  if (preferences.value.darkMode) {
    document.documentElement.classList.add('dark');
  } else {
    document.documentElement.classList.remove('dark');
  }
};

const goBack = () => {
  router.go(-1);
};

const changePassword = async () => {
  passwordError.value = '';
  
  if (passwordForm.value.new !== passwordForm.value.confirm) {
    passwordError.value = 'Las nuevas contraseñas no coinciden';
    return;
  }
  try {
    const res = await api.put('/cambiar-password', {
      current_password: passwordForm.value.current,
      new_password: passwordForm.value.new,
    });

    Swal.fire({
      icon: 'success',
      title: '¡Contraseña actualizada!',
      text: res.data.message || 'Tu contraseña se ha cambiado correctamente',
      confirmButtonColor: '#2563eb'
    });
    
    passwordForm.value = { current: '', new: '', confirm: '' };

  } catch (error) {
    console.error('Error al cambiar contraseña:', error);
    
    if (error.response && error.response.data) {
      passwordError.value = error.response.data.message || 'Error al actualizar la contraseña.';
    } else {
      passwordError.value = 'No se pudo conectar con el servidor.';
    }
  }
};

const savePreferences = () => {
  localStorage.setItem('user_preferences', JSON.stringify(preferences.value));
  
  Swal.fire({
    icon: 'success',
    title: 'Preferencias guardadas',
    text: 'Tus preferencias se han actualizado correctamente',
    confirmButtonColor: '#2563eb',
    timer: 2000
  });
};

const deleteAccount = () => {
  Swal.fire({
    title: '¿Desactivar / Dar de baja tu cuenta?',
    text: 'Tu cuenta pasará a estado inactivo de forma lógica en el sistema. ¿Estás seguro?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#dc2626',
    cancelButtonColor: '#64748b',
    confirmButtonText: 'Sí, dar de baja',
    cancelButtonText: 'Cancelar',
    reverseButtons: true
  }).then(async (result) => {
    if (result.isConfirmed) {
      try {
        Swal.fire({
          icon: 'info',
          title: 'Cuenta desactivada',
          text: 'Tu cuenta ha sido dada de baja correctamente.',
          confirmButtonColor: '#2563eb'
        }).then(() => {
          localStorage.removeItem('token');
          router.push('/login');
        });
      } catch (error) {
        console.error('Error al desactivar cuenta:', error);
        Swal.fire('Error', 'No se pudo procesar la solicitud', 'error');
      }
    }
  });
};
</script>