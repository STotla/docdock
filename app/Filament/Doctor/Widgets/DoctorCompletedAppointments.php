<?php

namespace App\Filament\Doctor\Widgets;

use App\Models\Appointment as ModelsAppointment;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class DoctorCompletedAppointments extends TableWidget
{
    protected static ?int $sort = 3;
    protected static ?string $heading = 'Completed Appointments';
    public static function canView(): bool
    {
        return request()->routeIs('filament.doctor.pages.today-statistics');
    }
    public function table(Table $table): Table
    {
        return $table
            ->query(
                fn(): Builder => ModelsAppointment::query()
                    ->where('doctor_id', auth()->user()->doctor->id)
                    ->whereDate('appointment_date', today())
                    ->where('status', 'completed')
            )->columns([
                TextColumn::make('name'),
                    TextColumn::make('phone')
                    ->label('Phone'), 
                TextColumn::make('appointment_start_time')
                    ->label('Time')
                    ,
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                 ViewAction::make(),
            ])
            ->toolbarActions([
                
            ])
            ->defaultPaginationPageOption(5);
    }
}
