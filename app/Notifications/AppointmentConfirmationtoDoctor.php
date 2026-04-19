<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
// Import the Filament Notification class
use Filament\Notifications\Notification as FilamentNotification;

class AppointmentConfirmationtoDoctor extends Notification
{
    use Queueable;

    public function __construct(public Appointment $appointment)
    {
        //
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Updated to support Filament's notification tab
     */
    public function toArray(object $notifiable): array
    {
        return FilamentNotification::make()
            ->title('New Appointment Booked')
            ->icon('heroicon-o-calendar-days')
            ->body("{$this->appointment->name} booked an appointment for {$this->appointment->appointment_date}")
            ->success()
            // This stores your custom data (id, ref, etc.) inside the 'data' key            
            ->getDatabaseMessage(); 
    }
}
