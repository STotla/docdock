{{-- filepath: e:\laracasts\docdock\resources\views\livewire\patient\doctors\show.blade.php --}}
@php
$doctor= $this->doctor;
$name = data_get($doctor,'user.name');
$speciality= data_get($doctor,'specialization.name');
$avatarUrl = data_get($doctor, 'profile_img_url');
$avatar= asset('/storage/'.$avatarUrl);
$experience = data_get($doctor,'experience');
$fee = Number::currency((data_get($doctor,'consultation_fee')),data_get($doctor,'currency'));
$about = data_get($doctor,'bio');
$location =  data_get($doctor,'clinic_name');
$clinicAddress = data_get($doctor,'clinic_address');
$city = data_get($doctor,'city');
$state = data_get($doctor,'state');




//$speciality = $doctor->specialization->name;

    $rating = data_get($doctor, 'average_rating', '');
@endphp

<div class="min-h-screen bg-slate-950 text-slate-100 antialiased font-sans">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        
        <!-- Top Navigation -->
        <div class="mb-10 flex items-center justify-between">
            <a href="/doctors" wire:navigate 
               class="group inline-flex items-center gap-2 text-sm font-bold text-slate-400 hover:text-blue-400 transition-colors">
                <div class="p-2 bg-slate-900 border border-slate-800 rounded-lg group-hover:border-blue-500/50">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </div>
                Return to Directory
            </a>
            <div class="hidden sm:block">
                <span class="inline-flex items-center gap-2 px-3 py-1 bg-green-500/10 border border-green-500/20 rounded-full text-[10px] font-black uppercase tracking-widest text-green-500">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                    </span>
                    Accepting New Patients
                </span>
            </div>
        </div>

        <div class="grid gap-8 lg:grid-cols-3">
            <!-- Left Column: Primary Content -->
            <section class="lg:col-span-2 space-y-8">
                
                
                <div class="relative overflow-hidden rounded-3xl border border-slate-800 bg-slate-900 shadow-2xl">
                    <div class="absolute top-0 right-0 p-8 opacity-10">
                        <svg class="w-32 h-32 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-2 10h-4v4h-2v-4H7v-2h4V7h2v4h4v2z"/></svg>
                    </div>
                    
                    <div class="relative flex flex-col gap-8 p-8 sm:flex-row sm:items-center">
                        <div class="relative h-32 w-32 shrink-0">
                            <div class="h-full w-full overflow-hidden rounded-2xl border-2 border-slate-700 bg-slate-800 shadow-xl">
                                @if($avatar)
                                    <img src="{{ $avatar }}" alt="{{ $name }}" class="h-full w-full object-cover scale-105 group-hover:scale-110 transition-transform">
                                @else
                                    <div class="flex h-full w-full items-center justify-center text-3xl font-black text-slate-500 bg-slate-800">
                                        {{ strtoupper(substr($name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="flex-1">
                            <div class="flex flex-wrap items-center gap-2 mb-2">
                                <span class="text-xs font-black uppercase tracking-[0.2em] text-blue-500">{{ $speciality }}</span>
                            </div>
                            <h1 class="text-4xl font-black tracking-tight text-white mb-4">{{ $name }}</h1>

                            <div class="flex flex-wrap items-center gap-3">
                                <div class="flex items-center gap-1.5 px-3 py-1.5 bg-slate-800 rounded-lg border border-slate-700/50">
                                    <span class="text-amber-400 font-bold">⭐ {{ $rating ?: 'N/A' }}</span>
                                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-tighter">Rating</span>
                                </div>
                                <div class="flex items-center gap-1.5 px-3 py-1.5 bg-slate-800 rounded-lg border border-slate-700/50">
                                    <span class="text-slate-100 font-bold">🩺 {{ $experience }}y</span>
                                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-tighter">Experience</span>
                                </div>
                                <div class="flex items-center gap-1.5 px-3 py-1.5 bg-slate-800 rounded-lg border border-slate-700/50 text-slate-300">
                                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                    <span class="text-xs font-bold">{{ $city }}, {{ $state }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Scheduling Section -->
                <div class="rounded-3xl border border-slate-800 bg-slate-900 p-8 shadow-xl">
                    <div class="  pb-6 flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-black text-white uppercase tracking-wider">Book a Slot</h2>
                            <p class="text-xs text-slate-500 mt-1">Select a date to view available timings</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Consultation Fee</p>
                            <p class="text-2xl font-black text-blue-500">{{ $fee }}</p>
                        </div>
                    </div>
                    
                    <livewire:patient.doctor-date-strip-availability :doctorId="$doctor->id" />
                </div>

                <!-- Reviews Display -->
                <div class="rounded-3xl border border-slate-800 bg-slate-900/50 p-1">
                    <livewire:patient.doctors.feature.review-display :doctor="$doctor"/>
                </div>
            </section>

            <!-- Right Column: Sidebar -->
            <aside class="space-y-6 lg:sticky lg:top-10 h-fit">
                
                <!-- Professional Bio -->
                <div class="rounded-3xl border border-slate-800 bg-slate-900 p-8 shadow-2xl">
                    <h2 class="text-xs font-black text-slate-500 uppercase tracking-[0.3em] mb-4">About Professional</h2>
                    <p class="text-sm leading-8 text-slate-300 font-medium">
                        {{ $about }}
                    </p>
                </div>

                <!-- Location Details -->
                <div class="rounded-3xl border border-slate-800 bg-blue-600/5 p-8 shadow-xl relative overflow-hidden group border-l-4 border-l-blue-600">
                    <div class="absolute -top-4 -right-4 text-blue-600 opacity-10 transition-transform group-hover:rotate-12">
                        <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                    </div>
                    
                    <h2 class="text-xs font-black text-blue-500 uppercase tracking-[0.3em] mb-4">Practice Location</h2>
                    <h3 class="text-lg font-bold text-white mb-2">{{ $location }}</h3>
                    <p class="text-sm leading-relaxed text-slate-400">
                        {{ $clinicAddress }}<br>
                        <span class="text-slate-300">{{ $city }}, {{ $state }}</span>
                    </p>
                    
                    <button class="mt-6 w-full py-3 bg-slate-800 hover:bg-slate-700 text-xs font-bold uppercase tracking-widest text-white rounded-xl transition-all border border-slate-700">
                        Get Directions
                    </button>
                </div>

                <!-- Quality Promise -->
                <div class="px-4 text-center">
                    <p class="text-[10px] text-slate-600 font-bold uppercase tracking-widest">
                        Verified Healthcare Professional
                    </p>
                </div>
            </aside>
        </div>
    </div>
</div>

