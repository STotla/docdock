{{-- filepath: e:\laracasts\docdock\resources\views\livewire\patient\welcome.blade.php --}}
<div class="min-h-screen bg-slate-950 text-slate-100">
    <section class="relative overflow-hidden">
        <div class="pointer-events-none absolute inset-0 bg-gradient-to-b from-primary/20 to-transparent"></div>

        <div class="mx-auto grid w-full max-w-7xl gap-10 px-6 py-20 lg:grid-cols-2 lg:items-center lg:px-8 lg:py-24">
            <div>
                <p class="mb-4 inline-flex rounded-full border border-primary/30 bg-primary/10 px-3 py-1 text-xs font-medium text-primary">
                    Patient care made simple
                </p>

                <h1 class="max-w-3xl text-4xl font-bold leading-tight tracking-tight sm:text-5xl">
                    Find trusted doctors and book your visit with <span class="text-primary">DocDock</span>
                </h1>

                <p class="mt-6 max-w-2xl text-slate-300">
                    Search doctors by specialty, check availability, and schedule appointments quickly from one place.
                </p>

                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ url('/register') }}"
                        class="rounded-md bg-primary px-5 py-3 text-sm font-semibold text-slate-950 hover:bg-primary/90">
                        Book Appointment
                    </a>
                    <a href="{{ url('/login') }}"
                        class="rounded-md border border-secondary bg-secondary px-5 py-3 text-sm font-semibold text-slate-100 hover:bg-secondary/90">
                        Sign In
                    </a>
                </div>

                <div class="mt-8 flex flex-wrap gap-6 text-sm text-slate-400">
                    <span>✔ Find specialists</span>
                    <span>✔ Easy booking</span>
                    <span>✔ Secure patient access</span>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-800 bg-slate-900/70 p-6 shadow-2xl">
                <h2 class="text-lg font-semibold">Popular specialties</h2>
                <p class="mt-2 text-sm text-slate-400">Browse doctors by the care you need.</p>

                <div class="mt-6 grid grid-cols-2 gap-4">
                    <div class="rounded-xl border border-slate-800 bg-slate-900 p-4">
                        <p class="font-medium">Cardiology</p>
                        <p class="mt-1 text-sm text-slate-400">Heart specialists</p>
                    </div>
                    <div class="rounded-xl border border-slate-800 bg-slate-900 p-4">
                        <p class="font-medium">Dermatology</p>
                        <p class="mt-1 text-sm text-slate-400">Skin care experts</p>
                    </div>
                    <div class="rounded-xl border border-slate-800 bg-slate-900 p-4">
                        <p class="font-medium">Pediatrics</p>
                        <p class="mt-1 text-sm text-slate-400">Child healthcare</p>
                    </div>
                    <div class="rounded-xl border border-slate-800 bg-slate-900 p-4">
                        <p class="font-medium">Orthopedics</p>
                        <p class="mt-1 text-sm text-slate-400">Bone and joint care</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mx-auto w-full max-w-7xl px-6 py-16 lg:px-8">
        <div class="mb-10">
            <h2 class="text-2xl font-bold tracking-tight sm:text-3xl">Why patients choose DocDock</h2>
            <p class="mt-3 text-slate-300">
                A simpler way to discover doctors and manage your appointments.
            </p>
        </div>

        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            <article class="rounded-xl border border-slate-800 bg-slate-900/60 p-6">
                <h3 class="font-semibold">Find the right doctor</h3>
                <p class="mt-2 text-sm text-slate-300">
                    Browse doctors by specialty, experience, and availability.
                </p>
            </article>

            <article class="rounded-xl border border-slate-800 bg-slate-900/60 p-6">
                <h3 class="font-semibold">Easy appointment booking</h3>
                <p class="mt-2 text-sm text-slate-300">
                    Choose a time that works for you and confirm in minutes.
                </p>
            </article>

            <article class="rounded-xl border border-slate-800 bg-slate-900/60 p-6">
                <h3 class="font-semibold">View doctor schedules</h3>
                <p class="mt-2 text-sm text-slate-300">
                    Check availability before booking to avoid long waits.
                </p>
            </article>

            <article class="rounded-xl border border-slate-800 bg-slate-900/60 p-6">
                <h3 class="font-semibold">Appointment reminders</h3>
                <p class="mt-2 text-sm text-slate-300">
                    Stay updated with reminders so you never miss a visit.
                </p>
            </article>

            <article class="rounded-xl border border-slate-800 bg-slate-900/60 p-6">
                <h3 class="font-semibold">Secure patient access</h3>
                <p class="mt-2 text-sm text-slate-300">
                    Keep your bookings and personal details safely managed.
                </p>
            </article>

            <article class="rounded-xl border border-slate-800 bg-slate-900/60 p-6">
                <h3 class="font-semibold">Faster clinic visits</h3>
                <p class="mt-2 text-sm text-slate-300">
                    Reduce waiting time with organized booking and check-in flow.
                </p>
            </article>
        </div>
    </section>

    <section class="mx-auto w-full max-w-7xl px-6 pb-16 lg:px-8">
        <div class="rounded-2xl border border-primary/30 bg-primary/10 p-8 text-center">
            <h2 class="text-2xl font-bold sm:text-3xl">Ready to book your next doctor visit?</h2>
            <p class="mx-auto mt-3 max-w-2xl text-slate-300">
                Explore available doctors, choose a specialty, and schedule your appointment with ease.
            </p>

            <div class="mt-6 flex justify-center gap-3">
                <a href="{{ url('/register') }}"
                    class="rounded-md bg-primary px-5 py-3 text-sm font-semibold text-slate-950 hover:bg-primary/90">
                    Get Started
                </a>
                <a href="{{ url('/login') }}"
                    class="rounded-md border border-secondary bg-secondary px-5 py-3 text-sm font-semibold text-slate-100 hover:bg-secondary/90">
                    Sign In
                </a>
            </div>
        </div>
    </section>
</div>