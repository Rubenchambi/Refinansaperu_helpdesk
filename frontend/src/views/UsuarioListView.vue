<template>
  <div class="p-8 bg-slate-50 min-h-screen">
    <div class="flex justify-between items-center mb-6">
      <div>
        <h1 class="text-2xl font-bold text-slate-800">Gestión de Usuarios</h1>
        <p class="text-slate-500">Administra el personal y sus accesos al sistema.</p>
      </div>
      <router-link 
        to="/usuarios/crear" 
        class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg font-semibold transition shadow-md"
      >
        + Nuevo Usuario
      </router-link>
    </div>

    <!-- Filtros -->
    <div class="bg-white p-6 rounded-lg shadow-sm border border-slate-200 mb-6">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
        <input v-model="filters.nombre" @keyup.enter="fetchUsuarios(1)" placeholder="Buscar por nombre..." class="p-2 border border-slate-300 rounded-md outline-none focus:ring-2 focus:ring-blue-500">
        <input v-model="filters.apellidos" @keyup.enter="fetchUsuarios(1)" placeholder="Buscar por apellidos..." class="p-2 border border-slate-300 rounded-md outline-none focus:ring-2 focus:ring-blue-500">
        <input v-model="filters.dni" @keyup.enter="fetchUsuarios(1)" placeholder="Buscar por DNI..." class="p-2 border border-slate-300 rounded-md outline-none focus:ring-2 focus:ring-blue-500">
        <div class="flex gap-2">
            <button @click="fetchUsuarios(1)" class="flex-1 bg-slate-800 text-white rounded-md hover:bg-slate-900 transition flex items-center justify-center gap-1">Filtrar</button>
            <button @click="clearFilters" class="px-4 border border-slate-300 rounded-md hover:bg-slate-50 transition flex items-center justify-center"><BackspaceIcon class="w-5 h-5" />Limpiar</button>
        </div>
      </div>
      <!-- Botones de Exportación -->
      <div class="flex gap-2">
        <button @click="exportarExcel" class="flex items-center gap-1.5 px-4 py-1 text-xs font-bold text-white bg-emerald-600 rounded hover:bg-emerald-700 transition">
         <DocumentIcon class="w-5 h-5" />Excel
        </button>
        <button @click="exportarPDF" class="flex items-center gap-1.5 px-4 py-1 text-xs font-bold text-white bg-red-600 rounded hover:bg-red-700 transition">
         <DocumentTextIcon class="w-5 h-5" />PDF
        </button>
      </div>
    </div>

    <!-- Tabla -->
    <div class="bg-white shadow-sm border border-slate-200 rounded-lg overflow-hidden">
      <table class="w-full text-left">
        <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 uppercase text-xs font-bold">
          <tr>
            <th class="p-4">Nro°</th>
            <th class="p-4">DNI</th>
            <th class="p-4">Nombre Completo</th>
            <th class="p-4">Email</th>
            <th class="p-4">Areas Asignadas</th>
            <th class="p-4">Rol</th>
            <th class="p-4">Estado</th>
            <th class="p-4 text-center">Acciones</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          <tr v-for="user in usuarios" :key="user.id" class="hover:bg-blue-50/50 transition-colors">
            <td class="p-4 font-mono text-sm text-slate-500 font-bold">{{ user.id }}</td>
            <td class="p-4 text-slate-600 text-sm">{{ user.dni }}</td>
            <td class="p-4 font-semibold text-slate-800">{{ user.nombreCompleto }}</td>
            <td class="p-4 text-slate-600 text-sm">{{ user.email }}</td>
            <td class="p-4">
              <div class="flex flex-wrap gap-1">
                <span v-for="area in user.areas" :key="area.id" class="px-2 py-0.5 bg-blue-100 text-blue-700 text-[10px] font-bold rounded border border-blue-200">
                  {{ area.nombre }}
                </span>
              </div>
            </td>
            <td class="p-4 text-slate-600 text-sm">{{ user.rol }}</td>
            <td class="p-4 text-slate-600 text-sm">
                 <span :class="user.estado ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700'" class="px-3 py-1 rounded-full text-[10px] font-bold uppercase">
                    {{ user.estado ? 'Activo' : 'Inactivo' }}
                </span>
            </td>
            <td class="p-4 text-center">
              <div class="flex justify-center gap-2">
                <button @click="irAEditar(user)" class="p-2 bg-yellow-50 text-yellow-600 rounded-lg hover:bg-yellow-500 hover:text-white transition-all shadow-sm">
                    <PencilIcon class="w-5 h-5"/>
                </button>
                <button @click="deleteUsuario(user.id)" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-all shadow-sm">
                    <TrashIcon class="w-5 h-5"/>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Paginación -->
      <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-between bg-slate-50">
        <div class="text-sm text-slate-600">Mostrando página <b>{{ pagination.current_page }}</b></div>
        <nav class="flex items-center gap-1">
          <button :disabled="!pagination.prev_page_url" @click="fetchUsuarios(pagination.current_page - 1)" class="px-3 py-1.5 border border-slate-300 rounded-md bg-white hover:bg-slate-100 disabled:opacity-50 transition-all">Anterior</button>
          <button :disabled="!pagination.next_page_url" @click="fetchUsuarios(pagination.current_page + 1)" class="px-3 py-1.5 border border-slate-300 rounded-md bg-white hover:bg-slate-100 disabled:opacity-50 transition-all">Siguiente</button>
        </nav>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '@/services/api';
