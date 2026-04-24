<?php

namespace App\Filament\Doctor\Pages;

use App\Models\Doctor;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;

use Filament\Notifications\Notification;
use Livewire\Attributes\Computed;
use UnitEnum;

class Subscription extends Page implements HasActions
{
    use InteractsWithActions;
    public string $view = 'filament.doctor.pages.subscription';
    public Doctor $doctor;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationLabel = 'Subscription';
    protected static string|UnitEnum|null $navigationGroup = 'Settings';
    protected static ?int $navigationSort = 1;
    
    public function subscribe(string $priceId)
    {
        switch($priceId) {
            case 'monthly_price':
                $stripePriceId = env('STRIPE_PRICE_MONTHLY_PRICE_ID');
                break;
            case 'half_yearly_price':
                $stripePriceId = env('STRIPE_PRICE_HALFYEAR_PRICE_ID');
                break;
            case 'yearly_price':
                $stripePriceId = env('STRIPE_PRICE_ANNUAL_PRICE_ID');
                break;
            default:
                abort(400, 'Invalid price ID');
        }
        $user = Auth::user();
        
        $checkout = $user->newSubscription('doctor-subscription', $stripePriceId)
            ->checkout([
                'success_url' => route('filament.doctor.pages.subscription') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('filament.doctor.pages.subscription'),
            ]);

        return redirect($checkout->url);
    }


public function cancelSubscription()
{
    $user = Auth::user();

    try {
        $user->subscription('doctor-subscription')->cancel();

        Notification::make()
            ->title('Auto-renewal cancelled')
            ->body('You will still have access until the end of your billing cycle.')
            ->success()
            ->send();

    } catch (\Exception $e) {
        \Log::error("Stripe Cancellation Error: " . $e->getMessage());

        Notification::make()
            ->title('Cancellation Failed')
            ->body('We could not reach Stripe to update your plan. Please try again in a few minutes.')
            ->danger()
            ->send();
    }
}
    public function resumeSubscription()
    {
        $user = Auth::user();
        $user->subscription('doctor-subscription')->resume();

        Notification::make()
            ->title('Subscription Resumed')
            ->success()
            ->send();
    }

    public function mount(): void
    {
        $user = auth()->user();
        abort_unless($user && $user->hasRole('doctor'), 403);
        $this->doctor = $user->doctor;

        if (request()->query('success')) {
            Notification::make()->title('Payment Successful!')->success()->send();
        }
    }
    
    #[Computed]
    public function cancelSubscriptionAction(): Action
    {
        return Action::make('cancelSubscription')
            ->label('Cancel Auto-Renewal')
            ->color('danger')
            ->outlined()
            ->icon('heroicon-m-x-circle')
            // This replaces wire:confirm with a Filament Modal
            ->requiresConfirmation()
            ->modalHeading('Stop Auto-Renewal?')
            ->modalDescription('You will still have access to all features until the end of your current billing cycle. We won’t charge you again.')
            ->modalSubmitActionLabel('Yes, stop renewal')
            ->action(fn () => $this->cancelSubscription());
    }
}
