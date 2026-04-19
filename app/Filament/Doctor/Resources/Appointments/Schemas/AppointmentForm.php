<?php

namespace App\Filament\Doctor\Resources\Appointments\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;

class AppointmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Placeholder::make('name')
                    ->label('Patient Name'),
                Placeholder::make('email')
                    ->label('Email address'),
                Placeholder::make('phone')
                    ->label('Phone number'),
                Placeholder::make('gender')
                    ->label('Gender')
                    ->placeholder('-'),
                Placeholder::make('age')
                    ->label('Age')
                    ->placeholder('-'),
                Placeholder::make('appointment_date')
                    ->label('Appointment Date')
                    ->date()
                    ->placeholder('-'),
                Placeholder::make('appointment_start_time')
                    ->label('Start Time')
                    ->time()
                    ->placeholder('-'),
                Placeholder::make('appointment_end_time')
                    ->label('End Time')
                    ->time()
                    ->placeholder('-'),
                Placeholder::make('note')
                    ->label('Note')
                    ->placeholder('-')
            ]);
    }

   
}
