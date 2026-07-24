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
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm shadow-slate-100/50 hover:shadow-lg transition-all duration-300">
              <div class="flex flex-wrap items-center justify-between gap-2 pb-4 mb-4 border-b border-slate-100">
                <div class="flex items-center gap-2">
                  <div class="p-2 bg-blue-50 text-blue-600 rounded-xl">
                    <UserIcon class="w-5 h-5" />
                  </div>
                  <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Generado por</p>
                    <p class="text-sm p-1 font-bold text-slate-700">{{ ticket.usuario_nombre }}</p>
                  </div>
                </div>
                <div class="flex items-center gap-1.5 text-xs font-medium text-slate-400 bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-100">
                  <CalendarIcon class="w-4 h-4 text-slate-400" />
                  <span>Ticket : {{ ticket.estadoTicket }}</span>
                </div>
              </div>
              <div class="space-y-3">
                  <!-- Cabecera del bloque -->
                  <div class="flex items-start gap-2.5">
                    <FileTextIcon class="w-6 h-6 text-slate-400 shrink-0 mt-0.5" />
                    <div>
                      <h1 class="text-sm font-semibold text-slate-700 tracking-tight leading-snug uppercase">
                        Detalles de ticket generado
                      </h1>
                    </div>
                  </div>

                  <!-- Listado con Iconos Específicos Dinámicos -->
                  <div class="flex flex-col gap-3 pl-8 text-sm">
                    <!-- Asunto -->
                    <p class="flex items-start gap-2 text-slate-600 leading-relaxed whitespace-pre-line">
                      <Type class="w-5 h-5 text-slate-400 shrink-0 mt-0.5" /> 
                      <span class="font-semibold text-slate-700 shrink-0">Asunto:</span> 
                      <span class="text-slate-800">{{ ticket.asunto }}</span>
                    </p>

                    <!-- Descripción -->
                    <p class="flex items-start gap-2 text-slate-600 leading-relaxed whitespace-pre-line">
                      <AlignLeft class="w-5 h-5 text-slate-400 shrink-0 mt-0.5" /> 
                      <span class="font-semibold text-slate-700 shrink-0">Descripción:</span> 
                      <span class="text-slate-800">{{ ticket.descripcion }}</span>
                    </p>

                    <!-- Fecha de Creación -->
                    <p class="flex items-start gap-2 text-slate-600 leading-relaxed whitespace-pre-line">
                      <Calendar class="w-5 h-5 text-slate-400 shrink-0 mt-0.5" /> 
                      <span class="font-semibold text-slate-700 shrink-0">Fecha de creación:</span> 
                      <span class="text-slate-800">{{ ticket.fecha_creacion }}</span>
                    </p>

                    <!-- Técnico Asignado -->
                    <p class="flex items-start gap-2 text-slate-600 leading-relaxed whitespace-pre-line">
                      <UserCheck class="w-5 h-5 text-slate-400 shrink-0 mt-0.5" /> 
                      <span class="font-semibold text-slate-700 shrink-0">Técnico Asignado:</span> 
                      <span class="text-slate-800">{{ ticket.nombre_tecnico || 'No asignado' }}</span>
                    </p>
                  </div>
                </div>


            </div>

