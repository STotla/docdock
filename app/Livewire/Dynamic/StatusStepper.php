<?php

namespace App\Livewire\Dynamic;

use Livewire\Component;

class StatusStepper extends Component
{
    public $status = 'pending';

    public function mount(String $status){
        $this->status= $status;
    }
    
    public function getProgressProperty()
    {
        return match ($this->status) {
            'pending' => '0%',
            'confirmed' => '50%',
            'completed' => '100%',
            default => '0%',
        };
    }
    public function render()
    {
        return view('livewire.dynamic.status-stepper'
        );
    }
}
