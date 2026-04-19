<?php

namespace App\Livewire\Patient\Appointments;

use App\Jobs\GenerateAppointmentSlip;
use App\Models\Appointment;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Show extends Component
{
    public Appointment $appointment;
    public function render(Appointment $appointment)
    {   

        return view('livewire.patient.appointments.show');
    }

    public function mount(Appointment $appointment)
    {
        $appointment->load('doctor', 'doctorSessionInstance','patient');
        $this->appointment = $appointment;

    }
}