<!-- NUEVO SEGMENTO: ARCHIVOS ADJUNTOS -->
<div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm shadow-slate-100/50 hover:shadow-lg transition-all duration-300">
  
  <div class="flex items-center gap-2.5">
    <Paperclip class="w-6 h-6 text-slate-400 shrink-0" />
    <h2 class="text-sm font-semibold text-slate-700 tracking-tight uppercase">
      Archivos y Evidencias Adjuntas
    </h2>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pl-8">
    
    <!-- BLOQUE A: IMAGEN REPORTE -->
    <div class="space-y-2">
      <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
        <ImageIcon class="w-4 h-4" /> Evidencia Visual
      </h3>
      
      <div v-if="ticket.imagen" class="relative group overflow-hidden rounded-xl border border-slate-200 bg-slate-50 max-w-sm aspect-video">
        <img :src="ticket.imagen" alt="Evidencia" class="w-full h-full object-cover" />
        <button 
          @click="abrirVisor(ticket.imagen, 'imagen', 'Evidencia Visual')"
          class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-xs font-semibold gap-1"
        >
          <ExternalLink class="w-4 h-4" /> Ver imagen completa
        </button>
      </div>
      
      <p v-else class="text-xs italic text-slate-400">No se adjuntaron imágenes.</p>
    </div>

    <!-- BLOQUE B: DOCUMENTOS (PDFs) -->
    <div class="space-y-2">
      <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
        <FileTextIcon class="w-4 h-4" /> Documentos Adjuntos ({{ ticket.archivos?.length || 0 }})
      </h3>

      <div v-if="ticket.archivos && ticket.archivos.length > 0" class="space-y-2 max-w-md">
        <div v-for="archivo in ticket.archivos" :key="archivo.id" class="flex items-center justify-between p-3 bg-slate-50 border border-slate-200 rounded-xl">
          <div class="flex items-center gap-2.5 min-w-0">
            <div class="p-2 bg-red-50 text-red-600 rounded-lg shrink-0">
              <FileTextIcon class="w-4 h-4" />
            </div>
            <div class="truncate">
              <p class="text-sm font-medium text-slate-700 truncate" :title="archivo.nombre_original">{{ archivo.nombre_original }}</p>
              <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">{{ archivo.extension }}</p>
            </div>
          </div>

          <!-- ACCIONES: Ver en Popup y Descargar -->
          <div class="flex items-center gap-1 shrink-0">
            <button 
              @click="abrirVisor(archivo.url, 'pdf', archivo.nombre_original)"
              class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
              title="Visualizar documento"
            >
              <Eye class="w-4.5 h-4.5" />
            </button>
            
            <!-- Botón conectado a la función de descarga segura -->
            <button 
              @click="descargarArchivo(archivo.url, archivo.nombre_original)"
              class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" 
              title="Descargar"
            >
              <Download class="w-4.5 h-4.5" />
            </button>
          </div>
        </div>
      </div>
      <p v-else class="text-xs italic text-slate-400">No se adjuntaron documentos.</p>
    </div>

  </div>
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
                  <option value="Finalizado">Finalizado</option>
                </select>
              </div>

              <div>
                <label class="text-xs font-bold text-slate-400 uppercase">Técnico Asignado</label>
                <select v-model="ticket.tecnicoAsignadoId" @change="asignarTecnico" class="w-full mt-1 p-2 border border-slate-200 rounded-lg text-sm">
                  <option :value="null">Asignar un Técnico</option>
                  <option v-for="tec in tecnicos" :key="tec.id" :value="tec.id">{{ tec.nombre }}</option>
                </select>
              </div>
            </div>

            <button v-if="usuarioPuedeComentar" @click="agregarNota" class="w-full mt-6 py-2.5 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700">
              Añadir Nota
            </button>
            <br>
            <br>
              <div class="bg-white p-4 rounded-xl border text-center border-slate-200 shadow-sm">
                <p class="text-slate-400 uppercase text-xs font-semibold">
                  {{ ticket.estadoTicket === 'Finalizado' ? 'Estado' : (ticket.tecnicoAsignadoId ? 'Tiempo en Gestión' : 'Tiempo de Espera') }}
                </p>
                  
                <div :class="['p-2 flex justify-center items-center gap-1 font-mono font-bold', tiempoTranscurrido.color]">
                  <!-- Solo mostramos el ícono si no está finalizado -->
                  <svg v-if="ticket.estadoTicket !== 'Finalizado'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                  
                  <span>{{ tiempoTranscurrido.texto }}</span>
                </div>
              </div>
          </div>




        </div>
      </div>
    </div>
  </div>

