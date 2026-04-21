<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Spatie\Permission\Models\Role;

class LifetimePatientStats extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 3;

    protected function getStats(): array
    {
        // 1. Get filters from Dashboard
        $month = $this->filters['month'] ?? null;
        $year = $this->filters['year'] ?? null;

        // 2. Filtered User Query (Patients)
        $patientQuery = User::role('patient')
            ->when($month, fn ($q) => $q->whereMonth('created_at', $month))
            ->when($year, fn ($q) => $q->whereYear('created_at', $year));

        // 3. Filtered Appointment Query (to find active patients)
        $appointmentQuery = Appointment::query()
            ->when($month, fn ($q) => $q->whereMonth('created_at', $month))
            ->when($year, fn ($q) => $q->whereYear('created_at', $year));

        // 4. Calculations
        $overallPatientRegistered = $patientQuery->count();
        
        // Count unique users who have at least one appointment in the filtered period
        $patientOptedforAppointment = $appointmentQuery->distinct()->count('user_id');

        return [
            Stat::make('Registered Patients', number_format($overallPatientRegistered))
                ->description('Total patients in selected period')
                ->descriptionIcon('heroicon-m-user-plus')
                ->color('info'),

            Stat::make('Patients with Bookings', number_format($patientOptedforAppointment))
                ->description('Unique patients who booked')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('success'),
        ];
    }
}
