import { fileURLToPath, URL } from 'node:url'
import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import tailwindcss from '@tailwindcss/vite' // <-- Este es el import correcto

export default defineConfig({
  plugins: [
    vue(),
    tailwindcss(), // <-- Aquí se activa el motor de Tailwind
  ],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url)),
    },
  },
})