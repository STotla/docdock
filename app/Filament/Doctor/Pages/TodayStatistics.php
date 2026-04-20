<?php

namespace App\Filament\Doctor\Pages;

use BackedEnum;
use Filament\Pages\Dashboard;
use UnitEnum;

class TodayStatistics extends Dashboard
{
     protected static ?string $title = 'Today Statistics';
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'Today Statistics';
        protected static string|UnitEnum|null $navigationGroup = 'Analytics';


    protected static ?int $navigationSort = 2;
    protected static string $routePath = '/';

}
