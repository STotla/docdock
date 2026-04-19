<div class="rounded-3xl   p-8 shadow-2xl backdrop-blur-sm">
    <!-- 1. Interactive Rating Header -->
    <div class="mb-10">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h3 class="text-2xl font-black text-white tracking-tight">Patient Experiences</h3>
                <p class="text-xs text-slate-500 uppercase tracking-widest font-bold mt-1">Verified Feedback</p>
            </div>
            <div class="hidden sm:block text-right">
                <div class="text-2xl font-black text-amber-400">★ {{ number_format($doctor->average_rating ?? 5.0, 1) }}</div>
                <div class="text-[10px] font-bold text-slate-500 uppercase tracking-tighter">Overall Score</div>
            </div>
        </div>

        <div class="grid grid-cols-1">
            @foreach($stats as $star => $data)
            <button wire:click="setFilter({{ $star }})" 
                class="group flex items-center gap-4 p-2 rounded-xl transition-all duration-300 {{ $ratingFilter == $star ? 'bg-amber-500/10 ring-1 ring-amber-500/50' : 'hover:bg-slate-800/60' }}">
                <span class="w-16 text-xs font-black {{ $ratingFilter == $star ? 'text-amber-400' : 'text-slate-400' }} tracking-tighter">{{ $star }} Stars</span>
                
                <div class="flex-1 h-2 bg-slate-800 rounded-full overflow-hidden border border-slate-700/50">
                    <div class="h-full bg-gradient-to-r from-amber-500 to-orange-500 rounded-full transition-all duration-700 ease-out shadow-[0_0_8px_rgba(245,158,11,0.4)]" 
                         style="width: {{ $data['percent'] }}%"></div>
                </div>
                
                <span class="w-12 text-[10px] font-black {{ $ratingFilter == $star ? 'text-amber-400' : 'text-slate-500' }} text-right group-hover:text-slate-300">
                    {{ round($data['percent']) }}%
                </span>
            </button>
            @endforeach
        </div>
    </div>

    <!-- 2. Refined Control Bar -->
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 border-y border-slate-800/60 py-5 mb-10">
        <div class="flex items-center gap-3">
            <span class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Order By</span>
            <div class="flex bg-slate-950 p-1 rounded-xl border border-slate-800">
                <button wire:click="setSort('latest')" 
                    class="px-4 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all {{ $sort === 'latest' ? 'bg-primary-600 text-white shadow-lg shadow-primary-900/20' : 'text-slate-500 hover:text-slate-300' }}">
                    Newest
                </button>
                <button wire:click="setSort('oldest')" 
                    class="px-4 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all {{ $sort === 'oldest' ? 'bg-primary-600 text-white shadow-lg shadow-primary-900/20' : 'text-slate-500 hover:text-slate-300' }}">
                    Oldest
                </button>
            </div>
        </div>

        @if($ratingFilter)
            <button wire:click="setFilter(null)" class="flex items-center gap-2 px-3 py-1.5 bg-amber-500/10 border border-amber-500/20 rounded-xl group transition-all hover:bg-amber-500/20">
                <span class="text-[10px] text-amber-500 font-black uppercase tracking-widest">Clear {{ $ratingFilter }} Star Filter</span>
                <svg class="w-3 h-3 text-amber-500 group-hover:rotate-90 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        @endif
    </div>

    <!-- 3. Modern Review Feed -->
    <div class="space-y-4">
        @forelse($reviews as $review)
            <div class="group p-6 bg-slate-950/40 rounded-2xl border border-slate-800 hover:border-slate-700 transition-all duration-300 border-l-4 border-l-transparent hover:border-l-amber-500 shadow-sm">
                <div class="flex flex-col gap-4">
                    <div class="flex justify-between items-start">
                        <!-- Star Rating -->
                        <div class="flex gap-0.5 text-amber-400">
                            @for($i = 0; $i < 5; $i++)
                                <svg class="w-4 h-4 {{ $i < $review->rating ? 'fill-current drop-shadow-[0_0_3px_rgba(245,158,11,0.5)]' : 'text-slate-800' }}" viewBox="0 0 20 20">
                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                </svg>
                            @endfor
                        </div>
                        <span class="text-[10px] font-bold text-slate-600 uppercase tracking-tighter bg-slate-900 px-2 py-1 rounded border border-slate-800 group-hover:text-slate-400 transition-colors">
                            {{ $review->created_at->diffForHumans() }}
                        </span>
                    </div>

                    <!-- Review Text -->
                    <p class="text-sm text-slate-300 leading-relaxed font-medium italic">
                        "{{ $review->review }}"
                    </p>

                    <!-- User Info Footer -->
                    <div class="flex items-center gap-3 pt-3 border-t border-slate-800/50">
                        <div class="h-8 w-8 rounded-full bg-slate-800 flex items-center justify-center text-[10px] font-black text-slate-500 border border-slate-700">
                            {{ strtoupper(substr($review->user->name, 0, 1)) }}
                        </div>
                        <span class="text-xs font-bold text-slate-400 group-hover:text-white transition-colors">
                            {{ $review->user->name }}
                        </span>
                        <span class="h-1 w-1 bg-slate-700 rounded-full"></span>
                        <span class="text-[10px] font-bold text-emerald-500/70 uppercase tracking-widest">Verified Patient</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-20 bg-slate-950/20 border-2 border-dashed border-slate-800 rounded-3xl">
                <div class="text-slate-700 mb-4">
                    <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.26 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                </div>
                <p class="text-slate-500 font-bold uppercase tracking-widest text-xs">No feedback found for this selection.</p>
            </div>
        @endforelse

        <!-- Infinite Scroll -->
        @if($reviews->hasMorePages())
            <div x-intersect="$wire.loadMore()" class="py-10 flex flex-col items-center gap-3">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-amber-500 shadow-[0_0_10px_rgba(245,158,11,0.2)]"></div>
                <span class="text-[10px] font-black text-slate-600 uppercase tracking-widest">Fetching more feedback</span>
            </div>
        @endif
    </div>
</div>
