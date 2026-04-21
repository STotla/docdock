<?php

namespace App\Filament\Widgets;

use App\Models\Doctor;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class LifetimeDoctorStats extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 2; // Positioned after Appointment stats

    protected function getStats(): array
    {
        // 1. Fetch filters from the Dashboard
        $month = $this->filters['month'] ?? null;
        $year = $this->filters['year'] ?? null;

        // 2. Base Query with Date Filtering
        $query = Doctor::query()
            ->when($month, fn ($q) => $q->whereMonth('created_at', $month))
            ->when($year, fn ($q) => $q->whereYear('created_at', $year));

        // 3. Data Calculation
        $overallDoctorRegister = (clone $query)->count();
        $practicingDoctors = (clone $query)->where('profile_status', 'approved')->count(); 
        $doctorsUnderReview = (clone $query)->where('profile_status', 'under review')->count(); 

        return [
            Stat::make('Registered Doctors', number_format($overallDoctorRegister))
                ->description('Total registrations ')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),

            Stat::make('Practicing Doctors', number_format($practicingDoctors))
                ->description('Active/Approved profiles')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),

            Stat::make('Under Review', number_format($doctorsUnderReview))
                ->description('Profiles awaiting verification')
                ->descriptionIcon('heroicon-m-magnifying-glass')
                ->color('warning'),
        ];
    }
}
