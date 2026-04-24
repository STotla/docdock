<div class="mx-auto w-full max-w-7xl px-6 py-12 lg:px-8 bg-slate-950">
    <!-- Header Area -->
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-4xl font-black tracking-tight text-white">Available <span
                    class="text-primary-500">Specialists</span></h1>
            <p class="mt-2 text-slate-400 font-medium">Verified experts ready to help you.</p>
        </div>

       
    </div>

    <!-- Search & Filter Dock -->
    <!-- Search & Filter Dock -->
    <div class="mb-10 p-3 bg-slate-900/40 border border-slate-800/60 rounded-[2.5rem] backdrop-blur-xl shadow-2xl">
        <div class="flex flex-col lg:flex-row items-center gap-4 lg:gap-2">

            <!-- 1. Search by Name -->
            <div class="flex-[1.5] flex items-center gap-3 px-6 py-2 w-full">
                <x-heroicon-o-magnifying-glass class="w-5 h-5 text-primary-500 shrink-0" />
                <input type="text" wire:model.live="searchName" placeholder="Search doctor name..."
                    class="bg-transparent border-none focus:ring-0 text-white placeholder-slate-600 w-full text-sm font-medium" />
            </div>

            <div class="hidden lg:block w-px h-10 bg-slate-800/50"></div>

            <!-- 2. Range Slider -->
            <div class="flex-[2] flex flex-col px-6 w-full group">
                <div class="flex justify-between items-center mb-3">
                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Search Radius</span>
                    <span class="text-xs font-black text-primary-400 bg-primary-500/10 px-3 py-1 rounded-full">
                        @if($geoLocationLoaded && ($userLat && $userLng))
                            {{ $radius > 200 ? 'Anywhere' : $radius . ' KM' }}
                        @else
                            <span class="animate-pulse">Loading location...</span>
                        @endif
                    </span>
                </div>
                <input type="range"
                    wire:model.live.debounce.300ms="radius"
                    {{ !$geoLocationLoaded || !($userLat && $userLng) ? 'disabled' : '' }}
                    min="10" max="210" step="10"
                    class="w-full h-2 bg-gradient-to-r from-slate-700 to-slate-800 rounded-lg appearance-none cursor-pointer accent-primary-500 hover:accent-primary-400 transition-all focus:outline-none focus:ring-2 focus:ring-primary-500/50 disabled:opacity-50 disabled:cursor-not-allowed">
                <div wire:loading.delay class="mt-2 text-xs text-primary-400 animate-pulse">Searching...</div>
                @if(!$geoLocationLoaded)
                    <div class="mt-2 text-xs text-slate-400">Waiting for location access...</div>
                @elseif(!($userLat && $userLng))
                    <div class="mt-2 text-xs text-yellow-400">Location not available - showing all doctors</div>
                @endif
            </div>

            <div class="hidden lg:block w-px h-10 bg-slate-800/50"></div>

            <!-- 3. Sorting Feature -->
            <div class="flex-1 flex items-center gap-3 px-6 py-2 w-full lg:w-auto group">
                <x-heroicon-o-bars-3-bottom-left class="w-4 h-4 text-primary-500 shrink-0 transition-colors group-hover:text-primary-400" />
                <div class="relative w-full lg:w-auto flex-1 lg:flex-none">
                    <select wire:model.live="sort_by"
                        class="w-full bg-transparent border-b-2 border-slate-700 hover:border-primary-500/70 focus:border-primary-500 focus:outline-none focus:ring-0 text-slate-200 text-xs font-bold py-2 px-0 cursor-pointer appearance-none uppercase tracking-wider transition-all duration-300">
                        <option value="recommended" class="bg-slate-900 text-slate-200">Recommended</option>
                        <option value="rating_high" class="bg-slate-900 text-slate-200">Top Rated</option>
                        <option value="exp_high" class="bg-slate-900 text-slate-200">Experience</option>
                        <option value="fee_low" class="bg-slate-900 text-slate-200">Fee: Low</option>
                    </select>
                    <div class="absolute right-0 top-1/2 -translate-y-1/2 pointer-events-none">
                        <x-heroicon-m-chevron-down class="w-4 h-4 text-slate-400 group-hover:text-primary-400 transition-colors duration-300" />
                    </div>
                </div>
            </div>

            <!-- 4. Reset Button -->
            @if($this->searchName || $this->radius != 50 || $this->sort_by != 'recommended')
                <button wire:click="resetFilters"
                    class="px-6 py-2 text-[10px] font-black text-red-500/80 hover:text-red-400 transition-colors uppercase tracking-tighter whitespace-nowrap">
                    Reset
                </button>
            @endif
        </div>
    </div>



    <!-- Doctor Grid -->
    <div wire:target="searchName,radius,sort_by" class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
        <!-- Skeleton Loaders (shown during loading) -->
        <div wire:loading class="contents">
            @for ($i = 0; $i < 12; $i++)
                <div class="rounded-[2.5rem] border border-slate-800 bg-slate-900/50 p-8 animate-pulse opacity-60">
                    <div class="flex items-start justify-between mb-6">
                        <div class="h-20 w-20 rounded-3xl bg-slate-800"></div>
                        <div class="flex flex-col items-end gap-2">
                            <div class="h-6 w-16 rounded-full bg-slate-800"></div>
                            <div class="h-6 w-20 rounded-full bg-slate-800"></div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <div class="h-6 w-32 rounded bg-slate-800 mb-2"></div>
                        <div class="h-4 w-40 rounded bg-slate-800"></div>
                    </div>

                    <div class="space-y-3 py-5 border-t border-slate-800/50">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-lg bg-slate-800"></div>
                            <div class="h-4 w-32 rounded bg-slate-800"></div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-lg bg-slate-800"></div>
                            <div class="h-4 w-24 rounded bg-slate-800"></div>
                        </div>
                    </div>

                    <div class="mt-8 flex items-center justify-between gap-4 border-t border-slate-800 pt-6">
                        <div>
                            <div class="h-4 w-20 rounded bg-slate-800 mb-2"></div>
                            <div class="h-8 w-24 rounded bg-slate-800"></div>
                        </div>
                        <div class="h-14 flex-1 rounded-2xl bg-slate-800"></div>
                    </div>
                </div>
            @endfor
        </div>
        <!-- Actual Doctor Cards (shown when data loaded) -->
        <div wire:loading.remove class="contents">
            @foreach ($doctors as $doctor)
                <div wire:key="doctor-{{ $doctor->id }}"
                    class="group relative flex flex-col justify-between rounded-[2.5rem] border border-slate-800 bg-slate-900/50 p-8 transition-all duration-500 hover:border-primary-500/30 hover:bg-slate-900 hover:-translate-y-2 opacity-0 animate-fadeIn">

                    <div>
                        <div class="flex items-start justify-between mb-6">
                            <div class="relative">
                                <img src="{{ asset('/storage/' . $doctor->profile_img_url) }}"
                                    class="h-20 w-20 rounded-3xl object-cover grayscale-[20%] group-hover:grayscale-0 transition-all duration-500">
                                <div
                                    class="absolute -bottom-1 -right-1 h-5 w-5 rounded-full border-4 border-slate-900 bg-green-500">
                                </div>
                            </div>

                            <div class="flex flex-col items-end gap-2">
                                <!-- Rating Badge -->
                                <div
                                    class="flex items-center gap-1 bg-amber-400/10 px-3 py-1 rounded-full border border-amber-400/20">
                                    <x-heroicon-s-star class="w-3 h-3 text-amber-400" />
                                    <span class="text-xs font-black text-amber-400">{{ $doctor->rating ?? '4.8' }}</span>
                                </div>

                                <!-- Distance Badge (New) -->
                                @if(isset($doctor->distance))
                                    <div
                                        class="flex items-center gap-1 {{ $doctor->distance < $this->radius ? 'bg-green-500/15 border border-green-500/30 text-green-400' : 'bg-slate-700/30 border border-slate-700/50 text-slate-500' }} px-3 py-1 rounded-full text-[10px] font-black tracking-tight uppercase transition-colors">
                                        <x-heroicon-s-map-pin class="w-3 h-3" />
                                        {{ number_format($doctor->distance, 1) }} KM
                                    </div>
                                @endif
                            </div>
                        </div>


                        <div class="mb-6">
                            <h3 class="text-xl font-black text-white group-hover:text-primary-400 transition-colors leading-tight">
                                {{ $doctor->user->name }}
                            </h3>
                            <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mt-1">
                                {{ $doctor->specialization->name }} • {{ $doctor->experience }} Yrs Exp
                            </p>
                        </div>

                        <div class="space-y-3 py-5 border-t border-slate-800/50">
                            <div class="flex items-center gap-3 text-sm text-slate-300 font-medium">
                                <div class="p-1.5 rounded-lg bg-slate-800 text-slate-500">
                                    <x-heroicon-s-building-office class="w-4 h-4" />
                                </div>
                                <span class="truncate">{{ $doctor->clinic_name }}</span>
                            </div>
                            <div class="flex items-center gap-3 text-sm text-slate-400">
                                <div class="p-1.5 rounded-lg bg-slate-800 text-slate-500">
                                    <x-heroicon-s-map-pin class="w-4 h-4" />
                                </div>
                                <span class="truncate">{{ $doctor->city }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex items-center justify-between gap-4 border-t border-slate-800 pt-6">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-500 mb-1">Consultation</p>
                            <p class="text-2xl font-black text-white italic">
                                ₹{{ number_format($doctor->consultation_fee) }}
                            </p>
                        </div>

                        <a href="{{ route('doctors.show', $doctor) }}" wire:navigate
                            class="h-14 flex-1 flex items-center justify-center rounded-2xl bg-primary-600 font-black text-white tracking-widest uppercase text-xs transition-all hover:bg-primary-500 hover:shadow-[0_0_20px_rgba(37,99,235,0.4)] active:scale-95">
                            View Profile
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Empty State -->
    @if($doctors->isEmpty())
        <div class="mt-12 rounded-[3rem] border-2 border-dashed border-slate-800/60 p-24 text-center">
            <x-heroicon-o-face-frown class="mx-auto h-16 w-16 text-slate-700 mb-4" />
            <h3 class="text-2xl font-black text-white">No Match Found</h3>
            <p class="mt-2 text-slate-500 font-medium">Try different keywords or reset your filters.</p>
        </div>
    @endif

    <div class="mt-16">
        {{-- {{ $doctors->links() }} --}}
    </div>
    <script>
        document.addEventListener('livewire:init', () => {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        @this.set('userLat', position.coords.latitude);
                        @this.set('userLng', position.coords.longitude);
                        @this.set('geoLocationLoaded', true);
                    },
                    (error) => {
                        console.warn("Location access denied by user.");
                        @this.set('geoLocationLoaded', true); // Mark as loaded even if denied
                    }
                );
            } else {
                @this.set('geoLocationLoaded', true); // Mark as loaded if geolocation not available
            }
        });
    </script>
    <style>
        input[type=range]::-webkit-slider-runnable-track {
            background: #1e293b;
            /* slate-800 */
            border-radius: 10px;
            height: 6px;
        }

        input[type=range]::-webkit-slider-thumb {
            margin-top: -5px;
            /* Centers thumb on track */
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .animate-fadeIn {
            animation: fadeIn 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }

        /* Stagger animation for each card using nth-child */
        .grid > .contents > div:nth-child(1) { animation-delay: 0s; }
        .grid > .contents > div:nth-child(2) { animation-delay: 0.05s; }
        .grid > .contents > div:nth-child(3) { animation-delay: 0.1s; }
        .grid > .contents > div:nth-child(4) { animation-delay: 0.15s; }
        .grid > .contents > div:nth-child(5) { animation-delay: 0.2s; }
        .grid > .contents > div:nth-child(6) { animation-delay: 0.25s; }
        .grid > .contents > div:nth-child(7) { animation-delay: 0.3s; }
        .grid > .contents > div:nth-child(8) { animation-delay: 0.35s; }
        .grid > .contents > div:nth-child(9) { animation-delay: 0.4s; }
        .grid > .contents > div:nth-child(10) { animation-delay: 0.45s; }
        .grid > .contents > div:nth-child(11) { animation-delay: 0.5s; }
        .grid > .contents > div:nth-child(12) { animation-delay: 0.55s; }
    </style>
</div>