<?php

namespace App\Livewire\Patient\Appointments\Feature;

use App\Models\Appointment;
use App\Models\Review;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class Ratingfeature extends Component
{
    public Appointment $appointment;
    public $showModal = false;
    public $rating;
    public $review;


    public function mount(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    public function render()
    {
        return view('livewire.patient.appointments.feature.ratingfeature');
    }

    public function saveReview()
    {
        $this->validate([
            'rating' => ['required', 'integer', 'max:5', 'min:1'],
            'review' => ['required']

        ]);
        try {
            Review::create([
                'user_id' => $this->appointment->user_id, 
                'doctor_id' => $this->appointment->doctor_id,
                'appointment_id' => $this->appointment->id,
                'rating' => $this->rating,
                'review' => $this->review,
            ]);
        } catch (\Exception $e) {

            Toaster::error( $e->getMessage().' Something went wrong.Please try again');
            return;
        }
        $this->showModal = false;

        Toaster::success('Rating has been submitted.');
        return;
    }
}
