import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            fonts: [
                // Fraunces carries the display voice: a soft, botanical serif.
                bunny('Fraunces', {
                    weights: [400, 600, 700],
                    styles: ['normal', 'italic'],
                }),
                // Figtree handles everything that has to be read rather than admired.
                bunny('Figtree', {
                    weights: [400, 500, 600, 700, 800],
                }),
            ],
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
