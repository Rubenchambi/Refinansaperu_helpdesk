<template>
  <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <div class="p-4 lg:col-span-1 rounded-2xl shadow-sm border border-slate-200 sticky top-8r">
        <h2 class="text-lg font-bold text-slate-700 mb-6 flex items-center gap-2">
          <span class="w-2 h-6 bg-blue-600 rounded-full"></span>
          {{ form.id ? 'Editar Cartera' : 'Nueva Cartera' }}
        </h2>
        
        <form @submit.prevent="guardarCartera" class="space-y-4">
          <div>
            <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Nombre Cartera *</label>
            <input v-model="form.nombre_cartera" required class="w-full p-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none transition" placeholder="Ej: Pichincha">
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Descripción</label>
            <input v-model="form.descripcion" class="w-full p-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none transition" placeholder="Descripción...">
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

      <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full text-left border-collapse">
          <thead class="bg-blue-500 text-sm font-medium text-white">
            <tr>
              <th class="px-4 py-3 text-left text-white-400 uppercase ">Nro.</th>
              <th class="p-4 text-xs font-bold text-white-400 uppercase">Nombre</th>
              <th class="p-4 text-xs font-bold text-white-400 uppercase">Descripción</th>
              <th class="p-4 text-xs font-bold text-white-400 uppercase">Estado</th>
              <th class="px-4 py-3 text-right text-white-400 uppercase">Acciones</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 text-s">
            <tr v-for="cartera in carteras" :key="cartera.id" class="hover:bg-white hover:shadow-md hover:scale-[1.005] transition-all duration-200 ease-in-out cursor-pointer">
              <td class="p-4 font-semibold text-center text-slate-700">{{ cartera.id }}</td>
              <td class="p-4 font-semibold text-slate-700">{{ cartera.nombre_cartera }}</td>
              <td class="p-4 text-slate-600">{{ cartera.descripcion }}</td>
              <td class="p-4">
                <span :class="cartera.estado ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700'" class="px-3 py-1 rounded-full text-[10px] font-bold uppercase">
                  {{ cartera.estado ? 'Activo' : 'Inactivo' }}
                </span>
              </td>
              <td class="p-4 text-right space-x-2">
                <button @click="editar(cartera)" class="p-2 bg-yellow-50 text-yellow-600 rounded-lg hover:bg-yellow-500 hover:text-white transition-all shadow-sm">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </button>
                <button @click="eliminar(cartera.id)" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-all shadow-sm">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
              </td>
            </tr>
          </tbody>
        </table>

        <div class="p-4 border-t border-slate-100 flex justify-between items-center">
          <button :disabled="!pagination.prev_page_url" @click="changePage(pagination.current_page - 1)" class="px-4 py-2 bg-blue-500 rounded-lg text-sm text-white disabled:opacity-50 hover:bg-blue-600">Anterior</button>
          <span class="text-sm text-slate-500 font-medium">Pág {{ pagination.current_page }} de {{ pagination.last_page }}</span>
          <button :disabled="!pagination.next_page_url" @click="changePage(pagination.current_page + 1)" class="px-4 py-2 bg-blue-500 rounded-lg text-sm text-white disabled:opacity-50 hover:bg-blue-600">Siguiente</button>
        </div>
      </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '../services/api';
import Swal from 'sweetalert2';

const carteras = ref([]);
const pagination = ref({});
const form = ref({ id: null, nombre_cartera: '', descripcion: '', estado: true });

const fetchCarteras = async (page = 1) => {
  try {
    const { data } = await api.get(`/carteras?page=${page}`);
    carteras.value = data.data;
    pagination.value = {
      current_page: data.current_page,
      last_page: data.last_page,
      prev_page_url: data.prev_page_url,
      next_page_url: data.next_page_url
    };
  } catch (error) {
    Swal.fire('Error', 'No se pudieron cargar las carteras', 'error');
  }
};

const changePage = (page) => fetchCarteras(page);

const guardarCartera = async () => {
  try {
    form.value.id ? await api.put(`/carteras/${form.value.id}`, form.value) : await api.post('/carteras', form.value);
    Swal.fire('¡Éxito!', 'Guardado correctamente', 'success');
    resetForm();
    fetchCarteras();
  } catch (error) {
    Swal.fire('Error', 'Verifica el nombre de la cartera', 'error');
  }
};

const editar = (cartera) => { form.value = { ...cartera }; };
const resetForm = () => { form.value = { id: null, nombre_cartera: '', descripcion: '', estado: true }; };

const eliminar = async (id) => {
  const { isConfirmed } = await Swal.fire({ title: '¿Desactivar cartera?', icon: 'warning', showCancelButton: true });
  if (isConfirmed) {
    await api.delete(`/carteras/${id}`);
    fetchCarteras();
  }
};

onMounted(fetchCarteras);
</script>