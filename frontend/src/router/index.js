import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import LoginView from '../views/LoginView.vue' // 1. Importalo aquí

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView,
    },
    {
      path: '/login', // 2. Define la ruta
      name: 'login',
      component: LoginView, // 3. Asígnalo al componente
    },
    {
      path: '/about',
      name: 'about',
      component: () => import('../views/AboutView.vue'),
    },
    {
    path: '/crear-ticket',
    name: 'crear-ticket',
    component: () => import('../views/CrearTicketView.vue')
    }
  ],
})

export default router