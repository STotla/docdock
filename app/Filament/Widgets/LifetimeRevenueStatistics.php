<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class LifetimeRevenueStatistics extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 1;
    protected ?string $heading = "Revenue History";

    protected function getStats(): array
    {
        $month = $this->filters['month'] ?? null;
        $year = $this->filters['year'] ?? null;

        $revenueGenerated = Appointment::query()
            ->when($month, fn ($q) => $q->whereMonth('created_at', $month))
            ->when($year, fn ($q) => $q->whereYear('created_at', $year))
            ->whereIn('status', ['completed', 'confirmed'])
            ->sum('amount');

        return [
            Stat::make('Total Revenue', $this->formatIndianCurrency($revenueGenerated))
                ->description('Earnings in Lakhs/Crores')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
        ];
    }

    /**
     * Formats currency into the Indian numbering system (Cr, Lakh, K)
     */
    protected function formatIndianCurrency($amount): string
    {
        if ($amount >= 10000000) {
            return '₹' . number_format($amount / 10000000, 2) . ' Cr';
        } elseif ($amount >= 100000) {
            return '₹' . number_format($amount / 100000, 2) . ' Lakh';
        } elseif ($amount >= 1000) {
            return '₹' . number_format($amount / 1000, 2) . ' K';
        }

        return '₹' . number_format($amount, 2);
    }
}
