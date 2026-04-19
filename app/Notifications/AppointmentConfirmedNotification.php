<?php

namespace App\Notifications;

use App\Mail\AppointmentSlipMail;
use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentConfirmedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Appointment $appointment)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail','database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): AppointmentSlipMail
    {
   return (new AppointmentSlipMail($this->appointment))
        ->to($this->appointment->email);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
       
             return [
            'appointment_id'   => $this->appointment->id,
            'appointment_ref'  => $this->appointment->appointment_id,
            'doctor_name'      => $this->appointment->doctor->user->name,
            'appointment_date' => $this->appointment->appointment_date,
            'start_time'       => $this->appointment->start_time,
            'message'          => 'Your appointment has been confirmed.',
        ];
        
    }
}
