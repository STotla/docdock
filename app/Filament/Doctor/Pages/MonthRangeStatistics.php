<?php

namespace App\Filament\Doctor\Pages;

use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Dashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Schemas\Schema;
use UnitEnum;

class MonthRangeStatistics extends Dashboard
{   
    use HasFiltersForm;
    protected static ?string $title = 'Monthly';
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-presentation-chart-line';
    protected static ?string $navigationLabel = 'Monthly Stats';
    protected static string|UnitEnum|null $navigationGroup = 'Analytics';

    protected static ?int $navigationSort = 3;
    protected static string $routePath = '/montly-statistics';

    public function filtersForm(Schema $schema): Schema
    {
        return $schema
            ->schema([
                 DatePicker::make('month_filter')
                ->label('Select Month')
                ->native(false)
                ->displayFormat('F Y') 
                ->format('Y-m')        
                ->closeOnDateSelection()
                ->live() 
                ->default(now()->format('Y-m')),
            ]);
    }
}
