<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Carbon\Carbon;

class LifetimeAppointmentsStatistics extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 1;
    
    protected function getStats(): array
    {
        // 1. Get filter data from the Dashboard
        $month = $this->filters['month'] ?? null;
        $year = $this->filters['year'] ?? null;

        // 2. Base Query applied with filters
        $query = Appointment::query()
            ->when($month, fn ($q) => $q->whereMonth('created_at', $month))
            ->when($year, fn ($q) => $q->whereYear('created_at', $year));

       
        $totalBooked = (clone $query)->count();
        
     
        $totalServed = (clone $query)
            ->whereIn('status', ['completed'])
            ->count();

        $totalPending = (clone $query)
            ->where('status', 'confirmed') 
            ->count();

        return [
            Stat::make('Appointments Booked', number_format($totalBooked))
                ->description('Total volume')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('info')
                ->chart([7, 3, 4, 5, 6, 3, 5, 8]), // Visual trend line

            Stat::make('Appointments Served', number_format($totalServed))
                ->description('Completed & Confirmed')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),

            Stat::make('Pending Appointments', number_format($totalPending))
                ->description('Awaiting action')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
        ];
    }
}
