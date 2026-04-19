<?php

namespace App\Livewire\Patient\Doctors;

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Doctor;
use Illuminate\Support\Carbon;

#[Layout('layouts.app')]
class Show extends Component
{
    public Doctor $doctor;
    public $days;
    public function render()
    {
        return view('livewire.patient.doctors.show');
    }
    public function mount(Doctor $doctor)
    {
        $this->doctor = $doctor;
        $dayName = [];
        $weekDayArray = $doctor->weeklySessions()->pluck('day_of_week')->unique()->values();
        foreach ($weekDayArray as $day) {
            $dayName[] = Carbon::now()
                ->startOfWeek(Carbon::MONDAY)
                ->addDays($day - 1)
                ->format('D');
        }
        if(!empty($dayName)){
            $this->days= implode(', ', $dayName);
        }else{
            $this->days = 'No sessions available';
            }
        
    }
}