import Swal from 'sweetalert2';
import { useRouter } from 'vue-router';
import { PencilIcon, TrashIcon } from '@heroicons/vue/24/outline';
import ExcelJS from 'exceljs';
import { saveAs } from 'file-saver';
import jsPDF from 'jspdf';
import autoTable from 'jspdf-autotable';
import { DocumentIcon, DocumentTextIcon, PrinterIcon, FunnelIcon, BackspaceIcon } from '@heroicons/vue/24/outline';
const router = useRouter();
const usuarios = ref([]);
const filters = ref({ nombre: '', apellidos: '', dni: '' });
const pagination = ref({ current_page: 1, prev_page_url: null, next_page_url: null });

const fetchUsuarios = async (page = 1) => {
  try {
    const res = await api.get(`/usuarios?page=${page}`, { params: filters.value });
    usuarios.value = res.data.data;
    pagination.value = {
        current_page: res.data.current_page,
        prev_page_url: res.data.prev_page_url,
        next_page_url: res.data.next_page_url
    };
  } catch {
    Swal.fire('Error', 'No se pudieron cargar los datos', 'error');
  }
};

const irAEditar = (user) => router.push({ name: 'usuarios.form', params: { id: user.id } });

const deleteUsuario = async (id) => {
  const result = await Swal.fire({ title: '¿Desactivar usuario?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#dc2626' });
  if (result.isConfirmed) {
    await api.delete(`/usuarios/${id}`);
    fetchUsuarios(pagination.value.current_page);
  }
};

const exportarExcel = async () => {
  const workbook = new ExcelJS.Workbook();
  const worksheet = workbook.addWorksheet('Usuarios');
  worksheet.columns = [
    { header: 'ID', key: 'id', width: 5 },
    { header: 'NOMBRE', key: 'nombre', width: 30 },
    { header: 'ÁREAS', key: 'areas', width: 40 }
  ];
  
  usuarios.value.forEach(u => {
    worksheet.addRow({
      id: u.id,
      nombre: u.nombreCompleto,
      areas: u.areas.map(a => a.nombre).join(', ')
    });
  });
  
  const buffer = await workbook.xlsx.writeBuffer();
  saveAs(new Blob([buffer]), 'Usuarios.xlsx');
};

const exportarPDF = () => {
  const doc = new jsPDF();
  autoTable(doc, {
    head: [['ID', 'Nombre', 'Áreas']],
    body: usuarios.value.map(u => [u.id, u.nombreCompleto, u.areas.map(a => a.nombre).join(', ')])
  });
  doc.save('Usuarios.pdf');
};

const clearFilters = () => { filters.value = { nombre: '',apellidos: '', dni: '' }; fetchUsuarios(); };

onMounted(() => fetchUsuarios());
</script>