<x-app-layout>
    <x-slot name="header">
        Account Settings
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-12 lg:gap-x-12">
                
                <!-- Left Sidebar: Descriptive Labels -->
                <aside class="py-6 px-2 sm:px-6 lg:py-0 lg:px-0 lg:col-span-3">
                    <nav class="space-y-1">
                        <p class="text-[10px] font-black uppercase tracking-[0.3em] text-primary-500 mb-4 px-3">Profile Management</p>
                        <div class="space-y-8">
                            <div class="px-3">
                                <h3 class="text-sm font-bold text-white">Basic Info</h3>
                                <p class="text-xs text-slate-500 mt-1">Update your public profile and contact email.</p>
                            </div>
                            <div class="px-3">
                                <h3 class="text-sm font-bold text-white">Medical Details</h3>
                                <p class="text-xs text-slate-500 mt-1">Additional information for your healthcare provider.</p>
                            </div>
                            <div class="px-3">
                                <h3 class="text-sm font-bold text-white">Security</h3>
                                <p class="text-xs text-slate-500 mt-1">Manage your password and account protection.</p>
                            </div>
                            <div class="px-3">
                                <h3 class="text-sm font-bold text-rose-500">Danger Zone</h3>
                                <p class="text-xs text-slate-600 mt-1">Permanently remove your account and data.</p>
                            </div>
                        </div>
                    </nav>
                </aside>

                <!-- Right Column: The Forms -->
                <div class="space-y-8 lg:col-span-9">
                    
                    <!-- Profile Info -->
                    <section class="rounded-3xl border border-slate-800 bg-slate-900/40 p-8 shadow-xl backdrop-blur-sm transition-all hover:border-slate-700">
                        <div class="max-w-2xl">
                            <livewire:profile.update-profile-information-form />
                        </div>
                    </section>

                    <!-- Additional Info (Medical/Patient) -->
                    <section class="rounded-3xl border border-slate-800 bg-slate-900/40 p-8 shadow-xl backdrop-blur-sm transition-all hover:border-slate-700 border-l-4 border-l-primary-600">
                        <div class="max-w-2xl">
                            <livewire:profile.update-additional-information-form />
                        </div>
                    </section>

                    <!-- Password Security -->
                    <section class="rounded-3xl border border-slate-800 bg-slate-900/40 p-8 shadow-xl backdrop-blur-sm transition-all hover:border-slate-700">
                        <div class="max-w-2xl">
                            <livewire:profile.update-password-form />
                        </div>
                    </section>

                    <!-- Delete Account -->
                    <section class="rounded-3xl border border-rose-500/20 bg-rose-500/5 p-8 shadow-xl transition-all hover:border-rose-500/40">
                        <div class="max-w-2xl">
                            <livewire:profile.delete-user-form />
                        </div>
                    </section>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
