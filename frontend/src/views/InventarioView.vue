<script setup>
import { ref, onMounted } from 'vue';
import api from '@/services/api'; // 👈 Tu instancia centralizada
import Swal from 'sweetalert2';
import { 
    Package, 
    Laptop, 
    Search, 
    Plus, 
    Edit3, 
    Trash2, 
    PhoneCall, 
    Layers,
    CheckCircle2,
    AlertTriangle,
    Wrench
} from 'lucide-vue-next';

// Control de Pestañas
const tabActiva = ref('activos');

// Estados y Filtros
const activos = ref([]);
const busquedaActivos = ref('');
const loadingActivos = ref(false);

const productos = ref([]);
const busquedaProductos = ref('');
const loadingProductos = ref(false);

// Funciones para cambiar de pestaña de forma explícita
const cambiarTab = (tab) => {
    tabActiva.value = tab;
    if (tab === 'activos' && activos.value.length === 0) {
        cargarActivos();
    } else if (tab === 'productos' && productos.value.length === 0) {
        cargarProductos();
    }
};

// Cargar Activos usando tu servicio 'api'
const cargarActivos = async () => {
    loadingActivos.value = true;
    try {
        const { data } = await api.get('/activos', { 
            params: { buscar: busquedaActivos.value } 
        });
        activos.value = data.data || data;
    } catch (error) {
        console.error("Error cargando activos:", error);
        Swal.fire('Error', 'No se pudieron cargar los activos.', 'error');
    } finally {
        loadingActivos.value = false;
    }
};

// Cargar Productos usando tu servicio 'api'
const cargarProductos = async () => {
    loadingProductos.value = true;
    try {
        const { data } = await api.get('/productos', { 
            params: { buscar: busquedaProductos.value } 
        });
        productos.value = data.data || data;
    } catch (error) {
        console.error("Error cargando productos:", error);
        Swal.fire('Error', 'No se pudo cargar el catálogo.', 'error');
    } finally {
        loadingProductos.value = false;
    }
};

// Acciones de los botones superiores
const abrirModalNuevoActivo = () => {
    Swal.fire('Info', 'Aquí abriremos el modal para registrar un nuevo activo físico', 'info');
};

const abrirModalNuevoProducto = () => {
    Swal.fire('Info', 'Aquí abriremos el modal para registrar un nuevo producto en el catálogo', 'info');
};

// Eliminación lógica usando tu servicio 'api'
const darDeBajaActivo = async (id) => {
    const result = await Swal.fire({
        title: '¿Dar de baja este activo?',
        text: "El equipo quedará inhabilitado del inventario activo.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Sí, dar de baja',
        cancelButtonText: 'Cancelar'
    });

    if (result.isConfirmed) {
        try {
            await api.delete(`/activos/${id}`);
            Swal.fire({ title: '¡Eliminado!', text: 'El activo ha sido dado de baja.', icon: 'success', timer: 1500, showConfirmButton: false });
            cargarActivos();
        } catch (error) {
            Swal.fire('Error', 'No se pudo procesar la baja', 'error');
        }
    }
};

onMounted(() => {
    cargarActivos();
    cargarProductos();
});
</script>

