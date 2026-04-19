<?php 
use Illuminate\Support\Carbon;

?>
<div class="max-w-7xl mx-auto px-6 py-10">
    <!-- Clean Header -->
    <div class="flex items-center justify-between mb-10">
        <h1 class="text-3xl font-black tracking-tight text-white">Appointments</h1>
        <a href="{{ route('doctors.search') }}" wire:navigate 
           class="p-3 bg-primary-600 hover:bg-primary-500 rounded-xl transition-all shadow-lg shadow-primary-900/20 active:scale-95">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
        </a>
    </div>

    <!-- Compact Filter Bar -->
  <div class="flex flex-col md:flex-row items-center gap-4 mb-8">
    <!-- Tabbed Switcher (Upcoming & Completed) -->
    <div class="inline-flex p-1 bg-slate-900 border border-slate-800 rounded-2xl shadow-inner shrink-0">
        <!-- Upcoming -->
        <label class="relative cursor-pointer">
            <input type="radio" wire:model.live="statusFilter" value="confirmed" class="peer hidden">
            <div class="px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-[0.15em] transition-all duration-300 peer-checked:bg-primary-600 peer-checked:text-white text-slate-500 hover:text-slate-300">
                Upcoming
            </div>
        </label>

        <!-- Completed -->
        <label class="relative cursor-pointer">
            <input type="radio" wire:model.live="statusFilter" value="completed" class="peer hidden">
            <div class="px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-[0.15em] transition-all duration-300 peer-checked:bg-primary-600 peer-checked:text-white text-slate-500 hover:text-slate-300">
                Completed
            </div>
        </label>
    </div>

    <!-- Search Box -->
    <div class="relative flex-1 w-full">
        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>
        <input type="text" wire:model.live="search" placeholder="Search doctor by name..." 
            class="w-full bg-slate-900 border border-slate-800 rounded-2xl py-3 pl-11 pr-4 text-sm text-slate-200 placeholder:text-slate-600 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all outline-none">
    </div>
</div>


    <!-- Clean Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse ($appointments as $appointment)
            <div class="group bg-slate-900/40 border border-slate-800 rounded-2xl p-5 hover:border-slate-600 transition-all">
                
                <!-- Date & Status Row -->
                <div class="flex justify-between items-start mb-5">
                    <div class="flex items-center gap-3">
                        <div class="text-center">
                            <span class="block text-xl font-black text-white leading-none">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d') }}</span>
                            <span class="text-[10px] font-black text-primary-500 uppercase tracking-tighter">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M') }}</span>
                        </div>
                        <div class="h-8 w-[1px] bg-slate-800"></div>
                        <div class="text-[11px] font-bold text-slate-400">
                            {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('l') }}<br>
                            <span class="text-slate-100">{{ Carbon::parse($appointment->appointment_start_time)->format('g:i A') }}</span>
                        </div>
                    </div>

                    <span class="px-2 py-0.5 rounded-md text-[9px] font-black uppercase tracking-widest border
                        {{ $appointment->status === 'completed' ? 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20' : '' }}
                        {{ $appointment->status === 'confirmed' ? 'bg-primary-500/10 text-primary-400 border-primary-500/20' : '' }}
                        {{ $appointment->status === 'cancelled' ? 'bg-rose-500/10 text-rose-500 border-rose-500/20' : '' }}">
                        {{ $appointment->status }}
                    </span>
                </div>

                <!-- Doctor Info -->
                <div class="flex items-center justify-between gap-4">
                    <div class="min-w-0">
                        <h3 class="text-sm font-black text-white truncate group-hover:text-primary-400 transition-colors">
                            {{ $appointment->doctor->user->name }}
                        </h3>
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest truncate">{{ $appointment->doctor->specialization->name }}</p>
                    </div>

                    <a href="{{ route('patient.appointments.show', $appointment) }}" wire:navigate
                       class="shrink-0 h-10 w-10 flex items-center justify-center bg-slate-950 hover:bg-primary-600 border border-slate-800 rounded-xl transition-all">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center bg-slate-900/20 border-2 border-dashed border-slate-800 rounded-2xl">
                <p class="text-slate-500 text-xs font-bold uppercase tracking-widest">No appointments found</p>
            </div>
        @endforelse
    </div>

    <div class="mt-8 ">
        {{ $appointments->links() }}
    </div>
</div>

