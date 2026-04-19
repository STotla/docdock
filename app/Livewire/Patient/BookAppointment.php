<?php

namespace App\Livewire\Patient;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\DoctorSessionInstance;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

#[Layout('layouts.app')]
class BookAppointment extends Component
{
    public Doctor $doctor;
    public string $date;
    public ?DoctorSessionInstance $slotDetail = null;

    public ?int $selectedInstanceId = null;

    // basic details (add more later)
    public ?string $note = null;

    public $name;
    public $email;
    public $phone;
    public $age;
    public $patientType;
    public $gender;

    public  Appointment $appointment;

    public function mount(Doctor $doctor, string $date): void
    {
        $this->doctor = $doctor;
        $this->date = $date;

        // If user came from "Book Slot" link with instance pre-selected:
        $instance = request()->query('instance');
        if ($instance) {
            $this->selectedInstanceId = (int) $instance;
        }
        $slotDetailQuery = DoctorSessionInstance::query()
            ->where('id', $this->selectedInstanceId)
            ->where('doctor_id', $doctor->id);
        if ($this->date === CarbonImmutable::today()->toDateString()) {
            $slotDetailQuery->whereTime('start_time', '>', CarbonImmutable::now('Asia/Kolkata')->toTimeString());
        }
        $this->slotDetail = $slotDetailQuery->first();
        if (!$this->slotDetail) {
            $this->selectedInstanceId = null; // reset if not valid
        }
    }


    public function bookAppointment(): void
    {
        $instanceId = $this->selectedInstanceId;
        $user = Auth::user();
        abort_unless($user, 401);
        $this->validate([
            'selectedInstanceId' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:10', 'min:10'],
            'age' => ['required', 'integer', 'min:0', 'max:150'],
            'gender' => ['required', 'string', 'max:20'],
        ]);

        $hasError = false;

        DB::transaction(function () use ($user, $instanceId, &$hasError) {
            if (!$instanceId) {
                $this->addError('selectedInstanceId', 'Please select a session.');
                $hasError = true;
                return;
            }

            $instance = DoctorSessionInstance::query()
                ->where('id', $instanceId)
                ->lockForUpdate()
                ->firstOrFail();

                
                if (!$instance) {
                    $this->addError('selectedInstanceId', 'Selected session not found.');
                    $hasError = true;
                    return;
                    }
                    if ((int) $instance->doctor_id !== (int) $this->doctor->id) {
                        abort(403);
                        }
                        if ($instance->capacity_booked >= $instance->capacity_total) {
                            $this->addError('selectedInstanceId', 'This session is full.');
                            Toaster::error('This session is full. Please select another session or try again later.');
                            $hasError = true;
                            return;
                            }
                            
                            $exists = Appointment::query()
                            ->where('user_id', $user->id)
                            ->where('doctor_session_instance_id', $instance->id)
                            ->where('doctor_id', $instance->doctor_id)
                            ->exists();
                            
                            if ($exists) {
                                $this->addError('selectedInstanceId', 'You already booked this session.');
                                Toaster::error('You already booked this session.');
                                $hasError = true;
                                return;
                                }
            $instance->load('doctor');
            $instance->increment('capacity_booked');
            $this->appointment = Appointment::create([
                'user_id' => $user->id,
                'doctor_id' => $instance->doctor_id,
                'doctor_session_instance_id' => $instance->id,
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'gender' => $this->gender,
                'age' => $this->age,
                'note' => $this->note,
                'amount' => $instance->doctor->consultation_fee,
                'currency' => $instance->doctor->currency,
                'payment_status' => 'pending',
                'status' => 'pending',
                'appointment_date' => $instance->date,
                'appointment_start_time' => $instance->start_time,
                'appointment_end_time' => $instance->end_time,
            ]);
        });

        if (!$hasError) {
            // Success! Send the user to Stripe
            if ($this->appointment->id) {
                // use the stripe
                $checkout = auth()->user()->checkoutCharge($this->appointment->amount*100, 'Consultation Fee', 1, [
                    'success_url' => route('patient.appointments.show', $this->appointment->id),
                    'cancel_url' => route('patient.appointments.show', $this->appointment->id),
                    'metadata' => [
                        'id' => $this->appointment->id,
                        'appointment_id' => $this->appointment->appointment_id,
                        'doctor_id' => $this->appointment->doctor_id,
                       
                    ],
                ]);
                $checkoutUrl = $checkout->url;
                $this->redirect($checkoutUrl, false);
            } else {
                Toaster::error('Failed to create appointment. Please try again.');
            }
        }



       
    }


    public function render()
    {
        $instances = DoctorSessionInstance::query()
            ->where('doctor_id', $this->doctor->id)
            ->where('status', 'open')
            ->where('id', $this->selectedInstanceId) // show selected instance even if full/closed
            ->orderBy('start_time')
            ->get()
            ->map(function (DoctorSessionInstance $i) {
                $i->remaining = max(0, $i->capacity_total - $i->capacity_booked);
                return $i;
            });

        return view('livewire.patient.book-appointment', [
            'instances' => $instances,
        ])->layout('components.layouts.app');
    }

    public function updatedPatientType($value)
    {
        if ($value == "self") {
            $user = Auth::user();
            $this->name = $user->name;
            $this->email = $user->email;
            $this->phone = $user->phone;
            $this->gender = $user->gender;
            $this->age = $user->age;
        } else {
            $this->name = null;
            $this->email = null;
            $this->phone = null;
            $this->age = null;
        }
    }
}
