// resources/css/filament/doctor/tailwind.config.js


export default {
    content: [
        './app/Filament/Doctor/**/*.php',
        './resources/views/filament/doctor/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
    safelist: [
        'bg-blue-50',    'dark:bg-blue-950/30',
        'bg-emerald-50', 'dark:bg-emerald-950/30',
        'bg-amber-50',   'dark:bg-amber-950/30',
        'bg-violet-50',  'dark:bg-violet-950/30',
    ],
}