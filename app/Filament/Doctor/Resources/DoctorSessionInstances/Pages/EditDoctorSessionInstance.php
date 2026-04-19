<?php

namespace App\Filament\Doctor\Resources\DoctorSessionInstances\Pages;

use App\Filament\Doctor\Resources\DoctorSessionInstances\DoctorSessionInstanceResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditDoctorSessionInstance extends EditRecord
{
    protected static string $resource = DoctorSessionInstanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
