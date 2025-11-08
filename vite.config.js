import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: [
                'resources/views/**',
                'app/Http/Controllers/**',
                'routes/**',
            ],
        }),
        tailwindcss({
            config: './tailwind.config.js',
            css: 'resources/css/app.css',
        }),
    ],
    server: {
        host: '0.0.0.0',
        hmr: {
            host: 'localhost',
        },
        watch: {
            usePolling: true,
        },
    },
    optimizeDeps: {
        include: [
            'alpinejs',
            '@alpinejs/focus',
            '@alpinejs/collapse',
            'axios',
        ],
    },
    build: {
        rollupOptions: {
            output: {
                manualChunks: {
                    alpine: ['alpinejs', '@alpinejs/focus', '@alpinejs/collapse'],
                    vendor: ['axios'],
                },
            },
        },
    },
});
