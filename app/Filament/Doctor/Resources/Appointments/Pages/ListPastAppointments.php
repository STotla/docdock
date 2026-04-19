<?php

namespace App\Filament\Doctor\Resources\Appointments\Pages;

use App\Filament\Doctor\Resources\Appointments\AppointmentResource;
use App\Models\User;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use UnitEnum;
use Illuminate\Database\Eloquent\Builder;

class ListPastAppointments extends ListRecords
{
    protected static string $resource = AppointmentResource::class;

    protected static bool $shouldRegisterNavigation = true;

     protected function getTableQuery(): Builder
    {
        $doctor = auth()->user()->doctor;
        return parent::getTableQuery()
                    ->where('doctor_id',$doctor->id)
                 ->where('appointment_date', '<', today());
     }
    protected function getHeaderActions(): array
    {
        return [

        ];
    }
    public function getBreadcrumb(): string
    {
        return 'Past Appointments';
    }
     public function getTitle(): string
    {   
        return 'Past Appointments';
    }
}
