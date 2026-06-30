<template>
  <div class="p-8">
    <div class="max-w-4xl mx-auto">
      <h1 class="text-2xl font-bold text-slate-800 mb-6">Crear Nuevo Ticket</h1>

      <form @submit.prevent="guardarTicket" class="bg-white p-8 rounded-lg shadow-sm border border-slate-200">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Cliente</label>
            <select v-model="form.cliente_id" class="w-full p-2 border border-slate-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
              <option value="">Seleccione cliente...</option>
              </select>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Tipo de Caso</label>
            <select v-model="form.tipo_caso_id" class="w-full p-2 border border-slate-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
              <option value="">Seleccione tipo...</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Prioridad</label>
            <div class="relative">
              <select v-model="form.prioridad" class="w-full p-2 border border-slate-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-500 pl-10">
                <option value="baja">Baja</option>
                <option value="media">Media</option>
                <option value="alta">Alta</option>
                <option value="urgente">Urgente</option>
              </select>
              <div 
                class="absolute left-3 top-3 w-5 h-5 rounded-full transition-colors duration-300"
                :class="prioridadColor"
              ></div>
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Departamento</label>
            <input v-model="form.departamento" type="text" class="w-full p-2 border border-slate-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-500" placeholder="Ej: Soporte Técnico">
          </div>

          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-slate-700 mb-1">Asunto</label>
            <input v-model="form.asunto" type="text" class="w-full p-2 border border-slate-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-500" placeholder="Título del problema">
          </div>

          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-slate-700 mb-1">Descripción</label>
            <textarea v-model="form.descripcion" rows="4" class="w-full p-2 border border-slate-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-500"></textarea>
          </div>
        </div>

        <div class="mt-8 flex justify-end gap-4">
          <button type="button" @click="$router.push('/')" class="px-6 py-2 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300 transition">Cancelar</button>
          <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Crear Ticket</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import Swal from 'sweetalert2';

const form = ref({
  cliente_id: '',
  tipo_caso_id: '',
  prioridad: 'baja',
  departamento: '',
  asunto: '',
  descripcion: ''
});

// Lógica de colores del semáforo
const prioridadColor = computed(() => {
  switch (form.value.prioridad) {
    case 'urgente': return 'bg-red-600';
    case 'alta': return 'bg-orange-500';
    case 'media': return 'bg-yellow-400';
    case 'baja': return 'bg-green-500';
    default: return 'bg-slate-400';
  }
});

const guardarTicket = () => {
  // Aquí irá tu llamada a axios.post
  Swal.fire({
    title: '¡Listo!',
    text: 'Ticket registrado exitosamente.',
    icon: 'success',
    confirmButtonColor: '#2563eb'
  });
};
</script>