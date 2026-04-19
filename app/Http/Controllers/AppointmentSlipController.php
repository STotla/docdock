<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AppointmentSlipController extends Controller
{
    public function download(Appointment $appointment){

       
    abort_if($appointment->user_id!==auth()->id(),403);

    $path ="appointments/{$appointment->user_id}/appointment-slip-{$appointment->appointment_id}.pdf";

   
    if (!Storage::disk('private')->exists($path)) {
            
            abort(404, 'Appointment slip not found.');
        }

    
     return Storage::disk('local')->download(
            $path,
            "appointment-slip-{$appointment->appointment_id}.pdf"
        );
}
}
