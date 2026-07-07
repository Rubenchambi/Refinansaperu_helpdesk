import { defineStore } from 'pinia'
import axios from 'axios'
import api from '@/services/api'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: JSON.parse(localStorage.getItem('user')) || null,
    token: localStorage.getItem('token') || null,
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
        
        localStorage.setItem('token', this.token)
        localStorage.setItem('user', JSON.stringify(this.user))
        
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
      
      localStorage.removeItem('token')
      localStorage.removeItem('user')
      
      delete api.defaults.headers.common['Authorization']
    },
    
    // Cargar token al iniciar la app (para que no se pierda al refrescar)
    init() {
      const token = localStorage.getItem('token')
      const user = localStorage.getItem('user')
      
      if (token && user) {
        this.token = token
        this.user = JSON.parse(user)
       api.defaults.headers.common['Authorization'] = `Bearer ${token}`
      }
    }
  }
})