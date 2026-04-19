<?php

namespace App\Livewire\Dynamic;

use Livewire\Component;

class PaymentStatus extends Component
{

public $status = 'pending';

public $classes;

    public function mount(){
        $this->classes = match ($this->status) {
            'pending' => 'bg-yellow-400 text-white',
            'paid' => 'bg-green-500 text-white',
            'failed' => 'bg-red-500 text-white',
            'marked_as_issue'=> 'bg-gray-500 text-white',
            default => 'bg-gray-300 text-gray-700',
        };
    }
    public function render()
    {
        return view('livewire.dynamic.payment-status');
    }
}
