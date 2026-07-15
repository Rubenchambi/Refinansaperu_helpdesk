import axios from 'axios';

const api = axios.create({
  baseURL: import.meta.env.VITE_API_URL,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json', // Importante para que Laravel responda JSON
  }
});

// Interceptor para inyectar el token automáticamente
api.interceptors.request.use((config) => {
  const token = localStorage.getItem('token'); // Asegúrate de que este sea el nombre de tu key
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
}, (error) => {
  return Promise.reject(error);
});

export default api;