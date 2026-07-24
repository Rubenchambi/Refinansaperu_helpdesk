<template>
  <div class="p-8 bg-slate-50 min-h-screen">
    <h1 class="text-2xl font-bold text-slate-800 mb-6">Administración del Sistema</h1>
    
<div class="flex gap-2 mb-6 border-b border-slate-200 pb-2">
  <!-- Pestaña: ROLES -->
  <button @click="activeTab = 'roles'" 
          :class="activeTab === 'roles' ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-500 hover:bg-slate-100 hover:text-slate-700'"
          class="flex items-center gap-2 px-3 py-1.5 text-sm font-semibold rounded-lg transition-all duration-200">
   <ShieldCheck class="w-4 h-4" />
    Roles
  </button>
  
  <!-- Pestaña: ÁREAS -->
  <button @click="activeTab = 'areas'" 
          :class="activeTab === 'areas' ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-500 hover:bg-slate-100 hover:text-slate-700'"
          class="flex items-center gap-2 px-3 py-1.5 text-sm font-semibold rounded-lg transition-all duration-200">
    <Network class="w-4 h-4" />
    Áreas
  </button>
  
  <!-- Pestaña: CARTERAS -->
  <button @click="activeTab = 'carteras'" 
          :class="activeTab === 'carteras' ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-500 hover:bg-slate-100 hover:text-slate-700'"
          class="flex items-center gap-2 px-3 py-1.5 text-sm font-semibold rounded-lg transition-all duration-200">
    <Briefcase class="w-4 h-4" />
    Carteras
  </button>
</div>

    <component :is="tabs[activeTab]" />
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import RolesComponent from '@/components/RolComponent.vue';
import AreasComponent from '@/components/AreasComponent.vue';
import CarterasComponent from '@/components/CarterasComponent.vue';
import { ShieldCheck, Network, Briefcase } from 'lucide-vue-next';
const activeTab = ref('roles');

// Objeto de mapeo: clave = string del botón, valor = componente importado
const tabs = {
  roles: RolesComponent,
  areas: AreasComponent,
  carteras: CarterasComponent
};
</script>