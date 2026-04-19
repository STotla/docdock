<?php

namespace App\Livewire\Doctor;

use App\Models\Country;
use App\Models\Specialization;
use App\Models\State;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Register as a Doctor')]
class SearchDoctors extends Component
{

public  $specializations;
public $states;
public $cities=[];

public $country;

public $state;
public $specialization;
public $city;
public function mount()
    {

    $specializations = Specialization::all();
    $this->specializations = $specializations;
    $this->country = Country::where('iso2', 'IN')->first();
    $this->states = $this->country->states;
    
    }
    public function render()
    {
        return view('livewire.doctor.search-doctors');
    }
    
    public function updatedState($value){

       $stateObject = State::where('name', $value)->first();
        $this->cities = $stateObject->cities;
    }


    public function search(){

    $this->validate([
        'specialization' =>'required'
    ]);
   $this->redirect(route('doctors.index',[
        's' => $this->specialization,
        'st' => $this->state,
        'c' => $this->city,
   ]),true);
    
    }


}