<template>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-[98%] mx-auto">
        
        <!-- Encabezado Principal -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 border-b border-slate-200 pb-5">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight flex items-center gap-2.5">
                    <Package class="w-7 h-7 text-indigo-600" />
                    Gestión de Inventario y Activos TI
                </h1>
                <p class="text-sm text-slate-500 mt-1">Control de equipos, celulares corporativos y líneas para campañas masivas.</p>
            </div>
            
            <!-- Botón de Acción Dinámico -->
            <div>
                <button v-if="tabActiva === 'activos'" @click="abrirModalNuevoActivo" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-xl text-sm font-medium shadow-sm transition flex items-center gap-2 cursor-pointer">
                    <Plus class="w-4 h-4" /> Registrar Nuevo Activo
                </button>
                <button v-else @click="abrirModalNuevoProducto" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 rounded-xl text-sm font-medium shadow-sm transition flex items-center gap-2 cursor-pointer">
                    <Plus class="w-4 h-4" /> Nuevo Producto / Modelo
                </button>
            </div>
        </div>

        <!-- Sistema de Pestañas (Tabs) -->
        <div class="flex space-x-2 border-b border-slate-200 mb-6">
            <button 
                type="button"
                @click="cambiarTab('activos')" 
                :class="tabActiva === 'activos' 
                    ? 'border-indigo-600 text-indigo-600 bg-indigo-50/50 font-semibold' 
                    : 'border-transparent text-slate-500 hover:text-slate-700 hover:bg-slate-50'"
                class="py-3 px-5 border-b-2 text-sm transition rounded-t-xl flex items-center gap-2.5 focus:outline-none cursor-pointer">
                <Laptop class="w-4 h-4" />
                Activos Físicos (Equipos & Celulares)
            </button>
            <button 
                type="button"
                @click="cambiarTab('productos')" 
                :class="tabActiva === 'productos' 
                    ? 'border-indigo-600 text-indigo-600 bg-indigo-50/50 font-semibold' 
                    : 'border-transparent text-slate-500 hover:text-slate-700 hover:bg-slate-50'"
                class="py-3 px-5 border-b-2 text-sm transition rounded-t-xl flex items-center gap-2.5 focus:outline-none cursor-pointer">
                <Layers class="w-4 h-4" />
                Catálogo de Productos
            </button>
        </div>

        <!-- ================= TAB 1: ACTIVOS FÍSICOS ================= -->
        <div v-show="tabActiva === 'activos'" class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div class="relative w-full md:w-96">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                        <Search class="w-4 h-4" />
                    </span>
                    <input 
                        type="text" 
                        v-model="busquedaActivos" 
                        @input="cargarActivos"
                        placeholder="Buscar por código, serie o teléfono..." 
                        class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    />
                </div>
            </div>

            <div class="overflow-x-auto w-full">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 text-slate-600 text-xs font-semibold uppercase tracking-wider border-b border-slate-200">
                            <th class="py-3.5 px-6">Código / Serie</th>
                            <th class="py-3.5 px-6">Producto / Modelo</th>
                            <th class="py-3.5 px-6">Línea / Operador</th>
                            <th class="py-3.5 px-6">Estado Físico</th>
                            <th class="py-3.5 px-6">Asignado a</th>
                            <th class="py-3.5 px-6 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        <tr v-for="activo in activos" :key="activo.id" class="hover:bg-slate-50/60 transition">
                            <td class="py-4 px-6">
                                <span class="font-semibold text-slate-800">{{ activo.codigoPatrimonial }}</span>
                                <span class="block text-xs text-slate-400 mt-0.5">Serie: {{ activo.numeroSerie }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="font-medium text-slate-700">{{ activo.producto?.nombre }}</span>
                                <span class="block text-xs text-slate-400 mt-0.5">{{ activo.producto?.marca }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <div v-if="activo.numeroTelefono" class="flex items-center gap-1.5 text-indigo-600 font-medium">
                                    <PhoneCall class="w-3.5 h-3.5" />
                                    <span>{{ activo.numeroTelefono }}</span>
                                </div>
                                <span v-if="activo.operador" class="text-xs text-slate-500 block mt-0.5">Operador: {{ activo.operador }}</span>
                                <span v-if="!activo.numeroTelefono" class="text-slate-400 text-xs italic">Sin línea</span>
                            </td>
                            <td class="py-4 px-6">
                                <span :class="{
                                    'bg-emerald-50 text-emerald-700 border-emerald-200': activo.estadoFisico === 'operativo',
                                    'bg-amber-50 text-amber-700 border-amber-200': activo.estadoFisico === 'reparacion',
                                    'bg-rose-50 text-rose-700 border-rose-200': activo.estadoFisico === 'averiado'
                                }" class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium border uppercase tracking-wide">
                                    <CheckCircle2 v-if="activo.estadoFisico === 'operativo'" class="w-3 h-3" />
                                    <Wrench v-else-if="activo.estadoFisico === 'reparacion'" class="w-3 h-3" />
                                    <AlertTriangle v-else class="w-3 h-3" />
                                    {{ activo.estadoFisico }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-slate-600 font-medium">
                                {{ activo.usuarioAsignado }}
                            </td>
                            <td class="py-4 px-6 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button class="p-1.5 text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition" title="Editar">
                                        <Edit3 class="w-4 h-4" />
                                    </button>
                                    <button @click="darDeBajaActivo(activo.id)" class="p-1.5 text-slate-500 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Dar de baja">
                                        <Trash2 class="w-4 h-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="activos.length === 0">
                            <td colspan="6" class="py-12 text-center text-slate-400 italic">No se encontraron activos registrados.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ================= TAB 2: PRODUCTOS (CATÁLOGO) ================= -->
        <div v-show="tabActiva === 'productos'" class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div class="relative w-full md:w-96">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                        <Search class="w-4 h-4" />
                    </span>
                    <input 
                        type="text" 
                        v-model="busquedaProductos" 
                        @input="cargarProductos"
                        placeholder="Buscar por nombre, marca o categoría..." 
                        class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    />
                </div>
            </div>

            <div class="overflow-x-auto w-full">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 text-slate-600 text-xs font-semibold uppercase tracking-wider border-b border-slate-200">
                            <th class="py-3.5 px-6">Categoría</th>
                            <th class="py-3.5 px-6">Nombre / Modelo</th>
                            <th class="py-3.5 px-6">Marca</th>
                            <th class="py-3.5 px-6">Stock Mínimo</th>
                            <th class="py-3.5 px-6 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        <tr v-for="prod in productos" :key="prod.id" class="hover:bg-slate-50/60 transition">
                            <td class="py-4 px-6">
                                <span class="bg-purple-50 text-purple-700 border border-purple-200 px-3 py-1 rounded-full text-xs font-medium">
                                    {{ prod.categoria || 'Hardware' }}
                                </span>
                            </td>
                            <td class="py-4 px-6 font-semibold text-slate-800">{{ prod.nombre }}</td>
                            <td class="py-4 px-6 text-slate-600">{{ prod.marca }}</td>
                            <td class="py-4 px-6 text-slate-600 font-medium">{{ prod.stockMinimo }} un.</td>
                            <td class="py-4 px-6 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button class="p-1.5 text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition" title="Editar">
                                        <Edit3 class="w-4 h-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="productos.length === 0">
                            <td colspan="5" class="py-12 text-center text-slate-400 italic">No hay productos en el catálogo.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</template>