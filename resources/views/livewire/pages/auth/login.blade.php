<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('patient.dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class=" bg-slate-950 flex flex-col justify-center items-center  selection:bg-primary-500/30">
    <!-- Subtle Background Glow -->
    <div class="fixed inset-0 z-0 pointer-events-none">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-lg h-64 bg-primary-500/10 blur-[120px]"></div>
    </div>

    <div class="w-full">
      
        <!-- The Login Card -->
        <div class="bg-slate-900/50  backdrop-blur-xl p-8  ">
            <div class="text-center mb-10">
                <h2 class="text-3xl font-black tracking-tight text-white uppercase">Sign <span class="text-primary-500">In</span></h2>
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-[0.3em] mt-2">Patient Access Portal</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-6" :status="session('status')" />

            <form wire:submit="login" class="space-y-6">
                <!-- Email Address -->
                <div class="space-y-2">
                    <x-input-label for="email" :value="__('Email Address')" class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1" />
                    <x-text-input wire:model="form.email" id="email" 
                        class="block w-full bg-slate-950 border-slate-800 text-slate-200 rounded-xl focus:ring-primary-500/20 focus:border-primary-500 py-3" 
                        type="email" name="email" required autofocus placeholder="name@example.com" />
                    <x-input-error :messages="$errors->get('form.email')" class="mt-1 text-xs text-rose-500" />
                </div>

                <!-- Password -->
                <div class="space-y-2">
                    <div class="flex justify-between items-center px-1">
                        <x-input-label for="password" :value="__('Password')" class="text-[10px] font-black uppercase tracking-widest text-slate-500" />
                        @if (Route::has('password.request'))
                            <a class="text-[10px] font-bold text-primary-500 hover:text-primary-400 transition-colors uppercase tracking-widest" href="{{ route('password.request') }}" wire:navigate>
                                Forgot?
                            </a>
                        @endif
                    </div>
                    <x-text-input wire:model="form.password" id="password" 
                        class="block w-full bg-slate-950 border-slate-800 text-slate-200 rounded-xl focus:ring-primary-500/20 focus:border-primary-500 py-3"
                        type="password" name="password" required placeholder="••••••••" />
                    <x-input-error :messages="$errors->get('form.password')" class="mt-1 text-xs text-rose-500" />
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <label for="remember" class="inline-flex items-center cursor-pointer group">
                        <input wire:model="form.remember" id="remember" type="checkbox" 
                            class="rounded border-slate-800 bg-slate-950 text-primary-500 focus:ring-primary-500/20 w-4 h-4 transition-all">
                        <span class="ms-3 text-[10px] font-black text-slate-500 group-hover:text-slate-300 uppercase tracking-widest">{{ __('Remember me') }}</span>
                    </label>
                </div>

                <!-- Login Button -->
                <div class="pt-2">
                    <button type="submit" class="w-full py-4 bg-primary-600 hover:bg-primary-500 text-white text-xs font-black uppercase tracking-[0.2em] rounded-2xl transition-all shadow-xl shadow-primary-900/20 active:scale-[0.98]">
                        {{ __('Authenticate') }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Footer Action -->
        <div class="text-center my-4">
            <p class="text-[11px] font-bold text-slate-600 uppercase tracking-widest">
                Don't have an account? 
                <a href="{{ route('register') }}" class="text-primary-500 hover:text-primary-400 font-black ml-2 underline underline-offset-4" wire:navigate>
                    Create One
                </a>
            </p>
        </div>
    </div>
</div>


