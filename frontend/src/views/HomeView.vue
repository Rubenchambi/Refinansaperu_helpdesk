<template>
  <div class="p-6 bg-slate-50 min-h-screen">
    
    <!-- HEADER -->
    <div class="mb-2">
      <h1 class="text-2xl font-bold text-slate-800">Dashboard</h1>
    </div>

    <div class="flex justify-between items-center mb-6">
      <div>
        <p class="text-slate-500">Resumen general del sistema de tickets</p>
      </div>
      
      <router-link 
        to="/tickets/crear" 
        class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded transition-all duration-300 shadow-sm hover:shadow-md flex items-center gap-2"
      >
        <i data-lucide="plus" class="w-4 h-4"></i>
        Nuevo Ticket
      </router-link>
    </div>

    <!-- TARJETAS DE ESTADÍSTICAS -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-8">
      
      <!-- Total -->
      <div class="bg-purple-50 rounded-xl shadow-sm border border-purple-100 overflow-hidden hover:shadow-md transition-all duration-300">
        <div class="flex h-full">
          <div class="w-1.5 bg-purple-500"></div>
          <div class="flex-1 p-5">
            <div class="flex items-center justify-between mb-2">
              <p class="text-[10px] font-bold tracking-[0.15em] text-purple-400 uppercase">Total Tickets</p>
              <i data-lucide="layers" class="w-4 h-4 text-purple-400"></i>
            </div>
            <p class="text-4xl font-extrabold text-purple-600 tracking-tight">10</p>
            <p class="text-[11px] text-purple-400 mt-2">Tickets activos</p>
          </div>
        </div>
      </div>

      <!-- Abiertos -->
      <div class="bg-red-50 rounded-xl shadow-sm border border-red-100 overflow-hidden hover:shadow-md transition-all duration-300">
        <div class="flex h-full">
          <div class="w-1.5 bg-red-500"></div>
          <div class="flex-1 p-5">
            <div class="flex items-center justify-between mb-2">
              <p class="text-[10px] font-bold tracking-[0.15em] text-red-400 uppercase">Abiertos</p>
              <i data-lucide="alert-circle" class="w-4 h-4 text-red-400"></i>
            </div>
            <p class="text-4xl font-extrabold text-red-500 tracking-tight">3</p>
            <p class="text-[11px] text-red-400 mt-2">Esperando asignación</p>
          </div>
        </div>
      </div>

      <!-- Pendientes -->
      <div class="bg-yellow-50 rounded-xl shadow-sm border border-yellow-100 overflow-hidden hover:shadow-md transition-all duration-300">
        <div class="flex h-full">
          <div class="w-1.5 bg-yellow-500"></div>
          <div class="flex-1 p-5">
            <div class="flex items-center justify-between mb-2">
              <p class="text-[10px] font-bold tracking-[0.15em] text-yellow-400 uppercase">Pendientes</p>
              <i data-lucide="clock" class="w-4 h-4 text-yellow-400"></i>
            </div>
            <p class="text-4xl font-extrabold text-yellow-500 tracking-tight">6</p>
            <p class="text-[11px] text-yellow-400 mt-2">En progreso con técnico</p>
          </div>
        </div>
      </div>

      <!-- Resueltos -->
      <div class="bg-green-50 rounded-xl shadow-sm border border-green-100 overflow-hidden hover:shadow-md transition-all duration-300">
        <div class="flex h-full">
          <div class="w-1.5 bg-green-500"></div>
          <div class="flex-1 p-5">
            <div class="flex items-center justify-between mb-2">
              <p class="text-[10px] font-bold tracking-[0.15em] text-green-400 uppercase">Resueltos</p>
              <i data-lucide="check-circle-2" class="w-4 h-4 text-green-400"></i>
            </div>
            <p class="text-4xl font-extrabold text-green-500 tracking-tight">4</p>
            <p class="text-[11px] text-green-400 mt-2">Tickets finalizados</p>
          </div>
        </div>
      </div>

    </div>

    <!-- GRID PRINCIPAL: GRÁFICO + TABLA -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
      
      <!-- GRÁFICO DONUT - DISTRIBUCIÓN DE TICKETS -->
      <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
          <i data-lucide="pie-chart" class="w-5 h-5 text-slate-500"></i>
          Distribución de Tickets
        </h3>
        <div class="flex items-center justify-center">
          <div class="relative w-48 h-48">
            <svg viewBox="0 0 100 100" class="w-full h-full transform -rotate-90">
              <circle cx="50" cy="50" r="40" fill="none" stroke="#f1f5f9" stroke-width="12" />
              <circle cx="50" cy="50" r="40" fill="none" stroke="#ef4444" stroke-width="12" 
                stroke-dasharray="57.6 251.2" stroke-dashoffset="0" stroke-linecap="round" />
              <circle cx="50" cy="50" r="40" fill="none" stroke="#eab308" stroke-width="12" 
                stroke-dasharray="115.5 251.2" stroke-dashoffset="-57.6" stroke-linecap="round" />
              <circle cx="50" cy="50" r="40" fill="none" stroke="#22c55e" stroke-width="12" 
                stroke-dasharray="77.8 251.2" stroke-dashoffset="-173.1" stroke-linecap="round" />
            </svg>
            <div class="absolute inset-0 flex flex-col items-center justify-center">
              <span class="text-3xl font-extrabold text-slate-800">13</span>
              <span class="text-xs text-slate-400">Total</span>
            </div>
          </div>
        </div>
        <div class="mt-4 space-y-2">
          <div class="flex items-center justify-between text-sm">
            <div class="flex items-center gap-2">
              <span class="w-3 h-3 rounded-full bg-red-500"></span>
              <span class="text-slate-600">Abiertos</span>
            </div>
            <span class="font-semibold text-slate-800">3</span>
          </div>
          <div class="flex items-center justify-between text-sm">
            <div class="flex items-center gap-2">
              <span class="w-3 h-3 rounded-full bg-yellow-500"></span>
              <span class="text-slate-600">Pendientes</span>
            </div>
            <span class="font-semibold text-slate-800">6</span>
          </div>
          <div class="flex items-center justify-between text-sm">
            <div class="flex items-center gap-2">
              <span class="w-3 h-3 rounded-full bg-green-500"></span>
              <span class="text-slate-600">Resueltos</span>
            </div>
            <span class="font-semibold text-slate-800">4</span>
          </div>
        </div>
      </div>

      <!-- TICKETS RECIENTES / ACTIVIDAD -->
      <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
          <i data-lucide="activity" class="w-5 h-5 text-slate-500"></i>
          Actividad Reciente
        </h3>
        <div class="space-y-4">
          <div class="flex items-start gap-3">
            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center shrink-0 mt-0.5">
              <i data-lucide="ticket" class="w-4 h-4 text-blue-600"></i>
            </div>
            <div class="flex-1">
              <p class="text-sm text-slate-800 font-medium">Nuevo ticket creado</p>
              <p class="text-xs text-slate-500">#TK-1024 - Problema con servidor</p>
              <p class="text-[10px] text-slate-400 mt-1">Hace 5 minutos</p>
            </div>
          </div>
          <div class="flex items-start gap-3">
            <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center shrink-0 mt-0.5">
              <i data-lucide="check-circle" class="w-4 h-4 text-green-600"></i>
            </div>
            <div class="flex-1">
              <p class="text-sm text-slate-800 font-medium">Ticket resuelto</p>
              <p class="text-xs text-slate-500">#TK-1018 - Actualización de software</p>
              <p class="text-[10px] text-slate-400 mt-1">Hace 32 minutos</p>
            </div>
          </div>
          <div class="flex items-start gap-3">
            <div class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center shrink-0 mt-0.5">
              <i data-lucide="user-check" class="w-4 h-4 text-yellow-600"></i>
            </div>
            <div class="flex-1">
              <p class="text-sm text-slate-800 font-medium">Asignado a técnico</p>
              <p class="text-xs text-slate-500">#TK-1020 → Carlos Mendez</p>
              <p class="text-[10px] text-slate-400 mt-1">Hace 1 hora</p>
            </div>
          </div>
          <div class="flex items-start gap-3">
            <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center shrink-0 mt-0.5">
              <i data-lucide="message-square" class="w-4 h-4 text-purple-600"></i>
            </div>
            <div class="flex-1">
              <p class="text-sm text-slate-800 font-medium">Comentario agregado</p>
              <p class="text-xs text-slate-500">#TK-1015 - Revisando logs...</p>
              <p class="text-[10px] text-slate-400 mt-1">Hace 2 horas</p>
            </div>
          </div>
        </div>
      </div>

      <!-- MÉTRICAS SLA -->
      <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
          <i data-lucide="gauge" class="w-5 h-5 text-slate-500"></i>
          Métricas SLA
        </h3>
        <div class="space-y-5">
          <div>
            <div class="flex justify-between items-center mb-2">
              <span class="text-sm text-slate-600">Tiempo promedio de respuesta</span>
              <span class="text-sm font-bold text-slate-800">12 min</span>
            </div>
            <div class="w-full bg-slate-100 rounded-full h-2.5">
              <div class="bg-blue-500 h-2.5 rounded-full" style="width: 75%"></div>
            </div>
          </div>
          <div>
            <div class="flex justify-between items-center mb-2">
              <span class="text-sm text-slate-600">Tickets resueltos a tiempo</span>
              <span class="text-sm font-bold text-slate-800">85%</span>
            </div>
            <div class="w-full bg-slate-100 rounded-full h-2.5">
              <div class="bg-green-500 h-2.5 rounded-full" style="width: 85%"></div>
            </div>
          </div>
          <div>
            <div class="flex justify-between items-center mb-2">
              <span class="text-sm text-slate-600">Satisfacción del cliente</span>
              <span class="text-sm font-bold text-slate-800">4.2/5.0</span>
            </div>
            <div class="w-full bg-slate-100 rounded-full h-2.5">
              <div class="bg-yellow-500 h-2.5 rounded-full" style="width: 84%"></div>
            </div>
          </div>
          <div>
            <div class="flex justify-between items-center mb-2">
              <span class="text-sm text-slate-600">Tickets críticos pendientes</span>
              <span class="text-sm font-bold text-red-600">2</span>
            </div>
            <div class="w-full bg-slate-100 rounded-full h-2.5">
              <div class="bg-red-500 h-2.5 rounded-full" style="width: 20%"></div>
            </div>
          </div>
        </div>
      </div>

    </div>

    <!-- TABLA DE TICKETS MEJORADA -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
      
      <!-- HEADER DE TABLA CON BÚSQUEDA Y FILTROS -->
      <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h2 class="font-bold text-lg text-slate-800">Tickets del Sistema</h2>
          <p class="text-sm text-slate-500">Gestiona y visualiza todos los tickets</p>
        </div>
        <div class="flex items-center gap-3">
          <div class="relative">
            <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
            <input 
              type="text" 
              placeholder="Buscar ticket..." 
              class="pl-9 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent w-64"
            />
          </div>
          <select class="px-4 py-2 border border-gray-200 rounded-lg text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
            <option>Todos los estados</option>
            <option>Abiertos</option>
            <option>Pendientes</option>
            <option>Resueltos</option>
          </select>
        </div>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left">
          <thead class="bg-slate-800 text-white">
            <tr>
              <th class="p-4 text-xs font-semibold tracking-wider uppercase">ID</th>
              <th class="p-4 text-xs font-semibold tracking-wider uppercase">Asunto</th>
              <th class="p-4 text-xs font-semibold tracking-wider uppercase">Cliente</th>
              <th class="p-4 text-xs font-semibold tracking-wider uppercase">Estado</th>
              <th class="p-4 text-xs font-semibold tracking-wider uppercase">Prioridad</th>
              <th class="p-4 text-xs font-semibold tracking-wider uppercase">Asignado</th>
              <th class="p-4 text-xs font-semibold tracking-wider uppercase">Creado</th>
              <th class="p-4 text-xs font-semibold tracking-wider uppercase text-center">Acciones</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            
            <!-- FILA 1 -->
            <tr class="hover:bg-slate-50 transition-colors">
              <td class="p-4 text-sm font-medium text-slate-700">#TK-1024</td>
              <td class="p-4 text-sm text-slate-800 font-medium">Error en servidor de correo</td>
              <td class="p-4 text-sm text-slate-600">Acme Corp</td>
              <td class="p-4">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">
                  <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1.5"></span>
                  Abierto
                </span>
              </td>
              <td class="p-4">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700 border border-red-200">
                  Alta
                </span>
              </td>
              <td class="p-4 text-sm text-slate-600 flex items-center gap-2">
                <div class="w-6 h-6 rounded-full bg-blue-500 text-white text-xs flex items-center justify-center font-bold">CM</div>
                Carlos M.
              </td>
              <td class="p-4 text-sm text-slate-500">08/07/2026</td>
              <td class="p-4">
                <div class="flex justify-center gap-2">
                  <button class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-500 hover:text-white transition-all shadow-sm" title="Ver detalle">
                    <i data-lucide="eye" class="w-4 h-4"></i>
                  </button>
                  <button @click="irAEditar(ticket)" class="p-2 bg-yellow-50 text-yellow-600 rounded-lg hover:bg-yellow-500 hover:text-white transition-all shadow-sm" title="Editar">
                    <i data-lucide="pencil" class="w-4 h-4"></i>
                  </button>
                  <button @click="eliminarTicket(ticket.id)" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-all shadow-sm" title="Eliminar">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                  </button>
                </div>
              </td>
            </tr>

            <!-- FILA 2 -->
            <tr class="hover:bg-slate-50 transition-colors">
              <td class="p-4 text-sm font-medium text-slate-700">#TK-1023</td>
              <td class="p-4 text-sm text-slate-800 font-medium">Actualización de licencias</td>
              <td class="p-4 text-sm text-slate-600">Tech Solutions</td>
              <td class="p-4">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                  <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 mr-1.5"></span>
                  Pendiente
                </span>
              </td>
              <td class="p-4">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700 border border-blue-200">
                  Media
                </span>
              </td>
              <td class="p-4 text-sm text-slate-600 flex items-center gap-2">
                <div class="w-6 h-6 rounded-full bg-green-500 text-white text-xs flex items-center justify-center font-bold">AL</div>
                Ana L.
              </td>
              <td class="p-4 text-sm text-slate-500">07/07/2026</td>
              <td class="p-4">
                <div class="flex justify-center gap-2">
                  <button class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-500 hover:text-white transition-all shadow-sm" title="Ver detalle">
                    <i data-lucide="eye" class="w-4 h-4"></i>
                  </button>
                  <button @click="irAEditar(ticket)" class="p-2 bg-yellow-50 text-yellow-600 rounded-lg hover:bg-yellow-500 hover:text-white transition-all shadow-sm" title="Editar">
                    <i data-lucide="pencil" class="w-4 h-4"></i>
                  </button>
                  <button @click="eliminarTicket(ticket.id)" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-all shadow-sm" title="Eliminar">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                  </button>
                </div>
              </td>
            </tr>

            <!-- FILA 3 -->
            <tr class="hover:bg-slate-50 transition-colors">
              <td class="p-4 text-sm font-medium text-slate-700">#TK-1022</td>
              <td class="p-4 text-sm text-slate-800 font-medium">Configuración de VPN</td>
              <td class="p-4 text-sm text-slate-600">Global Industries</td>
              <td class="p-4">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                  <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></span>
                  Resuelto
                </span>
              </td>
              <td class="p-4">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-700 border border-slate-200">
                  Baja
                </span>
              </td>
              <td class="p-4 text-sm text-slate-600 flex items-center gap-2">
                <div class="w-6 h-6 rounded-full bg-purple-500 text-white text-xs flex items-center justify-center font-bold">JR</div>
                Juan R.
              </td>
              <td class="p-4 text-sm text-slate-500">06/07/2026</td>
              <td class="p-4">
                <div class="flex justify-center gap-2">
                  <button class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-500 hover:text-white transition-all shadow-sm" title="Ver detalle">
                    <i data-lucide="eye" class="w-4 h-4"></i>
                  </button>
                  <button @click="irAEditar(ticket)" class="p-2 bg-yellow-50 text-yellow-600 rounded-lg hover:bg-yellow-500 hover:text-white transition-all shadow-sm" title="Editar">
                    <i data-lucide="pencil" class="w-4 h-4"></i>
                  </button>
                  <button @click="eliminarTicket(ticket.id)" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-all shadow-sm" title="Eliminar">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                  </button>
                </div>
              </td>
            </tr>

            <!-- FILA 4 -->
            <tr class="hover:bg-slate-50 transition-colors">
              <td class="p-4 text-sm font-medium text-slate-700">#TK-1021</td>
              <td class="p-4 text-sm text-slate-800 font-medium">Backup fallido</td>
              <td class="p-4 text-sm text-slate-600">DataSystems</td>
              <td class="p-4">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">
                  <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1.5"></span>
                  Abierto
                </span>
              </td>
              <td class="p-4">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700 border border-red-200">
                  Crítica
                </span>
              </td>
              <td class="p-4 text-sm text-slate-600 flex items-center gap-2">
                <div class="w-6 h-6 rounded-full bg-orange-500 text-white text-xs flex items-center justify-center font-bold">MP</div>
                María P.
              </td>
              <td class="p-4 text-sm text-slate-500">05/07/2026</td>
              <td class="p-4">
                <div class="flex justify-center gap-2">
                  <button class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-500 hover:text-white transition-all shadow-sm" title="Ver detalle">
                    <i data-lucide="eye" class="w-4 h-4"></i>
                  </button>
                  <button @click="irAEditar(ticket)" class="p-2 bg-yellow-50 text-yellow-600 rounded-lg hover:bg-yellow-500 hover:text-white transition-all shadow-sm" title="Editar">
                    <i data-lucide="pencil" class="w-4 h-4"></i>
                  </button>
                  <button @click="eliminarTicket(ticket.id)" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-all shadow-sm" title="Eliminar">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                  </button>
                </div>
              </td>
            </tr>

            <!-- FILA 5 -->
            <tr class="hover:bg-slate-50 transition-colors">
              <td class="p-4 text-sm font-medium text-slate-700">#TK-1020</td>
              <td class="p-4 text-sm text-slate-800 font-medium">Instalación de antivirus</td>
              <td class="p-4 text-sm text-slate-600">SecureNet</td>
              <td class="p-4">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                  <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 mr-1.5"></span>
                  Pendiente
                </span>
              </td>
              <td class="p-4">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-700 border border-slate-200">
                  Baja
                </span>
              </td>
              <td class="p-4 text-sm text-slate-600 flex items-center gap-2">
                <div class="w-6 h-6 rounded-full bg-blue-500 text-white text-xs flex items-center justify-center font-bold">CM</div>
                Carlos M.
              </td>
              <td class="p-4 text-sm text-slate-500">04/07/2026</td>
              <td class="p-4">
                <div class="flex justify-center gap-2">
                  <button class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-500 hover:text-white transition-all shadow-sm" title="Ver detalle">
                    <i data-lucide="eye" class="w-4 h-4"></i>
                  </button>
                  <button @click="irAEditar(ticket)" class="p-2 bg-yellow-50 text-yellow-600 rounded-lg hover:bg-yellow-500 hover:text-white transition-all shadow-sm" title="Editar">
                    <i data-lucide="pencil" class="w-4 h-4"></i>
                  </button>
                  <button @click="eliminarTicket(ticket.id)" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-all shadow-sm" title="Eliminar">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                  </button>
                </div>
              </td>
            </tr>

          </tbody>
        </table>
      </div>

      <!-- PAGINACIÓN -->
      <div class="p-4 border-t border-gray-100 flex items-center justify-between">
        <div class="text-sm text-slate-500">
          Mostrando <span class="font-semibold text-slate-700">1-5</span> de <span class="font-semibold text-slate-700">24</span> tickets
        </div>
        <div class="flex items-center gap-1">
          <button class="p-2 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-all disabled:opacity-50" disabled>
            <i data-lucide="chevron-left" class="w-5 h-5"></i>
          </button>
          <button class="px-3 py-1.5 rounded-lg bg-blue-500 text-white text-sm font-medium shadow-sm">1</button>
          <button class="px-3 py-1.5 rounded-lg text-slate-600 hover:bg-slate-100 text-sm font-medium transition-all">2</button>
          <button class="px-3 py-1.5 rounded-lg text-slate-600 hover:bg-slate-100 text-sm font-medium transition-all">3</button>
          <span class="px-2 text-slate-400">...</span>
          <button class="px-3 py-1.5 rounded-lg text-slate-600 hover:bg-slate-100 text-sm font-medium transition-all">5</button>
          <button class="p-2 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-all">
            <i data-lucide="chevron-right" class="w-5 h-5"></i>
          </button>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue';
import { createIcons, icons } from 'lucide';

onMounted(() => {
  createIcons({ icons });
});

const irAEditar = (ticket) => {
  console.log('Editar ticket:', ticket);
};

const eliminarTicket = (id) => {
  console.log('Eliminar ticket:', id);
};
</script>