<?php
use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {
    public function logout(Logout $logout): void
    {
        $logout();
        $this->redirect('/', navigate: true);
    }
}; ?>

<nav x-data="{ open: false }" class="sticky top-0 z-50 bg-slate-950/80 backdrop-blur-md border-b border-slate-800/60">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-18">
            <div class="flex items-center gap-10">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('welcome') }}" wire:navigate class="transition-transform hover:scale-105">
                        <x-application-logo class="block h-9 w-auto fill-current text-white" />
                    </a>
                </div>

                <!-- Main Desktop Navigation -->
                <div class="hidden space-x-1 sm:flex h-16">
                    <x-nav-link :href="route('welcome')" :active="request()->routeIs('welcome')" wire:navigate 
                        class="text-xs font-black uppercase tracking-widest px-4">
                        {{ __('Home') }}
                    </x-nav-link>
                    
                    <x-nav-link :href="route('doctors.search')" :active="request()->routeIs('doctors.search')" wire:navigate
                        class="text-xs font-black uppercase tracking-widest px-4">
                        {{ __('Find Doctors') }}
                    </x-nav-link>

                    @if(auth()->check())
                        <x-nav-link :href="route('patient.dashboard')" :active="request()->routeIs('patient.dashboard')" wire:navigate
                            class="text-xs font-black uppercase tracking-widest px-4 text-blue-400">
                            {{ __('My Dashboard') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-4">
                <!-- Portal Link (Highlighted) -->
                <div class="hidden md:flex">
                    <a href="{{ route('doctor.portal') }}" wire:navigate 
                       class="px-4 py-2 bg-slate-900 border border-slate-700 rounded-xl text-[10px] font-black uppercase tracking-widest text-slate-300 hover:bg-slate-800 hover:text-white transition-all">
                        {{ __('Doctor Portal') }}
                    </a>
                </div>

                @if(auth()->check())
                    <!-- User Controls -->
                    <div class="hidden sm:flex sm:items-center sm:ms-2">
                        <x-dropdown align="right" width="56"
                            contentClasses="py-2 bg-slate-900 border border-slate-800 rounded-2xl shadow-2xl overflow-hidden">
                            <x-slot name="trigger">
                                <button class="flex items-center gap-3 px-3 py-1.5 bg-slate-900 border border-slate-800 rounded-2xl hover:border-slate-600 transition-all group">
                                    <div class="h-8 w-8 rounded-full bg-blue-600 flex items-center justify-center text-xs font-black text-white ring-2 ring-slate-800 group-hover:ring-blue-500/20">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </div>
                                    <div class="text-start hidden lg:block">
                                        <div class="text-[10px] font-black text-white uppercase tracking-tighter"
                                             x-data="{{ json_encode(['name' => auth()->user()->name]) }}" 
                                             x-text="name"
                                             x-on:profile-updated.window="name = $event.detail.name"></div>
                                        <div class="text-[8px] font-bold text-slate-500 uppercase tracking-widest">Verified Account</div>
                                    </div>
                                    <svg class="h-4 w-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <div class="px-4 py-2 border-b border-slate-800 mb-1">
                                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Menu</p>
                                </div>
                                <x-dropdown-link :href="route('patient.appointments')" wire:navigate class="text-xs font-bold hover:bg-slate-800">
                                    {{ __('My Appointments') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('profile')" wire:navigate class="text-xs font-bold hover:bg-slate-800">
                                    {{ __('Account Settings') }}
                                </x-dropdown-link>
                                
                                <div class="mt-1 border-t border-slate-800 pt-1">
                                    <button wire:click="logout" class="w-full text-start group">
                                        <x-dropdown-link class="text-red-400 group-hover:bg-red-500/10">
                                            {{ __('Sign Out') }}
                                        </x-dropdown-link>
                                    </button>
                                </div>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @else
                    <div class="hidden sm:flex items-center gap-2">
                        <a href="{{ route('login') }}" wire:navigate class="text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-white px-4">Sign In</a>
                        <a href="{{ route('register') }}" wire:navigate class="bg-blue-600 hover:bg-blue-500 text-white text-[10px] font-black uppercase tracking-widest px-6 py-2.5 rounded-xl transition-all shadow-lg shadow-blue-900/20">Join Now</a>
                    </div>
                @endif

                <!-- Hamburger -->
                <div class="flex items-center sm:hidden">
                    <button @click="open = ! open" class="p-2 rounded-xl bg-slate-900 border border-slate-800 text-slate-400 hover:text-white transition-colors">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Responsive Mobile Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-slate-950 border-t border-slate-800 shadow-2xl">
        <div class="pt-2 pb-6 space-y-1 px-4">
            <x-responsive-nav-link :href="route('welcome')" :active="request()->routeIs('welcome')" wire:navigate class="rounded-xl">
                {{ __('Home') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('doctors.search')" :active="request()->routeIs('doctors.search')" wire:navigate class="rounded-xl">
                {{ __('Find a Doctor') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('patient.dashboard')" :active="request()->routeIs('patient.dashboard')" wire:navigate class="rounded-xl text-blue-400">
                {{ __('Patient Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('doctor.portal')" wire:navigate class="rounded-xl">
                {{ __('Doctor Portal') }}
            </x-responsive-nav-link>
        </div>

        @if(auth()->check())
            <div class="pt-4 pb-4 border-t border-slate-800 bg-slate-900/50">
                <div class="px-6 flex items-center gap-3 mb-6">
                    <div class="h-10 w-10 rounded-full bg-blue-600 flex items-center justify-center font-black text-white">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="text-sm font-black text-white uppercase tracking-tight">{{ auth()->user()->name }}</div>
                        <div class="text-[10px] font-bold text-slate-500">{{ auth()->user()->email }}</div>
                    </div>
                </div>

                <div class="space-y-1 px-4">
                    <x-responsive-nav-link :href="route('patient.appointments')" wire:navigate class="rounded-xl">
                        {{ __('My Appointments') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('profile')" wire:navigate class="rounded-xl">
                        {{ __('Profile Settings') }}
                    </x-responsive-nav-link>
                    <button wire:click="logout" class="w-full text-start">
                        <x-responsive-nav-link class="text-red-400">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </button>
                </div>
            </div>
        @endif
    </div>
</nav>
