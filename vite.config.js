import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import { glob } from 'glob';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                ...glob.sync('resources/assets/js/**/*.js'),
                ...glob.sync('Modules/**/resources/assets/js/**/*.js'),
            ],
            refresh: [
                'resources/views/**',
                'Modules/**/resources/views/**',
            ],
        }),
        tailwindcss(),
    ],
    resolve: {
        alias: {
            jquery: path.resolve(__dirname, 'node_modules/jquery/dist/jquery.js'),
        },
    },
    server: {
        host: '0.0.0.0',
        port: 5173,
        hmr: {
            host: 'localhost',
        },
        watch: {
            usePolling: true,
        },
    },
});
