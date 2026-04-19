<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class DoctorPortal extends Component
{
    #[Title('Doctor Portal')]
    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.doctor-portal');
    }
}
