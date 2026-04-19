<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased text-slate-900">
    <livewire:layout.navigation />

    <div class="min-h-screen grid grid-cols-1 lg:grid-cols-2 ">
        <!-- Left Half: Branding -->
        <!-- Left Half: Professional Branding -->
        <div
            class="relative hidden lg:flex items-center justify-center bg-gradient-to-br from-indigo-950 via-slate-900 to-emerald-900 p-10 overflow-hidden">
            <div
                class="absolute inset-0 opacity-20 bg-[radial-gradient(circle_at_top_left,_#10b981_0,_transparent_35%)]">
            </div>

            <div class="relative z-10 max-w-md text-slate-100">
                <a href="/" wire:navigate class="inline-flex items-center gap-3">
                    <x-application-logo class="h-24 w-auto text-emerald-400" />
                    <span class="text-3xl font-bold tracking-tight text-white">DocDock <span
                            class="text-emerald-400">Pro</span></span>
                </a>

                <h1 class="mt-8 text-4xl font-extrabold leading-tight text-white">
                    Grow your practice with DocDock
                </h1>
                <p class="mt-4 text-lg text-slate-300">
                    Set up your digital clinic in minutes. Manage availability, sync with your calendar, and receive
                    payments securely.
                </p>

                <ul class="mt-8 space-y-4 text-slate-200">
                    <li class="flex items-center gap-3">
                        <span class="bg-emerald-500/20 p-1 rounded-full text-emerald-400">✓</span>
                        Smart Scheduling & Reminders
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="bg-emerald-500/20 p-1 rounded-full text-emerald-400">✓</span>
                        Direct Patient Billing (via Cashier)
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="bg-emerald-500/20 p-1 rounded-full text-emerald-400">✓</span>
                        Secure Health Record Storage
                    </li>
                </ul>
            </div>
        </div>

        <!-- Right Half: Registration Form -->
        <div class="flex items-center justify-center bg-slate-50 px-6 py-10 sm:px-8">
            <div class="w-full max-w-lg rounded-2xl border border-slate-200 bg-white p-6 shadow-xl sm:p-10">
                <div class="mb-8 text-center">
                    <h2 class="text-2xl font-bold text-slate-800">Create Professional Profile</h2>
                    <p class="text-slate-500 mt-2">Join our network of certified specialists</p>
                </div>

                {{ $slot }}
            </div>
        </div>

    </div>
</body>

</html>