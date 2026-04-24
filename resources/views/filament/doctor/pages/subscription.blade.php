<x-filament-panels::page>
    @php
        $user = auth()->user();
       
        $isSubscribed = $user->subscribed('doctor-subscription');

        if ($isSubscribed) {
            $subscription = $user->subscription('doctor-subscription');
            $stripeSubscription = $subscription->asStripeSubscription();

            $nextBillingDate = $stripeSubscription->items->data[0]->current_period_end
                ?? $stripeSubscription->current_period_end
                ?? null;
            $nextBillingDate = $stripeSubscription->items->data[0]->current_period_end ?? null;
        }

        $plans = [
            [
                'name' => '1 Month',
                'price' => '499',
                'id' => 'monthly_price',
                'save' => 'Flexible'
            ],
            [
                'name' => '6 Months',
                'price' => '2499',
                'id' => 'half_yearly_price',
                'save' => 'Save ₹495',
                'featured' => true
            ],
            [
                'name' => '1 Year',
                'price' => '3999',
                'id' => 'yearly_price',
                'save' => 'Save ₹1,989'
            ],
        ];
    @endphp

    {{-- 1. Active Subscription Info Card --}}
    @if($isSubscribed)
        <div
            style="background: white; border: 1px solid rgb(var(--primary-200)); border-radius: 12px; padding: 1.5rem; margin-bottom: 2rem; box-shadow: var(--shadow-sm); display: flex; justify-content: space-between; align-items: center;">
            <div>
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                    <span
                        style="height: 10px; width: 10px; background-color: #22c55e; border-radius: 50%; display: inline-block;"></span>
                    <h2 style="font-weight: 700; color: rgb(var(--gray-900)); margin: 0;">Subscription Active</h2>
                </div>

                <p style="color: rgb(var(--gray-600)); font-size: 0.95rem; margin: 0;">
                    @if($subscription->onGracePeriod())
                        <span style="color: #ca8a04; font-weight: 600;">Status: Pending Cancellation.</span>
                        Access ends on: <strong>{{ $subscription->ends_at->format('M d, Y') }}</strong>
                    @else
                        Next billing date:
                        <strong>{{ \Carbon\Carbon::createFromTimestamp($nextBillingDate)->format('M d, Y') }}</strong>
                    @endif
                </p>
            </div>

            <div>
                @if($subscription->onGracePeriod())
                    <x-filament::button wire:click="resumeSubscription" color="success" icon="heroicon-m-play">
                        Resume Auto-Renewal
                    </x-filament::button>
                @else
                    {{-- This renders the Filament Action you defined in the PHP class --}}
                    {{ $this->cancelSubscriptionAction }}
                @endif

            </div>
        </div>
  @else
    {{-- Banner for New or Expired Users --}}
    @php
        $expiredSubscription = $user->subscription('doctor-subscription');
    $hasEnded = $subscription?->ended() ?? false; 
    @endphp

    <div style="background: {{ $hasEnded ? '#fff7ed' : '#f0fdf4' }}; 
                border: 1px solid {{ $hasEnded ? '#fed7aa' : '#bbf7d0' }}; 
                border-radius: 12px; padding: 1.5rem; margin-bottom: 2rem; display: flex; align-items: center; gap: 1rem;">
        
        <div style="background: {{ $hasEnded ? '#f97316' : '#22c55e' }}; 
                    border-radius: 50%; padding: 8px; display: flex; align-items: center; justify-content: center;">
            <x-filament::icon 
                icon="{{ $hasEnded ? 'heroicon-m-exclamation-triangle' : 'heroicon-m-check' }}" 
                style="width: 1.5rem; height: 1.5rem; color: white;" 
            />
        </div>

        <div>
            @if($hasEnded)
                <h2 style="color: #9a3412; font-weight: 700; font-size: 1.1rem; margin: 0;">Subscription Expired</h2>
                <p style="color: #c2410c; font-size: 0.9rem; margin: 0.25rem 0 0 0;">
                     {{ $user->name }}, your access ended on <strong>{{ $expiredSubscription->ends_at->format('M d, Y') }}</strong>. 
                    Please select a plan below to reactivate your account and continue accepting appointments.
                </p>
            @else
                <h2 style="color: #166534; font-weight: 700; font-size: 1.1rem; margin: 0;">Profile Approved!</h2>
                <p style="color: #15803d; font-size: 0.9rem; margin: 0.25rem 0 0 0;">
                     {{ $user->name }}, your credentials have been verified. Select a plan to start accepting appointments.
                </p>
            @endif
        </div>
    </div>
