import axios from 'axios';

const api = axios.create({
  baseURL: import.meta.env.VITE_API_URL, // Aquí lee tu archivo .env
  headers: {
    'Content-Type': 'application/json',
    // Si usas tokens de autenticación, aquí los configurarías también
  }
});

export default api;