<div class="min-h-screen bg-slate-950 text-slate-200 antialiased pb-20 selection:bg-primary-500/30">
    <div class="relative pt-24 pb-16 px-6 overflow-hidden">
        <div class="absolute inset-0 z-0">
            <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-primary-600/10 blur-[120px] rounded-full"></div>
            <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-primary-600/10 blur-[100px] rounded-full"></div>
        </div>

        <div class="relative z-10 max-w-4xl mx-auto text-center">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary-500/10 border border-primary-500/20 mb-6 backdrop-blur-md">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-primary-500"></span>
                </span>
                <span class="text-xs font-bold uppercase tracking-widest text-primary-400">Available in {{ $currentCity ?? 'Your Area' }}</span>
            </div>
            
            <h1 class="text-5xl md:text-7xl font-black text-white tracking-tighter mb-6">
                Choose a <span class="bg-clip-text text-transparent bg-gradient-to-r from-primary-400 via-primary-400 to-cyan-400">Specialization</span>
            </h1>
            
            <p class="text-slate-400 text-lg md:text-xl max-w-2xl mx-auto font-medium leading-relaxed">
                Select a category to view verified medical experts near your current location.
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
            @foreach($this->specializations as $s)
                <button wire:click="selectSpecialization({{ $s->id }})" 
                        class="group relative flex flex-col items-center p-8 bg-slate-900/20 border border-slate-800/40 rounded-[3rem] transition-all duration-700 hover:bg-slate-900/60 hover:border-primary-500/40 hover:-translate-y-4 shadow-2xl">
                    
                    <div class="absolute inset-0 bg-gradient-to-br from-primary-600/5 to-transparent opacity-0 group-hover:opacity-100 rounded-[3rem] transition-opacity duration-700"></div>

                    <div class="relative mb-8 transform transition-all duration-700 group-hover:rotate-6 group-hover:scale-110">
                        <div class="absolute inset-0 bg-primary-400/20 blur-3xl rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                        
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($s->icon) }}" 
                             alt="{{ $s->name }}" 
                             class="relative z-10 w-24 h-24 object-contain drop-shadow-[0_10px_10px_rgba(0,0,0,0.5)]" />
                    </div>

                    <h3 class="text-lg font-black tracking-tight text-slate-300 group-hover:text-white transition-colors duration-300 text-center">
                        {{ $s->name }}
                    </h3>

                    <span class="mt-2 text-xs font-bold text-slate-500 group-hover:text-primary-400 uppercase tracking-widest transition-colors">
                        {{ $s->doctors_count ?? 'Browse' }} Doctors
                    </span>

                    <div class="absolute bottom-6 opacity-0 group-hover:opacity-100 group-hover:bottom-8 transition-all duration-500">
                        <div class="bg-primary-600 p-2 rounded-full shadow-lg shadow-primary-600/40">
                            <x-heroicon-s-arrow-right class="w-4 h-4 text-white" />
                        </div>
                    </div>
                </button>
            @endforeach
        </div>

        <div class="mt-24 p-12 bg-slate-900/40 border border-slate-800/60 rounded-[3rem] text-center backdrop-blur-md">
            <h4 class="text-xl font-bold text-white mb-2">Can't find what you're looking for?</h4>
            <p class="text-slate-400 mb-6">Our support team is available 24/7 to assist with your medical search.</p>
            <button class="px-8 py-3 bg-slate-800 hover:bg-slate-700 text-white rounded-2xl font-bold transition-all">
                Contact Support
            </button>
        </div>
    </div>
</div>
