<?php

namespace App\Filament\Doctor\Resources\Appointments\Pages;

use App\Filament\Doctor\Resources\Appointments\AppointmentResource;
use Filament\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class DiagnoseAppointment extends EditRecord
{
    protected static string $resource = AppointmentResource::class;


    public function form(Schema $schema): Schema
    {
        return $schema    
                    ->schema([
                        // Sidebar: Patient Data (Read-only)
                        Section::make('Patient Overview')
                            ->description('Basic information for reference')
                            ->columnSpan(2)
                            ->schema([
                                Placeholder::make('patient_identity')
                                    ->label('Name')
                                    ->content(fn ($record) => new HtmlString("<strong>{$record->name}</strong>")),

                                Placeholder::make('stats')
                                    ->label('Details')
                                    ->content(fn ($record) => "{$record->age}Y / " . ucfirst($record->gender)),

                                Placeholder::make('contact')
                                    ->label('Phone Number')
                                    ->content(fn ($record) => $record->phone),

                                Placeholder::make('symptoms')
                                    ->label('Patient Complaints')
                                    ->content(fn ($record) => new HtmlString(
                                        '<div class="p-2 rounded bg-warning-50 text-warning-700 border border-warning-200 text-sm">' . 
                                        ($record->symptoms ?: 'No symptoms provided') . 
                                        '</div>'
                                    )),
                            ]),

                        // Main Area: Clinical Notes
                        Section::make('Diagnosis & Treatment')
                            ->description('Enter medical notes and prescription details below')
                            ->columnSpan(9)
                            ->schema([
                                Textarea::make('doctor_notes')
                                    ->label('Prescription / Clinical Notes')
                                    ->placeholder('e.g. Tab. Paracetamol 500mg - BD for 3 days...')
                                    ->rows(12) // Made it larger for easier writing
                                    ->required()
                                    ->columnSpanFull()
                                    ->extraInputAttributes(['style' => 'font-family: monospace; font-size: 1.1rem;']),
                            ]),
                    ]);
            
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('completeAppointment')
                ->label('Complete Appointment')
                ->color('success')
                ->icon('heroicon-m-check-badge')
                ->requiresConfirmation()
                ->modalHeading('Confirm Finalization')
                ->modalDescription('Are you sure you want to save these notes and mark this appointment as completed?')
                ->modalSubmitActionLabel('Yes, Finalize & Close')
                ->action(function () {
                    // 1. Save the textarea data to the record
                    $this->save();
                    
                    // 2. Update status
                    $this->record->update([
                        'status' => 'completed',
                        'completed_at' => now(),
                    ]);

                    Notification::make()
                        ->title('Session Completed Successfully')
                        ->success()
                        ->send();

                    // 3. Go back
                    return redirect($this->getResource()::getUrl('today-appointments'));
                }),
        ];
    }

    protected function getFormActions(): array
    {
        // We hide the default bottom "Save" button to force doctors to use the Header Action
        return [];
    }

    public function getTitle(): string
    {
        return "Diagnosing: " . $this->record->name;
    }
}
