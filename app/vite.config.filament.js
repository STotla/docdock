// vite.config.filament.js  (create this in project root)

import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/filament/doctor/theme.css'],
            buildDirectory: 'build-filament',
            refresh: true,
        }),
    ],
    css: {
        postcss: {
            plugins: [
                require('tailwindcss')({
                    config: './resources/css/filament/doctor/tailwind.config.js',
                }),
                require('autoprefixer'),
            ],
        },
    },
})