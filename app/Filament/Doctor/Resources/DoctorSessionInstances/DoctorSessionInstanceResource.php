<?php

namespace App\Filament\Doctor\Resources\DoctorSessionInstances;

use App\Filament\Doctor\Resources\DoctorSessionInstances\Pages\CreateDoctorSessionInstance;
use App\Filament\Doctor\Resources\DoctorSessionInstances\Pages\EditDoctorSessionInstance;
use App\Filament\Doctor\Resources\DoctorSessionInstances\Pages\ListDoctorSessionInstances;
use App\Filament\Doctor\Resources\DoctorSessionInstances\Pages\ViewDoctorSessionInstance;
use App\Filament\Doctor\Resources\DoctorSessionInstances\Schemas\DoctorSessionInstanceForm;
use App\Filament\Doctor\Resources\DoctorSessionInstances\Schemas\DoctorSessionInstanceInfolist;
use App\Filament\Doctor\Resources\DoctorSessionInstances\Tables\DoctorSessionInstancesTable;
use App\Models\DoctorSessionInstance;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class DoctorSessionInstanceResource extends Resource
{
    protected static ?string $model = DoctorSessionInstance::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Session';

    public static function form(Schema $schema): Schema
    {
        return DoctorSessionInstanceForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DoctorSessionInstanceInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DoctorSessionInstancesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDoctorSessionInstances::route('/'),
            'create' => CreateDoctorSessionInstance::route('/create'),
            'view' => ViewDoctorSessionInstance::route('/{record}'),
            'edit' => EditDoctorSessionInstance::route('/{record}/edit'),
        ];
    }
    public static function getEloquentQuery(): Builder
{
    return parent::getEloquentQuery()
        ->where('doctor_id', auth()->user()->doctor->id); 
}
}