<!-- POPUP VISOR MULTIMEDIA -->
<div v-if="mostrarModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm">
  <div class="relative w-full max-w-5xl h-[85vh] bg-white rounded-2xl shadow-2xl flex flex-col overflow-hidden">
    
    <!-- Encabezado del Popup -->
    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-white">
      <div class="min-w-0">
        <h3 class="text-base font-bold text-slate-800 truncate">{{ archivoSeleccionado.nombre }}</h3>
      </div>
      
      <div class="flex items-center gap-1.5 shrink-0">
        <button 
          @click="descargarArchivo(archivoSeleccionado.url, archivoSeleccionado.nombre)"
          class="p-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl transition-all hover:scale-105 flex items-center gap-1 text-xs font-semibold shadow-md shadow-blue-600/20"
          title="Descargar archivo"
        >
          <Download class="w-4 h-4" /> Descargar
        </button>

        <button 
          @click="mostrarModal = false" 
          class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-colors shrink-0"
          title="Cerrar"
        >
          <X class="w-5 h-5" />
        </button>
      </div>
    </div>

    <!-- Cuerpo del Popup -->
    <div class="flex-1 bg-slate-950 p-4 flex items-center justify-center overflow-auto">
      <img 
        v-if="archivoSeleccionado.tipo === 'imagen'" 
        :src="archivoSeleccionado.url" 
        alt="Vista previa" 
        class="max-w-full max-h-full object-contain rounded-lg"
      />

      <iframe 
        v-if="archivoSeleccionado.tipo === 'pdf'" 
        :src="archivoSeleccionado.url" 
        class="w-full h-full border-0 rounded-lg bg-white"
      ></iframe>
    </div>

  </div>
</div>

</template>


<script setup>
import { ref, onMounted,onUnmounted, computed } from 'vue';
import { useRoute } from 'vue-router';
import api from '@/services/api';
import Swal from 'sweetalert2';
import { useAuthStore } from '@/stores/auth';
import { 
  User as UserIcon, 
  Calendar as CalendarIcon, 
  FileText as FileTextIcon,
  Type, AlignLeft, Calendar, UserCheck, Paperclip, 
  Image as ImageIcon,Download, ExternalLink, X, Eye 
 } from 'lucide-vue-next'
const route = useRoute();
const ticket = ref({});
const bitacora = ref([]);
const authStore = useAuthStore();
const tecnicos = ref([]);
const usuarioAutenticado = computed(() => authStore.user);
const ahora = ref(new Date());
const mostrarModal = ref(false)
const archivoSeleccionado = ref({ url: '', tipo: '', nombre: '' })
let intervalo = null;

// NUEVA FUNCIÓN: Descarga forzada AJAX segura
const descargarArchivo = async (rutaOUrl, nombreOriginal) => {
  if (!rutaOUrl) return;

  try {
    let rutaLimpia = rutaOUrl;
    if (rutaOUrl.includes('/storage/')) {
      rutaLimpia = rutaOUrl.split('/storage/')[1];
    }

    const response = await api.get('/tickets/descargar-archivo', {
      params: { 
        ruta: rutaLimpia,
        nombre: nombreOriginal 
      },
      responseType: 'blob' // <-- Esto le dice a Axios: "trátalo como archivo binario"
    });

    // Creamos el archivo temporal en el navegador con su tipo MIME real (imagen/pdf)
    const blob = new Blob([response.data], { type: response.headers['content-type'] });
    const urlLocal = window.URL.createObjectURL(blob);
    
    const enlace = document.createElement('a');
    enlace.href = urlLocal;
    enlace.download = nombreOriginal || 'archivo';
    
    document.body.appendChild(enlace);
    enlace.click();
    
    document.body.removeChild(enlace);
    window.URL.revokeObjectURL(urlLocal);

  } catch (error) {
    console.error('Error al descargar el archivo:', error);
  }
};
// Función para abrir el modal con el contenido adecuado
const abrirVisor = (url, tipo, nombre = '') => {
  archivoSeleccionado.value = { url, tipo, nombre }
  mostrarModal.value = true
}
const prioridadColor = computed(() => {
  const map = { '1': 'bg-red-600', '2': 'bg-orange-500', '3': 'bg-yellow-400', '4': 'bg-green-500' };
  return map[ticket.value.prioridad] || 'bg-slate-400';
});

// En el script setup
onUnmounted(() => clearInterval(intervalo));

