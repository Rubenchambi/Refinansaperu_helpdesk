<script setup>
import { ref } from 'vue';
import { useAuthStore } from '@/stores/auth';
import { useRouter } from 'vue-router';
import Swal from 'sweetalert2';

const authStore = useAuthStore();
const router = useRouter();

const loading = ref(false);
const showPassword = ref(false); // Estado para mostrar/ocultar
const email = ref('');
const password = ref('');

const handleLogin = async () => {
  loading.value = true;
  Swal.fire({
    title: 'Iniciando sesión...',
    allowOutsideClick: false,
    didOpen: () => { Swal.showLoading(); }
  });

  try {
    const result = await authStore.login(email.value, password.value);
    Swal.close();

    if (result.success) {
      router.push('/');
    } else {
      // Mensaje dinámico basado en lo que devuelve el backend
      Swal.fire({
        icon: 'error',
        title: result.message.includes('bloqueado') ? 'Cuenta Bloqueada' : 'Error de acceso',
        html: result.message + (result.intentos ? `<br><small class="text-slate-500">Intento ${result.intentos} de 3</small>` : ''),
        confirmButtonColor: result.message.includes('bloqueado') ? '#dc2626' : '#2563eb',
        footer: result.message.includes('bloqueado') 
        ? `<a href="https://wa.me/51919766968?text=Hola,%20necesito%20ayuda%20para%20desbloquear%20mi%20cuenta%20en%20Refinansaperú" target="_blank" class="flex items-center justify-center gap-2 text-green-600 font-bold hover:underline">
          Contactar al Administrador Cel: 919766968  
          <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.591 5.52 0 10.016-4.496 10.016-10.016 0-5.52-4.496-10.016-10.016-10.016-5.52 0-10.016 4.496-10.016 10.016 0 2.106.632 4.09 1.716 5.811l-.856 3.125 3.208-.841zm7.425-6.526c-.161-.08-.946-.466-1.092-.519-.147-.054-.255-.081-.362.081-.107.161-.413.519-.506.626-.094.107-.188.12-.35.042-.162-.08-.682-.251-1.299-.802-.48-.428-.804-.958-.898-1.121-.094-.162-.01-.25.07-.349.074-.087.162-.227.243-.341.081-.115.107-.197.162-.329.054-.135.027-.251-.013-.351-.041-.102-.362-.873-.496-1.196-.131-.311-.266-.268-.362-.273-.093-.005-.201-.006-.309-.006-.108 0-.284.041-.433.216-.149.175-.568.555-.568 1.353s.581 1.569.662 1.677c.081.108 1.144 1.748 2.772 2.451.388.167.691.267.928.342.39.124.746.107 1.026.065.313-.047.946-.387 1.079-.761.134-.374.134-.694.094-.761-.041-.067-.148-.108-.309-.188z"/></svg>
          </a>` 
        : ''
      });
    }
  } catch (error) {
    Swal.close();
    Swal.fire({ icon: 'error', title: 'Oops...', text: 'Error de servidor.', confirmButtonColor: '#dc2626' });
  } finally {
    loading.value = false;
  }
};
</script>

<template>
  <div class="min-h-screen flex items-center justify-center bg-slate-50 font-sans">
    <div class="bg-white p-10 rounded-2xl shadow-xl w-full max-w-md border border-slate-100">
      <div class="text-center mb-8">
        <h2 class="text-3xl font-extrabold text-slate-800">Bienvenido</h2>
        <p class="text-slate-500 mt-2">Accede al panel de Refinansaperú</p>
      </div>

      <form @submit.prevent="handleLogin" class="space-y-6">
        <div>
          <label class="block text-sm font-semibold text-slate-600 mb-1">Email</label>
          <input v-model="email" type="email"  placeholder="usuario@refinansa.pe" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" required />
        </div>

        <div class="relative">
          <label class="block text-sm font-semibold text-slate-600 mb-1">Contraseña</label>
          <input 
            v-model="password" 
            :type="showPassword ? 'text' : 'password'" 
            placeholder="••••••••"
            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" 
            required 
          />
          <button 
            type="button" 
            @click="showPassword = !showPassword"
            class="absolute right-3 top-10 text-slate-400 hover:text-blue-600"
          >
            {{ showPassword ? '🙈' : '👁️' }}
          </button>
        </div>

        <button 
          :disabled="loading"
          type="submit" 
          class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition-all disabled:opacity-50"
        >
          {{ loading ? 'Validando...' : 'Iniciar Sesión' }}
        </button>
      </form>
    </div>
  </div>
</template>