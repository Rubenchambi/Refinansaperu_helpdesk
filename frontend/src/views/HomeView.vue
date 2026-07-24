<template>
  <div class="p-6 bg-slate-50 min-h-screen">
    
    <!-- HEADER -->
    <div class="mb-2">
      <h1 class="text-2xl font-bold text-slate-800">Dashboard</h1>
    </div>

    <div class="flex justify-between items-center mb-6">
      <div>
        <p class="text-slate-500">Resumen general del sistema de tickets</p>
      </div>
      
      <router-link 
        to="/tickets/crear" 
        class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded transition-all duration-300 shadow-sm hover:shadow-md flex items-center gap-2"
      >
        <i data-lucide="plus" class="w-4 h-4"></i>
        Nuevo Ticket
      </router-link>
    </div>

    <!-- TARJETAS DE ESTADÍSTICAS (4 ESTADOS + TOTAL) -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
      
      <!-- Total -->
      <div class="bg-purple-50 rounded-xl shadow-sm border border-purple-100 overflow-hidden hover:shadow-md transition-all duration-300">
        <div class="flex h-full">
          <div class="w-1.5 bg-purple-500"></div>
          <div class="flex-1 p-4">
            <div class="flex items-center justify-between mb-2">
              <p class="text-[9px] font-bold tracking-[0.15em] text-purple-400 uppercase">Total</p>
              <i data-lucide="layers" class="w-4 h-4 text-purple-400"></i>
            </div>
            <p class="text-3xl font-extrabold text-purple-600 tracking-tight">{{ stats.total }}</p>
            <p class="text-[10px] text-purple-400 mt-2">Registrados</p>
          </div>
        </div>
      </div>

      <!-- Pendiente -->
      <div class="bg-yellow-50 rounded-xl shadow-sm border border-yellow-100 overflow-hidden hover:shadow-md transition-all duration-300">
        <div class="flex h-full">
          <div class="w-1.5 bg-yellow-500"></div>
          <div class="flex-1 p-4">
            <div class="flex items-center justify-between mb-2">
              <p class="text-[9px] font-bold tracking-[0.15em] text-yellow-400 uppercase">Pendientes</p>
              <i data-lucide="clock" class="w-4 h-4 text-yellow-400"></i>
            </div>
            <p class="text-3xl font-extrabold text-yellow-500 tracking-tight">{{ stats.pendientes }}</p>
            <p class="text-[10px] text-yellow-400 mt-2">Sin iniciar</p>
          </div>
        </div>
      </div>

      <!-- En Proceso -->
      <div class="bg-blue-50 rounded-xl shadow-sm border border-blue-100 overflow-hidden hover:shadow-md transition-all duration-300">
        <div class="flex h-full">
          <div class="w-1.5 bg-blue-500"></div>
          <div class="flex-1 p-4">
            <div class="flex items-center justify-between mb-2">
              <p class="text-[9px] font-bold tracking-[0.15em] text-blue-400 uppercase">En Proceso</p>
              <i data-lucide="loader" class="w-4 h-4 text-blue-400"></i>
            </div>
            <p class="text-3xl font-extrabold text-blue-600 tracking-tight">{{ stats.en_proceso }}</p>
            <p class="text-[10px] text-blue-400 mt-2">Atendiendo</p>
          </div>
        </div>
      </div>

      <!-- Resuelto (Esperando OK) -->
      <div class="bg-indigo-50 rounded-xl shadow-sm border border-indigo-100 overflow-hidden hover:shadow-md transition-all duration-300">
        <div class="flex h-full">
          <div class="w-1.5 bg-indigo-500"></div>
          <div class="flex-1 p-4">
            <div class="flex items-center justify-between mb-2">
              <p class="text-[9px] font-bold tracking-[0.15em] text-indigo-400 uppercase">Resueltos</p>
              <i data-lucide="help-circle" class="w-4 h-4 text-indigo-400"></i>
            </div>
            <p class="text-3xl font-extrabold text-indigo-600 tracking-tight">{{ stats.resuelto }}</p>
            <p class="text-[10px] text-indigo-400 mt-2">Esperando OK</p>
          </div>
        </div>
      </div>

      <!-- Finalizado -->
      <div class="bg-green-50 rounded-xl shadow-sm border border-green-100 overflow-hidden hover:shadow-md transition-all duration-300">
        <div class="flex h-full">
          <div class="w-1.5 bg-green-500"></div>
          <div class="flex-1 p-4">
            <div class="flex items-center justify-between mb-2">
              <p class="text-[9px] font-bold tracking-[0.15em] text-green-400 uppercase">Finalizados</p>
              <i data-lucide="check-circle-2" class="w-4 h-4 text-green-400"></i>
            </div>
            <p class="text-3xl font-extrabold text-green-500 tracking-tight">{{ stats.finalizados }}</p>
            <p class="text-[10px] text-green-400 mt-2">Casos cerrados</p>
          </div>
        </div>
      </div>

    </div>

    <!-- GRID PRINCIPAL: GRÁFICO + TABLA -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
      
      <!-- GRÁFICO DONUT CON 4 ESTADOS -->
      <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
          <i data-lucide="pie-chart" class="w-5 h-5 text-slate-500"></i>
          Distribución de Tickets
        </h3>
        <div class="flex items-center justify-center">
          <div class="relative w-48 h-48">
            <svg viewBox="0 0 100 100" class="w-full h-full transform -rotate-90">
              <circle cx="50" cy="50" r="40" fill="none" stroke="#f1f5f9" stroke-width="12" />
              <!-- Pendientes -->
              <circle cx="50" cy="50" r="40" fill="none" stroke="#eab308" stroke-width="12" 
                :stroke-dasharray="dashPendientes" stroke-dashoffset="0" stroke-linecap="round" />
              <!-- En Proceso -->
              <circle cx="50" cy="50" r="40" fill="none" stroke="#3b82f6" stroke-width="12" 
                :stroke-dasharray="dashEnProceso" :stroke-dashoffset="offsetEnProceso" stroke-linecap="round" />
              <!-- Resuelto -->
              <circle cx="50" cy="50" r="40" fill="none" stroke="#6366f1" stroke-width="12" 
                :stroke-dasharray="dashResuelto" :stroke-dashoffset="offsetResuelto" stroke-linecap="round" />
              <!-- Finalizados -->
              <circle cx="50" cy="50" r="40" fill="none" stroke="#22c55e" stroke-width="12" 
                :stroke-dasharray="dashFinalizados" :stroke-dashoffset="offsetFinalizados" stroke-linecap="round" />
            </svg>
            <div class="absolute inset-0 flex flex-col items-center justify-center">
              <span class="text-3xl font-extrabold text-slate-800">{{ stats.total }}</span>
              <span class="text-xs text-slate-400">Total</span>
            </div>
          </div>
        </div>
        <div class="mt-4 space-y-2">
          <div class="flex items-center justify-between text-sm">
            <div class="flex items-center gap-2">
              <span class="w-3 h-3 rounded-full bg-yellow-500"></span>
              <span class="text-slate-600">Pendiente</span>
            </div>
            <span class="font-semibold text-slate-800">{{ stats.pendientes }}</span>
          </div>
          <div class="flex items-center justify-between text-sm">
            <div class="flex items-center gap-2">
              <span class="w-3 h-3 rounded-full bg-blue-500"></span>
              <span class="text-slate-600">En Proceso</span>
            </div>
            <span class="font-semibold text-slate-800">{{ stats.en_proceso }}</span>
          </div>
          <div class="flex items-center justify-between text-sm">
            <div class="flex items-center gap-2">
              <span class="w-3 h-3 rounded-full bg-indigo-500"></span>
              <span class="text-slate-600">Resuelto (Esperando OK)</span>
            </div>
            <span class="font-semibold text-slate-800">{{ stats.resuelto }}</span>
          </div>
          <div class="flex items-center justify-between text-sm">
            <div class="flex items-center gap-2">
              <span class="w-3 h-3 rounded-full bg-green-500"></span>
              <span class="text-slate-600">Finalizado</span>
            </div>
            <span class="font-semibold text-slate-800">{{ stats.finalizados }}</span>
          </div>
        </div>
      </div>

      <!-- ACTIVIDAD RECIENTE -->
      <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
          <i data-lucide="activity" class="w-5 h-5 text-slate-500"></i>
          Actividad Reciente
        </h3>
        <div class="space-y-4">
          <div v-for="ticket in ticketsRecientes" :key="ticket.id" class="flex items-start gap-3">
            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center shrink-0 mt-0.5">
              <i data-lucide="ticket" class="w-4 h-4 text-blue-600"></i>
            </div>
            <div class="flex-1">
              <p class="text-sm text-slate-800 font-medium">Ticket nro° : {{ ticket.codigo_ticket }}</p>
              <p class="text-xs text-slate-500 truncate">{{ ticket.asunto }}</p>
              <p class="text-[10px] text-slate-400 mt-1">{{ ticket.created_at }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- MÉTRICAS SLA -->
      <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
          <i data-lucide="gauge" class="w-5 h-5 text-slate-500"></i>
          Métricas SLA
        </h3>
        <div class="space-y-5">
          <div>
            <div class="flex justify-between items-center mb-2">
              <span class="text-sm text-slate-600">Tiempo promedio de respuesta</span>
              <span class="text-sm font-bold text-slate-800">12 min</span>
            </div>
            <div class="w-full bg-slate-100 rounded-full h-2.5">
              <div class="bg-blue-500 h-2.5 rounded-full" style="width: 75%"></div>
            </div>
          </div>
          <div>
            <div class="flex justify-between items-center mb-2">
              <span class="text-sm text-slate-600">Tickets finalizados a tiempo</span>
              <span class="text-sm font-bold text-slate-800">85%</span>
            </div>
            <div class="w-full bg-slate-100 rounded-full h-2.5">
              <div class="bg-green-500 h-2.5 rounded-full" style="width: 85%"></div>
            </div>
          </div>
          <div>
            <div class="flex justify-between items-center mb-2">
              <span class="text-sm text-slate-600">Satisfacción del cliente</span>
              <span class="text-sm font-bold text-slate-800">4.2/5.0</span>
            </div>
            <div class="w-full bg-slate-100 rounded-full h-2.5">
              <div class="bg-yellow-500 h-2.5 rounded-full" style="width: 84%"></div>
            </div>
          </div>
          <div>
            <div class="flex justify-between items-center mb-2">
              <span class="text-sm text-slate-600">Tickets pendientes</span>
              <span class="text-sm font-bold text-yellow-600">{{ stats.pendientes }}</span>
            </div>
            <div class="w-full bg-slate-100 rounded-full h-2.5">
              <div class="bg-yellow-500 h-2.5 rounded-full" style="width: 30%"></div>
            </div>
          </div>
        </div>
      </div>

    </div>

    <!-- TABLA DE TICKETS -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
      
      <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h2 class="font-bold text-lg text-slate-800">Tickets del Sistema</h2>
          <p class="text-sm text-slate-500">Gestiona y visualiza todos los tickets</p>
        </div>
        <div class="flex items-center gap-3">
          <div class="relative">
            <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
            <input 
              type="text" 
              v-model="busqueda"
              placeholder="Buscar ticket..." 
              class="pl-9 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent w-64"
            />
          </div>
          <select v-model="filtroEstado" class="px-4 py-2 border border-gray-200 rounded-lg text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
            <option value="">Todos los estados</option>
            <option value="Pendiente">Pendiente</option>
            <option value="En Proceso">En Proceso</option>
            <option value="Resuelto">Resuelto</option>
            <option value="Finalizado">Finalizado</option>
          </select>
        </div>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left">
          <thead class="bg-slate-800 text-white">
            <tr>
              <th class="p-4 text-xs font-semibold tracking-wider uppercase">Codigo Ticket</th>
              <th class="p-4 text-xs font-semibold tracking-wider uppercase">Asunto</th>
              <th class="p-4 text-xs font-semibold tracking-wider uppercase">Usuario Reportante</th>
              <th class="p-4 text-xs font-semibold tracking-wider uppercase">Estado</th>
              <th class="p-4 text-xs font-semibold tracking-wider uppercase">Prioridad</th>
              <th class="p-4 text-xs font-semibold tracking-wider uppercase">Asignado</th>
              <th class="p-4 text-xs font-semibold tracking-wider uppercase">Creado</th>
              <th class="p-4 text-xs font-semibold tracking-wider uppercase text-center">Acciones</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            <tr v-for="ticket in ticketsFiltrados" :key="ticket.id" class="hover:bg-slate-50 transition-colors">
              <td class="p-4 text-sm font-medium text-slate-700">Nro° : {{ ticket.codigo_ticket }}</td>
              <td class="p-4 text-sm text-slate-800 font-medium">{{ ticket.asunto }}</td>
              <td class="p-4 text-sm text-slate-600">{{ ticket.usuario_nombre }}</td>
              
              <!-- ESTADO ADAPTADO CON TUS SVGS Y ESTILOS -->
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

              <!-- PRIORIDAD ADAPTADA CON TUS ESTILOS -->
              <td class="p-4">
                <span class="inline-flex items-center gap-2 px-4 py-1 rounded-full bg-slate-100 border border-slate-200 text-sm font-medium">
                  <span class="w-2.5 h-2.5 rounded-full animate-ping" :class="getPrioridadColor(ticket.prioridad)"></span>
                  <span class="capitalize text-slate-700">{{ getPrioridadNombre(ticket.prioridad) }}</span>
                </span>
              </td>

              <td class="p-4">
                <span :class="['inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold border shadow-xs', getTecnicoBadgeStyle(ticket).clase]">
                  <!-- Ícono dinámico -->
                  <svg v-if="!ticket.tecnicoAsignadoId" class="w-3.5 h-3.5 animate-ping" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                  </svg>
                  <svg v-else class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                  </svg>

                  <span>{{ getTecnicoBadgeStyle(ticket).texto }}</span>
                </span>
              </td>
              <td class="p-4 text-sm text-slate-500">{{ ticket.fecha_creacion || 'Sin fecha' }}</td>
              <td class="p-4">
                <div class="flex justify-center gap-2">
                  <router-link :to="`/tickets/${ticket.id}`" class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-500 hover:text-white transition-all shadow-sm" title="Ver detalle">
                    <i data-lucide="eye" class="w-4 h-4"></i>
                  </router-link>
                <button @click="irAEditar(ticket)" :disabled="ticket.estadoTicket === 'Finalizado'" class="p-2 bg-yellow-50 text-yellow-600 rounded-lg hover:bg-yellow-500 hover:text-white transition-all shadow-sm" title="Editar">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </button>
                  <button @click="eliminarTicket(ticket.id)" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-all shadow-sm" title="Eliminar">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, nextTick } from 'vue';
import { createIcons, icons } from 'lucide';
import api from '@/services/api';
import { useRouter } from 'vue-router';
const stats = ref({ total: 0, pendientes: 0, en_proceso: 0, resuelto: 0, finalizados: 0 });
const tickets = ref([]);
const busqueda = ref('');
const filtroEstado = ref('');
const router = useRouter();

const cargarDatos = async () => {
  try {
    const [resStats, resTickets] = await Promise.all([
      api.get('/tickets-estadisticas'),
      api.get('/tickets')
    ]);
    stats.value = resStats.data;
    tickets.value = resTickets.data.data || resTickets.data;
    console.log('datos:',resTickets)
    await nextTick();
    createIcons({ icons });
  } catch (error) {
    console.error('Error al cargar dashboard:', error);
  }
};

const irAEditar = (ticket) => router.push({ name: 'tickets.form', params: { id: ticket.id } });

const ticketsRecientes = computed(() => {
  return tickets.value.slice(0, 4);
});

const ticketsFiltrados = computed(() => {
  return tickets.value.filter(t => {
    const coincideBusqueda = t.asunto.toLowerCase().includes(busqueda.value.toLowerCase()) || String(t.id).includes(busqueda.value);
    const coincideEstado = filtroEstado.value ? t.estadoTicket === filtroEstado.value : true;
    return coincideBusqueda && coincideEstado;
  });
});

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

// Helpers para estilos de Estado en tabla
const getEstadoBadgeStyle = (estado) => {
  switch (estado) {
    case 'Pendiente':
      return { clase: 'bg-yellow-50 text-yellow-700 border-yellow-200' };
    case 'En Proceso':
      return { clase: 'bg-skiblue-50 text-blue-700 border-blue-200' };
    case 'Resuelto':
      return { clase: 'bg-indigo-100 text-indigo-700 border-indigo-200' };
    case 'Finalizado':
      return { clase: 'bg-green-50 text-green-700 border-green-200' };
    default:
      return { clase: 'bg-slate-50 text-slate-700 border-slate-200' };
  }
};

// Helpers para Prioridad blindados contra números y nulos
const getPrioridadColor = (prioridad) => {
  if (!prioridad) return 'bg-blue-500';
  const val = String(prioridad).toLowerCase();
  
  switch (val) {
    case 'urgente':
    case '1':
      return 'bg-red-500';
    case 'alta':
    case '2': // Por si en BD se guarda con números
      return 'bg-orange-500';
    case 'media':
    case '3':
      return 'bg-yellow-500';
    case 'baja':
    case '4':
      return 'bg-green-500';
    default:
      return 'bg-blue-500';
  }
};

const getPrioridadNombre = (prioridad) => {
  if (!prioridad) return 'Normal';
  // Si en tu BD guardas números para la prioridad, los mapeamos a texto amigable aquí:
  const mapTexto = {
    '1': 'Urgente',
    '2': 'Alta',
    '3': 'Media',
    '4': 'Baja',
    'normal': 'Normal'
  };
  return mapTexto[String(prioridad).toLowerCase()] || prioridad;
};

// Gráfica SVG proporcional (Pendiente, En Proceso, Resuelto, Finalizado)
const circumference = 251.2;

const dashPendientes = computed(() => {
  if (!stats.value.total) return `0 ${circumference}`;
  return `${(stats.value.pendientes / stats.value.total) * circumference} ${circumference}`;
});

const dashEnProceso = computed(() => {
  if (!stats.value.total) return `0 ${circumference}`;
  return `${(stats.value.en_proceso / stats.value.total) * circumference} ${circumference}`;
});

const offsetEnProceso = computed(() => {
  if (!stats.value.total) return 0;
  return -((stats.value.pendientes / stats.value.total) * circumference);
});

const dashResuelto = computed(() => {
  if (!stats.value.total) return `0 ${circumference}`;
  return `${(stats.value.resuelto / stats.value.total) * circumference} ${circumference}`;
});

const offsetResuelto = computed(() => {
  if (!stats.value.total) return 0;
  const pendientesVal = (stats.value.pendientes / stats.value.total) * circumference;
  const enProcesoVal = (stats.value.en_proceso / stats.value.total) * circumference;
  return -(pendientesVal + enProcesoVal);
});

const dashFinalizados = computed(() => {
  if (!stats.value.total) return `0 ${circumference}`;
  return `${(stats.value.finalizados / stats.value.total) * circumference} ${circumference}`;
});

const offsetFinalizados = computed(() => {
  if (!stats.value.total) return 0;
  const pendientesVal = (stats.value.pendientes / stats.value.total) * circumference;
  const enProcesoVal = (stats.value.en_proceso / stats.value.total) * circumference;
  const resueltoVal = (stats.value.resuelto / stats.value.total) * circumference;
  return -(pendientesVal + enProcesoVal + resueltoVal);
});

const eliminarTicket = async (id) => {
  if (confirm('¿Estás seguro de eliminar este ticket?')) {
    try {
      await api.delete(`/tickets/${id}`);
      await cargarDatos();
    } catch (error) {
      console.error('Error al eliminar:', error);
    }
  }
};

onMounted(() => {
  cargarDatos();
});
</script>