<template>
  <div class="p-8 bg-slate-50 min-h-screen">
    <div class="max-w-4xl mx-auto">
      
      <!-- Header Dinámico -->
      <div class="mb-8 flex justify-between items-center">
        <div>
          <h1 class="text-2xl font-bold text-slate-800">{{ esEdicion ? 'Editar Ticket' : 'Nuevo Ticket' }}</h1>
          <p class="text-slate-500">{{ esEdicion ? 'Modifica los datos del ticket existente.' : 'Registra una nueva solicitud de soporte técnico.' }}</p>
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

<!-- Segmento 3: Evidencia y Archivos Adjuntos -->
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
          <h2 class="text-lg font-semibold text-slate-700 mb-4 flex items-center gap-2">
            <span class="w-1 h-6 bg-blue-600 rounded"></span> Adjuntos y Evidencia
          </h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Subir o Ver Foto -->
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Foto de Evidencia (Opcional)</label>
              
              <!-- Input para cambiar o subir imagen -->
              <input 
                type="file" 
                accept="image/*" 
                @change="handleImagen" 
                class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer"
              />

              <!-- 1. Vista Previa de la FOTO NUEVA (si el usuario seleccionó una) -->
              <div v-if="fotoPreview" class="mt-3 relative w-32 h-32 rounded-xl overflow-hidden border border-slate-200 shadow-sm">
                <img :src="fotoPreview" alt="Vista previa nueva" class="w-full h-full object-cover" />
                <button 
                  @click="quitarImagen" 
                  type="button" 
                  class="absolute top-1 right-1 bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-700 shadow-md"
                >
                  ✕
                </button>
              </div>

              <!-- 2. FOTO ACTUAL DEL SERVIDOR (Se muestra solo si estamos editando y NO ha seleccionado una foto nueva) -->
              <div v-else-if="imagenActualUrl && !form.imagen" class="mt-3 relative w-32 h-32 rounded-xl overflow-hidden border border-slate-200 shadow-sm bg-slate-50 flex flex-col items-center justify-center">
                <img :src="imagenActualUrl" alt="Foto actual" class="w-full h-full object-cover" />
                <span class="absolute bottom-0 inset-x-0 bg-black/60 text-white text-[10px] text-center py-0.5">Actual</span>
              </div>
            </div>

            <!-- Subir Documentos (PDF, Word, PPT) -->
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Documentos (PDF, Word, PPT)</label>
              <input 
                type="file" 
                multiple 
                accept=".pdf, .doc, .docx, .ppt, .pptx" 
                @change="handleDocumentos" 
                class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 cursor-pointer"
              />

              <!-- DOCUMENTOS YA GUARDADOS EN EL SERVIDOR (Modo Edición) -->
              <div v-if="documentosActuales.length > 0" class="mt-3">
                <p class="text-xs font-semibold text-slate-500 mb-1">Archivos ya adjuntos:</p>
                <ul class="space-y-1.5 max-h-40 overflow-y-auto">
                  <li 
                    v-for="doc in documentosActuales" 
                    :key="doc.id" 
                    class="text-xs text-blue-700 bg-blue-50/50 px-3 py-2 rounded-lg flex justify-between items-center border border-blue-100 shadow-xs"
                  >
                    <a :href="doc.url" target="_blank" class="truncate max-w-[90%] hover:underline flex items-center gap-1">
                      <span>📄</span> <strong>{{ doc.nombre_original }}</strong>
                    </a>
                  </li>
                </ul>
              </div>

              <!-- LISTA DE NUEVOS ARCHIVOS SELECCIONADOS -->
              <div v-if="form.documentos.length > 0" class="mt-3">
                <p class="text-xs font-semibold text-slate-500 mb-1">Nuevos archivos por agregar:</p>
                <ul class="space-y-1.5 max-h-40 overflow-y-auto">
                  <li 
                    v-for="(file, index) in form.documentos" 
                    :key="index" 
                    class="text-xs text-slate-700 bg-slate-50 px-3 py-2 rounded-lg flex justify-between items-center border border-slate-200 shadow-xs"
                  >
                    <span class="truncate max-w-[80%]">📄 <strong>{{ file.name }}</strong></span>
                    <button 
                      @click="quitarDocumento(index)" 
                      type="button" 
                      class="text-red-500 hover:text-red-700 font-bold px-1.5 py-0.5 rounded hover:bg-red-50"
                    >
                      ✕
                    </button>
                  </li>
                </ul>
              </div>

            </div>

          </div>
        </div>
        <!-- Botones Acción -->
        <div class="flex justify-end gap-4 pt-4">
          <button type="button" @click="$router.back()" class="px-6 py-2.5 text-slate-600 hover:bg-slate-100 rounded-lg font-medium transition">Cancelar</button>
          <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold shadow-lg shadow-blue-600/20 transition">
            {{ esEdicion ? 'Guardar Cambios' : 'Crear Ticket' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import Swal from 'sweetalert2';
import api from '@/services/api';
import { useRouter, useRoute } from 'vue-router';

const usuarios = ref([]);
const tiposCaso = ref([]);
const router = useRouter();
const route = useRoute();
const imagenActualUrl = ref(null);
const documentosActuales = ref([]);
const esEdicion = computed(() => !!route.params.id);

const form = ref({
  usuarioReportanteId: '',
  tipoCasoId: '',
  prioridad: 3,
  tipo_cola: 'soporte',
  asunto: '',
  descripcion: '',
  imagen: null,      // <-- NUEVO: Para la foto de evidencia
  documentos: []     // <-- NUEVO: Para los documentos adjuntos
});

// NUEVO: Variable para la vista previa de la foto en pantalla
const fotoPreview = ref(null);

// NUEVO: Función para manejar la selección de la foto
const handleImagen = (e) => {
  const file = e.target.files[0];
  if (file) {
    form.value.imagen = file;
    const reader = new FileReader();
    reader.onload = (event) => {
      fotoPreview.value = event.target.result;
    };
    reader.readAsDataURL(file);
  }
};

// NUEVO: Función para quitar la foto seleccionada
const quitarImagen = () => {
  form.value.imagen = null;
  fotoPreview.value = null;
};

// NUEVO: Función para manejar los documentos múltiples (PDF, Word, PPT)
const handleDocumentos = (e) => {
  const files = Array.from(e.target.files);
  form.value.documentos = [...form.value.documentos, ...files];
};

// NUEVO: Función para quitar un documento específico de la lista
const quitarDocumento = (index) => {
  form.value.documentos.splice(index, 1);
};

const cargarCatalogos = async () => {
  try {
    const [resUsuarios, resTipos] = await Promise.all([
      api.get('/usuarios?all=true'),
      api.get('/tipos-casos?all=true')
    ]);
    usuarios.value = resUsuarios.data.data || resUsuarios.data;
    tiposCaso.value = resTipos.data.data || resTipos.data;
  } catch (error) {
    console.error("Error cargando catálogos", error);
  }
};

const cargarTicket = async () => {
  if (!esEdicion.value) return;
  try {
    const res = await api.get(`/tickets/${route.params.id}`);
    const data = res.data.data || res.data;
    form.value = { ...data,
      usuarioReportanteId: Number(data.usuarioReportanteId),
      tipoCasoId: Number(data.tipoCasoId),
      prioridad: Number(data.prioridad),
      imagen: null,
      documentos: []
    };
    imagenActualUrl.value = data.imagen;
    documentosActuales.value = data.archivos || [];
  } catch (error) {
    Swal.fire('Error', 'No se pudieron cargar los datos del ticket', 'error');
  }
};

const prioridadColor = computed(() => {
  const map = { 1: 'bg-red-600', 2: 'bg-orange-500', 3: 'bg-yellow-400', 4: 'bg-green-500' };
  return map[form.value.prioridad] || 'bg-slate-400';
});

const guardarTicket = async () => {
  try {
    const formData = new FormData();
    formData.append('usuarioReportanteId', form.value.usuarioReportanteId || '');
    formData.append('tipoCasoId', form.value.tipoCasoId || '');
    formData.append('prioridad', form.value.prioridad || 1);
    formData.append('tipo_cola', form.value.tipo_cola || 'general');
    formData.append('asunto', form.value.asunto || '');
    formData.append('descripcion', form.value.descripcion || '');

    if (form.value.imagen instanceof File) {
      formData.append('imagen', form.value.imagen);
    }

    if (Array.isArray(form.value.documentos)) {
      form.value.documentos.forEach((doc) => {
        if (doc instanceof File) {
          formData.append('documentos[]', doc);
        }
      });
    }

    // Imprime esto en la consola del navegador antes de enviarlo
    for (let pair of formData.entries()) {
      console.log(pair[0] + ': ', pair[1]);
    }

    if (esEdicion.value) {
      await api.post(`/tickets-con-archivos/actualizar/${route.params.id}`, formData,{
        headers: {
          'Content-Type': 'multipart/form-data'
        }
       });
      Swal.fire('Éxito', 'Ticket actualizado correctamente', 'success');
    } else {
      await api.post('/tickets', formData,
        {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      }
      );
      Swal.fire('Éxito', 'Ticket creado correctamente', 'success');
    }
    
    router.push('/tickets');
  } catch (error) {
    console.error("Detalle del error:", error.response?.data);
    Swal.fire('Error', 'No se pudo guardar el ticket', 'error');
  }
};


onMounted(() => {
  cargarCatalogos();
  cargarTicket();
});
</script>