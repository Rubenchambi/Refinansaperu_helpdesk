<template>
  <div class="p-6 bg-slate-50 min-h-screen">
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-slate-800">Dashboard</h1>
      <p class="text-slate-500">Resumen general del sistema de tickets</p>
    </div>

      <div class="flex justify-between items-center mb-6">
          <div>
            <h1 class="text-2xl font-bold text-slate-800">Dashboard</h1>
          </div>
          
          <router-link 
            to="/tickets/crear" 
            class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded"
          >
            + Nuevo Ticket
          </router-link>
        </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
      <div class="bg-blue-600 p-4 rounded-lg text-white">
        <p class="text-sm">Abiertos</p>
        <p class="text-3xl font-bold">0</p>
      </div>
      <div class="bg-orange-500 p-4 rounded-lg text-white">
        <p class="text-sm">Pendientes</p>
        <p class="text-3xl font-bold">0</p>
      </div>
      <div class="bg-green-500 p-4 rounded-lg text-white">
        <p class="text-sm">Resueltos</p>
        <p class="text-3xl font-bold">0</p>
      </div>
      <div class="bg-slate-800 p-4 rounded-lg text-white">
        <p class="text-sm">Total Activos</p>
        <p class="text-3xl font-bold">0</p>
      </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
      <h2 class="font-bold text-lg mb-4">Resultados (0 tickets)</h2>
      <div class="overflow-x-auto">
        <table class="w-full text-left">
          <thead class="bg-slate-800 text-white">
            <tr>
              <th class="p-3">SLA</th>
              <th class="p-3">ID</th>
              <th class="p-3">ASUNTO</th>
              <th class="p-3">CLIENTE</th>
              <th class="p-3">ESTADO</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td colspan="5" class="p-4 text-center text-slate-400">No se encontraron tickets.</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>