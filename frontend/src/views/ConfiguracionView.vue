<template>
  <div class="p-8 bg-slate-50 min-h-screen">
    <h1 class="text-2xl font-bold text-slate-800 mb-6">Administración del Sistema</h1>
    
    <div class="flex gap-4 mb-6 border-b border-slate-200">
      <button @click="activeTab = 'roles'" 
              :class="activeTab === 'roles' ? 'bg-blue-600 text-white' : 'text-slate-500'"
              class="px-4 py-2 font-semibold rounded-lg transition-all">Roles</button>
      
      <button @click="activeTab = 'areas'" 
              :class="activeTab === 'areas' ? 'bg-blue-600 text-white' : 'text-slate-500'"
              class="px-4 py-2 font-semibold rounded-lg transition-all">Areas</button>
      
      <button @click="activeTab = 'carteras'" 
              :class="activeTab === 'carteras' ? 'bg-blue-600 text-white' : 'text-slate-500'"
              class="px-4 py-2 font-semibold rounded-lg transition-all">Carteras</button>
    </div>

    <component :is="tabs[activeTab]" />
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import RolesComponent from '@/components/RolComponent.vue';
import AreasComponent from '@/components/AreasComponent.vue';
import CarterasComponent from '@/components/CarterasComponent.vue';

const activeTab = ref('roles');

// Objeto de mapeo: clave = string del botón, valor = componente importado
const tabs = {
  roles: RolesComponent,
  areas: AreasComponent,
  carteras: CarterasComponent
};
</script>