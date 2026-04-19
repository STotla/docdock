<?php
$avatarUrl = data_get($this->doctor, 'profile_img_url');
$avatar = $avatarUrl ? asset('/storage/' . $avatarUrl) : null;
$doctor = $this->doctor;
$name = data_get($doctor, 'user.name');
$speciality = data_get($doctor, 'specialization.name');

$experience = data_get($doctor, 'experience');
$fee = Number::currency((data_get($doctor, 'consultation_fee')), data_get($doctor, 'currency'));
$about = data_get($doctor, 'bio');
$location = data_get($doctor, 'clinic_name');
$clinicAddress = data_get($doctor, 'clinic_address');
$city = data_get($doctor, 'city');
$state = data_get($doctor, 'state');
$rating = data_get($doctor, 'rating', '4.8');

// get the instance details from the livewire component

$startTime = $this->slotDetail ? Carbon\Carbon::parse($this->slotDetail->start_time)->format('h:i A') : 'N/A';
$endTime = $this->slotDetail ? Carbon\Carbon::parse($this->slotDetail->end_time)->format('h:i A') : 'N/A';
$CapcityRemaining = $this->slotDetail ? $this->slotDetail->capacity_total - $this->slotDetail->capacity_booked : 'N/A';

?>
<div class="min-h-screen bg-slate-950 text-slate-100 antialiased font-sans">
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        
        <!-- Navigation Header -->
        <nav class="mb-10 flex items-center justify-between">
            <a href="{{ url()->previous() }}" class="group flex items-center gap-2 text-sm font-medium text-slate-400 hover:text-white transition-colors">
                <div class="p-2 bg-slate-900 border border-slate-800 rounded-lg group-hover:border-slate-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </div>
                Back to doctor profile
            </a>
            <div class="hidden sm:block">
                <span class="text-xs uppercase tracking-widest text-slate-500 font-bold">Step 2 of 2: Confirm Booking</span>
            </div>
        </nav>

        <div class="grid gap-8 lg:grid-cols-3 items-start">
            
            <!-- Left Column: Details & Form -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Doctor Info Card -->
                <section class="relative overflow-hidden rounded-3xl border border-slate-800 bg-slate-900/40 p-1">
                    <div class="bg-slate-900/80 rounded-[1.4rem] p-6">
                        <div class="flex flex-col gap-6 sm:flex-row">
                            <div class="relative shrink-0">
                                <div class="h-28 w-28 overflow-hidden rounded-2xl border-2 border-slate-800 bg-slate-800 shadow-2xl">
                                    @if($avatar)
                                        <img src="{{ $avatar }}" alt="{{ $name }}" class="h-full w-full object-cover">
                                    @else
                                        <div class="flex h-full w-full items-center justify-center text-3xl font-bold bg-gradient-to-br from-slate-700 to-slate-900 text-slate-400">
                                            {{ strtoupper(substr($name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="absolute -bottom-2 -right-2 bg-green-500 w-6 h-6 rounded-full border-4 border-slate-900 shadow-lg"></div>
                            </div>

                            <div class="flex-1">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h1 class="text-3xl font-extrabold tracking-tight text-white">{{ $name }}</h1>
                                        <p class="text-primary-400 font-medium tracking-wide uppercase text-xs mt-1">{{ $speciality }}</p>
                                    </div>
                                    <div class="bg-slate-800/50 border border-slate-700 px-3 py-1 rounded-lg text-amber-400 font-bold text-sm">
                                        ⭐ {{ $rating }}
                                    </div>
                                </div>

                                <div class="mt-5 flex flex-wrap items-center gap-3">
                                    <span class="inline-flex items-center gap-1.5 rounded-md bg-slate-800/50 px-3 py-1.5 text-xs font-medium text-slate-300 border border-slate-700/50">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        {{ $experience }} Experience
                                    </span>
                                    <span class="inline-flex items-center gap-1.5 rounded-md bg-slate-800/50 px-3 py-1.5 text-xs font-medium text-slate-300 border border-slate-700/50">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        {{ $location }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Patient Details Form -->
                <section class="rounded-3xl border border-slate-800 bg-slate-900/40 p-8 shadow-xl">
                    <div class="mb-8 border-b border-slate-800 pb-6 flex items-center justify-between">
                        <h2 class="text-xl font-bold text-white flex items-center gap-3">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-primary-600 text-sm">1</span>
                            Patient Information
                        </h2>
                        
                        <!-- Toggle Switch Style Radio -->
                        <div class="flex p-1 bg-slate-950 rounded-xl border border-slate-800">
                            <label class="cursor-pointer">
                                <input type="radio" wire:model.live="patientType" value="self" class="peer hidden">
                                <span class="px-4 py-1.5 rounded-lg text-xs font-bold transition-all inline-block peer-checked:bg-primary-600 peer-checked:text-white text-slate-500">Self</span>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" wire:model.live="patientType" value="other" class="peer hidden">
                                <span class="px-4 py-1.5 rounded-lg text-xs font-bold transition-all inline-block peer-checked:bg-primary-600 peer-checked:text-white text-slate-500">Others</span>
                            </label>
                        </div>
                    </div>

                    <form wire:submit.prevent="bookAppointment" class="space-y-6">
                        <div wire:loading.class="blur-sm pointer-events-none opacity-50 transition-all duration-300" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            @php 
                                $inputClasses = "w-full bg-slate-950 rounded-xl border-slate-800 text-slate-200 placeholder:text-slate-600 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all py-3";
                                $labelClasses = "block text-xs font-bold uppercase tracking-widest text-slate-500 mb-2 ml-1";
                            @endphp

                            <div class="md:col-span-2 sm:col-span-1">
                                <label class="{{ $labelClasses }}">Full Patient Name</label>
                                <input class="{{ $inputClasses }}" type="text" required wire:model="name" placeholder="John Doe">
                                @error('name') <span class="mt-1 text-xs text-red-400 ml-1">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="{{ $labelClasses }}">Email Address</label>
                                <input class="{{ $inputClasses }}" type="email" required wire:model="email" placeholder="john@example.com">
                                @error('email') <span class="mt-1 text-xs text-red-400 ml-1">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="{{ $labelClasses }}">Contact Number</label>
                                <input class="{{ $inputClasses }}" type="text" wire:model="phone" placeholder="+1 (555) 000-0000">
                                @error('phone') <span class="mt-1 text-xs text-red-400 ml-1">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="{{ $labelClasses }}">Patient Age</label>
                                <input class="{{ $inputClasses }}" type="number" required wire:model="age" placeholder="25">
                            </div>
                            
                            <div>
                                <label class="{{ $labelClasses }}">Gender</label>
                                <select class="{{ $inputClasses }}" wire:model="gender" required>
                                    <option value="">Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="pt-6 border-t border-slate-800 flex justify-end">
                            <button type="submit" class="w-full sm:w-auto px-10 py-4 bg-primary-600 hover:bg-primary-500 text-white font-bold rounded-2xl transition-all shadow-xl shadow-primary-900/20 active:scale-95">
                                Confirm Appointment
                            </button>
                        </div>
                    </form>
                </section>
            </div>

            <!-- Right Column: Booking Summary Sticky Card -->
            <aside class="space-y-6 lg:sticky lg:top-8">
                <div class="rounded-3xl border border-slate-800 bg-slate-900 p-6 shadow-2xl">
                    <h2 class="text-lg font-bold text-white mb-6 border-b border-slate-800 pb-4">Booking Summary</h2>
                    
                    <div class="space-y-6">
                        <!-- Timing Card -->
                        <div class="p-4 bg-slate-950 rounded-2xl border border-slate-800">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="p-2 bg-primary-500/10 rounded-lg text-primary-500">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Appointment Time</span>
                            </div>
                            <div class="text-lg font-bold text-slate-100 pl-10">
                                {{ $startTime }} <span class="text-slate-600 mx-1">-</span> {{ $endTime }}
                            </div>
                        </div>

                        <!-- Capacity Status -->
                        <div class="flex items-center justify-between px-2">
                            <span class="text-sm font-medium text-slate-400">Available Slots</span>
                            <span class="inline-flex items-center rounded-full bg-green-500/10 px-2.5 py-0.5 text-xs font-bold text-green-500 border border-green-500/20">
                                {{ $CapcityRemaining }} Remaining
                            </span>
                        </div>

                        <!-- Price Info -->
                        <div class="pt-6 border-t border-slate-800">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-slate-400 font-medium">Consultation Fee</span>
                                <span class="text-xl font-extrabold text-white">{{ $fee }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Trust Badge -->
                <div class="px-6 py-4 bg-slate-900/40 rounded-2xl border border-slate-800 flex items-center gap-4">
                    <div class="p-2 bg-slate-800 rounded-full">
                        <svg class="w-5 h-5 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    </div>
                    <p class="text-[11px] text-slate-500 leading-tight">Your booking is secured. A confirmation SMS will be sent shortly.</p>
                </div>
            </aside>

        </div>
    </div>
</div>
