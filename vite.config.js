import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import { google } from 'laravel-vite-plugin/fonts'
import tailwindcss from '@tailwindcss/vite'

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            fonts: [
                google('Outfit', {
                    weights: ['200..800'],
                }),
                google('Bebas Neue'),
                google('Indie Flower'),
                google('Playfair Display', {
                    weights: ['400..900'],
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
