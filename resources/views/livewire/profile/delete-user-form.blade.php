<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component
{
    public string $password = '';

    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}; ?>

<section>
    <header class="mb-8">
        <div class="flex items-center gap-3 mb-2">
            <div class="h-5 w-1 bg-rose-600 rounded-full shadow-[0_0_10px_rgba(225,29,72,0.4)]"></div>
            <h2 class="text-sm font-black uppercase tracking-[0.2em] text-white">
                {{ __('Delete Account') }}
            </h2>
        </div>

        <p class="text-xs font-medium text-slate-500 tracking-wide leading-relaxed">
            {{ __('Once your account is deleted, all resources and data will be permanently removed. Please download any information you wish to retain before proceeding.') }}
        </p>
    </header>

    <button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="px-6 py-3 bg-rose-600/10 border border-rose-500/20 hover:bg-rose-600 hover:text-white text-rose-500 text-xs font-black uppercase tracking-widest rounded-xl transition-all active:scale-95"
    >
        {{ __('Deactivate Account') }}
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->isNotEmpty()" focusable>
        <form wire:submit="deleteUser" class="p-8 bg-slate-900 border border-slate-800 rounded-3xl">

            <div class="mb-6">
                <h2 class="text-xl font-black text-white tracking-tight">
                    {{ __('Confirm Permanent Deletion') }}
                </h2>

                <p class="mt-2 text-sm text-slate-400 leading-relaxed">
                    {{ __('This action cannot be undone. Please enter your account password to authorize the permanent removal of all your data.') }}
                </p>
            </div>

            <div class="space-y-2">
                <x-input-label for="password" value="{{ __('Confirm Password') }}" class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1" />

                <x-text-input
                    wire:model="password"
                    id="password"
                    name="password"
                    type="password"
                    class="block w-full bg-slate-950 border-slate-800 text-slate-200 rounded-xl focus:ring-rose-500/20 focus:border-rose-500 py-3"
                    placeholder="{{ __('Enter password to confirm...') }}"
                />

                <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs text-rose-500" />
            </div>

            <div class="mt-8 flex items-center justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')" class="px-6 py-3 text-xs font-black uppercase tracking-widest text-slate-500 hover:text-white transition-colors">
                    {{ __('Cancel') }}
                </button>

                <button type="submit" class="px-8 py-3 bg-rose-600 hover:bg-rose-500 text-white text-xs font-black uppercase tracking-widest rounded-xl transition-all shadow-lg shadow-rose-900/20 active:scale-95">
                    {{ __('Permanently Delete') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>

