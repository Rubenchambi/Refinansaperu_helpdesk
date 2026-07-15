<template>
  <div class="p-8">
    <div class="flex justify-between items-center mb-6">
      <div>
        <h1 class="text-2xl font-bold text-slate-800">Tickets</h1>
        <p class="text-slate-500">Gestión de tickets registrados en el sistema.</p>
      </div>
      <router-link
        to="/tickets/crear"
        class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg font-semibold transition shadow-md"
      >
        + Nuevo Ticket
      </router-link>
    </div>

    <div class="bg-white shadow-sm border border-slate-200 rounded-lg overflow-hidden">
      <table class="w-full text-left">
        <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 uppercase text-xs font-bold">
          <tr>
            <th class="p-4">Código</th>
            <th class="p-4">Asunto</th>
            <th class="p-4">Prioridad</th>
            <th class="p-4">Cola</th>
            <th class="p-4 text-center">Acciones</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          <tr v-for="ticket in tickets" :key="ticket.id" class="hover:bg-blue-50/50 transition-colors duration-200">
            <td class="p-4 font-mono text-sm text-slate-500 font-bold">{{ ticket.codigo_ticket }}</td>
            
            <td class="p-4">
              <p class="font-semibold text-slate-800">{{ ticket.asunto }}</p>
              <p class="text-xs text-slate-400">Creado el {{ ticket.fecha_creacion }}</p>
            </td>
            
            <td class="p-4">
              <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-100 border border-slate-200 text-sm font-medium">
                <span class="w-2.5 h-2.5 rounded-full" :class="getPrioridadColor(ticket.prioridad)"></span>
                <span class="capitalize text-slate-700">{{ getPrioridadNombre(ticket.prioridad) }}</span>
              </span>
            </td>
            
            <td class="p-4 text-slate-600 text-sm">
              <div class="flex items-center gap-1">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                {{ ticket.tipo_cola }}
              </div>
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
                <button class="p-2 bg-yellow-50 text-yellow-600 rounded-lg hover:bg-yellow-500 hover:text-white transition-all shadow-sm" title="Editar">
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
          <button @click="cargarTickets(meta.current_page - 1)" :disabled="!meta.current_page || meta.current_page === 1" class="flex items-center px-3 py-1.5 border border-slate-300 rounded-md bg-white hover:bg-slate-100 disabled:opacity-50 transition-all">
            Anterior
          </button>
          <button @click="cargarTickets(meta.current_page + 1)" :disabled="!meta.current_page || meta.current_page === meta.last_page" class="flex items-center px-3 py-1.5 border border-slate-300 rounded-md bg-white hover:bg-slate-100 disabled:opacity-50 transition-all">
            Siguiente
          </button>
        </nav>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '@/services/api';
import Swal from 'sweetalert2';

const tickets = ref([]);
const meta = ref({});

const cargarTickets = async (page = 1) => {
  const { data } = await api.get(`/tickets?page=${page}`);
  tickets.value = data.data;
  meta.value = data.meta;
  /*tickets.value = [
        { id: 99, codigo_ticket: '20260713-000001-1', asunto: 'Ticket de Prueba', estado_ticket: 'pendiente', prioridad: 1, tipo_cola: 'soporte' }
    ];*/
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

onMounted(() => cargarTickets());
</script>