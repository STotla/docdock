<?php

namespace App\Livewire\Patient\Appointments;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $statusFilter = 'confirmed'; // Default to "Upcoming"
    

    public function setStatus($status)
    {
        $this->statusFilter = $status;
        $this->resetPage();
    }
    public function render()
{
    $appointments = auth()->user()->appointments()
        ->with(['doctor.user', 'doctor.specialization', 'doctorSessionInstance'])
        ->when($this->statusFilter, function ($query) {
            return $query->where('status', $this->statusFilter);
        })
        ->latest()
        ->paginate(10);

    return view('livewire.patient.appointments.index', [
        'appointments' => $appointments,
    ]);
}
   

}
