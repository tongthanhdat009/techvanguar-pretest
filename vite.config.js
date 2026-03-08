import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                // Admin
                'resources/css/admin/admin.css',
                'resources/js/admin/admin.js',
                // Client
                'resources/css/client/client.css',
                'resources/js/client/client.js',
                // Auth
                'resources/css/auth/auth.css',
                'resources/js/auth/auth.js',
                // Public
                'resources/css/public/public.css',
                'resources/js/public/public.js',
            ],
            refresh: true,
        }),
    ],
    server: {
        host: '127.0.0.1',
        port: 5173,
        strictPort: false,
        hmr: {
            host: '127.0.0.1',
        },
    },
});
