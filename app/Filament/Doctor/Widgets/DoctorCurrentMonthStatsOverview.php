<?php

namespace App\Filament\Doctor\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;
use Illuminate\Support\Number;

class DoctorCurrentMonthStatsOverview extends StatsOverviewWidget
{protected static ?int $sort = 1;

protected ?string $heading = 'Current Month Stats';
    public ?string $startDate = null;
    public ?string $endDate   = null;


    public function mount(): void
    {
        $this->startDate = now()->startOfMonth()->toDateString();
        $this->endDate   = now()->endOfMonth()->toDateString();
    }


    private function periodQueries(): array
    {
        $start    = Carbon::parse($this->startDate)->startOfDay();
        $end      = Carbon::parse($this->endDate)->endOfDay();
        $duration = $start->diffInDays($end);   // # days in current range

        $prevEnd   = $start->copy()->subDay()->endOfDay();
        $prevStart = $prevEnd->copy()->subDays($duration)->startOfDay();

        $base = auth()->user()->doctor->appointments();

        $current  = (clone $base)->whereBetween('appointment_date', [$start, $end]);
        $previous = (clone $base)->whereBetween('appointment_date', [$prevStart, $prevEnd]);

        return [$current, $previous];
    }

    private function trend(int|float $current, int|float $previous): array
    {
        if ($previous == 0) {
            return [
                'description' => $current == 0
                    ? 'No data in either period'
                    : '100% increase — new activity this period',
                'color' => 'success',
                'icon'  => 'heroicon-m-arrow-trending-up',
            ];
        }

        $pct       = (($current - $previous) / $previous) * 100;
        $rounded   = abs(round($pct, 1));
        $increased = $pct >= 0;

        return [
            'description' => ($increased
                    ? "↑ {$rounded}% increase"
                    : "↓ {$rounded}% decrease")
                . " vs previous month",
            'color' => $increased ? 'success' : 'danger',
            'icon'  => $increased
                ? 'heroicon-m-arrow-trending-up'
                : 'heroicon-m-arrow-trending-down',
        ];
    }

     private function buildSparkline($baseQuery, string $mode): array
    {
        $start     = Carbon::parse($this->startDate)->startOfDay();
        $end       = Carbon::parse($this->endDate)->endOfDay();
        $totalDays = max(1, (int) $start->diffInDays($end));
        $buckets   = min(8, $totalDays + 1);
        $daysEach  = (int) ceil($totalDays / $buckets);
        $points    = [];

        for ($i = 0; $i < $buckets; $i++) {
            $bStart = $start->copy()->addDays($i * $daysEach)->startOfDay();
            $bEnd   = $bStart->copy()->addDays($daysEach - 1)->endOfDay();
            if ($bEnd->gt($end)) {
                $bEnd = $end->copy();
            }

            $q        = (clone $baseQuery)->whereBetween('appointment_date', [$bStart, $bEnd]);
            $points[] = $mode === 'sum'
                ? (float) $q->sum('amount')
                : $q->count();
        }

        return $points;
    }

    private function sparkline(string $status, string $mode, bool $futureOnly = false): array
    {
        $start = Carbon::parse($this->startDate)->startOfDay();
        $end   = Carbon::parse($this->endDate)->endOfDay();

        $q = auth()->user()->doctor->appointments()
            ->whereBetween('appointment_date', [$start, $end])
            ->where('status', $status);

        if ($futureOnly) {
            $q->where('appointment_date', '>=', now());
        }

        return $this->buildSparkline($q, $mode);
    }
    protected function getStats(): array
    {
        $currency = auth()->user()->doctor->currency;

        [$current, $previous] = $this->periodQueries();

        // ── 1. Appointments Done ──────────────────────────────────────────────
        $doneCur  = (clone $current)->where('status', 'completed')->count();
        $donePrev = (clone $previous)->where('status', 'completed')->count();
        $doneTrend = $this->trend($doneCur, $donePrev);

        // ── 2. Upcoming Appointments ──────────────────────────────────────────
        $upcomingCur  = (clone $current)
            ->where('status', 'confirmed')
            ->where('appointment_date', '>=', now())
            ->count();
        
        

        // ── 3. Revenue Earned ─────────────────────────────────────────────────
        $earnedCur  = (clone $current)->where('status', 'completed')->sum('amount');
        $earnedPrev = (clone $previous)->where('status', 'completed')->sum('amount');
        $earnedTrend = $this->trend($earnedCur, $earnedPrev);

        // 4. New Patient Served

        $startDate=Carbon::parse($this->startDate);

        $patientCurrentMonth = $this->newJoinedPatientswithRespecttoPreviousMonth($this->startDate,$this->endDate);
        $patientPreviousMonth = $this->newJoinedPatientswithRespecttoPreviousMonth($startDate->copy()->subMonth(),$this->startDate);
    $diff = $patientCurrentMonth - $patientPreviousMonth;
     $isPositive = $diff >= 0;   
    return [
            Stat::make('Appointments Done', number_format($doneCur))
                ->description($doneTrend['description'])
                ->descriptionIcon($doneTrend['icon'])
                ->color($doneTrend['color'])
                ->chart($this->sparkline('completed', 'count')),

            Stat::make('Upcoming Appointments', number_format($upcomingCur))
            ->description('Pending appointents')
            ->color('warning')
            ->chart($this->sparkline('confirmed', 'count')),
                

            Stat::make('Revenue Earned', Number::currency($earnedCur, $currency) )
                ->description($earnedTrend['description'])
                ->descriptionIcon($earnedTrend['icon'])
                ->color($earnedTrend['color'])
                ->chart($this->sparkline('completed', 'sum')),

                Stat::make('New Patients', $patientCurrentMonth)
            ->description("Newly Joined Patients".$isPositive ? "Up by {$diff}" : "Down by " . abs($diff))
            ->descriptionIcon($isPositive ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
            // 3. Simple two-point sparkline: [Start Point, End Point]
            ->chart([$patientPreviousMonth, $patientCurrentMonth]) 
            // 4. Color the line and description based on trend
            ->color($isPositive ? 'success' : 'danger'),
           
        ];
    }

    public function newJoinedPatientswithRespecttoPreviousMonth($startDate,$endDate){
      $doctor = auth()->user()->doctor;

    // We look for Patients who:
    return User::query()
        // 1. Have an appointment with THIS doctor in THIS month
        ->whereHas('appointments', function ($query) use ($doctor, $startDate,$endDate) {
            $query->where('doctor_id', $doctor->id)
                  ->whereBetween('appointment_date', [$startDate, $endDate]);
        })
        // 2. DO NOT have ANY appointment with THIS doctor BEFORE the month started
        ->whereDoesntHave('appointments', function ($query) use ($doctor, $startDate) {
            $query->where('doctor_id', $doctor->id)
                  ->where('appointment_date', '<', $startDate);
        })
        ->count();
    }

}

