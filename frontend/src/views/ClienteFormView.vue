<template>
  <div class="p-8 bg-slate-50 min-h-screen">
    <div class="max-w-5xl mx-auto">
      
      <div class="flex justify-between items-end mb-8">
        <div>
          <h1 class="text-3xl font-bold text-slate-800 flex items-center gap-3">
            <span class="bg-blue-600 p-2 rounded-xl text-white">
              <UserIcon class="w-7 h-7" />
            </span>
            {{ form.id ? 'Editar Cliente' : 'Nuevo Cliente' }}
          </h1>
          <p class="text-slate-500 mt-2">Gestiona la información completa del cliente o asesor.</p>
        </div>
        <button @click="volver" class="flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 rounded-lg shadow-sm hover:border-blue-400 transition">
          <ArrowLeftIcon class="w-4 h-4" /> Volver
        </button>
      </div>

      <form @submit.prevent="guardarCliente" class="space-y-6">
        
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
          <h2 class="text-lg font-semibold text-slate-700 mb-4 flex items-center gap-2">
            <IdentificationIcon class="w-5 h-5 text-blue-600" /> Datos de Contacto
          </h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-slate-600 mb-1">Nombre Completo / Contacto *</label>
              <input v-model="form.contacto" type="text" class="w-full p-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition" required>
            </div>
            <div>
              <label class="block text-sm font-medium text-slate-600 mb-1">Correo Electrónico *</label>
              <input v-model="form.email" type="email" class="w-full p-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition" required>
            </div>
            <div>
              <label class="block text-sm font-medium text-slate-600 mb-1">Teléfono *</label>
              <input v-model="form.telefono" type="text" class="w-full p-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition">
            </div>
          </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
          <h2 class="text-lg font-semibold text-slate-700 mb-4 flex items-center gap-2">
            <BuildingOfficeIcon class="w-5 h-5 text-blue-600" /> Información Corporativa
          </h2>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
              <label class="block text-sm font-medium text-slate-600 mb-1">Empresa</label>
              <input v-model="form.razonSocial" type="text" class="w-full p-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition">
            </div>
            <div>
              <label class="block text-sm font-medium text-slate-600 mb-1">DNI / RUC</label>
              <input v-model="form.ruc" type="text" class="w-full p-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition">
            </div>
            <div>
              <label class="block text-sm font-medium text-slate-600 mb-1">Cargo *</label>
              <select v-model="form.cargo" class="w-full p-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition" required>
                <option value="" disabled>Seleccione...</option>
                <option value="Gestor Call">Gestor Call</option>
                <option value="Gestor Campo">Gestor Campo</option>
                <option value="Supervisor">Supervisor</option>
                <option value="Coordinador">Coordinador</option>
                <option value="Administrativo">Administrativo</option>
                <option value="Gerencia">Gerencia</option>
              </select>
            </div>
          </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
          <h2 class="text-lg font-semibold text-slate-700 mb-4 flex items-center gap-2">
            <MapPinIcon class="w-5 h-5 text-blue-600" /> Ubicación y Gestión
          </h2>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
              <label class="block text-sm font-medium text-slate-600 mb-1">Distrito</label>
              <input v-model="form.distrito" type="text" class="w-full p-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition">
            </div>
            <div>
              <label class="block text-sm font-medium text-slate-600 mb-1">Ciudad</label>
              <input v-model="form.ciudad" type="text" class="w-full p-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition">
            </div>
            <div>
              <label class="block text-sm font-medium text-slate-600 mb-1">Cartera Asignada</label>
              <input v-model="form.cartera_asignada" type="text" class="w-full p-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition">
            </div>
            <div class="md:col-span-3">
              <label class="block text-sm font-medium text-slate-600 mb-1">Dirección Completa</label>
              <input v-model="form.direccion" type="text" class="w-full p-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition">
            </div>
          </div>
        </div>

        <div class="flex justify-end gap-4 pt-4">
          <button type="button" @click="volver" class="px-8 py-3 text-slate-600 font-semibold hover:bg-slate-200 rounded-xl transition">Cancelar</button>
          <button type="submit" class="px-8 py-3 bg-blue-600 text-white font-semibold rounded-xl shadow-lg shadow-blue-200 hover:bg-blue-700 transition flex items-center gap-2">
            <CheckIcon class="w-5 h-5" /> {{ form.id ? 'Actualizar Cambios' : 'Guardar Cliente' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>
<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';
import { useRoute, useRouter } from 'vue-router';
import { UserIcon, ArrowLeftIcon, IdentificationIcon, MapPinIcon, CheckIcon } from '@heroicons/vue/24/outline';

const route = useRoute();
const router = useRouter();

const form = ref({
  contacto: '', email: '', telefono: '', razonSocial: '', 
  distrito: '', ciudad: '', direccion: '', ruc: '', cargo: '', cartera_asignada: ''
});

onMounted(async () => {
  const id = route.params.id;
  if (id) {
    try {
      const response = await axios.get(`http://127.0.0.1:8000/api/clientes/${id}`);
      form.value = response.data;
    } catch (error) {
      Swal.fire('Error', 'No se pudieron cargar los datos del cliente.', 'error');
    }
  }
});

const volver = () => {
  router.push('/clientes');
};

const guardarCliente = async () => {
  try {
    const esEdicion = !!form.value.id;
    const url = esEdicion 
      ? `http://127.0.0.1:8000/api/clientes/${form.value.id}` 
      : 'http://127.0.0.1:8000/api/clientes';
    
    // Si es edición usamos PUT, si es nuevo usamos POST
    await axios[esEdicion ? 'put' : 'post'](url, form.value);

    await Swal.fire({
      icon: 'success',
      title: '¡Éxito!',
      text: esEdicion ? 'Cliente actualizado correctamente.' : 'Cliente registrado correctamente.',
      confirmButtonColor: '#2563eb'
    });

    router.push('/clientes');
  } catch (error) {
    Swal.fire('Error', 'No se pudo procesar la solicitud.', 'error');
  }
};
</script>