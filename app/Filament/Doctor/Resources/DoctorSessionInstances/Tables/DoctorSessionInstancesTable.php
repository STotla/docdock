<?php

namespace App\Filament\Doctor\Resources\DoctorSessionInstances\Tables;

use App\Models\DoctorSessionInstance;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;

class DoctorSessionInstancesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('date')
                    ->date()
                    ->sortable(),
                TextColumn::make('start_time')
                    ->time()
                    ->formatStateUsing(fn ($state) => Carbon::parse($state)->format('h:i A'))
                                        ->sortable(),
                TextColumn::make('end_time')
                    ->time()
                     ->formatStateUsing(fn ($state) => Carbon::parse($state)->format('h:i A'))
                    ->sortable(),
                TextColumn::make('capacity_total')
                ->label('Slots')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('capacity_booked')
                    ->label('Booked Slots')
                    ->numeric()
                    ->badge()
                    ->color(fn(DoctorSessionInstance $record): string => match (true) {
                        $record->capacity_total <= 0 => 'gray',
                        ($record->capacity_booked / $record->capacity_total) * 100 >= 100 => 'danger',
                        ($record->capacity_booked / $record->capacity_total) * 100 >= 50 => 'warning',
                        default => 'success',
                    })

                    ->sortable(),
                TextColumn::make('status')
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


            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
