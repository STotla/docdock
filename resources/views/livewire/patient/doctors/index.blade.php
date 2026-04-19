<div class="mx-auto w-full max-w-7xl px-6 py-12 lg:px-8 bg-slate-950">
    <!-- Header & Dynamic Filters -->
    <div class="mb-10 space-y-6">
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight text-white">Available Specialists</h1>
            <p class="mt-2 text-slate-400 text-sm">Find and book appointments with top-rated doctors.</p>
        </div>

        @if($this->specialization || $this->state || $this->city)
        <div class="flex flex-wrap items-center gap-3 p-4 bg-slate-900/50 border border-slate-800 rounded-2xl">
            <span class="text-xs font-bold uppercase tracking-widest text-slate-500 mr-2">Filtered By:</span>
            
            @if($this->specialization)
                <div class="inline-flex items-center gap-2 rounded-lg bg-primary-500/10 px-3 py-1.5 text-xs font-semibold text-blue-400 border border-blue-500/20">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19.423 15.641a7 7 0 111.414-1.414l2.121 2.121-1.414 1.414-2.121-2.121z"/></svg>
                    {{ $this->specialization->name }}
                </div>
            @endif

            @if($this->state || $this->city)
                <div class="inline-flex items-center gap-2 rounded-lg bg-emerald-500/10 px-3 py-1.5 text-xs font-semibold text-emerald-400 border border-emerald-500/20">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                    {{ collect([$this->city, $this->state])->filter()->join(', ') }}
                </div>
            @endif
        </div>
        @endif
    </div>

    <!-- Doctor Grid -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($doctors as $doctor)
            <div class="group relative flex flex-col justify-between rounded-3xl border border-slate-800 bg-slate-900 p-6 transition-all duration-300 hover:border-slate-600 hover:shadow-2xl hover:shadow-blue-900/10">
                
                <div>
                    {{-- Top Row: Avatar & Specialization --}}
                    <div class="flex items-center justify-between mb-5">
                        <div class="relative">
                            <img src="{{ asset('/storage/'.$doctor->profile_img_url) }}" 
                                 alt="{{ $doctor->user->name }}" 
                                 class="h-16 w-16 rounded-2xl object-cover ring-2 ring-slate-800 transition-transform group-hover:scale-105">
                            <div class="absolute -bottom-1 -right-1 h-4 w-4 rounded-full border-2 border-slate-900 bg-green-500"></div>
                        </div>
                        <span class="rounded-full bg-slate-800 px-3 py-1 text-[10px] font-bold uppercase tracking-tighter text-slate-300 border border-slate-700">
                             {{ $doctor->specialization->name }}
                        </span>
                    </div>

                    {{-- Doctor Name & Exp --}}
                    <div class="mb-4">
                        <h3 class="text-lg font-bold text-white group-hover:text-blue-400 transition-colors">
                            {{ $doctor->user->name ?? 'Doctor' }}
                        </h3>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">
                            {{ $doctor->experience ?? '0' }} Years Experience
                        </p>
                    </div>

                    {{-- Location Info --}}
                    <div class="space-y-2 py-4 border-t border-slate-800/50">
                        <div class="flex items-center gap-2 text-sm text-slate-300">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-7h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            <span class="truncate">{{ $doctor->clinic_name ?? 'General Clinic' }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm text-slate-400">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                            <span class="truncate text-xs">{{ $doctor->city }}, {{ $doctor->state }}</span>
                        </div>
                    </div>
                </div>

                {{-- Footer: Fee & Action --}}
                <div class="mt-6 flex items-center justify-between gap-4 border-t border-slate-800 pt-5">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Consultation</p>
                        <p class="text-lg font-black text-white">
                            @if(!empty($doctor->consultation_fee))
                                {{ Number::currency($doctor->consultation_fee, $doctor->currency) }}
                            @else
                                <span class="text-sm text-slate-400 italic">N/A</span>
                            @endif
                        </p>
                    </div>

                    <a href="{{ route('doctors.show', $doctor) }}" wire:navigate
                       class="flex h-11 items-center justify-center rounded-xl bg-primary-600 px-6 text-sm font-bold text-white transition-all hover:bg-primary-500 shadow-lg shadow-blue-900/20 active:scale-95">
                        Profile
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Empty State --}}
    @if($doctors->isEmpty())
        <div class="mt-12 rounded-3xl border-2 border-dashed border-slate-800 p-20 text-center">
            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-slate-900">
                <svg class="h-8 w-8 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 9.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h3 class="text-xl font-bold text-white">No Specialists Found</h3>
            <p class="mt-2 text-slate-500">Try adjusting your location or specialization filters.</p>
        </div>
    @endif

    <div class="mt-12">
        {{ $doctors->links() }}
    </div>
</div>
