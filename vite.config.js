import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import { fontsource } from 'laravel-vite-plugin/fonts'
import tailwindcss from '@tailwindcss/vite'

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            fonts: [
                fontsource('Outfit', {
                    weights: [200, 300, 400, 500, 600, 700, 800],
                }),
                fontsource('Bebas Neue'),
                fontsource('Indie Flower'),
                fontsource('Playfair Display', {
                    weights: [400, 500, 600, 700, 800, 900],
                    styles: ['normal', 'italic'],
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
})
