<script setup>
import { ref } from 'vue';
import { useAuthStore } from '@/stores/auth';
import { useRouter } from 'vue-router';
import Swal from 'sweetalert2';

const authStore = useAuthStore();
const router = useRouter();

const email = ref('');
const password = ref('');

const handleLogin = async () => {
  // Mostramos el cargando de SweetAlert2
  Swal.fire({
    title: 'Iniciando sesión...',
    allowOutsideClick: false,
    didOpen: () => { Swal.showLoading(); }
  });

  try {
    const success = await authStore.login(email.value, password.value);
    
    // Cerramos el cargando
    Swal.close();

    if (success) {
      await Swal.fire({
        icon: 'success',
        title: '¡Bienvenido!',
        text: 'Has ingresado correctamente a Refinansaperú',
        confirmButtonColor: '#2563eb'
      });
      router.push('/');
    } else {
      Swal.fire({
        icon: 'error',
        title: 'Error de acceso',
        text: 'Credenciales incorrectas, intenta nuevamente.',
        confirmButtonColor: '#dc2626'
      });
    }
  } catch (error) {
    Swal.close();
    Swal.fire({
      icon: 'error',
      title: 'Oops...',
      text: 'Hubo un problema al conectar con el servidor.',
      confirmButtonColor: '#dc2626'
    });
  }
};
</script>

<template>
  <div class="min-h-screen flex items-center justify-center bg-slate-50 font-sans">
    <div class="bg-white p-10 rounded-2xl shadow-xl w-full max-w-md border border-slate-100">
      
      <div class="text-center mb-8">
        <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Bienvenido</h2>
        <p class="text-slate-500 mt-2">Accede al panel de Refinansaperú</p>
      </div>
      
      <form @submit.prevent="handleLogin" class="space-y-6">
        <div>
          <label class="block text-sm font-semibold text-slate-600 mb-1">Email corporativo</label>
          <input 
            v-model="email" 
            type="email" 
            placeholder="usuario@refinansaperu.com"
            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
            required 
          />
        </div>
        
        <div>
          <label class="block text-sm font-semibold text-slate-600 mb-1">Contraseña</label>
          <input 
            v-model="password" 
            type="password" 
            placeholder="••••••••"
            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
            required 
          />
        </div>
        
        <button 
          type="submit" 
          class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg shadow-lg shadow-blue-200 transition-all active:scale-95"
        >
          Iniciar Sesión
        </button>
      </form>

      <div class="mt-8 text-center">
        <a href="#" class="text-sm text-blue-600 hover:underline font-medium">¿Olvidaste tu contraseña?</a>
      </div>
    </div>
  </div>
</template>