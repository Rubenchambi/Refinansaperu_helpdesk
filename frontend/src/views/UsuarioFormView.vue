<template>
  <div class="p-8 bg-slate-50 min-h-screen">
    <div class="max-w-5xl mx-auto">
      <div class="flex justify-between items-end mb-6">
        <div>
          <h1 class="text-3xl font-bold text-slate-800 flex items-center gap-3">
            <span class="bg-blue-600 p-2 rounded-xl text-white">
              <UserIcon class="w-7 h-7" />
            </span>
            {{ form.id ? 'Editar Usuario' : 'Nuevo Usuario' }}
          </h1>
          <p class="text-slate-500 mt-2">Configura los accesos y datos personales del personal.</p>
        </div>
        <button @click="router.back()" class="flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 rounded-lg shadow-sm hover:border-blue-400 transition">
          <ArrowLeftIcon class="w-4 h-4" /> Volver
        </button>
      </div>

      <form @submit.prevent="guardarUsuario" class="space-y-5">
        
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
          <h2 class="text-lg font-semibold text-slate-700 mb-4 flex items-center gap-2">
            <IdentificationIcon class="w-5 h-5 text-blue-600" /> Información Personal
          </h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <input v-model="form.nombre" placeholder="Nombres" class="w-full p-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none" required>
            <input v-model="form.apellidos" placeholder="Apellidos" class="w-full p-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none" required>
            <input v-model="form.dni" placeholder="Documento de Identidad" class="w-full p-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none" required>
            <input v-model="form.email" type="email" placeholder="Correo Electrónico" class="w-full p-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none" required>
            <input v-model="form.password" type="password" :placeholder="form.id ? 'Deja en blanco para no cambiar' : 'Contraseña'" :required="!form.id" class="w-full p-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
            <select v-model="form.rolId" class="w-full p-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none" required>
              <option value="" disabled >Seleccione un Rol</option>
              <option v-for="rol in roles" :key="rol.id" :value="rol.id">{{ rol.nombre }}</option>
            </select>
          </div>
        </div>

        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
          <h2 class="text-lg font-semibold text-slate-700 mb-4 flex items-center gap-2">
            <BuildingOfficeIcon class="w-5 h-5 text-blue-600" /> Asignación de Áreas
          </h2>
          <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <label v-for="area in areas" :key="area.id" class="flex items-center p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-blue-50 transition">
              <input type="checkbox" :value="Number(area.id)" v-model="form.area_ids" class="w-4 h-4 text-blue-600 rounded border-slate-300 mr-3">
              <span class="text-sm font-medium text-slate-700">{{ area.nombre }}</span>
            </label>
          </div>
          <button v-if="areaPage < totalAreaPages" type="button" @click="fetchAreas(areaPage + 1)" class="mt-4 text-xs font-bold text-blue-600 hover:text-blue-800 transition uppercase tracking-wider underline">
            <PlusCircleIcon class="w-6 h-6" />
          </button>
        </div>

        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
          <h2 class="text-lg font-semibold text-slate-700 mb-4 flex items-center gap-2">
            <BriefcaseIcon class="w-5 h-5 text-emerald-600" /> Asignación de Carteras
          </h2>
          
          <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <label v-for="cartera in carteras" :key="cartera.id" 
                  class="flex items-center p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-emerald-50 transition">
              
              <input 
                  type="checkbox" 
                  :value="Number(cartera.id)" 
                  v-model="form.cartera_ids" 
                  class="w-4 h-4 text-emerald-600 rounded border-slate-300 mr-3"
              >
              
              <span class="text-sm font-medium text-slate-700">{{ cartera.nombre_cartera }}</span>
            </label>
          </div>
          
          <button 
            v-if="carteraPage < totalCarteraPages" 
            type="button" 
            @click="fetchCarteras(carteraPage + 1)" 
            class="mt-4 text-xs font-bold text-emerald-600 hover:text-emerald-800 transition uppercase tracking-wider underline flex items-center gap-2"
          >
            <PlusCircleIcon class="w-6 h-6" />
          </button>
        </div>

        <div class="flex justify-end gap-4 pt-4">
          <button type="button" @click="router.back()" class="px-8 py-3 text-slate-600 font-semibold hover:bg-slate-200 rounded-xl transition">Cancelar</button>
          <button type="submit" class="px-8 py-3 bg-blue-600 text-white font-semibold rounded-xl shadow-lg shadow-blue-200 hover:bg-blue-700 transition flex items-center gap-2">
            <CheckIcon class="w-5 h-5" /> {{ form.id ? 'Actualizar Cambios' : 'Guardar Usuario' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '@/services/api';
import Swal from 'sweetalert2';
import { useRoute, useRouter } from 'vue-router';
import { UserIcon, ArrowLeftIcon, IdentificationIcon, BuildingOfficeIcon, BriefcaseIcon, CheckIcon, PlusCircleIcon } from '@heroicons/vue/24/outline';

const route = useRoute();
const router = useRouter();

const form = ref({ 
    id: null, nombre: '', apellidos: '', dni: '', email: '', password: '', rolId: '', area_ids: [], cartera_ids: [] 
});

const areas = ref([]);
const carteras = ref([]);
const roles = ref([]);
const areaPage = ref(1);
const totalAreaPages = ref(1);
const carteraPage = ref(1);
const totalCarteraPages = ref(1);

const fetchAreas = async (page = 1) => {
    const { data } = await api.get(`/areas?page=${page}`);
    areas.value = page === 1 ? data.data : [...areas.value, ...data.data];
    areaPage.value = data.current_page;
    totalAreaPages.value = data.last_page;
};

const fetchCarteras = async (page = 1) => {
    const { data } = await api.get(`/carteras?page=${page}`);
    carteras.value = page === 1 ? data.data : [...carteras.value, ...data.data];
    carteraPage.value = data.current_page;
    totalCarteraPages.value = data.last_page;
};

const fetchData = async () => {
  try {
    const [resRoles] = await Promise.all([api.get('/roles')]);
    roles.value = resRoles.data.data;
    await fetchAreas(1);
    await fetchCarteras(1);
  }catch  (error){
    console.error("Error al cargar selects:", error);   
  }
};

onMounted(async () => {
    try {
        await fetchData();
        
        if (route.params.id) {
            const { data } = await api.get(`/usuarios/${route.params.id}`);
            const user = data.data;
            const nombresUsuario = user.carteras || [];

            form.value = {
                id: user.id,
                nombre: user.nombre,
                apellidos: user.apellidos,
                dni: user.dni,
                email: user.email,
                rolId: user.rol_id,
                area_ids: user.areas ? user.areas.map(a => Number(a.id)) : [],
                cartera_ids: carteras.value
                    .filter(c => nombresUsuario.includes(c.nombre_cartera))
                    .map(c => Number(c.id))
            };
        }
    } catch (error) {
        Swal.fire('Error', 'No se pudieron cargar los datos del usuario', 'error');
    }
});

const guardarUsuario = async () => {
    try {
        form.value.id ? await api.put(`/usuarios/${form.value.id}`, form.value) : await api.post('/usuarios', form.value);
             
        Swal.fire('¡Éxito!', 'Guardado correctamente', 'success');
        router.push('/usuarios');
    } catch (error) {
        Swal.fire('Error', 'Verifica los campos obligatorios.', 'error');
    }
};
</script>