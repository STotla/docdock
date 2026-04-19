<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component
{
    public string $name = '';
    public string $email = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section>
    <header class="mb-8">
        <div class="flex items-center gap-3 mb-2">
            <div class="h-5 w-1 bg-primary-600 rounded-full"></div>
            <h2 class="text-sm font-black uppercase tracking-[0.2em] text-white">
                {{ __('Profile Information') }}
            </h2>
        </div>

        <p class="text-xs font-medium text-slate-500 tracking-wide">
            {{ __("Update your account's public identity and primary contact email.") }}
        </p>
    </header>

    <form wire:submit="updateProfileInformation" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <x-input-label for="name" :value="__('Full Name')" class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1" />
                <x-text-input wire:model="name" id="name" name="name" type="text" 
                    class="block w-full bg-slate-950 border-slate-800 text-slate-200 rounded-xl focus:ring-primary-500/20 focus:border-primary-500 transition-all py-3" 
                    required autofocus autocomplete="name" />
                <x-input-error class="mt-2 text-xs text-rose-500" :messages="$errors->get('name')" />
            </div>

            <div class="space-y-2">
                <x-input-label for="email" :value="__('Email Address')" class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1" />
                <x-text-input wire:model="email" id="email" name="email" type="email" 
                    class="block w-full bg-slate-950 border-slate-800 text-slate-200 rounded-xl focus:ring-primary-500/20 focus:border-primary-500 transition-all py-3" 
                    required autocomplete="username" />
                <x-input-error class="mt-2 text-xs text-rose-500" :messages="$errors->get('email')" />

                @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
                    <div class="mt-4 p-4 bg-amber-500/5 border border-amber-500/20 rounded-2xl">
                        <p class="text-xs font-bold text-amber-500 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            {{ __('Your email address is unverified.') }}
                        </p>

                        <button wire:click.prevent="sendVerification" class="mt-2 text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-white transition-colors underline decoration-slate-700 underline-offset-4">
                            {{ __('Resend Verification Link') }}
                        </button>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-3 font-bold text-[10px] text-emerald-500 uppercase tracking-widest bg-emerald-500/10 px-2 py-1 rounded inline-block">
                                {{ __('New link dispatched.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <div class="flex items-center justify-end gap-4 pt-6 border-t border-slate-800/50">
            <x-action-message class="text-xs font-bold text-emerald-500" on="profile-updated">
                {{ __('Information updated successfully.') }}
            </x-action-message>

            <button type="submit" class="px-8 py-3 bg-primary-600 hover:bg-primary-500 text-white text-xs font-black uppercase tracking-widest rounded-xl transition-all shadow-lg shadow-primary-900/20 active:scale-95">
                {{ __('Save Changes') }}
            </button>
        </div>
    </form>
</section>

