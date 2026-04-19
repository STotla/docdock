<?php

namespace App\Filament\Doctor\Resources\Appointments\Pages;

use App\Filament\Doctor\Resources\Appointments\AppointmentResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class DiagnoseAppointment extends EditRecord
{
    protected static string $resource = AppointmentResource::class;

   protected function getHeaderActions(): array
{
    return [
        Action::make('completeAppointment')
            ->label('Complete Appointment')
            ->color('success')
            ->icon('heroicon-m-check-badge')
            ->visible(fn () => $this->record->status !== 'completed') // Only show if not already completed
            // 1. Define the Modal Form
            ->form([
                Textarea::make('doctor_notes')
                    ->label('Additional Notes')
                    ->required()
                    ->rows(5),
            ])
            // 2. Handle the Logic
            ->action(function (array $data) {
               $this->record->markAsCompleted($data['doctor_notes']);

                Notification::make()
                    ->title('Appointment Marked as Completed')
                    ->success()
                    ->send();

                // 3. Redirect back to the list
                return redirect($this->getResource()::getUrl('today-appointments'));
            })
            // Optional: Ask for confirmation before opening modal
            ->requiresConfirmation()
            ->modalHeading('Finalize Appointment')
            ->modalDescription('Please add your final notes to complete this session.')
            ->modalSubmitActionLabel('Save & Complete'),
    ];
    
}

public function getBreadcrumbs(): array
{
    return [
        url(route('filament.doctor.resources.appointments.today-appointments')) => 'Today\'s Appointments',
        '#' => 'Diagnose',
    ];
}

public  function getTitle(): string
{
    return 'Diagnose Appointment with ' . $this->record->name;
}

    protected function getFormActions(): array
    {
        return [
        ];
    }
}
