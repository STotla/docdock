<?php

namespace App\Listeners;

use App\Events\DoctorAppliedForReview;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyAdminOfReview
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(DoctorAppliedForReview $event): void
    {
        $doctor = $event->doctor;
        $admins = User::role('admin')->get();
        Notification::make()
        ->title('New Review Application')
            ->warning() // Orange color for attention
            ->icon('heroicon-o-document-magnifying-glass')
            ->body("Dr. {$doctor->user->name} has submitted their profile for verification.")
            ->actions([
                Action::make('review')
                    ->label('View Profile')
                    ->url(route('filament.admin.resources.doctors.view', ['record' => $doctor]))
                    ->button(),
            ])
            ->sendToDatabase($admins);
    }
}
