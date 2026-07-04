<template>
  <div class="p-8">
    <div class="flex justify-between items-center mb-6">
      <div>
        <h1 class="text-2xl font-bold text-slate-800">Gestión de Clientes</h1>
        <p class="text-slate-500">Administra los asesores y su cartera asignada.</p>
      </div>
      <router-link 
        to="/clientes/crear" 
        class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg font-semibold transition shadow-md"
      >
        + Nuevo Asesor
      </router-link>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-sm border border-slate-200 mb-6">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
        <input v-model="filters.contacto" placeholder="Buscar por contacto..." class="p-2 border border-slate-300 rounded-md focus:ring-2 focus:ring-blue-500 outline-none">
        <input v-model="filters.ruc" placeholder="Buscar por RUC..." class="p-2 border border-slate-300 rounded-md focus:ring-2 focus:ring-blue-500 outline-none">
        <select v-model="filters.cargo" class="p-2 border border-slate-300 rounded-md">
            <option value="">Todos los cargos</option>
            <option>Gestor Call</option>
            <option>Gestor Campo</option>
            <option>Supervisor</option>
            <option>Coordinador</option>
            <option>Administrativo</option>
            <option>Gerencia</option>
        </select>
        <div class="flex gap-2">
            <button @click="fetchClientes" class="flex-1 bg-slate-800 text-white rounded-md hover:bg-slate-900 transition">Filtrar</button>
            <button @click="clearFilters" class="px-4 border border-slate-300 rounded-md hover:bg-slate-50 transition">Limpiar</button>
        </div>
      </div>
      
      <div class="flex gap-2">
        <button class="px-4 py-1 text-xs font-bold text-white bg-green-600 rounded hover:bg-green-700">Excel</button>
        <button class="px-4 py-1 text-xs font-bold text-white bg-red-600 rounded hover:bg-red-700">PDF</button>
        <button class="px-4 py-1 text-xs font-bold text-white bg-slate-600 rounded hover:bg-slate-700">Imprimir</button>
      </div>
    </div>

    <div class="bg-white shadow-sm border border-slate-200 rounded-lg overflow-hidden">
      <table class="w-full text-left">
        <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 uppercase text-xs font-bold">
          <tr>
            <th class="p-4">Nro°</th>
            <th class="p-4">Ruc - Dni</th>
            <th class="p-4">Contacto</th>
            <th class="p-4">Razón Social</th>
            <th class="p-4">Cargo</th>
            <th class="p-4">Cartera</th>
            <th class="p-4">Teléfono</th>
            <th class="p-4 text-center">Acciones</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          <tr v-for="cliente in clientes" :key="cliente?.id" class="hover:bg-blue-50/50 transition-colors">
            <td class="p-4 font-mono text-sm text-slate-500 font-bold">{{ cliente?.id }}</td>
            <td class="p-4 text-slate-600 text-sm">{{ cliente?.ruc }}</td>
            <td class="p-4 font-semibold text-slate-800">{{ cliente?.contacto }}</td>
            <td class="p-4 text-slate-600 text-sm">{{ cliente?.razonSocial || '-' }}</td>
            <td class="p-4 text-slate-600 text-sm">{{ cliente?.cargo }}</td>
            <td class="p-4 text-slate-600">{{ cliente?.cartera_asignada }}</td>
            <td class="p-4 text-slate-600">{{ cliente?.telefono }}</td>
            <td class="p-4 text-center">
              <div class="flex justify-center gap-2">
                <button @click="irAEditar(cliente.id)" class="p-2 bg-yellow-50 text-yellow-600 rounded-lg hover:bg-yellow-500 hover:text-white transition-all shadow-sm"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                <button @click="deleteCliente(cliente.id)" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-all shadow-sm"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>

      <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-between bg-slate-50">
        <div class="text-sm text-slate-600">Mostrando <b>{{ pagination.current_page }}</b> de <b>{{ pagination.last_page }}</b></div>
        <nav class="flex items-center gap-1">
          <button :disabled="!pagination.prev_page_url" @click="fetchClientes(pagination.current_page - 1)" class="px-3 py-1.5 border border-slate-300 rounded-md bg-white hover:bg-slate-100 disabled:opacity-50 transition-all">Anterior</button>
          <button :disabled="!pagination.next_page_url" @click="fetchClientes(pagination.current_page + 1)" class="px-3 py-1.5 border border-slate-300 rounded-md bg-white hover:bg-slate-100 disabled:opacity-50 transition-all">Siguiente</button>
        </nav>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';
import { useRouter } from 'vue-router'; // Importamos el router

const router = useRouter(); // Inicializamos el router

// 1. Estados reactivos
const clientes = ref([]);
const filters = ref({ contacto: '', ruc: '', cargo: '' });
const pagination = ref({ 
  current_page: 1, last_page: 1, prev_page_url: null, next_page_url: null 
});

const irAEditar = (cliente) => {
  sessionStorage.setItem('cliente_a_editar', cliente.id);
  router.push({
    path: '/clientes/crear',
  
  });
};

// 3. Función para traer clientes
const fetchClientes = async (page = 1) => {
  try {
    const response = await axios.get('http://127.0.0.1:8000/api/clientes?page=' + page, { 
       params: filters.value 
    });
    clientes.value = response.data.data;
    pagination.value = {
      current_page: response.data.current_page,
      last_page: response.data.last_page,
      prev_page_url: response.data.prev_page_url,
      next_page_url: response.data.next_page_url
    };
  } catch (error) {
    Swal.fire('Error', 'No se pudieron cargar los datos', 'error');
  }
};

// 4. Eliminación lógica
const deleteCliente = async (id) => {
  const result = await Swal.fire({
    title: '¿Desactivar asesor?',
    text: "El asesor será dado de baja del sistema.",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#dc2626',
    confirmButtonText: 'Sí, desactivar'
  });

  if (result.isConfirmed) {
    try {
      await axios.patch(`http://127.0.0.1:8000/api/clientes/${id}`, { estado: false });
      Swal.fire('¡Éxito!', 'Asesor desactivado.', 'success');
      fetchClientes(pagination.value.current_page);
    } catch (error) {
      Swal.fire('Error', 'No se pudo procesar la solicitud.', 'error');
    }
  }
};

onMounted(() => fetchClientes());
</script>