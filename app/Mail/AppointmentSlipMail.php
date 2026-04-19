<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AppointmentSlipMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public Appointment $appointment)
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: ' Your Appointment Confirmation',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
         $qrCode = base64_encode(
    QrCode::format('svg') // Changed from 'png' to 'svg'
        ->size(120)
        ->errorCorrection('H')
        ->generate(route('doctor.appointment.verify', [
            'doctorId'      => $this->appointment->doctor_id,
            'appointmentId' => $this->appointment->appointment_id,
        ])));
        return new Content(
            view: 'emails.appointment-confirmation',
            with:[
                'apppointment'=>$this->appointment,
                'qrCode'=>$qrCode,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
