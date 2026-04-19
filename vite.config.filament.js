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
        postcss: './postcss.config.filament.js',
    },
})