<?php

namespace App\Livewire\Doctor\Appointment;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Verify extends Component
{
    public function render()
    {
        return view('livewire.doctor.appointment.verify');
    }

    public function mount(){
        
    }
}
