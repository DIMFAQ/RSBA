import { defineConfig } from 'vite';
import laravel, { refreshPaths } from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

const localIp = process.env.VITE_HMR_HOST || getLocalIp();

export default defineConfig({
    server: {
<<<<<<< HEAD
        port: 5173, // Fixed port
        strictPort: true, // Fail if the port is already in use
=======
        host: '0.0.0.0',
        port: 5173,
        strictPort: true,
        cors: true,
        hmr: {
            host: localIp,
        },
>>>>>>> 420fcdd (feat: implement Kelola component for employee work schedule management and update Vite configuration)
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            // refresh: true,
            refresh: [
                ...refreshPaths,
                'app/Livewire/**',
            ],
        }),
        tailwindcss()
    ],
});
