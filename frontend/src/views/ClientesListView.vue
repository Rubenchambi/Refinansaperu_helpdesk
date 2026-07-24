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
        <input v-model="filters.contacto" @keyup.enter="fetchClientes(1)" placeholder="Buscar por contacto..." class="p-2 border border-slate-300 rounded-md focus:ring-2 focus:ring-blue-500 outline-none">
        <input v-model="filters.ruc" @keyup.enter="fetchClientes(1)" placeholder="Buscar por RUC..." class="p-2 border border-slate-300 rounded-md focus:ring-2 focus:ring-blue-500 outline-none">
        <select v-model="filters.cargo" @change="fetchClientes(1)" class="p-2 border border-slate-300 rounded-md">
            <option value="">Todos los cargos</option>
            <option>Gestor Call</option>
            <option>Gestor Campo</option>
            <option>Supervisor</option>
            <option>Coordinador</option>
            <option>Administrativo</option>
            <option>Gerencia</option>
        </select>
        <div class="flex gap-2">
            <button @click="fetchClientes(1)" class="flex-1 bg-slate-800 text-white rounded-md hover:bg-slate-900 transition flex items-center justify-center gap-1">
              <FunnelIcon class="w-5 h-5" />Filtrar
            </button>
            <button @click="clearFilters" class="px-4 border border-slate-300 rounded-md hover:bg-slate-50 transition flex items-center justify-center">
              <BackspaceIcon class="w-5 h-5" />Limpiar
            </button>
        </div>
      </div>
      
      <div class="flex gap-2">
        <button @click="exportarExcel" class="flex items-center gap-1.5 px-4 py-1 text-xs font-bold text-white bg-emerald-600 rounded hover:bg-emerald-700 shadow-sm transition">
          <DocumentIcon class="w-5 h-5" /> Excel
        </button>
        <button @click="exportarPDF" class="flex items-center gap-1.5 px-4 py-1 text-xs font-bold text-white bg-red-600 rounded hover:bg-red-700 shadow-sm transition">
          <DocumentTextIcon class="w-5 h-5" /> PDF
        </button>
        <button @click="imprimir" class="flex items-center gap-1.5 px-4 py-1 text-xs font-bold text-white bg-slate-600 rounded hover:bg-slate-700 shadow-sm transition">
          <PrinterIcon class="w-5 h-5" /> Imprimir
        </button>
      </div>
    </div>

    <div class="bg-white shadow-sm border border-slate-200 rounded-lg overflow-hidden">
      <div id="imprimir-tabla">
      <table class="w-full text-left">
        <thead class="bg-blue-500 border-b border-slate-200 text-white uppercase text-xs font-bold">
          <tr>
            <th class="p-4">Nro°</th>
            <th class="p-4">Ruc - Dni</th>
            <th class="p-4">Contacto</th>
            <th class="p-4">Correo Electrónico</th>
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
            <td class="p-4 font-semibold text-slate-800">{{ cliente?.email }}</td>
            <td class="p-4 text-slate-600 text-sm">{{ cliente?.razonSocial || '-' }}</td>
            <td class="p-4">
              <span 
                :class="[
                  'px-3 py-1 text-xs font-bold rounded-full border',
                  cliente.cargo === 'Gerencia' ? 'bg-purple-100 text-purple-700 border-purple-200' :
                  cliente.cargo === 'Supervisor' ? 'bg-indigo-100 text-indigo-700 border-indigo-200' :
                  cliente.cargo === 'Coordinador' ? 'bg-blue-100 text-blue-700 border-blue-200' :
                  cliente.cargo === 'Gestor Call' ? 'bg-emerald-100 text-emerald-700 border-emerald-200' :
                  cliente.cargo === 'Gestor Campo' ? 'bg-orange-100 text-orange-700 border-orange-200' :
                  'bg-slate-100 text-slate-600 border-slate-200'
                ]"
              >
                {{ cliente.cargo }}
              </span>
            </td>
            <td class="p-4 text-slate-600">{{ cliente?.cartera_asignada }}</td>
            <td class="p-4 text-slate-600">{{ cliente?.telefono }}</td>
            <td class="p-4 text-center">
              <div class="flex justify-center gap-2">
                <button @click="irAEditar(cliente)" class="p-2 bg-yellow-50 text-yellow-600 rounded-lg hover:bg-yellow-500 hover:text-white transition-all shadow-sm">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </button>
                <button @click="deleteCliente(cliente.id)" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-all shadow-sm">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
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
import api from '@/services/api';
import Swal from 'sweetalert2';
import { useRouter } from 'vue-router';
import * as XLSX from 'xlsx';
import jsPDF from 'jspdf';
import autoTable from 'jspdf-autotable'; // Importamos autoTable explícitamente
import { DocumentIcon, DocumentTextIcon, PrinterIcon, FunnelIcon, BackspaceIcon } from '@heroicons/vue/24/outline';
import ExcelJS from 'exceljs';
import { saveAs } from 'file-saver';

