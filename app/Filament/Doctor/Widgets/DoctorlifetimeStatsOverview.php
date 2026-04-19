<?php

namespace App\Filament\Doctor\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class DoctorlifetimeStatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 2;
    protected  ?string $heading="Lifetime Appointments Statistics";
 
    protected function getStats(): array
    {
        $query = auth()->user()->doctor->appointments();
        $currency= auth()->user()->doctor->currency;
        $totalAppointments = (clone $query)->count();
 
        $totalPatients = (clone $query)
            ->distinct('user_id')
            ->count('user_id');
 
        $lifetimeEarnings = (clone $query)
            ->where('status', 'completed')
            ->sum('amount');
 
        return [
            Stat::make('Total Appointments', number_format($totalAppointments))
            ->color('primary')
                ->description('All-time bookings')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('primary')
               ,
 
            Stat::make('Total Patients', number_format($totalPatients))
                ->description('Unique patients served')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),
 
            Stat::make('Lifetime Earnings', Number::currency($lifetimeEarnings, $currency) )
                ->description('Total completed earnings')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
        ];
    }
    
}
