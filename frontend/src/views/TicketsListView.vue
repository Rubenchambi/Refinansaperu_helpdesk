<template>
  <div class="p-8">
<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6">
  <!-- Izquierda: Título del Módulo -->
  <div class="flex-none">
    <h1 class="text-2xl font-bold text-slate-800">Tickets</h1>
    <p class="text-slate-500">Gestión de tickets registrados en el sistema.</p>
  </div>

  <!-- Centro: Buscador Alargado y Grande -->
  <div class="flex-1 w-full md:max-w-lg mx-auto">
    <div class="relative w-full">
      <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400">
        <SearchIcon class="w-5 h-5" />
      </div>
      <input 
        type="text"
        v-model="filters.busqueda"
        @keyup.enter="cargarTickets(1)" 
        placeholder="Buscar ticket por número, asunto o cliente..." 
        class="w-full pl-12 pr-4 py-2.5 text-base bg-white border border-slate-200 rounded-xl text-slate-700 placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all shadow-sm"
      />
    </div>
  </div>

  <div class="flex-none text-right">
    <router-link
      to="/tickets/crear"
      class="group inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-bold tracking-wide transition-all duration-200 hover:shadow-lg active:scale-95 whitespace-nowrap"
    >
      <PlusIcon class="w-5 h-5 transition-transform duration-200 group-hover:scale-110" />
      Nuevo Ticket
    </router-link>
  </div>
</div>

    <div class="bg-white shadow-sm border border-slate-200 rounded-lg overflow-hidden">
      <table class="w-full text-left">
        <thead class="bg-blue-500 border-b border-slate-200 text-white uppercase text-xs font-bold">
          <tr>
            <th class="p-4">Código</th>
            <th class="p-4">Asunto</th>
            <th class="p-4">Prioridad</th>
            <th class="p-4">Cola</th>
            <th class="p-4">Estado de Ticket</th>
            <th class="p-4">Tecnico Asignado</th>
            <th class="p-4 text-center">Acciones</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          <tr v-for="ticket in tickets" :key="ticket.id" class="hover:bg-blue-50/80 transition-colors duration-200">
            <td class="p-4 font-mono text-sm text-slate-500 font-bold">{{ ticket.codigo_ticket }}</td>
            
            <td class="p-4">
              <p class="font-semibold text-slate-800">{{ ticket.asunto }}</p>
              <p class="text-xs text-slate-400">Creado el {{ ticket.fecha_creacion }}</p>
            </td>
            
            <td class="p-4">
              <span class="inline-flex items-center gap-2 px-4 py-1 rounded-full bg-slate-100 border border-slate-200 text-sm font-medium">
                <span class="w-2.5 h-2.5 rounded-full animate-ping" :class="getPrioridadColor(ticket.prioridad)"></span>
                <span class="capitalize text-slate-700">{{ getPrioridadNombre(ticket.prioridad) }}</span>
              </span>
            </td>
            
            <td class="p-4 text-slate-600 text-sm">
              <div class="flex items-center gap-2">
                <!-- Icono de Soporte (Computadora/Herramienta) -->
                <svg v-if="ticket.tipo_cola === 'soporte'" class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>

                <!-- Icono de General (Carpeta/Documento/Globe) -->
                <svg v-else class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                </svg>

                <span class="capitalize">{{ ticket.tipo_cola }}</span>
              </div>
            </td>
            
            <td class="p-4">
              <span :class="['inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold border shadow-xs', getEstadoBadgeStyle(ticket.estadoTicket).clase]">
                <svg v-if="ticket.estadoTicket === 'Pendiente'" class="w-3.5 h-3.5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <svg v-else-if="ticket.estadoTicket === 'En Proceso'" class="w-3.5 h-3.5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                <svg v-else-if="ticket.estadoTicket === 'Resuelto'" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>

                <svg v-else-if="ticket.estadoTicket === 'Finalizado'" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span>{{ ticket.estadoTicket }}</span>
              </span>
            </td>

            <td class="p-4">
              <span :class="['inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold border shadow-xs', getTecnicoBadgeStyle(ticket).clase]">
                <svg v-if="!ticket.tecnicoAsignadoId" class="w-3.5 h-3.5 animate-ping" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <svg v-else class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>

                <span>{{ getTecnicoBadgeStyle(ticket).texto }}</span>
              </span>
            </td>

            <td class="p-4 text-center">
              <div class="flex justify-center gap-2">
                <button 
                  @click="$router.push(`/tickets/${ticket.id}`)"
                  class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-600 hover:text-white transition-all shadow-sm" 
                  title="Ver Detalle"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                  </svg>
                </button>
                <button @click="irAEditar(ticket)" :disabled="ticket.estadoTicket === 'Finalizado'" class="p-2 bg-yellow-50 text-yellow-600 rounded-lg hover:bg-yellow-500 hover:text-white transition-all shadow-sm" title="Editar">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </button>
                <button @click="confirmarEliminar(ticket.id)" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-all shadow-sm" title="Eliminar">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Paginación Dinámica -->
      <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-between bg-slate-50">
        <div class="text-sm text-slate-600">
          Mostrando página <strong>{{ meta.current_page }}</strong> de <strong>{{ meta.last_page }}</strong>
        </div>
        <nav class="flex items-center gap-1">
          <button @click="cargarTickets(meta.current_page - 1)" :disabled="!meta.current_page || meta.current_page === 1" class="flex items-center px-3 py-1.5 border border-slate-300 rounded-md bg-blue-500 text-white hover:bg-blue-400 disabled:opacity-50 transition-all">
            Anterior
          </button>
          <button @click="cargarTickets(meta.current_page + 1)" :disabled="!meta.current_page || meta.current_page === meta.last_page" class="flex items-center px-3 py-1.5 border border-slate-300 rounded-md bg-blue-500 text-white hover:bg-blue-400 disabled:opacity-50 transition-all">
            Siguiente
          </button>
        </nav>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted,watch } from 'vue';
