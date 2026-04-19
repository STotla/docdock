<div class="mx-auto grid w-full max-w-7xl gap-10 px-6 py-20  lg:items-center lg:px-8 lg:py-24">
    <div class="flex items-start justify-between gap-4 flex-wrap ">
        <div>
            <h1 class="text-white text-3xl font-bold leading-tight tracking-tight sm:text-5xl mb-4">
                {{ $this->greeting }},
                <span class="text-teal-400">{{ explode(' ', $this->patient->name)[0] }}</span> 👋
            </h1>

            <p class="  flex items-center text-sm text-slate-400">
                <span class="relative flex size-3 mr-3 ">
                    <span
                        class="absolute inline-flex h-full w-full animate-ping rounded-full bg-sky-400 opacity-75"></span>
                    <span class="relative inline-flex size-3 rounded-full bg-sky-500"></span>
                </span>
                <span>
                    @if ($this->upcomingAppointmentCount > 0)
                        You have <strong class="text-slate-200">{{ $this->upcomingAppointmentCount }} upcoming
                            {{ Str::plural('appointment', $this->upcomingAppointmentCount) }}</strong> in the next 7 days
                    @else
                        No upcoming appointments — your schedule is clear
                    @endif
                </span>
            </p>
        </div>

    </div>
    <div class="flex items-center gap-2.5">
        <div class="bg-slate-800 border border-white/5 rounded-xl px-4 py-2 text-xs text-slate-400">
            {{ now()->format('D, M j Y') }}
        </div>
        <a href="{{ route('doctors.search') }}"
            class="px-4 py-2 rounded-xl text-sm font-semibold bg-teal-500 hover:bg-teal-600 text-slate-900 transition-all duration-200 hover:-translate-y-0.5 active:scale-95">
            + Book Appointment
        </a>
    </div>

    @if ($this->isNewPatient)
        <div class="slide-in flex items-center gap-4 rounded-2xl border border-teal-500/20 p-5 mb-7"
            style="background: linear-gradient(135deg, rgba(20,184,166,0.1), rgba(139,92,246,0.06))">
            {{-- Pulsing star icon --}}
            <div class="w-11 h-11 shrink-0 rounded-xl flex items-center justify-center"
                style="background:var(--teal-glow); border:1px solid rgba(20,184,166,0.25)">
                <svg class="w-5 h-5 text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-sm font-medium text-teal-400 mb-0.5">Welcome to DocDock!</p>
                <p class="text-sm text-slate-300">
                    It looks like you're <strong class="text-slate-100">new here</strong>. Book your first appointment to
                    get started on your health journey.
                </p>
            </div>
            <a href="{{ route('doctors.search') }}"
                class="shrink-0 px-5 py-2.5 rounded-xl text-sm font-semibold bg-teal-500 hover:bg-teal-600 text-slate-900 transition-all duration-200 whitespace-nowrap hover:-translate-y-0.5 active:scale-95">
                Find Doctors →
            </a>
        </div>
    @endif
    <div class="grid grid-cols-2 gap-3.5 mb-7">
        <div class="grid grid-cols-2 gap-3.5 mb-7">
            @php
                $statCards = [
                    [
                        'color' => '#14b8a6',
                        'bg' => 'rgba(20,184,166,0.1)',
                        'icon' => 'heroicon-m-calendar',
                        'value' => $this->upcomingAppointmentCount,
                        'label' => 'Upcoming appointments',
                        'sub' => $this->upcomingAppointmentCount > 0 ? 'Next: ' . \Carbon\Carbon::parse($this->upcomingAppointments->first()?->appointment_date)->format('d F') : 'Schedule is clear',
                        'subClr' => $this->upcomingAppointmentCount > 0 ? '#22c55e' : '#64748b',
                    ],
                    [
                        'color' => '#14b8a6',
                        'bg' => 'rgba(20,184,166,0.1)',
                        'icon' => 'heroicon-m-calendar',
                        'value' => $this->pastAppointmentsCount,
                        'label' => 'Completed Appointments',
                        'sub' => 'Appintments done so far',
                        'subClr' => '#14b8a6'
                    ],
                    [
                        'color' => '#F97316',
                        'icon' => 'heroicon-m-currency-rupee',
                        'bg' => 'rgba(20,184,166,0.1)',
                        'value' => Number::currency($this->totalRevenueSpent, 'inr'),
                        'label' => 'Expenditure',
                        'sub' => 'Money Spent so far',
                        'subClr' => '#14b8a6'
                    ]

                ];
            @endphp


            @foreach ($statCards as $card)
                <div
                    class="bg-slate-900 border border-white/[0.06] rounded-xl p-5 relative overflow-hidden hover:border-white/10 transition-all duration-200 group">
                    <div class="absolute top-0 left-0 right-0 h-0.5 rounded-t-xl" style="background:{{ $card['color'] }}">
                    </div>

                    <div class="w-9 h-9 rounded-lg flex items-center justify-center mb-4"
                        style="background:{{ $card['bg'] }}">
                        <x-dynamic-component :component="$card['icon']" class="w-5 h-5"
                            style="color:{{ $card['color'] }}" />
                    </div>

                    <p class="text-2xl font-semibold text-white mb-0.5">{{ $card['value'] }}</p>
                    <p class="font-bold text-xl text-slate-400">{{ $card['label'] }}</p>
                    <p class="text-xs mt-1.5" style="color:{{ $card['subClr'] }}">{{ $card['sub'] }}</p>
                </div>
            @endforeach
        </div>

        <div class="space-y-5">
            <!-- Upcoming Appointments Container -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden flex flex-col shadow-2xl">

                <!-- Header with Live Pulse -->
                <div
                    class="flex items-center justify-between px-6 py-5 border-b border-slate-800/60 bg-slate-900/50 backdrop-blur-sm">
                    <div class="flex items-center gap-3">
                        <div class="relative flex h-2 w-2">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                        </div>
                        <h2 class="text-sm font-black uppercase tracking-widest text-slate-100">Next 7 Days</h2>
                    </div>

                    <span
                        class="text-[10px] font-black uppercase tracking-[0.2em] px-3 py-1.5 text-blue-400 bg-blue-500/10 border border-blue-500/20 rounded-lg">
                        {{ $this->upcomingAppointments->count() }} Scheduled
                    </span>
                </div>

                <!-- Scrollable List -->
                <div class="relative">
                    <div
                        class="max-h-[300px] overflow-y-auto scrollbar-thin scrollbar-thumb-slate-800 scrollbar-track-transparent">

                        @if ($this->upcomingAppointments->isEmpty())
                            <div class="flex flex-col items-center justify-center py-16 px-6 text-center">
                                <div class="p-4 bg-slate-950 rounded-full border border-slate-800 mb-4">
                                    <svg class="w-8 h-8 text-slate-700" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="1.5">
                                        <rect x="3" y="4" width="18" height="18" rx="2" />
                                        <path d="M16 2v4M8 2v4M3 10h18" />
                                    </svg>
                                </div>
                                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-4">Your schedule is
                                    clear</p>
                                <a href="{{ route('doctors.search') }}" wire:navigate
                                    class="px-6 py-2.5 text-[10px] font-black uppercase tracking-widest bg-blue-600 hover:bg-blue-500 text-white rounded-xl transition-all shadow-lg shadow-blue-900/20">
                                    Find a Doctor
                                </a>
                            </div>
                        @else
                            <div class="divide-y divide-slate-800/40">
                                @foreach ($this->upcomingAppointments as $appt)
                                    <a wire:navigate href="{{ route('patient.appointments.show', $appt) }}"
                                        class="flex items-center gap-5 px-6 py-5 hover:bg-slate-800/30 transition-all group">

                                        <!-- Modern Date Badge -->
                                        <div
                                            class="text-center bg-slate-950 border border-slate-800 rounded-2xl px-3 py-2.5 shrink-0 min-w-[56px] group-hover:border-blue-500/30 transition-colors">
                                            <p class="text-xl font-black text-white leading-none">
                                                {{ \Carbon\Carbon::parse($appt->appointment_date)->format('j') }}
                                            </p>
                                            <p class="text-[9px] font-black uppercase tracking-widest text-blue-500 mt-1">
                                                {{ \Carbon\Carbon::parse($appt->appointment_date)->format('M') }}
                                            </p>
                                        </div>

                                        <!-- Doctor Info -->
                                        <div class="flex-1 min-w-0">
                                            <p
                                                class="text-sm font-bold text-slate-100 group-hover:text-blue-400 transition-colors truncate">
                                                Dr. {{ $appt->doctor->user->name }}
                                            </p>
                                            <div class="flex items-center gap-2 mt-1">
                                                <span
                                                    class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">{{ $appt->doctor->specialization->name }}</span>
                                                <span class="h-1 w-1 bg-slate-700 rounded-full"></span>
                                                <span
                                                    class="text-[10px] font-medium text-slate-500 truncate">{{ $appt->doctor->clinic_name }}</span>
                                            </div>
                                        </div>

                                        <!-- Semantic Status Pill -->
                                        @php
                                            $statusClasses = [
                                                'confirmed' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                                'booked' => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
                                                'pending' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                                                'cancelled' => 'bg-rose-500/10 text-rose-400 border-rose-500/20',
                                            ];
                                            $currentClass = $statusClasses[$appt->status] ?? 'bg-slate-800 text-slate-400 border-slate-700';
                                        @endphp

                                        <span
                                            class="shrink-0 text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded-lg border {{ $currentClass }}">
                                            {{ $appt->status }}
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Fade Overlay -->
                    <div
                        class="absolute bottom-0 left-0 right-0 h-10 bg-gradient-to-t from-slate-900 to-transparent pointer-events-none">
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden flex flex-col shadow-2xl">
                <!-- 1. Header with Glass Switcher -->
               <div class="flex flex-col sm:flex-row items-center justify-between px-6 py-5 border-b border-slate-800/60 bg-slate-900/50 backdrop-blur-md gap-4">
    <!-- Section Title with Teal Accent -->
    <div class="flex items-center gap-4">
        <div class="h-8 w-1 bg-gradient-to-b from-teal-400 to-teal-600 rounded-full shadow-[0_0_15px_rgba(20,184,166,0.3)]"></div>
        <div>
            <h2 class="text-sm font-black uppercase tracking-[0.2em] text-white">Past Visits</h2>
            <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mt-0.5">Medical History</p>
        </div>
    </div>

    <!-- Segmented Filter Switcher -->
    <div class="flex p-1 bg-slate-950 border border-slate-800 rounded-2xl shadow-inner group">
        @foreach (['all' => 'All Records', 'recent' => 'Recent'] as $key => $label)
            <button wire:click="setVisitFilter('{{ $key }}')" 
                class="relative text-[10px] font-black uppercase tracking-widest px-5 py-2 rounded-xl transition-all duration-300
                {{ $this->visitFilter === $key 
                    ? 'bg-teal-600 text-white shadow-lg shadow-teal-900/40 border border-teal-500/50' 
                    : 'text-slate-500 hover:text-slate-300' }}">
                {{ $label }}
            </button>
        @endforeach
    </div>
