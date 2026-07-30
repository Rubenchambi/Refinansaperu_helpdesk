import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

// Componentes principales cargados de forma estática
import LoginView from '../views/LoginView.vue'
import HomeView from '../views/HomeView.vue'
import ProfileView from '@/views/ProfileView.vue'
import ConfiguracionView from '@/views/ConfiguracionView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    // 🔓 RUTA PÚBLICA (Solo para invitados)
    {
      path: '/login',
      name: 'login',
      component: LoginView,
      meta: { requiresGuest: true }
    },

    // 🔒 RUTAS PROTEGIDAS (Requieren sesión activa)
    {
      path: '/',
      name: 'home',
      component: HomeView,
      meta: { requiresAuth: true }
    },
    {
      path: '/tickets',
      name: 'tickets.index',
      component: () => import('../views/TicketsListView.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/tickets/crear',
      name: 'tickets.crear',
      component: () => import('../views/CrearTicketView.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/tickets/:id',
      name: 'TicketDetail',
      component: () => import('../views/TicketDetailView.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/tickets/crear/:id?', 
      name: 'tickets.form',
      component: () => import('../views/CrearTicketView.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/clientes',
      name: 'clientes.index',
      component: () => import('../views/ClientesListView.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/clientes/crear/:id?',
      name: 'clientes.form',
      component: () => import('../views/ClienteFormView.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/tipos-casos',
      name: 'tipos.casos',
      component: () => import('../views/TipoCasoView.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/areas',
      name: 'areas.areas',
      component: () => import('../components/AreasComponent.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/carteras',
      name: 'carteras.carteras',
      component: () => import('../components/CarterasComponent.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/usuarios',
      name: 'usuarios.index',
      component: () => import('../views/UsuarioListView.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/usuarios/crear/:id?', 
      name: 'usuarios.form',
      component: () => import('../views/UsuarioFormView.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/administracion',
      name: 'administracion',
      component: ConfiguracionView,
      meta: { requiresAuth: true }
    },
    {
      path: '/perfil',
      name: 'perfil',
      component: ProfileView,
      meta: { requiresAuth: true }
    },
    {
      path: '/configuracion',
      name: 'configuracion',
      component: () => import('@/views/SettingsView.vue'),
      meta: { requiresAuth: true }
    }
  ],
})

// 🛑 PORTERO DE SEGURIDAD GLOBAL (Navigation Guard)
router.beforeEach((to, from, next) => {
  const authStore = useAuthStore()

  // Verificamos si hay token activo en la tienda o session
  const estaAutenticado = !!authStore.token

  // 1. Si la ruta exige autenticación y NO estás logueado -> Al Login por la fuerza
  if (to.meta.requiresAuth && !estaAutenticado) {
    next({ name: 'login' })
  } 
  // 2. Si ya estás logueado e intentas entrar al Login -> Te mandamos al Dashboard (Home)
  else if (to.meta.requiresGuest && estaAutenticado) {
    next({ name: 'home' })
  } 
  // 3. De lo contrario, continúa libremente
  else {
    next()
  }
})

export default router