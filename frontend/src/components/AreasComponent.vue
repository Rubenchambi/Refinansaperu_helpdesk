<template>
  <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      
      <div class="p-4 lg:col-span-1  rounded-2xl shadow-sm border border-slate-200 sticky top-8r">
        <h2 class="text-lg font-bold text-slate-700 mb-6 flex items-center gap-2">
          <span class="w-2 h-6 bg-blue-600 rounded-full"></span>
          {{ form.id ? 'Editar Área' : 'Nueva Área' }}
        </h2>
        
        <form @submit.prevent="guardarArea" class="space-y-4">
          <div>
            <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Nombre del Área *</label>
            <input v-model="form.nombre" required class="w-full p-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none transition" placeholder="Ej: Sistemas">
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
              <th class="p-4 text-xs font-bold text-white-400 uppercase ">Nro.</th>
              <th class="p-4 text-xs font-bold text-white-400 uppercase">Nombre</th>
              <th class="p-4 text-xs font-bold text-white-400 uppercase">Estado</th>
              <th class="p-4 text-xs font-bold text-white-400 uppercase text-right ">Acciones</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 text-s">
            <tr v-for="area in areas" :key="area.id" class="hover:bg-white hover:shadow-md hover:scale-[1.005] transition-all duration-200 ease-in-out cursor-pointer">
              <td class="p-4 font-semibold text-center text-slate-700">{{ area.id }}</td>
              <td class="p-4 font-semibold text-slate-700">{{ area.nombre }}</td>
              <td class="p-4">
                <span :class="area.estado ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700'" class="px-3 py-1 rounded-full text-[10px] font-bold uppercase">
                  {{ area.estado ? 'Activo' : 'Inactivo' }}
                </span>
              </td>
              <td class="p-4 text-right space-x-2">
                <button @click="editar(area)" class="p-2 bg-yellow-50 text-yellow-600 rounded-lg hover:bg-yellow-500 hover:text-white transition-all shadow-sm">
                  <PencilIcon class="w-5 h-5" />
                </button>
                <button @click="eliminar(area.id)" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-all shadow-sm">
                  <TrashIcon class="w-5 h-5" />
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
import { BuildingOffice2Icon, PencilIcon, TrashIcon } from '@heroicons/vue/24/outline';

const areas = ref([]);
const pagination = ref({});
const form = ref({ id: null, nombre: '', estado: true });

const fetchAreas = async (page = 1) => { // <-- Recibimos la página
  try {
    const { data } = await api.get(`/areas?page=${page}`);
    areas.value = data.data; // Asumiendo que usas paginate() en el controller
    pagination.value = {
      current_page: data.current_page,
      last_page: data.last_page,
      prev_page_url: data.prev_page_url,
      next_page_url: data.next_page_url
    };
  } catch (error) {
    Swal.fire('Error', 'No se pudieron cargar las áreas', 'error');
  }
};
const changePage = (page) => fetchAreas(page);

const guardarArea = async () => {
  try {
    const esEdicion = !!form.value.id;
    esEdicion ? await api.put(`/areas/${form.value.id}`, form.value) : await api.post('/areas', form.value);
    Swal.fire('¡Éxito!', 'Guardado correctamente', 'success');
    resetForm();
    fetchAreas();
  } catch (error) {
    console.log(error.response?.data);
    Swal.fire('Error', 'Verifica el nombre del área', 'error');
  }
};

const editar = (area) => { form.value = { ...area }; };
const resetForm = () => { form.value = { id: null, nombre: '', estado: true }; };

const eliminar = async (id) => {
  const { isConfirmed } = await Swal.fire({ title: '¿Eliminar área?', icon: 'warning', showCancelButton: true });
  if (isConfirmed) {
    await api.delete(`/areas/${id}`);
    fetchAreas();
  }
};

onMounted(fetchAreas);
</script>