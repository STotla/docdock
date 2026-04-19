<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'DocDock') }}</title>

        <!-- Geist Font for Premium feel -->
        <link rel="stylesheet" href="https://jsdelivr.net" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body { font-family: 'Geist Sans', sans-serif; }
        </style>
    </head>
    <body class="antialiased bg-slate-950 text-slate-200">
        <livewire:layout.navigation />
        <div class="min-h-screen grid grid-cols-1 lg:grid-cols-2">
            
            <!-- Left Half: Branding (Dark Mesh) -->
            <div class="relative hidden lg:flex items-center justify-center bg-slate-950 p-12 overflow-hidden border-r border-slate-800/50">
                <!-- Background Mesh Glows -->
                <div class="absolute inset-0 z-0">
                    <div class="absolute top-0 left-0 w-full h-full bg-[radial-gradient(circle_at_20%_20%,_rgba(37,99,235,0.15)_0%,_transparent_50%)]"></div>
                    <div class="absolute bottom-0 right-0 w-full h-full bg-[radial-gradient(circle_at_80%_80%,_rgba(20,184,166,0.1)_0%,_transparent_50%)]"></div>
                </div>

                <div class="relative z-10 max-w-lg">
                    <a href="/" wire:navigate class="inline-flex items-center gap-4 group">
                        <div class="p-3 bg-slate-900 rounded-2xl border border-slate-800 shadow-2xl group-hover:border-primary-500/50 transition-colors">
                            <x-application-logo class="h-12 w-auto fill-current text-primary-500" />
                        </div>
                        <span class="text-3xl font-black tracking-tighter text-white">DocDock</span>
                    </a>

                    <h1 class="mt-12 text-5xl font-black leading-[1.1] text-white tracking-tight">
                        Healthcare <span class="text-primary-500">Simplified.</span>
                    </h1>
                    
                    <p class="mt-6 text-lg text-slate-400 font-medium leading-relaxed">
                        Secure access, fast response, and a wide range of services right at your fingertips.
                    </p>

                    <div class="mt-10 space-y-4">
                        @foreach(['Fast Booking', 'Organized Appointments', 'Secure & Reliable'] as $feature)
                        <div class="flex items-center gap-3">
                            <div class="flex h-6 w-6 items-center justify-center rounded-full bg-primary-500/10 border border-primary-500/20 text-primary-500">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span class="text-sm font-bold text-slate-300 uppercase tracking-widest">{{ $feature }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right Half: Form Slot (Slate Dark) -->
            <div class="relative flex items-center justify-center bg-slate-950 px-6 py-12 sm:px-12">
                <!-- Mobile Logo -->
                <div class="absolute top-8 left-1/2 -translate-x-1/2 lg:hidden">
                    <a href="/" wire:navigate class="flex items-center gap-3">
                        <x-application-logo class="h-10 w-auto text-primary-500" />
                        <span class="text-xl font-black text-white tracking-tighter">DocDock</span>
                    </a>
                </div>

                <div class="w-full ">
                    <!-- This container now blends with your dark inputs -->
                    <div class=" bg-slate-900/50 p-8 sm:p-10 shadow-2xl backdrop-blur-sm">
                        {{ $slot }}
                    </div>
                    
                    <!-- Footer Link inside the scrollable area -->
                    <p class="mt-8 text-center text-[10px] font-black uppercase tracking-[0.3em] text-slate-600">
                        &copy; {{ date('Y') }} DocDock &bull; Secure Auth
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>
