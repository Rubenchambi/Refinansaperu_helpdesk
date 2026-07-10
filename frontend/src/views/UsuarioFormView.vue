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
            <input v-model="form.dni" length="6"  placeholder="Documento de Identidad" class="w-full p-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none" required>
            <input v-model="form.email"  type="email" placeholder="Correo Electrónico" class="w-full p-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none" required>
            <input v-model="form.password" minlength="6" type="password" :placeholder="form.id ? 'Deja en blanco para no cambiar' : 'Contraseña'" :required="!form.id" class="w-full p-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
            
            <select v-model="form.rolId" class="w-full p-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none" required>
              <option value="" disabled>Seleccione un Rol</option>
              <option v-for="rol in roles" :key="rol.id" :value="rol.id">{{ rol.nombre }}</option>
            </select>
          </div>
        </div>

        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
          <h2 class="text-lg font-semibold text-slate-700 mb-4 flex items-center gap-2">
            <BuildingOfficeIcon class="w-5 h-5 text-blue-600" /> Asignación de Áreas
          </h2>
          <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <label v-for="area in areas" :key="area.id" 
                   class="flex items-center p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-blue-50 transition">
              <input type="checkbox" :value="Number(area.id)" v-model="form.area_ids" class="w-4 h-4 text-blue-600 rounded border-slate-300 mr-3">
              <span class="text-sm font-medium text-slate-700">{{ area.nombre }}</span>
            </label>
          </div>
          
          <button 
            v-if="areaPage < totalAreaPages"
            type="button" 
            @click="fetchAreas(areaPage + 1)"
            class="mt-4 text-xs font-bold text-blue-600 hover:text-blue-800 transition uppercase tracking-wider underline"
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
import { ref, onMounted, nextTick } from 'vue';
import api from '@/services/api';
import Swal from 'sweetalert2';
import { useRoute, useRouter } from 'vue-router';
import { UserIcon, ArrowLeftIcon, IdentificationIcon, BuildingOfficeIcon, CheckIcon, PlusCircleIcon } from '@heroicons/vue/24/outline';

const route = useRoute();
const router = useRouter();

const form = ref({ 
    id: null, nombre: '', apellidos: '', dni: '', email: '', password: '', rolId: '', area_ids: [] 
});
const areas = ref([]);
const roles = ref([]);
const areaPage = ref(1);
const totalAreaPages = ref(1);

const fetchAreas = async (page = 1) => {
    try {
        const { data } = await api.get(`/areas?page=${page}`);
        if (page === 1) {
            areas.value = data.data;
        } else {
            areas.value = [...areas.value, ...data.data];
        }
        areaPage.value = data.current_page;
        totalAreaPages.value = data.last_page;
    } catch (error) {
        console.error("Error al cargar áreas:", error);
    }
};

const fetchData = async () => {
    try {
        const [resRoles] = await Promise.all([api.get('/roles')]);
        roles.value = resRoles.data.data;
        await fetchAreas(1);
    } catch (error) {
        console.error("Error al cargar selects:", error);
    }
};

onMounted(async () => {
    await fetchData();
    if (route.params.id) {
        try {
            const res = await api.get(`/usuarios/${route.params.id}`);
            const data = res.data.data;
            form.value = {
                id: data.id,
                nombre: data.nombre,
                apellidos: data.apellidos,
                dni: data.dni,
                email: data.email,
                rolId: data.rol_id,
                area_ids: data.areas ? data.areas.map(a => Number(a.id)) : []
            };
        } catch (error) {
            Swal.fire('Error', 'No se pudieron cargar los datos del usuario', 'error');
        }
    }
});

const guardarUsuario = async () => {
    try {
        if (form.value.id) {
            await api.put(`/usuarios/${form.value.id}`, form.value);
            Swal.fire('¡Éxito!', 'Usuario actualizado correctamente.', 'success');
        } else {
            await api.post('/usuarios', form.value);
            Swal.fire('¡Éxito!', 'Usuario registrado correctamente.', 'success');
        }
        router.push('/usuarios');
    } catch (error) {
        // AQUÍ ESTÁ EL CAMBIO IMPORTANTE:
        if (error.response && error.response.data.errors) {
            console.log("Errores de validación del servidor:", error.response.data.errors);
            // Esto te dirá exactamente qué campo está fallando (ej: 'area_ids' o 'rolId')
        } else {
            console.log("Error general:", error);
        }
        Swal.fire('Error', 'Verifica los campos obligatorios.', 'error');
    }

};
</script>