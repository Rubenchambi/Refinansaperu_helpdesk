import { defineStore } from 'pinia'
import api from '@/services/api'
import router from '@/router'
import Swal from 'sweetalert2'

// Función auxiliar segura para leer del sessionStorage
const getStorageItem = (key) => {
  try {
    const item = sessionStorage.getItem(key)
    return item ? JSON.parse(item) : null
  } catch (e) {
    return sessionStorage.getItem(key)
  }
}

// Variables globales fuera del store para el temporizador de inactividad
let idleTimer = null
const INACTIVITY_LIMIT = 5 * 60 * 1000 // 5 minutos en milisegundos

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: getStorageItem('user'),
    token: sessionStorage.getItem('token') || null,
  }),
  
  getters: {
    isAdmin: (state) => state.user?.rol?.nombre === 'Administrador',
    isAuthenticated: (state) => !!state.token,
    rolNombre: (state) => state.user?.rol?.nombre || 'Sin rol',
  },
  
  actions: {
    async login(email, password) {
      try {
        const response = await api.post('/login', { email, password })
        this.token = response.data.token
        this.user = response.data.usuario
        
        sessionStorage.setItem('token', this.token)
        sessionStorage.setItem('user', JSON.stringify(this.user))
        sessionStorage.removeItem('saludoMostrado')
        api.defaults.headers.common['Authorization'] = `Bearer ${this.token}`

        // 💡 Activamos el monitor de inactividad al iniciar sesión exitosamente
        this.iniciarMonitorInactividad()

        return { success: true }
      } catch (error) {
        console.error("Error en login:", error)
        return { 
          success: false, 
          message: error.response?.data?.message || 'Error al iniciar sesión' 
        }
      }
    },
    
    logout() {
      // 💡 Detenemos el monitor al hacer logout manual
      this.detenerMonitorInactividad()

      this.token = null
      this.user = null
      
      sessionStorage.removeItem('token')
      sessionStorage.removeItem('user')
      sessionStorage.removeItem('saludoMostrado')
      
      delete api.defaults.headers.common['Authorization']
    },
    
    // Cargar token al iniciar la app
    init() {
      const token = sessionStorage.getItem('token')
      const user = sessionStorage.getItem('user')
      
      if (token && user) {
        this.token = token
        this.user = JSON.parse(user)
        api.defaults.headers.common['Authorization'] = `Bearer ${token}`
        
        // 💡 Si recarga la página y ya hay sesión, encendemos el vigilante de inactividad
        this.iniciarMonitorInactividad()
      }
    },

    // --- NUEVAS FUNCIONES DE INACTIVIDAD ---

    reiniciarTimerInactividad() {
      clearTimeout(idleTimer)
      if (this.token) {
        idleTimer = setTimeout(() => {
          this.expirarSesionPorInactividad()
        }, INACTIVITY_LIMIT)
      }
    },

    expirarSesionPorInactividad() {
      // Limpiamos todo llamando al logout local
      this.logout()

      // Mostramos la alerta de SweetAlert2
      Swal.fire({
        icon: 'warning',
        title: 'Sesión expirada',
        text: 'Tu sesión se cerró automáticamente por 5 minutos de inactividad.',
        confirmButtonText: 'Entendido',
        timer: 5000,
        timerProgressBar: true
      })

      // Redirigimos al login de forma segura
      router.push({ name: 'login' })
    },

    iniciarMonitorInactividad() {
      this.detenerMonitorInactividad() // Evita duplicar listeners si ya estaban activos
      
      window.addEventListener('mousemove', () => this.reiniciarTimerInactividad())
      window.addEventListener('keydown', () => this.reiniciarTimerInactividad())
      window.addEventListener('click', () => this.reiniciarTimerInactividad())
      window.addEventListener('scroll', () => this.reiniciarTimerInactividad())
      
      this.reiniciarTimerInactividad()
    },

    detenerMonitorInactividad() {
      clearTimeout(idleTimer)
      window.removeEventListener('mousemove', () => this.reiniciarTimerInactividad())
      window.removeEventListener('keydown', () => this.reiniciarTimerInactividad())
      window.removeEventListener('click', () => this.reiniciarTimerInactividad())
      window.removeEventListener('scroll', () => this.reiniciarTimerInactividad())
    }
  }
})