<?php
namespace App\Filament\Doctor\Pages;

use BackedEnum;
use Filament\Pages\Dashboard;
use UnitEnum;

class AlltimeStatistics extends Dashboard
{
    protected static ?string $title = 'All-time Statistics';
            protected static string|UnitEnum|null $navigationGroup = 'Analytics';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'All Time Statistics';
    protected static ?int $navigationSort = 2;
    protected static string $routePath = '/all-time-stats';
    
}
