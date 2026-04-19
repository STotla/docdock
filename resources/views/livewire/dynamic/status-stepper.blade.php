@php
    $steps = [
        ['key' => 'pending',     'label' => 'Booked',      'sub' => 'Just booked'],
        ['key' => 'confirmed',   'label' => 'Confirmed',   'sub' => 'Doctor confirmed'],
        ['key' => 'in_progress', 'label' => 'In Progress', 'sub' => 'Appointment ongoing'],
        ['key' => 'completed',   'label' => 'Completed',   'sub' => 'All done'],
    ];

    $order   = ['pending' => 0, 'confirmed' => 1, 'in_progress' => 2, 'completed' => 3];
    $current = $order[$status] ?? 0;
@endphp

<div class="flex items-start w-full pt-2 pb-8">
    @foreach ($steps as $i => $step)
        @php
            $isDone   = $i < $current;
            $isActive = $i === $current;
        @endphp

        <div class="flex flex-col items-center flex-1 relative">

            {{-- Connector line --}}
            @if (!$loop->last)
                <div class="absolute top-[17px] left-1/2 right-[-50%] h-0.5 bg-slate-800 z-0">
                    <div class="h-full bg-indigo-500 transition-all duration-500 rounded-full"
                         style="width: {{ $isDone ? '100%' : ($isActive ? '50%' : '0%') }}"></div>
                </div>
            @endif

            {{-- Circle --}}
            <div class="relative z-10 w-9 h-9 rounded-full flex items-center justify-center border-2 transition-all duration-300
                {{ $isDone   ? 'bg-green-500 border-green-500' : '' }}
                {{ $isActive ? 'bg-indigo-500 border-indigo-500 ring-4 ring-indigo-500/20' : '' }}
                {{ !$isDone && !$isActive ? 'bg-slate-900 border-slate-700' : '' }}">

                @if ($isDone)
                    <svg class="w-4 h-4 text-white" viewBox="0 0 16 16" fill="none">
                        <polyline points="3,8 6.5,12 13,4"
                                  stroke="currentColor" stroke-width="2"
                                  stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                @elseif ($isActive)
                    <span class="w-2.5 h-2.5 rounded-full bg-white"></span>
                @else
                    <span class="w-2.5 h-2.5 rounded-full bg-slate-700"></span>
                @endif
            </div>

            {{-- Label --}}
            <span class="mt-2.5 text-[10px] font-medium uppercase tracking-wider text-center
                {{ $isDone   ? 'text-green-400' : '' }}
                {{ $isActive ? 'text-indigo-400' : '' }}
                {{ !$isDone && !$isActive ? 'text-slate-600' : '' }}">
                {{ $step['label'] }}
            </span>

            {{-- Sub-label --}}
            <span class="mt-0.5 text-[10px] text-center
                {{ $isDone || $isActive ? 'text-slate-500' : 'text-slate-800' }}">
                {{ $isDone ? 'Done' : ($isActive ? $step['sub'] : 'Pending') }}
            </span>
        </div>
    @endforeach
</div>