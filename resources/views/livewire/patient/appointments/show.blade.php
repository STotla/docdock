<?php
use Illuminate\Support\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
$doctor = $this->appointment->doctor;
$appointment = $this->appointment;
$doctorName = data_get($doctor, 'user.name');
$doctorAvatarUrl = data_get($doctor, 'profile_img_url');
$doctorAvatar = asset('/storage/' . $doctorAvatarUrl);
$doctorSpeciality = data_get($doctor, 'specialization.name');
$clinicAddress = data_get($doctor, 'clinic_address');
$clinicName = data_get($doctor, 'clinic_name');
$city = data_get($doctor, 'city');
$state = data_get($doctor, 'state');
$experience = data_get($doctor, 'experience');
$rating = data_get($doctor, 'rating', '4.8');

?>
<div class="bg-slate-950 min-h-screen">
    <div class="container  max-w-7xl px-4 py-10 sm:px-6 lg:px-8 mx-auto px-4 py-8 text-gray-100">
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-10 border-b border-slate-800 pb-6">
    <div>
        <!-- Breadcrumb / Small Label -->
        <div class="flex items-center gap-2 mb-2">
            <span class="text-[10px] font-black uppercase tracking-[0.3em] text-blue-500">Patient Dashboard</span>
            <svg class="w-3 h-3 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <span class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500">Booking View</span>
        </div>
        
        <!-- Main Title -->
        <h1 class="text-3xl md:text-4xl font-black tracking-tight text-white">
            Appointment <span class="text-slate-500">Details</span>
        </h1>
    </div>

    <!-- Actions -->
    <div class="flex items-center gap-3">
        {{-- Optional: Additional action buttons can go here --}}
        
        <a href="{{ route('patient.appointments') }}" wire:navigate
            class="group inline-flex items-center gap-2 px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-slate-300 hover:text-white border border-slate-800 rounded-xl transition-all shadow-lg active:scale-95">
            <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span class="text-xs font-bold uppercase tracking-widest">Back to List</span>
        </a>
    </div>
