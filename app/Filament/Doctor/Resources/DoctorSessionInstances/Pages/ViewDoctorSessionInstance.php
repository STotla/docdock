<?php

namespace App\Filament\Doctor\Resources\DoctorSessionInstances\Pages;

use App\Filament\Doctor\Resources\DoctorSessionInstances\DoctorSessionInstanceResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDoctorSessionInstance extends ViewRecord
{
    protected static string $resource = DoctorSessionInstanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
