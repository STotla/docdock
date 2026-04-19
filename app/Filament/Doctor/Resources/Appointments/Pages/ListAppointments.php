<?php

namespace App\Filament\Doctor\Resources\Appointments\Pages;

use App\Filament\Doctor\Resources\Appointments\AppointmentResource;
use App\Models\User;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use UnitEnum;
use Illuminate\Database\Eloquent\Builder;

class ListAppointments extends ListRecords
{
    protected static string $resource = AppointmentResource::class;

    

     protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()
                 ->where('doctor_id', User::find(auth()->id())->doctor->id);
     }
    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
