<template>
  <div class="p-8 bg-slate-50 min-h-screen">
    <div class="max-w-4xl mx-auto">
      
      <!-- Header -->
      <div class="mb-8 flex justify-between items-center">
        <div>
          <h1 class="text-2xl font-bold text-slate-800">Nuevo Ticket</h1>
          <p class="text-slate-500">Registra una nueva solicitud de soporte técnico.</p>
        </div>
        <button @click="$router.back()" class="text-slate-500 hover:text-blue-600 transition font-medium flex items-center gap-2">
          &larr; Volver
        </button>
      </div>

      <form @submit.prevent="guardarTicket" class="space-y-6">
        
        <!-- Segmento 1: Información Principal -->
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
          <h2 class="text-lg font-semibold text-slate-700 mb-4 flex items-center gap-2">
            <span class="w-1 h-6 bg-blue-600 rounded"></span> Datos del Caso
          </h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Usuario / Reportante</label>
              <select v-model="form.usuarioReportanteId" class="w-full p-2.5 bg-slate-50 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                <option value="">Seleccione al usuario...</option>
                <option v-for="user in usuarios" :key="user.id" :value="user.id">
                  {{ user.nombreCompleto }}
                </option>
              </select>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Tipo de Caso</label>
              <select v-model="form.tipoCasoId" class="w-full p-2.5 bg-slate-50 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                <option value="">Seleccione tipo de caso...</option>
                <option v-for="tipo in tiposCaso" :key="tipo.id" :value="tipo.id">
                  {{ tipo.nombre }}
                </option>
              </select>
            </div>
          </div>
        </div>

        <!-- Segmento 2: Clasificación y Detalles -->
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
          <h2 class="text-lg font-semibold text-slate-700 mb-4 flex items-center gap-2">
            <span class="w-1 h-6 bg-blue-600 rounded"></span> Clasificación
          </h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Prioridad mejorada -->
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Prioridad</label>
              <div class="relative flex items-center">
                <div class="absolute left-3 w-3 h-3 rounded-full transition-all duration-300" :class="prioridadColor"></div>
                <select v-model="form.prioridad" class="w-full pl-9 p-2.5 bg-slate-50 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none appearance-none">
                  <option :value="4">Baja</option>
                  <option :value="3">Media</option>
                  <option :value="2">Alta</option>
                  <option :value="1">Urgente</option>
                </select>
              </div>
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Tipo de Cola</label>
              <div class="flex gap-4 pt-2">
                <label class="flex items-center gap-2 cursor-pointer">
                  <input type="radio" v-model="form.tipo_cola" value="soporte" class="text-blue-600 focus:ring-blue-500">
                  <span class="text-sm">Soporte</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                  <input type="radio" v-model="form.tipo_cola" value="general" class="text-blue-600 focus:ring-blue-500">
                  <span class="text-sm">General</span>
                </label>
              </div>
            </div>

            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-slate-700 mb-1">Asunto</label>
              <input v-model="form.asunto" type="text" class="w-full p-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Breve resumen del problema">
            </div>

            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-slate-700 mb-1">Descripción Detallada</label>
              <textarea v-model="form.descripcion" rows="4" class="w-full p-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"></textarea>
            </div>
          </div>
        </div>

        <!-- Botones Acción -->
        <div class="flex justify-end gap-4 pt-4">
          <button type="button" @click="$router.back()" class="px-6 py-2.5 text-slate-600 hover:bg-slate-100 rounded-lg font-medium transition">Cancelar</button>
          <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold shadow-lg shadow-blue-600/20 transition">Crear Ticket</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import Swal from 'sweetalert2';
import api from '@/services/api';
// 1. Estados para los catálogos
const usuarios = ref([]);
const tiposCaso = ref([]);

const form = ref({
  usuarioReportanteId: '',
  tipoCasoId: '',
  prioridad: 3,
  tipo_cola: 'soporte',
  asunto: '',
  descripcion: ''
});

// 2. Función para cargar catálogos desde el backend
const cargarCatalogos = async () => {
  try {
    // Usando tu instancia 'api' que ya tiene el .env configurado
    const [resUsuarios, resTipos] = await Promise.all([
      api.get('/usuarios'),      // Ajusta según tu endpoint real
      api.get('/tipos-casos')   // Ajusta según tu endpoint real
    ]);
    usuarios.value = resUsuarios.data.data || resUsuarios.data;
    tiposCaso.value = resTipos.data.data ||resTipos.data;
    console.log("Usuarios cargados:", usuarios.value);
    console.log("Tipos cargados:", tiposCaso.value);
  } catch (error) {
    console.error("Error cargando catálogos", error);
  }
};
const prioridadColor = computed(() => {
  const map = { 1: 'bg-red-600', 2: 'bg-orange-500', 3: 'bg-yellow-400', 4: 'bg-green-500' };
  return map[form.value.prioridad] || 'bg-slate-400';
});

const guardarTicket = async () => {
  try {
    await api.post('/tickets', form.value);
    Swal.fire({
      icon: 'success',
      title: 'Ticket Creado',
      text: 'El ticket ha sido generado correctamente.',
      confirmButtonColor: '#2563eb'
    });
    router.push('/tickets');
  } catch (error) {
    Swal.fire('Error', 'No se pudo crear el ticket.', 'error');
  }
};

// 3. Ejecutar al montar la vista
onMounted(() => {
  cargarCatalogos();
});
</script>