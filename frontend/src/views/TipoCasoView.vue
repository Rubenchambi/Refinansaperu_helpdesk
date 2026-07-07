<template>
  <div class="p-8 bg-slate-50 min-h-screen">
    <div class="mb-8" >
      
      <h1 class="text-3xl font-extrabold text-slate-800">Tipos de Caso</h1>
      <p class="text-slate-500">Administra las categorías para la clasificación de tickets.</p>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Formulario -->
      <div class="lg:col-span-1">
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 sticky top-8">
          <h2 class="text-lg font-bold text-slate-700 mb-6 flex items-center gap-2">
            <span class="w-2 h-6 bg-blue-600 rounded-full"></span>
             <ClipboardDocumentListIcon class="w-5 h-5" />{{ form.id ? 'Editar Categoría' : 'Añadir Nuevo' }}
          </h2>
          
          <form @submit.prevent="guardarTipoCaso" class="space-y-4">
            <div>
              <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Nombre del Tipo *</label>
              <input v-model="form.nombre" required class="w-full p-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none transition" placeholder="Ej: Falla de Impresora">
            </div>
            
            <div class="relative">
            <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Prioridad *</label>
            <select v-model="form.prioridadBase" required class="w-full p-3 pl-10 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none transition bg-white appearance-none">
                <option value="" disabled>Selecciona una prioridad</option>
                <option value="Muy Baja">Muy Baja</option>
                <option value="Baja">Baja</option>
                <option value="Media">Media</option>
                <option value="Alta">Alta</option>
                <option value="Crítica">Crítica</option>
            </select>
            
            <div class="absolute left-4 top-9 w-5 h-5 rounded-full border border-slate-200"
                :class="[
                form.prioridadBase === 'Crítica' ? 'bg-red-500' :
                form.prioridadBase === 'Alta' ? 'bg-orange-500' :
                form.prioridadBase === 'Media' ? 'bg-yellow-400' :
                form.prioridadBase === 'Baja' ? 'bg-blue-500' :
                form.prioridadBase === 'Muy Baja' ? 'bg-emerald-500' :
                'bg-slate-300'
                ]">
            </div>
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Descripción</label>
              <textarea v-model="form.descripcion" rows="4" class="w-full p-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none transition" placeholder="Detalle del caso..."></textarea>
            </div>

            <label class="flex items-center gap-3 cursor-pointer">
              <input type="checkbox" v-model="form.estado" class="w-5 h-5 rounded text-blue-600 focus:ring-blue-500">
              <span class="text-sm font-semibold text-slate-600">Activo</span>
            </label>

            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-800 text-white font-bold py-3 rounded-xl transition shadow-lg shadow-slate-200">
               {{ form.id ? 'Actualizar' : 'Guardar' }}
            </button>
            <button v-if="form.id" type="button" @click="resetForm" class="w-full text-slate-400 hover:text-slate-600 text-sm font-bold py-2 transition">
              Cancelar edición
            </button>
          </form>
        </div>
      </div>

      <!-- Tabla -->
      <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
          <table class="w-full text-left">
            <thead class="bg-slate-50 border-b border-slate-100">
              <tr>
                <th class="p-5 text-xs font-bold text-slate-500 uppercase">Nombre</th>
                <th class="p-5 text-xs font-bold text-slate-500 uppercase">Prioridad</th>
                <th class="p-5 text-xs font-bold text-slate-500 uppercase">Descripcion</th>
                <th class="p-5 text-xs font-bold text-slate-500 uppercase">Estado</th>
                <th class="p-5 text-xs font-bold text-slate-500 uppercase text-right">Acciones</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
              <tr v-for="caso in tiposCaso" :key="caso.id" class="hover:bg-slate-50 transition-colors">
                <td class="p-5 font-semibold text-slate-700">{{ caso.nombre }}</td>
                <td class="p-5">
                <span 
                    :class="[
                    'px-3 py-1 text-xs font-bold rounded-full border',
                    caso.prioridadBase === 'Crítica' ? 'bg-red-100 text-red-700 border-red-200' :
                    caso.prioridadBase === 'Alta' ? 'bg-orange-100 text-orange-700 border-orange-200' :
                    caso.prioridadBase === 'Media' ? 'bg-yellow-100 text-yellow-700 border-yellow-200' :
                    caso.prioridadBase === 'Baja' ? 'bg-blue-100 text-blue-700 border-blue-200' :
                    caso.prioridadBase === 'Muy Baja' ? 'bg-emerald-100 text-emerald-700 border-emerald-200' :
                    'bg-slate-100 text-slate-600 border-slate-200'
                    ]"
                >
                    {{ caso.prioridadBase }}
                </span>
                </td>
                <td class="p-5 text-sm text-slate-500">{{ caso.descripcion }}</td>
                <td class="p-5">
                  <span :class="caso.estado ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700'" class="px-3 py-1 rounded-full text-[10px] font-bold uppercase">
                    {{ caso.estado ? 'Activo' : 'Inactivo' }}
                  </span>
                </td>
                <td class="p-5 text-right space-x-2">
                  <button @click="editar(caso)" class="p-2 bg-yellow-50 text-yellow-600 rounded-lg hover:bg-yellow-500 hover:text-white transition-all shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                  </button>
                  <button @click="eliminar(caso.id)" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-all shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
          <!-- Paginación Simple -->
          <div class="p-4 border-t border-slate-100 flex justify-between items-center">
            <button :disabled="!pagination.prev_page_url" @click="changePage(pagination.current_page - 1)" class="px-4 py-2 bg-blue-500 rounded-lg text-sm text-white disabled:opacity-50">Anterior</button>
            <span class="text-sm text-blue-500">Pág {{ pagination.current_page }} de {{ pagination.last_page }}</span>
            <button :disabled="!pagination.next_page_url" @click="changePage(pagination.current_page + 1)" class="px-4 py-2 bg-blue-500 rounded-lg text-sm text-white disabled:opacity-50">Siguiente</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '@/services/api';
