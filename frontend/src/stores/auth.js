import { defineStore } from 'pinia'
import api from '@/services/api'

// Función auxiliar segura para leer del sessionStorage
const getStorageItem = (key) => {
  try {
    const item = sessionStorage.getItem(key)
    return item ? JSON.parse(item) : null
  } catch (e) {
    return sessionStorage.getItem(key)
  }
}

export const useAuthStore = defineStore('auth', {
  state: () => ({
    // 💡 Cambiamos localStorage por sessionStorage
    user: getStorageItem('user'),
    token: sessionStorage.getItem('token') || null,
  }),
  
  getters: {
    // Verificar si es admin
    isAdmin: (state) => state.user?.rol?.nombre === 'Administrador',
    
    // Verificar si está autenticado
    isAuthenticated: (state) => !!state.token,
    
    // Obtener nombre del rol
    rolNombre: (state) => state.user?.rol?.nombre || 'Sin rol',
  },
  
  actions: {
    async login(email, password) {
      try {
        const response = await api.post('/login', { email, password })
        this.token = response.data.token
        this.user = response.data.usuario
        
        // 💡 Guardamos en sessionStorage en lugar de localStorage
        sessionStorage.setItem('token', this.token)
        sessionStorage.setItem('user', JSON.stringify(this.user))
        sessionStorage.removeItem('saludoMostrado')//limpiar variable
        api.defaults.headers.common['Authorization'] = `Bearer ${this.token}`

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
      this.token = null
      this.user = null
      
      // 💡 Limpiamos sessionStorage
      sessionStorage.removeItem('token')
      sessionStorage.removeItem('user')
      
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
      }
    }
  }
})