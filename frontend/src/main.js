import './assets/style.css'
import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'
import axios from 'axios'

const app = createApp(App)

const pinia = createPinia()
app.use(pinia)

// Inicializar auth store antes de montar la app
import { useAuthStore } from '@/stores/auth'
const authStore = useAuthStore()
authStore.init()

// Configurar axios si hay token
if (authStore.token) {
  axios.defaults.headers.common['Authorization'] = `Bearer ${authStore.token}`
}

app.use(router)

app.mount('#app')