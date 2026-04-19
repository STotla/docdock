<?php

namespace App\Jobs;

use App\Models\Appointment;
use App\Notifications\AppointmentConfirmationtoDoctor;
use App\Notifications\AppointmentConfirmedNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class GenerateAppointmentSlip implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels, Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public Appointment $appointment)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->appointment->load([
            'patient',
            'doctor',
        ]);
      $qrCode = base64_encode(
    QrCode::format('svg') // Changed from 'png' to 'svg'
        ->size(120)
        ->errorCorrection('H')
        ->generate(route('doctor.appointment.verify', [
            'doctorId'      => $this->appointment->doctor_id,
            'appointmentId' => $this->appointment->appointment_id,
        ]))
);
        $pdf = Pdf::LoadView('pdf.appointment-slip', [
            'appointment' => $this->appointment->load('doctor', 'doctorSessionInstance'),
            'qrCode'      => $qrCode,
        ])->setPaper('a4', 'portrait');

        $filename = 'appointment-slip-' . $this->appointment->appointment_id . '.pdf';
        $filePath = "appointments/{$this->appointment->user_id}/{$filename}";
        Storage::disk('private')->put($filePath, $pdf->output());

        $this->appointment->doctor->user->notify(new AppointmentConfirmationtoDoctor($this->appointment));

        $this->appointment->patient->notify(new AppointmentConfirmedNotification($this->appointment));

    }
}
