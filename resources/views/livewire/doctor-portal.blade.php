<div class="bg-slate-950 text-slate-100 min-h-screen">

    <div class="relative overflow-hidden">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-16 sm:py-24">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Hero Text -->
                <div class="space-y-8">
                    <div class="space-y-4">
                        <h2 class="text-4xl sm:text-5xl font-bold text-white">
                            Elevate Your Medical Practice
                        </h2>
                        <p class="text-lg text-slate-300">
                            DocDock is a comprehensive healthcare management platform designed to streamline patient
                            consultations, appointments, and medical records—all in one place.
                        </p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-4">
                        @auth
                            <a href="{{ url('/dashboard') }}"
                                class="inline-flex items-center justify-center px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold transition shadow-lg hover:shadow-xl">
                                Go to Dashboard
                                <svg class="ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </a>
                        @else
                            <a href="{{ route('doctor.signup') }}"
                                class="inline-flex items-center justify-center px-8 py-3 bg-primary-500  px-4 text-white rounded-lg hover:bg-primary-700 font-semibold transition shadow-lg hover:shadow-xl">
                                Get Started as Doctor
                                <svg class="ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </a>
                            <a href="{{ route('filament.doctor.auth.login') }}"
                                   class="inline-flex items-center justify-center px-8 py-3 bg-secondary-500 text-white border-2 border-secondary-500 rounded-lg hover:bg-secondary-600 hover:border-secondary-600 font-semibold transition">

                                Sign In
                            </a>
                        @endauth
                    </div>
                </div>

                <!-- Hero Image -->
                <div class="relative">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-blue-500/10 to-blue-600/10 rounded-2xl blur-3xl">
                    </div>
                    <div
                        class="relative bg-gradient-to-br from-slate-800 to-slate-700 rounded-2xl p-8 shadow-xl border border-slate-700">
                        <div
                            class="aspect-square bg-gradient-to-br from-blue-600/20 to-blue-700/20 rounded-xl flex items-center justify-center">
                            <img class="h-full w-auto object-cover" src="{{ asset('storage/doctor-d.jpg') }}"
                                alt="Hero Image">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="bg-slate-900 py-16 sm:py-24 border-t border-slate-800">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h3 class="text-3xl sm:text-4xl font-bold text-white mb-4">Why Choose DocDock?</h3>
                <p class="text-lg text-slate-400 max-w-2xl mx-auto">Powerful features designed to simplify your practice
                    and enhance patient care</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1: Appointment Management -->
                <div
                    class="group relative bg-slate-800 p-8 rounded-xl border border-slate-700 hover:border-blue-500 transition shadow-sm hover:shadow-lg">
                    <div
                        class="flex items-center justify-center h-12 w-12 rounded-lg bg-blue-600/20 text-blue-400 mb-4">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h4 class="text-lg font-semibold text-white mb-2">Smart Scheduling</h4>
                    <p class="text-slate-400">Effortlessly manage patient appointments with automated reminders and
                        calendar integration.</p>
                </div>

                <!-- Feature 2: Patient Records -->
                <div
                    class="group relative bg-slate-800 p-8 rounded-xl border border-slate-700 hover:border-blue-500 transition shadow-sm hover:shadow-lg">
                    <div
                        class="flex items-center justify-center h-12 w-12 rounded-lg bg-blue-600/20 text-blue-400 mb-4">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h4 class="text-lg font-semibold text-white mb-2">Digital Records</h4>
                    <p class="text-slate-400">Secure, organized patient medical records accessible anytime, anywhere
                        with full HIPAA compliance.</p>
                </div>

                <!-- Feature 3: Session Planning -->
                <div
                    class="group relative bg-slate-800 p-8 rounded-xl border border-slate-700 hover:border-blue-500 transition shadow-sm hover:shadow-lg">
                    <div
                        class="flex items-center justify-center h-12 w-12 rounded-lg bg-blue-600/20 text-blue-400 mb-4">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6V4m6 2a2 2 0 11-4 0m4 0a2 2 0 11-4 0m4 0V4m-6 2a2 2 0 11-4 0m4 0a2 2 0 11-4 0m4 0V4m-6 6h12m-12 0a2 2 0 01-2-2m2 2a2 2 0 00-2 2m2-2a2 2 0 01-2-2m2 2a2 2 0 00-2 2m2-2V8m0 4v4m6-12a2 2 0 11-4 0m4 0a2 2 0 11-4 0" />
                        </svg>
                    </div>
                    <h4 class="text-lg font-semibold text-white mb-2">Weekly Sessions</h4>
                    <p class="text-slate-400">Schedule and manage recurring weekly sessions to build consistent patient
                        relationships.</p>
                </div>

                <!-- Feature 4: Specializations -->
                <div
                    class="group relative bg-slate-800 p-8 rounded-xl border border-slate-700 hover:border-blue-500 transition shadow-sm hover:shadow-lg">
                    <div
                        class="flex items-center justify-center h-12 w-12 rounded-lg bg-blue-600/20 text-blue-400 mb-4">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h4 class="text-lg font-semibold text-white mb-2">Specializations</h4>
                    <p class="text-slate-400">Define your medical specializations and let patients find you easily by
                        expertise.</p>
                </div>

                <!-- Feature 5: Location Management -->
                <div
                    class="group relative bg-slate-800 p-8 rounded-xl border border-slate-700 hover:border-blue-500 transition shadow-sm hover:shadow-lg">
                    <div
                        class="flex items-center justify-center h-12 w-12 rounded-lg bg-blue-600/20 text-blue-400 mb-4">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <h4 class="text-lg font-semibold text-white mb-2">Multi-Location Support</h4>
                    <p class="text-slate-400">Manage patients across multiple clinic locations and practice settings
                        seamlessly.</p>
                </div>

                <!-- Feature 6: Consultations -->
                <div
                    class="group relative bg-slate-800 p-8 rounded-xl border border-slate-700 hover:border-blue-500 transition shadow-sm hover:shadow-lg">
                    <div
                        class="flex items-center justify-center h-12 w-12 rounded-lg bg-blue-600/20 text-blue-400 mb-4">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h4 class="text-lg font-semibold text-white mb-2">Consultations</h4>
                    <p class="text-slate-400">Conduct and track patient consultations with detailed notes and follow-up
                        management.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-slate-800 border-t border-slate-700 py-16 sm:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center">
            <h3 class="text-3xl sm:text-4xl font-bold text-white mb-4">Ready to Transform Your Practice?</h3>
            <p class="text-lg text-slate-300 mb-8 max-w-2xl mx-auto">Join hundreds of doctors already using DocDock to
                provide better patient care and streamline their practice.</p>
            @auth
                <a href="{{ url('/dashboard') }}"
                    class="inline-flex items-center justify-center px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold transition shadow-lg hover:shadow-xl">
                    Go to Dashboard
                </a>
            @else
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('doctor.signup') }}"
                        class="inline-flex items-center justify-center px-8 py-3 bg-primary-500  px-4 text-white rounded-lg hover:bg-primary-700 font-semibold transition shadow-lg hover:shadow-xl">
                        Get Started as Doctor
                        <svg class="ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>

                    <a href="{{ route('filament.doctor.auth.login') }}"
                                   class="inline-flex items-center justify-center px-8 py-3 bg-secondary-500 text-white border-2 border-secondary-500 rounded-lg hover:bg-secondary-600 hover:border-secondary-600 font-semibold transition">
                        Already Have an Account? Sign In
                    </a>
                </div>
            @endauth
        </div>
    </div>
</div>