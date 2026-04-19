<?php
namespace App\Filament\Doctor\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Appointment;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Filament\Widgets\ChartWidget\Concerns\HasFiltersSchema;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Carbon;

class AppointmentTrendChart extends ChartWidget
{
    use HasFiltersSchema;
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 1;


   protected ?string $maxHeight = '300px';

   
   public function getHeading(): string
{
    
    $month = $this->filters['month'] ?? now()->format('m');
    $year = $this->filters['year'] ?? now()->year;
    
    return "Appointment Booking Trend: {$month} {$year}";
}

    protected function getData(): array
    {
                $month = $this->filters['month'] ?? now()->format('m');
    $year = $this->filters['year'] ?? now()->year;

        $start = Carbon::createFromDate((int)$year, (int)$month, 1)->startOfMonth();
        $end = $start->copy()->endOfMonth();

          $doctorQuery = auth()->user()->doctor->appointments()->getQuery();

    $completedData = Trend::query((clone $doctorQuery)->where('status', 'completed'))
    ->dateColumn('appointment_date')
        ->between(start: $start, end: $end)
        ->perDay()
        ->count();

    $pendingData = Trend::query((clone $doctorQuery)->where('status', 'confirmed'))
    ->dateColumn('appointment_date')
        ->between(start: $start, end: $end)
        ->perDay()
        ->count();
        return [
            'datasets' => [
                [
                    'label' => 'Completed',
                    'data' => $completedData->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#3b82f6',
                    'borderWidth'=>0 // Primary Color
                ],
                [
                    'label' => 'Upcoming',
                    'data' => $pendingData->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#f59e0b', // Secondary Color
                    'borderWidth'=>0
                    ],
            ],
            // 4. Labels show days of the current month (1, 2, 3...)
            'labels' => $completedData->map(fn (TrendValue $value) => Carbon::parse($value->date)->format('d ')),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
    protected function getOptions(): array
{
    return [
        'scales' => [
            'y' => [
                'ticks' => [
                    'precision' => 0, // Forces the scale to use whole numbers only
                    'stepSize' => 1,  // Optional: ensures increments of 1
                ],
            ],
            'x' => [
                'grid' => [
                    'display' => false,
                ],
            ],
        ],
        
    ];

}
 public function filtersSchema(Schema $schema): Schema
    {
        return $schema->schema([
            Select::make('month')
                ->options([
                    '01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April',
                    '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August',
                    '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December',
                ])
                ->default(now()->format('m')),
            Select::make('year')
                ->options(
                    collect(range(now()->year, now()->year - 5))
                        ->mapWithKeys(fn ($year) => [$year => $year])
                        ->toArray()
                )
                ->default(now()->year),
        ]);
    }

}
