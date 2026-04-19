<?php

namespace App\Filament\Doctor\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class DoctorUpcomingStatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 3;

    protected  ?string $heading="Upcoming Appointment Statistics";

    protected function getStats(): array
    {
         $query = auth()->user()->doctor->appointments();
        // $query= (clone $query)->whereMonth('appointment_date',now());
         $upcomingAppointments = (clone $query)->where('status','confirmed')->count();
         $upcomingRevenue = (clone $query)->where('status','confirmed')->sum('amount');
         $currency = auth()->user()->doctor->currency;
        return [
            Stat::make(
            label:'Upcoming Appointments',
            value: $upcomingAppointments
            )->description('Upcoming Appointments')
            ->color('primary')
             ->descriptionIcon('heroicon-m-calendar'),
            Stat::make(
                label:'Expected Revenue',
                value:  Number::currency($upcomingRevenue, $currency)                
            )->description('Upcoming Revenue')
            ->color('success')   
            ->descriptionIcon('heroicon-m-banknotes')
        
        ];      


    }
}
