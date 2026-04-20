<?php

namespace App\Providers\Filament;

use App\Filament\Doctor\Pages\AlltimeStatistics;
use App\Filament\Doctor\Pages\MonthRangeStatistics;
use App\Filament\Doctor\Pages\TodayStatistics;
use App\Filament\Doctor\Widgets\DoctorStatsOverview;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class DoctorPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('doctor')
            ->path('doctor')
            ->login()
            ->colors([
                'danger' => Color::Red,
                'gray' => Color::Zinc,
                'info' => Color::Blue,
                'primary' => Color::Cyan,
                'success' => Color::Green,
                'warning' => Color::Amber,
            ])
            ->brandName('DocDock - Doctor Panel')
            ->darkMode(true)

            ->discoverResources(in: app_path('Filament/Doctor/Resources'), for: 'App\Filament\Doctor\Resources')
            ->discoverPages(in: app_path('Filament/Doctor/Pages'), for: 'App\Filament\Doctor\Pages')
            ->pages([                   
                 AlltimeStatistics::class,
                 TodayStatistics::class,
                 MonthRangeStatistics::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Doctor/Widgets'), for: 'App\Filament\Doctor\Widgets')
           
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
                'role:doctor',
                'doctorGate',
            ])
              ->databaseNotifications() ;
    }
}
