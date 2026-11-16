import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

// Vite = the bundler: it compiles our SASS into one small CSS file and our
// JS into one small JS file, then tells Blade how to load them (@vite).
// (Tailwind was removed — rule F1: no CSS frameworks. We ship our own SASS.)
export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/sass/style.scss', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
