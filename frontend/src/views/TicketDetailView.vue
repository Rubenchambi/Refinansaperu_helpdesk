<template>
  <div class="p-8 bg-slate-50 min-h-screen">
    <div class="max-w-6xl mx-auto">
      
      <div class="flex justify-between items-center mb-6">
        <button @click="$router.back()" class="text-slate-500 hover:text-blue-600 font-medium flex items-center gap-2 transition">
          &larr; Volver a la lista
        </button>
        <span class="px-3 py-1 bg-slate-200 text-slate-700 rounded-full text-xs font-bold uppercase tracking-wider">
          Ticket #{{ ticket.codigo_ticket }}
        </span>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 space-y-6">
          <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
            <h1 class="text-2xl font-bold text-slate-800 mb-2">{{ ticket.asunto }}</h1>
            <p class="text-slate-600 leading-relaxed">{{ ticket.descripcion }}</p>
          </div>

          <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
            <h2 class="font-semibold text-slate-700 mb-6 flex items-center gap-2">
              <span class="w-1 h-5 bg-blue-600 rounded"></span> Historial de Seguimiento
            </h2>
            <ul role="list" class="-mb-8">
              <li v-for="(evento, index) in bitacora" :key="evento.id">
                <div class="relative pb-8">
                  <span v-if="index !== bitacora.length - 1" class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-slate-200"></span>
                  <div class="relative flex space-x-3">
                    <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white" 
                          :class="evento.accion === 'cambio_estado' ? 'bg-amber-500' : 'bg-blue-500'">
                      <i :class="evento.accion === 'cambio_estado' ? 'fas fa-sync' : 'fas fa-comment'" class="text-white text-xs"></i>
                    </span>
                    <div class="flex min-w-0 flex-1 justify-between space-x-4">
                      <div>
                        <p class="text-sm text-slate-500">
                          <span class="font-bold text-slate-900">{{ evento.usuario_nombre }}</span> 
                          {{ evento.accion === 'cambio_estado' ? 'cambió el estado a ' + evento.estado_nuevo : 'añadió una nota' }}
                        </p>
                        <p class="mt-1 text-sm text-slate-700 italic" v-if="evento.comentario">"{{ evento.comentario }}"</p>
                      </div>
                      <div class="text-right text-xs text-slate-400">
                        <p>{{ evento.fecha }}</p>
                      </div>
                    </div>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </div>

        <div class="space-y-6">
          <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm sticky top-6">
            <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4">Gestión del Caso</h3>
            
            <div class="space-y-4 mb-6">
              <div><label class="text-xs text-slate-500">Prioridad</label>
                <div class="flex items-center gap-2 mt-1"><div class="w-3 h-3 rounded-full" :class="prioridadColor"></div> {{ ticket.prioridad }}</div>
              </div>
              <div><label class="text-xs text-slate-500">Estado</label>
                <div class="mt-1 font-semibold text-blue-700">{{ ticket.estadoTicket }}</div>
              </div>
            </div>

            <div v-if="usuarioAutenticado?.rol?.id === 1" class="space-y-4 pt-4 border-t border-slate-100">
              <div>
                <label class="text-xs font-bold text-slate-400 uppercase">Estado</label>
                <select v-model="ticket.estadoTicket" @change="cambiarEstado" class="w-full mt-1 p-2 border border-slate-200 rounded-lg text-sm">
                  <option value="Pendiente">Pendiente</option>
                  <option value="En Proceso">En Proceso</option>
                  <option value="Resuelto">Resuelto</option>
                </select>
              </div>

              <div>
                <label class="text-xs font-bold text-slate-400 uppercase">Técnico Asignado</label>
                <select v-model="ticket.tecnicoAsignadoId" @change="asignarTecnico" class="w-full mt-1 p-2 border border-slate-200 rounded-lg text-sm">
                  <option :value="null">Sin asignar</option>
                  <option v-for="tec in tecnicos" :key="tec.id" :value="tec.id">{{ tec.nombre }}</option>
                </select>
              </div>
            </div>

            <button v-if="usuarioPuedeComentar" @click="agregarNota" class="w-full mt-6 py-2.5 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700">
              Añadir Nota
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>


<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRoute } from 'vue-router';
import api from '@/services/api';
import Swal from 'sweetalert2';
import { useAuthStore } from '@/stores/auth';

const route = useRoute();
const ticket = ref({});
const bitacora = ref([]);
const authStore = useAuthStore();
const tecnicos = ref([]);
const usuarioAutenticado = computed(() => authStore.user);

const prioridadColor = computed(() => {
  const map = { '1': 'bg-red-600', '2': 'bg-orange-500', '3': 'bg-yellow-400', '4': 'bg-green-500' };
  return map[ticket.value.prioridad] || 'bg-slate-400';
});

const cargarTecnicos = async () => {
  const { data } = await api.get('/tecnicos');
  tecnicos.value = data;
};
// Lógica de Asignación
const asignarTecnico = async () => {
  try {
      await api.put(`/tickets/${route.params.id}/asignar`, { 
      tecnicoAsignadoId: ticket.value.tecnicoAsignadoId 
    });
    cargarTicket();
    Swal.fire('Asignado', 'Técnico actualizado con éxito', 'success');
  } catch (error) {
    Swal.fire('Error', 'No se pudo asignar al técnico', 'error');
    console.log('los datos guardados son:',error);
  }
};

const usuarioPuedeComentar = computed(() => {
    if (!usuarioAutenticado.value || !usuarioAutenticado.value.rol) return false;
    const rolesAutorizados = [1, 2]; 
    const esRolValido = rolesAutorizados.includes(usuarioAutenticado.value.rol.id);
    const esEstadoValido = ticket.value.estadoTicket !== 'Resuelto';
    return esEstadoValido && esRolValido;
});

const agregarNota = async () => {
  const { value: nota } = await Swal.fire({
    title: 'Nueva nota de seguimiento',
    input: 'textarea',
    inputPlaceholder: 'Describe los detalles...',
    showCancelButton: true,
    confirmButtonText: 'Guardar',
    confirmButtonColor: '#2563eb'
  });

  if (nota) {
    try {
      await api.post(`/tickets/${route.params.id}/bitacoras`, { mensaje: nota });
      Swal.fire('¡Éxito!', 'Nota agregada correctamente', 'success');
      cargarTicket(); // Función reutilizada para refrescar
    } catch (error) {
      Swal.fire('Error', 'No se pudo guardar la nota', 'error');
    }
  }
};

const cambiarEstado = async () => {
  try {
    await api.put(`/tickets/${route.params.id}/estado`, { 
      estadoTicket: ticket.value.estadoTicket 
    });

    await cargarTicket(); 
    Swal.fire({
      icon: 'success',
      title: '¡Actualizado!',
      text: 'El estado ha sido modificado',
      timer: 1500,
      showConfirmButton: false
    });
  } catch (error) {
    Swal.fire('Error', 'No se pudo actualizar el estado', 'error');
  }
};

const cargarTicket = async () => {
  try {
    const { data } = await api.get(`/tickets/${route.params.id}`);
    ticket.value = data.data;
    bitacora.value = data.data.bitacoras || []; 
  } catch (error) {
    console.error("Error al cargar ticket:", error);
  }
};

onMounted(()=>{cargarTicket(); cargarTecnicos();});
</script>