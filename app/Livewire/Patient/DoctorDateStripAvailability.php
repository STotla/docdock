<?php

namespace App\Livewire\Patient;

use App\Models\Doctor;
use App\Models\DoctorSessionInstance;
use Carbon\CarbonImmutable;
use Livewire\Attributes\Computed;
use Livewire\Component;

class DoctorDateStripAvailability extends Component
{
    public Doctor $doctor;

    public string $selectedDate; // YYYY-MM-DD
    public int $daysToShow = 30;


    public function mount(int $doctorId): void
    {
        $this->doctor = Doctor::query()->findOrFail($doctorId);
        $this->selectedDate = CarbonImmutable::today()->toDateString();
    }

    public function selectDate(string $date): void
    {
        $this->selectedDate = $date;
    }

    public function getDatesProperty(): array
    {
        $start = CarbonImmutable::today();

        return collect(range(0, $this->daysToShow - 1))
            ->map(fn ($i) => $start->addDays($i))
            ->map(fn (CarbonImmutable $d) => [
                'date' => $d->toDateString(),
                'label' => $d->format('D'),
                'day' => $d->format('d'),
                'month' => $d->format('M'),
            ])
            ->all();
    }

    #[Computed]
    public function instances(){
        $instanceQuery = $this->doctor->sessionInstances()->whereDate('date', $this->selectedDate)
                        ->where('status', 'open')
                        ;
        if($this->selectedDate === CarbonImmutable::today()->toDateString()){
            $instanceQuery->whereTime('start_time', '>', CarbonImmutable::now('Asia/Kolkata')->toTimeString());
        }
        $instanceQuery->orderBy('start_time');

        return $instanceQuery->get();
    }

    public function render()
    {   
        return view('livewire.patient.doctor-date-strip-availability',[
           
        ]);
    }

}