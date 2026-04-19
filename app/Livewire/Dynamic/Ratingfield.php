<?php

namespace App\Livewire\Dynamic;

use App\Models\Doctor;
use Livewire\Component;

class Ratingfield extends Component
{

public function mount(Doctor $doctor){
    
}
    public function render()
    {
        return view('livewire.dynamic.ratingfield');
    }
}
