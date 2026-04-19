<div class="relative min-h-[65vh] flex items-center justify-center overflow-hidden antialiased">
    <!-- Background Layer -->
    <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 bg-cover bg-center bg-no-repeat transition-transform duration-1000" 
             style="background-image: url('/storage/search-doctor-bg.jpg');">
            <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-[1px]"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>
        </div>
    </div>
    
    <div class="relative z-10 w-full max-w-6xl px-6 py-20">
        <!-- Text Header -->
        <div class="text-center mb-12">
            
            <h1 class="text-4xl md:text-6xl font-black text-white tracking-tight mb-4">
                Find Your <span class="text-primary-500">Specialist</span>
            </h1>
            <p class="text-slate-400 text-lg max-w-2xl mx-auto font-medium">
                Book appointments with verified doctors in your preferred location.
            </p>
        </div>

        <!-- Search Bar Card -->
        <div class="bg-slate-900/40 border border-slate-800/60 p-4 rounded-[2.5rem] shadow-2xl backdrop-blur-xl">
            <form wire:submit.prevent="search" class="flex flex-col lg:flex-row items-stretch gap-4">
                
                @php 
                    $selectClasses = "w-full bg-slate-950 border border-slate-800 text-slate-200 rounded-2xl pl-12 pr-10 py-4 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 appearance-none cursor-pointer transition-all";
                    $iconClasses = "absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 w-5 h-5 pointer-events-none";
                    $arrowClasses = "absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 w-4 h-4 pointer-events-none";
                @endphp

                <!-- Specialization Dropdown -->
                <div class="flex-1 relative">
                    <svg class="{{ $iconClasses }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <select wire:model="specialization" class="{{ $selectClasses }}">
                        <option value="" class="bg-slate-900">Select Specialisation</option>
                        @foreach ($this->specializations as $s)
                            <option value="{{ $s->id }}" class="bg-slate-900">{{ $s->name }}</option>
                        @endforeach
                    </select>
                    <svg class="{{ $arrowClasses }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                </div>

                <!-- State Dropdown -->
                <div class="flex-1 relative">
                    <svg class="{{ $iconClasses }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    </svg>
                    <select wire:model.live="state" class="{{ $selectClasses }}">
                        <option value="" class="bg-slate-900">Select State</option>
                        @foreach ($this->states as $st)
                            <option value="{{ $st->name }}" class="bg-slate-900">{{ Str::ucfirst($st->name) }}</option>
                        @endforeach
                    </select>
                    <svg class="{{ $arrowClasses }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                </div>

                <!-- City Dropdown -->
                <div class="flex-1 relative">
                    <svg class="{{ $iconClasses }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-7h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <select wire:model.live="city" class="{{ $selectClasses }}">
                        <option value="" class="bg-slate-900">Select City</option>
                        @foreach ($this->cities as $c)
                            <option value="{{ $c->name }}" class="bg-slate-900">{{ Str::ucfirst($c->name) }}</option>
                        @endforeach
                    </select>
                    <svg class="{{ $arrowClasses }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="bg-primary-600 hover:bg-primary-500 text-white font-black uppercase tracking-widest px-12 py-4 rounded-2xl shadow-xl shadow-blue-900/40 transition-all active:scale-95 whitespace-nowrap">
                    Search Now
                </button>
            </form>
        </div>
    </div>
</div>
