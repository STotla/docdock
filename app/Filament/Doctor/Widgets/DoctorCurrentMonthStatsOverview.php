<?php

namespace App\Filament\Doctor\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Carbon;
use Illuminate\Support\Number;

class DoctorCurrentMonthStatsOverview extends StatsOverviewWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 1;
    protected ?string $heading = 'Monthly Statistics';

    public ?string $startDate = null;
    public ?string $endDate   = null;

    public static function canView(): bool
    {
        return request()->routeIs('filament.doctor.pages.month-range-statistics');
    }
private function updateDatesFromFilter(): void
    {
        /** 
         * FIX: Explicitly check for both $pageFilters (v4) and $filters (v3/v4 mismatch)
         * This prevents the "blank screen" crash when changing values.
         */
        $appliedFilters = $this->pageFilters ?? $this->filters ?? [];
        
        $filterValue = $appliedFilters['month_filter'] ?? now()->format('Y-m');
        
        
        try {
            // Use Carbon's safe parsing
            $start = \Illuminate\Support\Carbon::parse($filterValue)->startOfMonth();
            $this->startDate = $start->toDateString();
            $this->endDate   = $start->copy()->endOfMonth()->toDateString();
        } catch (\Exception $e) {
            // Emergency fallback to prevent white screen
            $this->startDate = now()->startOfMonth()->toDateString();
            $this->endDate   = now()->endOfMonth()->toDateString();
        }
    }



    private function periodQueries(): array
    {
        $this->updateDatesFromFilter();

        $start = Carbon::parse($this->startDate)->startOfDay();
        $end   = Carbon::parse($this->endDate)->endOfDay();
        
        $base = auth()->user()->doctor->appointments();
        $current = (clone $base)->whereBetween('appointment_date', [$start, $end]);

        $prevStart = $start->copy()->subMonth()->startOfMonth();
        $prevEnd   = $prevStart->copy()->endOfMonth();
        $previous  = (clone $base)->whereBetween('appointment_date', [$prevStart, $prevEnd]);

        return [$current, $previous];
    }

    // --- RE-ADDED MISSING METHODS ---

    private function trend(int|float $current, int|float $previous): array
    {
        if ($previous == 0) {
            return [
                'description' => $current == 0 ? 'No data' : '100% increase',
                'color' => 'success',
                'icon'  => 'heroicon-m-arrow-trending-up',
            ];
        }

        $pct = (($current - $previous) / $previous) * 100;
        $rounded = abs(round($pct, 1));
        $increased = $pct >= 0;

        return [
            'description' => ($increased ? "↑ {$rounded}%" : "↓ {$rounded}%") . " vs last month",
            'color' => $increased ? 'success' : 'danger',
            'icon'  => $increased ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down',
        ];
    }

    private function buildSparkline($baseQuery, string $mode): array
    {
        $start = Carbon::parse($this->startDate)->startOfDay();
        $end   = Carbon::parse($this->endDate)->endOfDay();
        $totalDays = max(1, (int) $start->diffInDays($end));
        $buckets = 8;
        $daysEach = (int) ceil($totalDays / $buckets);
        $points = [];

        for ($i = 0; $i < $buckets; $i++) {
            $bStart = $start->copy()->addDays($i * $daysEach)->startOfDay();
            $bEnd   = $bStart->copy()->addDays($daysEach - 1)->endOfDay();
            $q = (clone $baseQuery)->whereBetween('appointment_date', [$bStart, $bEnd]);
            $points[] = $mode === 'sum' ? (float) $q->sum('amount') : $q->count();
        }
        return $points;
    }

    private function sparkline(string $status, string $mode): array
    {
        $q = auth()->user()->doctor->appointments()
            ->whereBetween('appointment_date', [$this->startDate, $this->endDate])
            ->where('status', $status);

        return $this->buildSparkline($q, $mode);
    }

    // --- GET STATS ---

    protected function getStats(): array
    {
        $this->updateDatesFromFilter();
        $currency = auth()->user()->doctor->currency;
        [$current, $previous] = $this->periodQueries();

        $doneCur = (clone $current)->where('status', 'completed')->count();
        $donePrev = (clone $previous)->where('status', 'completed')->count();
        $doneTrend = $this->trend($doneCur, $donePrev);

        $earnedCur = (clone $current)->where('status', 'completed')->sum('amount');
        $earnedPrev = (clone $previous)->where('status', 'completed')->sum('amount');
        $earnedTrend = $this->trend($earnedCur, $earnedPrev);

        $patientCur = $this->newJoinedPatientswithRespecttoPreviousMonth($this->startDate, $this->endDate);
        
        return [
            Stat::make('Appointments Done', number_format($doneCur))
                ->description($doneTrend['description'])
                ->color($doneTrend['color'])
                ->chart($this->sparkline('completed', 'count')),

            Stat::make('Revenue Earned', Number::currency($earnedCur, $currency))
                ->description($earnedTrend['description'])
                ->color($earnedTrend['color'])
                ->chart($this->sparkline('completed', 'sum')),

            Stat::make('New Patients', $patientCur)
                ->color('success'),
        ];
    }

    public function newJoinedPatientswithRespecttoPreviousMonth($startDate, $endDate)
    {
        $doctor = auth()->user()->doctor;
        return User::query()
            ->whereHas('appointments', fn($q) => $q->where('doctor_id', $doctor->id)->whereBetween('appointment_date', [$startDate, $endDate]))
            ->whereDoesntHave('appointments', fn($q) => $q->where('doctor_id', $doctor->id)->where('appointment_date', '<', $startDate))
            ->count();
    }
}
