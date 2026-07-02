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
            <th class="p-4">ID</th>
            <th class="p-4">Asunto</th>
            <th class="p-4">Prioridad</th>
            <th class="p-4">Departamento</th>
            <th class="p-4 text-center">Acciones</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
        <tr v-for="ticket in tickets" :key="ticket.id" class="hover:bg-blue-50/50 transition-colors duration-200">
            
            <td class="p-4 font-mono text-sm text-slate-500 font-bold">#{{ ticket.id }}</td>
            
            <td class="p-4">
            <p class="font-semibold text-slate-800">{{ ticket.asunto }}</p>
            <p class="text-xs text-slate-400">Creado hace 2 horas</p>
            </td>
            
            <td class="p-4">
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-100 border border-slate-200 text-sm font-medium">
                <span class="w-2.5 h-2.5 rounded-full" :class="getPrioridadColor(ticket.prioridad)"></span>
                <span class="capitalize text-slate-700">{{ ticket.prioridad }}</span>
            </span>
            </td>
            
            <td class="p-4 text-slate-600 text-sm">
            <div class="flex items-center gap-1">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                {{ ticket.departamento }}
            </div>
            </td>
            
            <td class="p-4 text-center">
            <div class="flex justify-center gap-2">
                <button class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-600 hover:text-white transition-all shadow-sm" title="Visualizar">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </button>
                <button class="p-2 bg-yellow-50 text-yellow-600 rounded-lg hover:bg-yellow-500 hover:text-white transition-all shadow-sm" title="Editar">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </button>
                <button class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-all shadow-sm" title="Eliminar">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            </div>
            </td>
        </tr>
        </tbody>
      </table>

        <!-- Paginación Profesional -->
        <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-between bg-slate-50">
            <!-- Información -->
            <div class="text-sm text-slate-600">
                Mostrando <span class="font-bold text-slate-800">1</span> a <span class="font-bold text-slate-800">10</span> de <span class="font-bold text-slate-800">50</span> resultados
            </div>

            <!-- Controles -->
            <nav class="flex items-center gap-1">
                <!-- Botón Anterior -->
                <button class="flex items-center px-3 py-1.5 border border-slate-300 rounded-md bg-white hover:bg-slate-100 text-slate-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Anterior
                </button>

                <!-- Números (Ejemplo) -->
                <button class="px-3 py-1.5 border border-blue-600 bg-blue-600 text-white rounded-md font-medium">1</button>
                <button class="px-3 py-1.5 border border-slate-300 bg-white hover:bg-slate-100 text-slate-500 rounded-md transition-all">2</button>
                <button class="px-3 py-1.5 border border-slate-300 bg-white hover:bg-slate-100 text-slate-500 rounded-md transition-all">3</button>

                <!-- Botón Siguiente -->
                <button class="flex items-center px-3 py-1.5 border border-slate-300 rounded-md bg-white hover:bg-slate-100 text-slate-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all">
                Siguiente
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </nav>

        </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const tickets = ref([
  { id: 101, asunto: 'Falla en acceso al servidor', prioridad: 'urgente', departamento: 'Soporte TI' },
  { id: 102, asunto: 'Error en reporte mensual', prioridad: 'alta', departamento: 'Finanzas' },
  { id: 103, asunto: 'Solicitud de nuevo usuario', prioridad: 'media', departamento: 'RRHH' },
  { id: 104, asunto: 'Consulta general', prioridad: 'baja', departamento: 'General' }
]);

// Lógica de colores exacta a la de tu formulario
const getPrioridadColor = (prioridad) => {
  switch (prioridad) {
    case 'urgente': return 'bg-red-600';
    case 'alta': return 'bg-orange-500';
    case 'media': return 'bg-yellow-400';
    case 'baja': return 'bg-green-500';
    default: return 'bg-slate-400';
  }
};
</script>