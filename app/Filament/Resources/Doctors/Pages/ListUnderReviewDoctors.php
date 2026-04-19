<?php

namespace App\Filament\Resources\DoctorResource\Pages;

use App\Filament\Resources\Doctors\DoctorResource;
use App\Filament\Resources\Doctors\Pages\ListDoctors;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;

class ListUnderReviewDoctors extends ListDoctors
{
    protected static string $resource = DoctorResource::class;

    // THIS is what makes it show in the navigation (your Filament version)
    protected static bool $shouldRegisterNavigation = true;

   
 public static function getNavigationLabel(): string
    {
        return 'Under Review';
    }
    //protected static string|\UnitEnum|null $navigationGroup = 'Doctors';
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    //protected static ?int $navigationSort = 11;

    public function getTitle(): string
    {
        return 'Doctors - Under Review';
    }


    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()
            ->where('profile_status', 'under review');
    }
}