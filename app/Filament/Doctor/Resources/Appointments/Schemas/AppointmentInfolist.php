<?php

namespace App\Filament\Doctor\Resources\Appointments\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class AppointmentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
               
                TextEntry::make('name'),
                TextEntry::make('email')
                    ->label('Email address'),
                TextEntry::make('phone'),
                TextEntry::make('gender')
                    ->placeholder('-'),
               
                TextEntry::make('status'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('age')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('appointment_date')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('appointment_start_time')
                    ->time()
                    ->placeholder('-'),
                TextEntry::make('appointment_end_time')
                    ->time()
                    ->placeholder('-'),
                     TextEntry::make('note')
                    ->placeholder('-')
                    ->columnSpanFull(),
            ]);
    }
}