</div>


                <!-- 2. Scrollable Body with Fading Edge -->
                <div class="relative">
                    <div
                        class="max-h-[400px] overflow-y-auto overflow-x-hidden scrollbar-thin scrollbar-thumb-slate-800 scrollbar-track-transparent">

                        <!-- Loading State (Matches Actual Row Height) -->
                        <div wire:loading wire:target="setVisitFilter" class="w-full">
                            @for ($i = 0; $i < 3; $i++)
                                <div class="flex items-center gap-5 px-6 py-5 border-b border-slate-800/40 animate-pulse">
                                    <div class="h-12 w-12 bg-slate-800 rounded-xl"></div>
                                    <div class="flex-1 space-y-3">
                                        <div class="h-3 w-1/3 bg-slate-800 rounded"></div>
                                        <div class="h-2 w-2/3 bg-slate-800/50 rounded"></div>
                                    </div>
                                </div>
                            @endfor
                        </div>

                        <!-- Visits List -->
                        <div wire:loading.remove wire:target="setVisitFilter">
                            @forelse ($this->lastVisits as $visit)
                                <a wire:navigate href="{{ route('patient.appointments.show', $visit) }}"
                                    class="group flex items-center gap-5 px-6 py-5 border-b border-slate-800/40 last:border-0 hover:bg-slate-800/30 transition-all">

                                    <!-- Date Block -->
                                    <div
                                        class="text-center bg-slate-950 border border-slate-800 rounded-2xl px-3 py-2.5 shrink-0 min-w-[56px] transition-transform group-hover:scale-105">
                                        <p class="text-l font-black text-white leading-none">
                                            {{ \Carbon\Carbon::parse($visit->appointment_date)->format('j') }}
                                        </p>
                                        <p class="text-[9px] font-black uppercase tracking-widest text-teal-500 mt-1">
                                            {{ \Carbon\Carbon::parse($visit->appointment_date)->format('M') }}
                                        </p>
                                    </div>

                                    <!-- Info -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between mb-1">
                                            <p
                                                class="text-sm font-bold text-slate-100 group-hover:text-teal-400 transition-colors truncate">
                                                {{ $visit->doctor->user->name }}
                                            </p>
                                            <span
                                                class="text-[9px] font-black uppercase tracking-widest text-slate-500 bg-slate-800 px-2 py-0.5 rounded border border-slate-700">
                                                {{ $visit->status }}
                                            </span>
                                        </div>

                                        <p class="text-xs text-slate-400 font-medium truncate">
                                            {{ $visit->doctor->specialization->name }} ·
                                            <span class="text-slate-500 italic">{{ Str::limit($visit->notes, 30) }}</span>
                                        </p>

                                        <p class="text-[11px] text-slate-500 mt-1.5 flex items-center gap-1.5">
                                            <svg class="w-3 h-3 text-teal-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ $visit->doctor_notes ?? 'Routine check-up completed' }}
                                        </p>
                                    </div>
                                </a>
                            @empty
                                <div class="flex flex-col items-center justify-center py-20 text-center">
                                    <svg class="w-12 h-12 text-slate-800 mb-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p class="text-sm font-bold text-slate-600 uppercase tracking-widest">No Visits Record
                                        Found</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Bottom Fade Effect (Indicates more content) -->
                    <div
                        class="absolute bottom-0 left-0 right-0 h-12 bg-gradient-to-t from-slate-900 to-transparent pointer-events-none">
                    </div>
                </div>
            </div>

        </div>
    </div>


</div>