@endif

    {{-- 2. Header Section --}}
    <div
        style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2.5rem; padding: 0 0.5rem;">
        <div>
            <h3 style="font-size: 1.5rem; font-weight: 800; color: rgb(var(--gray-950));">Subscription Plans</h3>
            <p style="color: rgb(var(--gray-500));">Select the duration that fits your clinic's needs.</p>
        </div>
        <x-filament::badge color="success" icon="heroicon-m-shield-check" size="lg">
            VERIFIED PRACTITIONER
        </x-filament::badge>
    </div>

    {{-- 3. Pricing Grid --}}
    <div style="display: flex; flex-wrap: wrap; gap: 1.5rem; justify-content: center; width: 100%;">
        @foreach($plans as $plan)
            @php
                $isActivePlan = $user->subscribedToPrice($plan['id'], 'doctor-subscription');
            @endphp
            <div style="flex: 1; min-width: 280px; max-width: 360px;">
                <x-filament::section
                    class="{{ ($plan['featured'] ?? false) ? 'border-primary-600 ring-2 ring-primary-500' : '' }} {{ $isActivePlan ? 'border-success-600 ring-2 ring-success-500' : '' }}">
                    @if($isActivePlan)
                        <div
                            style="background: #22c55e; color: white; text-align: center; font-size: 0.7rem; font-weight: 800; padding: 4px; border-radius: 4px; margin-bottom: 10px; text-transform: uppercase;">
                            Your Current Plan
                        </div>
                    @endif

                    <div style="text-align: center; padding: 0.5rem 0;">
                        <span
                            style="text-transform: uppercase; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.05em; color: rgb(var(--primary-600));">
                            {{ $plan['name'] }} Access
                        </span>

                        <div style="font-size: 3.5rem; font-weight: 900; margin: 1rem 0; color: rgb(var(--gray-900));">
                            <span
                                style="font-size: 1.5rem; vertical-align: top; color: rgb(var(--gray-400));">₹</span>{{ $plan['price'] }}
                        </div>

                        <div
                            style="display: inline-block; background: rgb(var(--primary-50)); color: rgb(var(--primary-700)); padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; margin-bottom: 2rem;">
                            {{ $plan['save'] }}
                        </div>

                        <div
                            style="text-align: left; display: flex; flex-direction: column; gap: 0.8rem; margin-bottom: 1rem;">
                            @foreach(['DocDock verified badge', 'Smart AI Booking Assistant', 'Patient Medical History', 'Unlimited Staff Accounts'] as $feat)
                                <div style="display: flex; align-items: center; gap: 0.6rem; font-size: 0.85rem;">
                                    <x-filament::icon icon="heroicon-m-check-circle"
                                        style="width: 1.1rem; height: 1.1rem; color: rgb(var(--primary-500));" />
                                    <span>{{ $feat }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <x-slot name="footer">
                        @if($isActivePlan)
                            <x-filament::button disabled color="success" icon="heroicon-m-check" style="width: 100%;">
                                Active Plan
                            </x-filament::button>
                        @else
                            <x-filament::button wire:click="subscribe('{{ $plan['id'] }}')" :color="($plan['featured'] ?? false) ? 'primary' : 'gray'" size="lg" style="width: 100%;" :disabled="$isSubscribed && !$subscription->onGracePeriod()">
                                @if($isSubscribed)
                                    Switch to {{ $plan['name'] }}
                                @else
                                    Activate {{ $plan['name'] }}
                                @endif
                            </x-filament::button>
                        @endif
                    </x-slot>
                </x-filament::section>
            </div>
        @endforeach
    </div>
</x-filament-panels::page>