const router = useRouter();

// 1. Estados reactivos
const clientes = ref([]);
const filters = ref({ contacto: '', ruc: '', cargo: '' });
const pagination = ref({ 
  current_page: 1, last_page: 1, prev_page_url: null, next_page_url: null 
});

const irAEditar = (cliente) => {
  router.push({ name: 'clientes.form', params: { id: cliente.id } });
};

// 3. Función para traer clientes
const fetchClientes = async (page = 1) => {
  try {
    const response = await api.get(`/clientes?page=${page}`, { 
       params: filters.value 
    });

    clientes.value = response.data.data;
    pagination.value = /*pagination.value = response.data;*/{
      
      current_page: response.data.current_page,
      last_page: response.data.last_page,
      prev_page_url: response.data.prev_page_url,
      next_page_url: response.data.next_page_url
    };
  } catch (error) {
    Swal.fire('Error', 'No se pudieron cargar los datos', 'error');
  }
};

// 4. Función para limpiar filtros
const clearFilters = () => {
  filters.value = { contacto: '', ruc: '', cargo: '' };
  fetchClientes(1);
};

// 5. Función para eliminar cliente
const deleteCliente = async (id) => {
  const result = await Swal.fire({
    title: '¿Eliminar asesor?',
    text: "El asesor será eliminado del sistema.",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#dc2626',
    cancelButtonColor: '#64748b',
    confirmButtonText: 'Sí, Eliminar',
    cancelButtonText: 'Cancelar'
  });

  if (result.isConfirmed) {
    try {
     await api.delete(`/clientes/${id}`);
      Swal.fire('¡Éxito!', 'Asesor eliminado correctamente.', 'success');
      fetchClientes(pagination.value.current_page);
    } catch (error) {
      Swal.fire('Error', 'No se pudo procesar la solicitud.', 'error');
    }
  }
};

// --- FUNCIONES DE EXPORTACIÓN ---

// A. Exportar a Excel con formato
const exportarExcel = async () => {
  const workbook = new ExcelJS.Workbook();
  const worksheet = workbook.addWorksheet('Clientes');

  // Definir columnas
  worksheet.columns = [
    { header: 'ID', key: 'id', width: 8 },
    { header: 'RUC/DNI', key: 'ruc', width: 15 },
    { header: 'CONTACTO', key: 'contacto', width: 25 },
    { header: 'EMAIL', key: 'email', width: 30 },
    { header: 'EMPRESA', key: 'empresa', width: 25 },
    { header: 'CARGO', key: 'cargo', width: 15 },
    { header: 'CARTERA', key: 'cartera', width: 20 },
    { header: 'TELEFONO', key: 'telefono', width: 15 }
  ];

  // Agregar datos
  clientes.value.forEach(c => {
    worksheet.addRow({
      id: c.id,
      ruc: c.ruc,
      contacto: c.contacto,
      email: c.email,
      empresa: c.razonSocial || '-',
      cargo: c.cargo,
      cartera: c.cartera_asignada,
      telefono: c.telefono
    });
  });

  // Estilo para el encabezado
  const headerRow = worksheet.getRow(1);
 headerRow.eachCell((cell, colNumber) => {
  // Solo aplicamos hasta la columna 8 (Teléfono)
  if (colNumber <= 8) {
    cell.fill = {
      type: 'pattern',
      pattern: 'solid',
      fgColor: { argb: 'FF1E40AF' } // Azul profesional
    };
    cell.font = { color: { argb: 'FFFFFFFF' }, bold: true };
    cell.alignment = { vertical: 'middle', horizontal: 'center' };
    worksheet.getColumn(1).alignment = { horizontal: 'center' };
    worksheet.getColumn(2).alignment = { horizontal: 'center' };
  }
});

  // Estilo para todas las celdas
  worksheet.eachRow((row) => {
    row.eachCell((cell) => {
      cell.border = {
        top: { style: 'thin' }, left: { style: 'thin' },
        bottom: { style: 'thin' }, right: { style: 'thin' }
      };
    });
  });

  // Generar archivo
  const buffer = await workbook.xlsx.writeBuffer();
  const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
  saveAs(blob, 'Reporte_Clientes.xlsx');
};

// Corregido: Uso de autoTable importado
const exportarPDF = () => {
  const doc = new jsPDF();
  doc.text("Reporte de Clientes", 14, 15);
  
  autoTable(doc, {
    head: [['ID', 'RUC', 'Contacto', 'Cargo', 'Cartera']],
    body: clientes.value.map(c => [c.id, c.ruc, c.contacto, c.cargo, c.cartera_asignada]),
    startY: 20,
    theme: 'striped'
  });
  
  doc.save("Reporte_Clientes.pdf");
};

const imprimir = () => {
  window.print();
};

onMounted(() => fetchClientes());
</script>