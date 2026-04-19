<?php

namespace App\Providers;

use App\Listeners\StripeWebhookListener;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\Events\WebhookReceived;
use Filament\Notifications\Livewire\DatabaseNotifications;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
            DatabaseNotifications::trigger('partials.notification-trigger');
          Event::listen(
            WebhookReceived::class,
            StripeWebhookListener::class
        );
    }
}
