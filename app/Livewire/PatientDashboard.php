<?php

namespace App\Livewire;

use App\Models\Appointment;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Lazy;

#[Layout('layouts.app')]
#[Lazy]
class PatientDashboard extends Component
{
    public $visitFilter='recent';
    

    #[Computed]
    public function greeting(): string
    {
        $hour = now()->hour;

        return match (true) {
            $hour < 12 => 'Good morning',
            $hour < 17 => 'Good afternoon',
            default    => 'Good evening',
        };
    }
 
      #[Computed]
    public function upcomingAppointments()
    {
        $query = auth()->user()->appointments();
        $upcomingAppointments = (clone $query)->where('status' ,'!=','completed')
                                ->whereBetween('appointment_date', [now(), now()->addDays(7)])
                                ->orderBy('appointment_date')
                                ->get();
        return $upcomingAppointments;

    }
       #[Computed]
    public function pastAppointments()
    {
        $query = auth()->user()->appointments();
        $pastAppointments = (clone $query)->where('status' ,'=','completed')
                                ->orderBy('appointment_date','desc')
                                ->get();
        return $pastAppointments;

    }
    
    public function render()
    {
        return view('livewire.patient-dashboard');
    }
     #[Computed]
    public function patient()
    {
        return User::find(auth()->id());
    }

    #[Computed]
    public function isNewPatient(): bool
    {
        return Appointment::where('user_id', auth()->id())->count() === 0;
    }

    #[Computed]
    public function upcomingAppointmentCount(){
        return $this->upcomingAppointments()->count();
    }

    #[Computed]
    public function pastAppointmentsCount(){
        return $this->pastAppointments()->count();
    }

    #[Computed]
    public function totalRevenueSpent(){
        $query = auth()->user()->appointments();
        $revenueSpent = (clone $query)->where('payment_status' ,'=','paid')
                        ->sum('amount');
                                
        return $revenueSpent;
    }
    public function setVisitFilter($key){
    $this->visitFilter=$key;
    return $this->pastVistiLogic($key);

    }
     #[Computed]
    public function lastVisits(){
       
        return $this->pastVistiLogic('all');
    }

    public function pastVistiLogic($key){
         $query = auth()->user()->appointments();
       
     $lastVisits = (clone $query)->where('status' ,'=','completed')
                ->where('appointment_date', '>=', now()->subDays(30)->toDateString())
            ->orderBy('appointment_date','desc')
                                ->get();
        return $lastVisits;
    }
    
}
