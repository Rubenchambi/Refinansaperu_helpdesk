<template>
  <div class="p-8">
    <div class="max-w-6xl mx-auto">
      
      <div class="flex justify-between items-center mb-6">
        <div>
          <h1 class="text-2xl font-bold text-slate-800">
            {{ form.id ? 'Editar Cliente' : 'Añadir Nuevo Cliente' }}
          </h1>

          <pre>{{ form }}</pre>
          <p class="text-slate-500">Complete el formulario para gestionar la información.</p>
        </div>
        <button 
          @click="volver" 
          class="flex items-center gap-2 text-slate-600 hover:text-blue-600 font-semibold transition py-2 px-4 border border-slate-300 rounded-lg hover:border-blue-500"
        >
          <span>&larr; Volver a la lista</span>
        </button>
      </div>

      <form @submit.prevent="guardarCliente" class="bg-white p-8 rounded-lg shadow-sm border border-slate-200">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-slate-700 mb-1">Nombre Completo / Contacto *</label>
            <input v-model="form.contacto" type="text" class="w-full p-2 border border-slate-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-500" required>
          </div>

          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Correo Electrónico *</label>
            <input v-model="form.email" type="email" class="w-full p-2 border border-slate-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-500" required>
          </div>

          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Teléfono *</label>
            <input v-model="form.telefono" type="text" class="w-full p-2 border border-slate-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
          </div>

          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Empresa (Opcional)</label>
            <input v-model="form.razonSocial" type="text" class="w-full p-2 border border-slate-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
          </div>

          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Cargo *</label>
            <select v-model="form.cargo" class="w-full p-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" required>
              <option value="" disabled>Seleccione el Cargo</option>
              <option value="Gestor Call">Gestor Call</option>
              <option value="Gestor Campo">Gestor Campo</option>
              <option value="Supervisor">Supervisor</option>
              <option value="Coordinador">Coordinador</option>
              <option value="Administrativo">Administrativo</option>
              <option value="Gerencia">Gerencia</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Distrito</label>
            <input v-model="form.distrito" type="text" class="w-full p-2 border border-slate-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
          </div>

          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Ciudad</label>
            <input v-model="form.ciudad" type="text" class="w-full p-2 border border-slate-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
          </div>

          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Cartera Asignada</label>
            <input v-model="form.cartera_asignada" type="text" class="w-full p-2 border border-slate-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
          </div>

          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">DNI / RUC</label>
            <input v-model="form.ruc" type="text" class="w-full p-2 border border-slate-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
          </div>

          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-slate-700 mb-1">Dirección</label>
            <input v-model="form.direccion" type="text" class="w-full p-2 border border-slate-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
          </div>

        </div>

        <div class="mt-8 flex justify-end gap-4">
          <button type="button" @click="volver" class="px-6 py-2 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300 transition">Cancelar</button>
          <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            {{ form.id ? 'Actualizar Cliente' : 'Guardar Cliente' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';
import { useRouter } from 'vue-router';

const router = useRouter();
const form = ref({
  contacto: '', email: '', telefono: '', razonSocial: '', 
  distrito: '', ciudad: '', direccion: '', ruc: '', cargo: '', cartera_asignada: ''
});

// Recuperar datos desde el estado del router (lo que enviamos al hacer clic en editar)
onMounted(async () => {
  const idRecibido = window.history.state.cliente;
   console.log("ESTO ES LO QUE VIENE DE LA API:", idRecibido);
  if (idRecibido) {
    try {

      const response = await axios.get(`http://127.0.0.1:8000/api/clientes/${idRecibido}`);
      form.value = { ...response.data };
      const data = response.data;

      // Asignación campo por campo para asegurar reactividad
      form.value.contacto = data.contacto || '';
      form.value.email = data.email || '';
      form.value.telefono = data.telefono || '';
      form.value.razonSocial = data.razonSocial || '';
      form.value.cargo = data.cargo || '';
      form.value.distrito = data.distrito || '';
      form.value.ciudad = data.ciudad || '';
      form.value.cartera_asignada = data.cartera_asignada || '';
      form.value.ruc = data.ruc || '';
      form.value.direccion = data.direccion || '';
      form.value.id = data.id; // ¡Importante para que sepa que es edición!
      console.log("ESTO ES LO QUE VIENE DE LA API:", response.data);
      
      console.log("Formulario rellenado con:", form.value);
    } catch (error) {
      console.error("Error al traer datos:", error);
      Swal.fire('Error', 'No se pudieron cargar los datos.', 'error');
    }
  }
});

const volver = () => {
  router.push('/clientes');
};

const guardarCliente = async () => {
  try {
    const esEdicion = !!form.value.id;
    const url = esEdicion 
      ? `http://127.0.0.1:8000/api/clientes/${form.value.id}` 
      : 'http://127.0.0.1:8000/api/clientes';
    
    // Si es edición usamos PUT, si es nuevo usamos POST
    await axios[esEdicion ? 'put' : 'post'](url, form.value);

    await Swal.fire({
      icon: 'success',
      title: '¡Éxito!',
      text: esEdicion ? 'Cliente actualizado correctamente.' : 'Cliente registrado correctamente.',
      confirmButtonColor: '#2563eb'
    });

    router.push('/clientes');
  } catch (error) {
    Swal.fire('Error', 'No se pudo procesar la solicitud.', 'error');
  }
};
</script>