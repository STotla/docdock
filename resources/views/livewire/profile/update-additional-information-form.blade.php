<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use App\Models\Country;
use App\Models\State;

new class extends Component {
    public $phone = '';
    public $dob = '';
    public $gender = '';
    public $address = '';
    public $city = '';
    public $state = '';
    public $zipcode = '';
    public Country $country;
    public $states;
    public $cities = [];

    /**
     * Mount the component.
     */
    public function mount(): void
    {

        $this->country = Country::where('iso2', 'IN')->first();
        $this->states = $this->country->states;


        $this->phone = Auth::user()->phone;
        $this->dob = Auth::user()->dob;
        $this->gender = Auth::user()->gender;
        $this->address = Auth::user()->address;
        $this->state = Auth::user()->state;
        $this->zipcode = Auth::user()->zipcode;
        if ($this->state) {
             $this->loadCities($this->state);
            $this->city = Auth::user()->city;
        }


    }
    public function updatedState($value)
    {
        $this->loadCities($value);
    }
    public function loadCities($value){
             $stateObject = State::where('name', $value)->first();
            $this->cities = $stateObject->cities;
    }


    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateAdditonalInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'phone' => 'nullable|string|max:20',
            'dob' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zipcode' => 'nullable|string|max:10',
        ]);
        $user->fill($validated);
        $user->save();

        $this->dispatch('profile-updated', name: $user->name);




    }



}; ?>

<section>
    <header class="mb-8">
        <div class="flex items-center gap-3 mb-2">
            <div class="h-5 w-1 bg-primary-600 rounded-full"></div>
            <h2 class="text-sm font-black uppercase tracking-[0.2em] text-white">
                {{ __('Medical & Contact Details') }}
            </h2>
        </div>
        <p class="text-xs font-medium text-slate-500 tracking-wide">
            {{ __("Please ensure your contact and location details are accurate for appointment scheduling.") }}
        </p>
    </header>

    <form wire:submit="updateAdditonalInformation" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Phone -->
            <div class="space-y-2">
                <x-input-label for="phone" :value="__('Phone Number')" class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1" />
                <x-text-input wire:model="phone" id="phone" type="text" 
                    class="block w-full bg-slate-950 border-slate-800 text-slate-200 rounded-xl focus:ring-primary-500/20 focus:border-primary-500 py-3" 
                    required autocomplete="phone" />
                <x-input-error class="mt-2 text-xs text-rose-500" :messages="$errors->get('phone')" />
            </div>

            <!-- DOB -->
            <div class="space-y-2">
                <x-input-label for="dob" :value="__('Date of Birth')" class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1" />
                <x-text-input type="date" id="dob" wire:model="dob" 
                    class="block w-full bg-slate-950 border-slate-800 text-slate-200 rounded-xl focus:ring-primary-500/20 focus:border-primary-500 py-3" />
                <x-input-error class="mt-2 text-xs text-rose-500" :messages="$errors->get('dob')" />
            </div>

            <!-- Gender -->
            <div class="space-y-2">
                <x-input-label for="gender" :value="__('Gender')" class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1" />
                <select id="gender" wire:model.live="gender"
                    class="block w-full bg-slate-950 border-slate-800 text-slate-200 rounded-xl focus:ring-primary-500/20 focus:border-primary-500 py-3 text-sm">
                    <option value="">Select Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
                <x-input-error class="mt-2 text-xs text-rose-500" :messages="$errors->get('gender')" />
            </div>

            <!-- Zip -->
            <div class="space-y-2">
                <x-input-label for="zipcode" :value="__('Zip / Postal Code')" class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1" />
                <x-text-input wire:model="zipcode" id="zipcode" type="text" 
                    class="block w-full bg-slate-950 border-slate-800 text-slate-200 rounded-xl focus:ring-primary-500/20 focus:border-primary-500 py-3" />
                <x-input-error class="mt-2 text-xs text-rose-500" :messages="$errors->get('zipcode')" />
            </div>

            <!-- Address (Full Width) -->
            <div class="md:col-span-2 space-y-2">
                <x-input-label for="address" :value="__('Permanent Address')" class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1" />
                <textarea wire:model="address" id="address" rows="3"
                    class="block w-full bg-slate-950 border-slate-800 text-slate-200 rounded-xl focus:ring-primary-500/20 focus:border-primary-500 transition-all p-4 text-sm"
                    required></textarea>
                <x-input-error class="mt-2 text-xs text-rose-500" :messages="$errors->get('address')" />
            </div>

            <!-- State -->
            <div class="space-y-2">
                <x-input-label for="state" :value="__('Province / State')" class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1" />
                <select id="state" wire:model.live="state"
                    class="block w-full bg-slate-950 border-slate-800 text-slate-200 rounded-xl focus:ring-primary-500/20 focus:border-primary-500 py-3 text-sm">
                    <option value="">Select State</option>
                    @foreach ($this->states as $state)
                        <option value="{{ $state->name }}">{{Str::ucfirst($state->name)}}</option>
                    @endforeach
                </select>
            </div>

            <!-- City -->
            <div class="space-y-2">
                <x-input-label for="city" :value="__('City')" class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1" />
                <select id="city" wire:model.live="city"
                    class="block w-full bg-slate-950 border-slate-800 text-slate-200 rounded-xl focus:ring-primary-500/20 focus:border-primary-500 py-3 text-sm">
                    <option value="">Select City</option>
                    @foreach ($this->cities as $city)
                        <option value="{{ $city->name }}">{{Str::ucfirst($city->name)}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="flex items-center justify-end gap-4 pt-6 border-t border-slate-800/50">
            <x-action-message class="text-xs font-bold text-emerald-500" on="profile-updated">
                {{ __('Medical records updated.') }}
            </x-action-message>

            <button type="submit" class="px-8 py-3 bg-primary-600 hover:bg-primary-500 text-white text-xs font-black uppercase tracking-widest rounded-xl transition-all shadow-lg shadow-primary-900/20 active:scale-95">
                {{ __('Save Details') }}
            </button>
        </div>
    </form>
</section>
