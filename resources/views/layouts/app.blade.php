<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Geist Font: Engineered for Dark Mode & SaaS -->
        <link rel="stylesheet" href="https://jsdelivr.net" />
        <link rel="stylesheet" href="https://jsdelivr.net" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles

        <style>
            /* Using 600-700 instead of 900 to prevent "blooming" on dark bg */
            body { font-family: 'Geist Sans', sans-serif; font-feature-settings: "ss01", "ss03"; }
            h1, h2, h3, .font-heading { font-family: 'Geist Sans', sans-serif; font-weight: 700; letter-spacing: -0.02em; }
            
            /* Text smoothing for Dark Mode */
            body { -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; }
        </style>
    </head>
    <body class="antialiased bg-slate-950 text-slate-400 selection:bg-blue-500/30">
        <!-- Background Mesh - Reduced opacity for cleaner look -->
        <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
            <div class="absolute -top-[10%] -left-[5%] w-[40%] h-[40%] rounded-full bg-blue-600/5 blur-[120px]"></div>
            <div class="absolute bottom-[10%] -right-[5%] w-[30%] h-[30%] rounded-full bg-indigo-500/5 blur-[100px]"></div>
        </div>

        <div class="relative z-10 min-h-screen flex flex-col">
            <livewire:layout.navigation />

            @if (isset($header))
                <header class="relative border-b border-slate-800/40 bg-slate-950/40 backdrop-blur-md">
                    <div class="max-w-7xl mx-auto py-10 px-6 sm:px-8">
                        <div class="flex items-center gap-4">
                            <!-- Refined accent bar -->
                            <div class="h-8 w-1 bg-gradient-to-b from-blue-500 to-blue-700 rounded-full"></div>
                            <h1 class="text-3xl font-bold text-slate-100 uppercase tracking-tight">
                                {{ $header }}
                            </h1>
                        </div>
                    </div>
                </header>
            @endif

            <main class="flex-1 w-full mx-auto">
                {{ $slot }}
            </main>
        </div>

        <x-toaster-hub />   
        @livewireScripts
    </body>
</html>
