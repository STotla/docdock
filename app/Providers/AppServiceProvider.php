<?php

namespace App\Providers;

use App\Listeners\StripeWebhookListener;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\Events\WebhookReceived;
use Filament\Notifications\Livewire\DatabaseNotifications;
use Illuminate\Support\Facades\DB;

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
        if (config('database.default') === 'sqlite') {
        $db = DB::connection()->getPdo();
        
        $db->sqliteCreateFunction('acos', 'acos', 1);
        $db->sqliteCreateFunction('cos', 'cos', 1);
        $db->sqliteCreateFunction('sin', 'sin', 1);
        $db->sqliteCreateFunction('radians', 'deg2rad', 1);
        
    }
    }
}
