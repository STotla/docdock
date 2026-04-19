<?php

namespace App\Livewire\Patient;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Welcome extends Component
{
    #[Title('Welcome')]
    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.patient.welcome');
    }
}
