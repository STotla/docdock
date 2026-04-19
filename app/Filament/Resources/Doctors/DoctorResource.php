<?php

namespace App\Filament\Resources\Doctors;

use App\Filament\Resources\DoctorResource\Pages\ListApprovedDoctors;
use App\Filament\Resources\DoctorResource\Pages\ListDraftDoctors;
use App\Filament\Resources\DoctorResource\Pages\ListRejectedDoctors;
use App\Filament\Resources\DoctorResource\Pages\ListUnderReviewDoctors;
use App\Filament\Resources\Doctors\Pages\EditDoctor;
use App\Filament\Resources\Doctors\Pages\ListDoctors;
use App\Filament\Resources\Doctors\Pages\ViewDoctor;
use App\Filament\Resources\Doctors\Schemas\DoctorForm;
use App\Filament\Resources\Doctors\Schemas\DoctorInfolist;
use App\Filament\Resources\Doctors\Tables\DoctorsTable;
use App\Models\Doctor;
use BackedEnum;
use Filament\Navigation\NavigationItem;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class DoctorResource extends Resource
{
    protected static ?string $model = Doctor::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = 'Doctors';

    protected static ?string $recordTitleAttribute = 'Doctor';

    public static function form(Schema $schema): Schema
    {
        return DoctorForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DoctorInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DoctorsTable::configure($table);
    }

    public static function getNavigationItems(): array
    {
        return [
            NavigationItem::make('All Doctors')
                ->group(static::$navigationGroup)
                ->icon(Heroicon::OutlinedRectangleStack)
                ->url(static::getUrl('index'))
                ->badge(fn (): string => (string) Doctor::query()->count(),'primary')
                ->isActiveWhen(function (): bool {
                    $routeName = request()->route()?->getName() ?? '';

                    return str_contains($routeName, '.resources.doctors.')
                        && ! str_ends_with($routeName, '.draft');
                })
                ->sort(1),

            
                   NavigationItem::make('Approved ')
                ->group(static::$navigationGroup)
                ->icon(Heroicon::OutlinedCheckBadge)
                ->url(static::getUrl('approved'))
                ->badge(fn (): string => (string) Doctor::query()->where('profile_status', 'approved')->count(),'success')
                ->isActiveWhen(function (): bool {
                    $routeName = request()->route()?->getName() ?? '';

                    return str_ends_with($routeName, '.approved');
                })
                ->sort(2),

                NavigationItem::make('Under Review ')
                ->group(static::$navigationGroup)
                ->icon(Heroicon::OutlinedClock)
                ->url(static::getUrl('under_review'))
                ->badge(fn (): string => (string) Doctor::query()->where('profile_status', 'under review')->count())
                ->isActiveWhen(function (): bool {
                    $routeName = request()->route()?->getName() ?? '';

                    return str_ends_with($routeName, '.under_review');
                })
                ->sort(2),
                 NavigationItem::make('Rejected ')
                ->group(static::$navigationGroup)
                ->icon(Heroicon::OutlinedXCircle,'warning')
                ->url(static::getUrl('rejected'))
                ->badge(fn (): string => (string) Doctor::query()->where('profile_status', 'rejected')->count(),'danger')
                ->isActiveWhen(function (): bool {
                    $routeName = request()->route()?->getName() ?? '';

                    return str_ends_with($routeName, '.rejected');
                })
                ->sort(2),
        ];
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'draft' => ListDraftDoctors::route('/draft'),
            'under_review'=>ListUnderReviewDoctors::route('/under_review'),
            'approved'=>ListApprovedDoctors::route('/approved'),
            'rejected'=>ListRejectedDoctors::route('/rejected'),
            'index' => ListDoctors::route('/'),
            'view' => ViewDoctor::route('/{record}'),
            'edit' => EditDoctor::route('/{record}/edit'),
        ];
    }
}
