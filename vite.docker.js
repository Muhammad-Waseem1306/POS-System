import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        host: '0.0.0.0',
        port: 3000,
        strictPort: true,
        origin: 'http://localhost:3000',
        hmr: {
            host: 'localhost',
            port: 3000,
        },
        watch: {
            usePolling: true,
        },
    },
    plugins: [
        laravel({
            input: 'resources/js/app.jsx',
            refresh: true,
        }),
        react(),
    ],
});
