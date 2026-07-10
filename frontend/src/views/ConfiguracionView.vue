<template>
  <div class="p-8 bg-slate-50 min-h-screen">
    <h1 class="text-2xl font-bold text-slate-800 mb-6">Administración del Sistema</h1>
    
    <div class="flex gap-4 mb-6 border-b border-slate-200">
      <button @click="activeTab = 'roles'" 
              :class="activeTab === 'roles' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-slate-500'"
              class="px-4 py-2 font-semibold">Roles</button>
      <button @click="activeTab = 'areas'" 
              :class="activeTab === 'areas' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-slate-500'"
              class="px-4 py-2 font-semibold">Áreas</button>
    </div>

    <component :is="activeTab === 'roles' ? RolesComponent : AreasComponent" />
  </div>
</template>

<script setup>
import { ref } from 'vue';
import RolesComponent from '@/components/RolComponent.vue';
import AreasComponent from '@/components/AreasComponent.vue';

const activeTab = ref('roles');
</script>