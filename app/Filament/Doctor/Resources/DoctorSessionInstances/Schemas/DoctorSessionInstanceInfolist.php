<?php

namespace App\Filament\Doctor\Resources\DoctorSessionInstances\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class DoctorSessionInstanceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                
                TextEntry::make('date')
                    ->date(),
                TextEntry::make('start_time')
                    ->time(),
                TextEntry::make('end_time')
                    ->time(),
                TextEntry::make('capacity_total')
                    ->numeric(),
                TextEntry::make('capacity_booked')
                    ->numeric(),
                TextEntry::make('status'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
