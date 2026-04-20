<?php

namespace App\Filament\Doctor\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class DoctorTodayStatistics extends StatsOverviewWidget
{
    protected static ?int $sort = 1;
    public static function canView(): bool
    {
        return request()->routeIs('filament.doctor.pages.today-statistics');
    }
    protected function getStats(): array
    {
        $query = auth()->user()->doctor->appointments()->whereDate('appointment_date', today());
        $currency= auth()->user()->doctor->currency;
        $appointmentsCompleted = (clone $query)->where('status', 'completed')->count();

        $remainingAppointments = (clone $query)->where('status', 'confirmed')->count();

        $revenueEarned = (clone $query)->sum('amount');

        return [
            Stat::make('Appointments Completed', number_format($appointmentsCompleted))
                ->color('primary')
                ->description('Today completed Appointments')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('primary'),
            Stat::make('Pending Appointments', number_format($remainingAppointments))
                ->color('primary')
                ->description('Today Pending Appointments')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('primary'),

Stat::make('Revenue Earned', Number::currency($revenueEarned, $currency) )
                ->description('Today\'s Revenue')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

        ];
    }
}
