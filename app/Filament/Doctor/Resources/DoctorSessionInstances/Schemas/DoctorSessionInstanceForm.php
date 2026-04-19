<?php

namespace App\Filament\Doctor\Resources\DoctorSessionInstances\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class DoctorSessionInstanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
              
                DatePicker::make('date')
                    ->required(),
                TimePicker::make('start_time')
                    ->required(),
                TimePicker::make('end_time')
                    ->required(),
                TextInput::make('capacity_total')
                    ->required()
                    ->numeric(),
                Placeholder::make('capacity_booked')
                    ->numeric()
                    ->default(0),
                Select::make('status')
                
                    ->options([
                        'open' => 'Open',
                        'close' => 'Close',
                    ])
                    ->required()
                    ->default('open')
                    ->native(false)
            ]);
    }
}