const tiempoTranscurrido = computed(() => {
  // 1. Caso Finalizado (Mensaje fijo)
  if (ticket.value.estadoTicket === 'Finalizado') {
    return {
      texto: 'Ticket Finalizado',
      color: 'text-emerald-600 bg-emerald-50 border-emerald-200'
    };
  }

  // 2. Cálculo normal para otros estados
  const tieneTecnico = ticket.value.tecnicoAsignadoId !== null;
  const fechaReferencia = tieneTecnico 
    ? ticket.value.asignado_at 
    : ticket.value.fecha_creacion;

  if (!fechaReferencia) return { texto: 'Esperando...', color: 'text-slate-400' };

  // Convertimos string a fecha
  const [fecha, hora] = fechaReferencia.split(' ');
  const [d, m, y] = fecha.split('/');
  const [h, min] = hora.split(':');
  const fechaInicio = new Date(y, m - 1, d, h, min);
  
  const diffMs = ahora.value - fechaInicio;
  const diffSegundos = Math.floor(diffMs / 1000);
  
  if (diffSegundos < 0) return { texto: '0h - 0m - 0s', color: 'text-slate-500' };

  const h_res = Math.floor(diffSegundos / 3600);
  const m_res = Math.floor((diffSegundos % 3600) / 60);
  const s_res = diffSegundos % 60;

  const totalMinutos = diffSegundos / 60;
  
  // Lógica de colores
  let color = 'text-slate-600';
  if (totalMinutos > 70) color = 'text-red-600 animate-pulse font-bold';
  else if (totalMinutos >= 60) color = 'text-orange-500';
  else if (totalMinutos >= 30) color = 'text-yellow-500';
  else if (totalMinutos >= 10) color = 'text-green-600';

  return {
    texto: `${h_res}h - ${m_res}m - ${s_res}s`,
    color: color
  };
});
// Lógica de colores según tus reglas
const getClaseColor = computed(() => {
  const min = tiempoCalculado.value.totalMinutos;
  if (min > 70) return 'text-red-600 animate-pulse font-bold'; // > 1h 10m
  if (min >= 60) return 'text-orange-500'; // 1 hora
  if (min >= 30) return 'text-yellow-500'; // 30 min
  if (min >= 20) return 'text-amber-500';  // 20 min
  if (min >= 10) return 'text-green-500';  // 10 min
  return 'text-slate-500'; // menos de 10 min
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
  // Si el usuario elige "Resuelto", forzamos el SweetAlert de nota
  if (ticket.value.estadoTicket === 'Resuelto') {
    const { value: nota } = await Swal.fire({
      title: 'Validando resultado',
      text: 'Para resolver el ticket, describe brevemente la solución:',
      input: 'textarea',
      inputPlaceholder: 'Explica qué se hizo...',
      showCancelButton: true,
      confirmButtonText: 'Confirmar si esta resuelto',
      inputValidator: (value) => {
        if (!value) return '¡Necesitas escribir la solución!'
      }
    });

    if (!nota) {
      // Si cancela, regresamos el select al estado anterior (puedes refrescar para recuperar)
      cargarTicket();
      return;
    }
    
    // Si escribió la nota, guardamos estado Y nota
    await ejecutarCambioEstado(ticket.value.estadoTicket, nota);
  } else {
    // Si es otro estado, cambio normal
    await ejecutarCambioEstado(ticket.value.estadoTicket);
  }
};

const ejecutarCambioEstado = async (nuevoEstado, comentario = null) => {
  try {
    await api.put(`/tickets/${route.params.id}/estado`, { 
      estadoTicket: nuevoEstado,
      comentario: comentario // Asegúrate de recibir esto en tu backend
    });
    Swal.fire('¡Éxito!', 'Estado actualizado', 'success');
    cargarTicket();
  } catch (error) {
    Swal.fire('Error', 'No se pudo actualizar', 'error');
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

onMounted(()=>{
  cargarTicket(); 
  cargarTecnicos();
  intervalo = setInterval(() => {
    ahora.value = new Date();
  }, 1000);
  
});
</script>