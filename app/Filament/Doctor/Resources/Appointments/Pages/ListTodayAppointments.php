<?php

namespace App\Filament\Doctor\Resources\Appointments\Pages;

use App\Filament\Doctor\Resources\Appointments\AppointmentResource;
use App\Models\User;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use UnitEnum;
use Illuminate\Database\Eloquent\Builder;

class ListTodayAppointments extends ListAppointments
{
    protected static string $resource = AppointmentResource::class;

    protected static bool $shouldRegisterNavigation = true;
    public function getTitle(): string
    {   
        return 'Today\'s Appointments';
    }

    public function getNavigarionLabel(): string
    {
        return 'Today\'s Appointments';
    }
    public function getBreadcrumb(): string
    {
        return 'Today\'s Appointments';
    }
     protected function getTableQuery(): Builder
    {
         $doctor = auth()->user()->doctor;
        return parent::getTableQuery()
                 ->whereDate
                 ('appointment_date', today())
                 ->where('status','confirmed');
      
     }
   
}
