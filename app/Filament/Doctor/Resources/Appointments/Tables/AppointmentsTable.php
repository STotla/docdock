<?php

namespace App\Filament\Doctor\Resources\Appointments\Tables;

use App\Filament\Doctor\Resources\Appointments\Pages\ListUpcomingAppointments;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AppointmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                
             
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                     TextColumn::make('appointment_date')
                    ->date()
                    ->sortable(),
                    
                TextColumn::make('phone')
                    ->searchable(),
                
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'danger',
                        'confirmed' => 'warning',
                        'cancelled' => 'danger',
                        'completed' => 'success',
                        default => 'gray',  
                    })
                    ->sortable()
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
               
              
              
            ])
            ->defaultSort('appointment_date', 'asc')
            ->filters([
                //
            ])
            ->recordActions([
              
                Action::make('Diagnose')
                    ->icon('heroicon-o-document-check')
                     
                    ->url(fn ($record) => route('filament.doctor.resources.appointments.diagnose', $record))
                ->visible(fn () => request()->routeIs('*.today-appointments')),
                  ViewAction::make(),
            ]);
           
    }
}
