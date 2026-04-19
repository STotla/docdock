<?php

namespace App\Filament\Doctor\Resources\DoctorSessionInstances\Pages;

use App\Filament\Doctor\Resources\DoctorSessionInstances\DoctorSessionInstanceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDoctorSessionInstances extends ListRecords
{
    protected static string $resource = DoctorSessionInstanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