import Swal from 'sweetalert2';
import {ClipboardDocumentListIcon} from '@heroicons/vue/24/outline';
const tiposCaso = ref([]);
const pagination = ref({});
const form = ref({ id: null, nombre: '', descripcion: '', prioridadBase: 1, estado: true });

const fetchTiposCaso = async (page = 1) => {
  try {
    const { data } = await api.get(`/tipos-casos?page=${page}`);
    tiposCaso.value = data.data;
    pagination.value = {
      current_page: data.current_page,
      last_page: data.last_page,
      prev_page_url: data.prev_page_url,
      next_page_url: data.next_page_url
    };
  } catch (error) {
    Swal.fire('Error', 'No se pudieron cargar los datos', 'error');
  }
};

const changePage = (page) => fetchTiposCaso(page);

const guardarTipoCaso = async () => {
  try {
    const esEdicion = !!form.value.id;
    esEdicion ? await api.put(`/tipos-casos/${form.value.id}`, form.value) : await api.post('/tipos-casos', form.value);
    Swal.fire('¡Éxito!', 'Guardado correctamente', 'success');
    resetForm();
    fetchTiposCaso();
  } catch (error) {
    console.log("Errores de validación:", error.response.data.errors);
    Swal.fire('Error', 'Revisa los campos', 'error');
  }
};

const editar = (caso) => {
  form.value = { ...caso };
  window.scrollTo({ top: 0, behavior: 'smooth' });
};

const eliminar = async (id) => {
  const { isConfirmed } = await Swal.fire({ title: '¿Eliminar?', icon: 'warning', showCancelButton: true });
  if (isConfirmed) {
    await api.delete(`/tipos-casos/${id}`);
    fetchTiposCaso();
  }
};

const resetForm = () => form.value = { id: null, nombre: '', descripcion: '', prioridadBase: 'Media', estado: true };

onMounted(fetchTiposCaso);
</script>