import api from '@/services/api';
import Swal from 'sweetalert2';
import { useRouter } from 'vue-router';
import { Search as SearchIcon, PlusCircle as PlusIcon } from 'lucide-vue-next'
const tickets = ref([]);
const meta = ref({});
const router = useRouter();
const filters = ref({busqueda: '',});


const irAEditar = (ticket) => router.push({ name: 'tickets.form', params: { id: ticket.id } });

const cargarTickets = async (page = 1) => {
  try {
  const { data } = await api.get(`/tickets?page=${page}`,{  params: filters.value});
  tickets.value = data.data;
  meta.value = data.meta;
  } catch (error) {
    Swal.fire('Error', 'No se pudieron cargar los tickets', 'error');
  }
};

const confirmarEliminar = (id) => {
  Swal.fire({
    title: '¿Estás seguro?',
    text: "Esta acción no se puede revertir.",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Sí, eliminar'
  }).then(async (result) => {
    if (result.isConfirmed) {
      await api.delete(`/tickets/${id}`);
      cargarTickets();
      Swal.fire('¡Eliminado!', 'El ticket ha sido removido.', 'success');
    }
  });
};

const getPrioridadColor = (p) => {
  const colors = { 1: 'bg-red-600', 2: 'bg-orange-500', 3: 'bg-yellow-400', 4: 'bg-green-500' };
  return colors[p] || 'bg-slate-400';
};

const getPrioridadNombre = (p) => {
  const names = { 1: 'Urgente', 2: 'Alta', 3: 'Media', 4: 'Baja' };
  return names[p] || 'N/A';
};

const getEstadoBadgeStyle = (estado) => {
  switch (estado) {
    case 'Pendiente':
      return { clase: 'bg-amber-50 text-amber-700 border-amber-200' };
    case 'En Proceso':
      return { clase: 'bg-blue-50 text-blue-700 border-blue-200' };
    case 'Resuelto':
      return { clase: 'bg-indigo-50 text-indigo-700 border-indigo-200' }; 
    case 'Finalizado':
      return { clase: 'bg-emerald-50 text-emerald-700 border-emerald-200' };
    default:
      return { clase: 'bg-slate-100 text-slate-700 border-slate-200' };
  }
};

const getTecnicoBadgeStyle = (ticket) => {
  if (!ticket.tecnicoAsignadoId) {
    return { 
      texto: 'Por Asignar', 
      clase: 'bg-amber-50 text-amber-700 border-amber-200' 
    };
  }

  return { 
    texto: ticket.nombre_tecnico || `Técnico #${ticket.tecnicoAsignadoId}`, 
    clase: 'bg-indigo-50 text-indigo-700 border-indigo-200' 
  };
};

watch(() => filters.value.busqueda, () => {
  cargarTickets(1);
});

onMounted(() => cargarTickets());
</script>