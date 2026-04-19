<?php

namespace App\Livewire\Patient\Doctors;

use App\Models\Doctor;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Title('Doctors')]
#[Layout('layouts.app')]
class Index extends Component
{

#[Url(as:'s')]
public $specializationId;

#[Url(as:'st')]
public $stateCode;

#[Url(as:'c')]
public $city;

public $state;

public $specialization;
    public function render()
    {  
        $this->specialization = $this->specializationId ? \App\Models\Specialization::find($this->specializationId) : null;
        $query = Doctor::query();
        $query->where('profile_status', 'approved');
        $query->when($this->specializationId, function ($q) {
           
            $q->where('specialization_id', $this->specializationId);
        })
        ->when($this->stateCode, function ($q) {
            $q->where('state_code', $this->stateCode);
        })
        ->when($this->city, function ($q) {
            $q->where('city', 'like', '%' . $this->city . '%');
        });


        
        return view('livewire.patient.doctors.index',[
            'doctors' => $query->paginate(10,'*','doctors-page'),
            ]);
    }

    
}
