<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component
{
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('password-updated');
    }
}; ?>

<section>
    <header class="mb-8">
        <div class="flex items-center gap-3 mb-2">
            <div class="h-5 w-1 bg-primary-600 rounded-full"></div>
            <h2 class="text-sm font-black uppercase tracking-[0.2em] text-white">
                {{ __('Security Update') }}
            </h2>
        </div>

        <p class="text-xs font-medium text-slate-500 tracking-wide">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form wire:submit="updatePassword" class="space-y-6">
        <!-- Current Password -->
        <div class="max-w-md space-y-2">
            <x-input-label for="update_password_current_password" :value="__('Current Password')" class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1" />
            <x-text-input wire:model="current_password" id="update_password_current_password" name="current_password" type="password" 
                class="block w-full bg-slate-950 border-slate-800 text-slate-200 rounded-xl focus:ring-primary-500/20 focus:border-primary-500 transition-all py-3" 
                autocomplete="current-password" />
            <x-input-error :messages="$errors->get('current_password')" class="mt-2 text-xs text-rose-500" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
            <!-- New Password -->
            <div class="space-y-2">
                <x-input-label for="update_password_password" :value="__('New Password')" class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1" />
                <x-text-input wire:model="password" id="update_password_password" name="password" type="password" 
                    class="block w-full bg-slate-950 border-slate-800 text-slate-200 rounded-xl focus:ring-primary-500/20 focus:border-primary-500 transition-all py-3" 
                    autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs text-rose-500" />
            </div>

            <!-- Confirm Password -->
            <div class="space-y-2">
                <x-input-label for="update_password_password_confirmation" :value="__('Confirm New Password')" class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1" />
                <x-text-input wire:model="password_confirmation" id="update_password_password_confirmation" name="password_confirmation" type="password" 
                    class="block w-full bg-slate-950 border-slate-800 text-slate-200 rounded-xl focus:ring-primary-500/20 focus:border-primary-500 transition-all py-3" 
                    autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-xs text-rose-500" />
            </div>
        </div>

        <div class="flex items-center justify-end gap-4 pt-6 border-t border-slate-800/50">
            <x-action-message class="text-xs font-bold text-emerald-500" on="password-updated">
                {{ __('Password secured successfully.') }}
            </x-action-message>

            <button type="submit" class="px-8 py-3 bg-primary-600 hover:bg-primary-500 text-white text-xs font-black uppercase tracking-widest rounded-xl transition-all shadow-lg shadow-primary-900/20 active:scale-95">
                {{ __('Update Password') }}
            </button>
        </div>
    </form>
</section>

