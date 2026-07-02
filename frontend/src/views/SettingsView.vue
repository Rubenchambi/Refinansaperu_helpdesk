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
          <div v-if="passwordError" class="text-sm text-red-600 bg-red-50 p-2 rounded-lg">
            {{ passwordError }}
          </div>
          <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors shadow-sm">
            Actualizar contraseña
          </button>
        </form>
      </div>

      <!-- Preferencias -->
      <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h2 class="text-lg font-semibold text-slate-800 flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-slate-500">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75" />
          </svg>
          Preferencias
        </h2>
        <div class="mt-4 space-y-3">
          <label class="flex items-center gap-3 p-3 hover:bg-slate-50 rounded-lg transition-colors cursor-pointer">
            <input type="checkbox" v-model="preferences.notifications" class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
            <span class="text-sm text-slate-700">Recibir notificaciones por email</span>
          </label>
          <label class="flex items-center gap-3 p-3 hover:bg-slate-50 rounded-lg transition-colors cursor-pointer">
            <input type="checkbox" v-model="preferences.darkMode" class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
            <span class="text-sm text-slate-700">Activar modo oscuro</span>
          </label>
          <label class="flex items-center gap-3 p-3 hover:bg-slate-50 rounded-lg transition-colors cursor-pointer">
            <input type="checkbox" v-model="preferences.responsive" class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
            <span class="text-sm text-slate-700">Diseño responsivo (experimental)</span>
          </label>
          <div class="mt-4">
            <button @click="savePreferences" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors shadow-sm">
              Guardar preferencias
            </button>
          </div>
        </div>
      </div>

      <!-- Zona de peligro (opcional) -->
      <div class="bg-white rounded-xl shadow-sm border border-red-200 p-6">
        <h2 class="text-lg font-semibold text-red-600 flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
          </svg>
          Zona de peligro
        </h2>
        <p class="text-sm text-slate-600 mt-2">Eliminar tu cuenta es irreversible. Esta acción eliminará todos tus datos.</p>
        <button @click="deleteAccount" class="mt-4 px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700 transition-colors shadow-sm">
          Eliminar cuenta
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import Swal from 'sweetalert2';

const router = useRouter();
const authStore = useAuthStore();

const passwordForm = ref({ current: '', new: '', confirm: '' });
const passwordError = ref('');
const preferences = ref({
  notifications: true,
  darkMode: false,
  responsive: false,
});

const goBack = () => {
  router.go(-1);
};

const changePassword = () => {
  passwordError.value = '';
  if (passwordForm.value.new !== passwordForm.value.confirm) {
    passwordError.value = 'Las contraseñas no coinciden';
    return;
  }
  if (passwordForm.value.new.length < 6) {
    passwordError.value = 'La contraseña debe tener al menos 6 caracteres';
    return;
  }
  // Aquí llamas a tu API para cambiar la contraseña
  console.log('Cambiar contraseña:', passwordForm.value);
  Swal.fire({
    icon: 'success',
    title: 'Contraseña actualizada',
    text: 'Tu contraseña se ha cambiado correctamente',
    confirmButtonColor: '#2563eb'
  });
  passwordForm.value = { current: '', new: '', confirm: '' };
};

const savePreferences = () => {
  console.log('Preferencias guardadas:', preferences.value);
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
    title: '¿Eliminar cuenta?',
    text: 'Esta acción es irreversible. ¿Estás seguro?',
    icon: 'error',
    showCancelButton: true,
    confirmButtonColor: '#dc2626',
    cancelButtonColor: '#64748b',
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar',
    reverseButtons: true
  }).then((result) => {
    if (result.isConfirmed) {
      // Lógica para eliminar cuenta
      console.log('Eliminar cuenta');
      Swal.fire({
        icon: 'info',
        title: 'Cuenta eliminada',
        text: 'Tu cuenta ha sido eliminada. Serás redirigido.',
        confirmButtonColor: '#2563eb'
      }).then(() => {
        authStore.logout();
        router.push('/login');
      });
    }
  });
};
</script>