</div>




        <div class="grid gap-6 lg:grid-cols-3">
            <!-- Doctor Profile -->
           <section class="lg:col-span-2 space-y-8">
    <div class="rounded-3xl border border-slate-800 bg-slate-900/50 p-8 shadow-2xl backdrop-blur-sm border-l-4 border-l-blue-600">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-xs font-black uppercase tracking-[0.3em] text-slate-500">Live Journey</h2>
            <span class="px-3 py-1 bg-blue-500/10 border border-blue-500/20 rounded-lg text-[10px] font-black text-blue-400 uppercase tracking-widest">Appointment ID: #{{ $appointment->id }}</span>
        </div>
        <livewire:dynamic.status-stepper :status="$appointment->status" />
    </div>

    @if($appointment->status == 'completed' && $appointment->doctor_notes)
    <div class="rounded-3xl border border-slate-800 bg-blue-600/5 p-8 shadow-xl relative overflow-hidden group">
        <div class="absolute -top-6 -right-6 text-blue-600/10 group-hover:rotate-12 transition-transform">
            <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-2 10h-4v4h-2v-4H7v-2h4V7h2v4h4v2z"/></svg>
        </div>
        <h2 class="text-xs font-black uppercase tracking-[0.3em] text-blue-500 mb-4">Doctor's Consultation Notes</h2>
        <p class="text-lg leading-relaxed text-slate-200 italic font-medium">
            "{{ $appointment->doctor_notes }}"
        </p>
    </div>
    @endif

    <div class="rounded-3xl border border-slate-800 bg-slate-900/40 p-8 shadow-xl">
        <h2 class="text-xs font-black uppercase tracking-[0.3em] text-slate-500 mb-8">Patient Information</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            <div class="space-y-1">
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Name</p>
                <p class="text-base font-bold text-white">{{ $appointment->name }}</p>
            </div>
            <div class="space-y-1">
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Contact</p>
                <p class="text-base font-bold text-white">{{ $appointment->phone }}</p>
            </div>
            <div class="space-y-1">
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Demographics</p>
                <p class="text-base font-bold text-white">{{ $appointment->age ?? '-' }}y, {{ ucfirst($appointment->gender ?? '-') }}</p>
            </div>
            <div class="space-y-1">
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Identity</p>
                <p class="text-base font-bold text-white">#{{ $appointment->user_id ?? 'GUEST' }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="rounded-3xl border border-slate-800 bg-slate-900/40 p-8 flex flex-col justify-between">
            <h2 class="text-xs font-black uppercase tracking-[0.3em] text-slate-500 mb-6">Schedule</h2>
            <div class="space-y-4">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-slate-800 rounded-xl text-blue-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <p class="text-base font-bold text-white">{{ Carbon::parse($appointment->appointment_date)->format('F j, Y') }}</p>
                        <p class="text-xs text-slate-500 font-medium">{{ Carbon::parse($appointment->appointment_date)->format('l') }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-slate-800 rounded-xl text-blue-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-base font-bold text-white">{{ Carbon::parse($appointment->appointment_start_time)->format('g:i A') }} - {{ Carbon::parse($appointment->appointment_end_time)->format('g:i A') }}</p>
                        <p class="text-xs text-slate-500 font-medium">Session Duration: 30 mins</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-3xl border border-slate-800 bg-slate-900/40 p-8">
            <div class="flex justify-between items-start mb-6">
                <h2 class="text-xs font-black uppercase tracking-[0.3em] text-slate-500">Payment</h2>
                <livewire:dynamic.payment-status :status="$appointment->payment_status" />
            </div>
            <div class="space-y-2">
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Consultation Fee</p>
                <div class="text-3xl font-black text-white">
                    {{ Number::currency($appointment->amount, $appointment->currency) }}
                </div>
                <p class="text-[10px] text-emerald-500 font-bold uppercase tracking-widest bg-emerald-500/5 py-1 px-2 rounded inline-block">Includes Taxes & Fees</p>
            </div>
        </div>
    </div>

    @if($appointment->status == 'completed')
    <div class="rounded-3xl border border-slate-800 bg-slate-900/40 p-8 shadow-xl">
        <h2 class="text-xs font-black uppercase tracking-[0.3em] text-slate-500 mb-6 text-center">Feedback & Experience</h2>
        <livewire:patient.appointments.feature.ratingfeature :appointment="$appointment" />
    </div>
    @endif

    <div class="rounded-3xl border border-slate-800 bg-slate-950/50 p-8">
        <h2 class="text-xs font-black uppercase tracking-[0.3em] text-slate-500 mb-4">Patient Additional Notes</h2>
        <p class="text-sm leading-relaxed text-slate-400 font-medium">
            {{ $appointment->notes ?? 'No additional medical history or notes provided for this session.' }}
        </p>
    </div>
</section>


           <aside class="lg:col-span-1">
    <div class="sticky top-6 space-y-6">
        @if($appointment->status == 'confirmed')
        <div class="rounded-3xl border border-blue-500/20 bg-blue-600/5 p-6 shadow-xl backdrop-blur-sm">
            <h2 class="text-xs font-black uppercase tracking-[0.3em] text-blue-500 mb-4">Documents</h2>
            <a href="{{ route('appointment.slip.download', $appointment) }}" target="_blank"
                class="flex items-center justify-between group p-4 bg-slate-900 border border-slate-800 rounded-2xl hover:border-blue-500/50 transition-all">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-blue-500/10 rounded-lg text-blue-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <span class="text-sm font-bold text-slate-200">Appointment Slip</span>
                </div>
                <svg class="w-4 h-4 text-slate-600 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>
        @endif

        <div class="rounded-3xl border border-slate-800 bg-slate-900 p-6 shadow-2xl">
            <h2 class="text-xs font-black uppercase tracking-[0.3em] text-slate-500 mb-6">Doctor Profile</h2>
            <div class="flex items-center gap-4 mb-6">
                <div class="h-20 w-20 shrink-0 overflow-hidden rounded-2xl border-2 border-slate-800 bg-slate-800 shadow-lg">
                    @if($doctorAvatar)
                        <img src="{{ $doctorAvatar }}" alt="{{ $doctorName }}" class="h-full w-full object-cover">
                    @else
                        <div class="flex h-full w-full items-center justify-center text-xl font-black text-slate-600 bg-slate-950">
                            {{ strtoupper(substr($doctorName, 0, 1)) }}
                        </div>
                    @endif
                </div>

                <div class="min-w-0">
                    <a href="{{ route('doctors.show', $doctor->id) }}" class="block text-xl font-black text-white hover:text-blue-500 truncate transition-colors">
                        {{ $doctorName }}
                    </a>
                    <p class="text-xs font-bold text-blue-500 uppercase tracking-wider mt-1">{{ $doctorSpeciality }}</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 mb-8">
                <div class="bg-slate-950/50 border border-slate-800 rounded-xl p-3 text-center">
                    <div class="text-amber-400 font-black text-sm">★ {{ $doctor->average_rating }}</div>
                    <div class="text-[9px] font-bold text-slate-600 uppercase tracking-widest mt-1">Rating</div>
                </div>
                <div class="bg-slate-950/50 border border-slate-800 rounded-xl p-3 text-center">
                    <div class="text-slate-100 font-black text-sm">{{ $experience }}y+</div>
                    <div class="text-[9px] font-bold text-slate-600 uppercase tracking-widest mt-1">Exp.</div>
                </div>
            </div>

            <div class="space-y-4 pt-6 border-t border-slate-800">
                <h2 class="text-xs font-black uppercase tracking-[0.3em] text-slate-500">Clinic Location</h2>
                <div class="flex gap-4">
                    <div class="shrink-0 p-3 bg-slate-800 rounded-xl h-fit">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-white mb-1">{{ $clinicName }}</h3>
                        <p class="text-xs leading-relaxed text-slate-400 font-medium">
                            {{ $clinicAddress }}<br>
                            <span class="text-slate-500">{{ $city }}, {{ $state }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</aside>

        </div>
    </div>
</div>