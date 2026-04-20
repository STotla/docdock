<?php

namespace App\Filament\Doctor\Widgets;

use App\Models\Appointment as ModelsAppointment;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class DoctorUpcomingAppointments extends TableWidget
{
    protected static ?int $sort = 2;
     protected static ?string $heading = 'Pending Appointments';
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
                    ->where('status', 'confirmed')
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
                 Action::make('Diagnose')
                    ->icon('heroicon-o-document-check')
                     
                    ->url(fn ($record) => route('filament.doctor.resources.appointments.diagnose', $record))
            ])
            ->toolbarActions([
                
            ])
            ->defaultPaginationPageOption(5);;
    }
}
