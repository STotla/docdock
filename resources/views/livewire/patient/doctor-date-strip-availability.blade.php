<div class=" border border-slate-800 rounded-2xl p-6 shadow-2xl">
    <!-- Header with Live Date -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-white tracking-tight">Select a Schedule</h2>
            <p class="text-xs text-slate-500 mt-1 uppercase tracking-widest font-bold">
                {{ \Carbon\Carbon::parse($selectedDate)->format('F Y') }}
            </p>
        </div>

        <div class="flex flex-col items-end">
            <span wire:loading wire:target="selectDate" class="flex items-center gap-2 text-[10px] font-black uppercase text-primary-500 tracking-tighter">
                <svg class="animate-spin h-3 w-3" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                Syncing Slots
            </span>
            <div wire:loading.remove wire:target="selectDate" class="text-[10px] font-black uppercase tracking-widest text-slate-400 bg-slate-800 px-2 py-1 rounded">
                {{ \Carbon\Carbon::parse($selectedDate)->format('D, d M') }}
            </div>
        </div>
    </div>

    <!-- Enhanced Date Strip -->
    <div class="flex gap-3 overflow-x-auto pb-4 scrollbar-hide">
        @foreach ($this->dates as $d)
        @php($active = $selectedDate === $d['date'])
        <button type="button" 
                wire:click="selectDate('{{ $d['date'] }}')" 
                wire:loading.attr="disabled"
                class="min-w-[75px] group flex flex-col items-center justify-center rounded-2xl py-3 border transition-all duration-300
                       {{ $active 
                          ? 'bg-primary-600 border-primary-500 shadow-lg shadow-primary-900/40 ring-4 ring-primary-500/10' 
                          : 'bg-slate-950 border-slate-800 hover:border-slate-600 hover:bg-slate-900' }}">
            
            <span class="text-[10px] font-black uppercase tracking-tighter mb-1 {{ $active ? 'text-primary-100' : 'text-slate-500 group-hover:text-slate-400' }}">
                {{ $d['label'] }}
            </span>
            <span class="text-xl font-black {{ $active ? 'text-white' : 'text-slate-200' }}">
                {{ $d['day'] }}
            </span>
            <span class="text-[10px] font-bold {{ $active ? 'text-primary-200' : 'text-slate-600' }}">
                {{ $d['month'] }}
            </span>
        </button>
        @endforeach
    </div>

    <!-- List Container -->
    <div class="mt-6 relative">
        
        {{-- SKELETON LOADER --}}
        <div wire:loading wire:target="selectDate" class="w-full space-y-3">
            @for ($x = 0; $x < 2; $x++)
                <div class="bg-slate-950 border border-slate-800 rounded-xl p-5 animate-pulse">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-4">
                            <div class="h-10 w-10 bg-slate-800 rounded-lg"></div>
                            <div class="space-y-2">
                                <div class="h-2 w-20 bg-slate-800 rounded"></div>
                                <div class="h-4 w-32 bg-slate-800 rounded"></div>
                            </div>
                        </div>
                        <div class="h-10 w-28 bg-slate-800 rounded-lg"></div>
                    </div>
                </div>
            @endfor
        </div>

        {{-- ACTUAL SLOTS --}}
        <div wire:loading.remove wire:target="selectDate" class="w-full space-y-3">
            @forelse ($this->instances as $i)
                <div class="group relative overflow-hidden bg-slate-950 border border-slate-800 rounded-2xl p-5 flex items-center justify-between hover:border-primary-500/50 transition-all hover:shadow-xl hover:shadow-primary-900/10 border-l-4 border-l-primary-600">
                    
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-slate-900 rounded-xl text-primary-500 border border-slate-800 group-hover:border-primary-500/30 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>

                        <div>
                            <div class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 mb-1">Morning Session</div>
                            <div class="text-base font-bold text-slate-100 flex items-center gap-3">
                                <span>{{ \Carbon\Carbon::parse($i->start_time)->format('g:i A') }}</span>
                                <span class="h-1 w-1 bg-slate-700 rounded-full"></span>
                                <span class="text-slate-400 font-medium">{{ \Carbon\Carbon::parse($i->end_time)->format('g:i A')}}</span>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('patient.appointments.book', ['doctor' => $doctor->id, 'date' => $selectedDate]) }}?instance={{ $i->id }}"
                        wire:navigate
                        class="px-6 py-3 bg-primary-600 hover:bg-primary-500 text-white text-xs font-black uppercase tracking-widest rounded-xl transition-all shadow-lg shadow-primary-900/30 active:scale-95">
                        Book Slot
                    </a>
                </div>
            @empty
                <div class="text-center py-10 bg-slate-950 rounded-2xl border border-dashed border-slate-800">
                    <div class="text-slate-600 mb-2">
                        <svg class="w-10 h-10 mx-auto opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <p class="text-slate-500 text-sm italic">No slots available for this date.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
