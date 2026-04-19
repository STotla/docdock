<?php

namespace App\Filament\Doctor\Resources\Appointments;

use App\Filament\Doctor\Resources\Appointments\Pages\CreateAppointment;
use App\Filament\Doctor\Resources\Appointments\Pages\DiagnoseAppointment;
use App\Filament\Doctor\Resources\Appointments\Pages\EditAppointment;
use App\Filament\Doctor\Resources\Appointments\Pages\ListAppointments;
use App\Filament\Doctor\Resources\Appointments\Pages\ListPastAppointments;
use App\Filament\Doctor\Resources\Appointments\Pages\ListTodayAppointments;
use App\Filament\Doctor\Resources\Appointments\Pages\ListUpcomingAppointments;
use App\Filament\Doctor\Resources\Appointments\Pages\ViewAppointment;
use App\Filament\Doctor\Resources\Appointments\Schemas\AppointmentForm;
use App\Filament\Doctor\Resources\Appointments\Schemas\AppointmentInfolist;
use App\Filament\Doctor\Resources\Appointments\Tables\AppointmentsTable;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\User;
use BackedEnum;
use Filament\Navigation\NavigationItem;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Appointment';

    protected static string|UnitEnum|null $navigationGroup = 'Appointments';

    public static function form(Schema $schema): Schema
    {
        return AppointmentForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AppointmentInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AppointmentsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getNavigationItems(): array
    {
        $doctorId = Doctor::query()->where('user_id', auth()->id())->value('id');

         return [
            NavigationItem::make('Today\'s Appointments')
                ->group(static::$navigationGroup)
                ->url(fn(): string => ListTodayAppointments::getUrl())
                ->badge(
                    Appointment::query()->where('doctor_id', $doctorId)->whereDate('appointment_date', today())->count(),
                    color: 'primary'
                )
                ->isActiveWhen(function (): bool {
                    $routeName = request()->route()?->getName() ?? '';
                    return str_ends_with($routeName, '.today-appointments');
                })

                ->sort(1),

            NavigationItem::make('Upcoming Appointments')
                ->group(static::$navigationGroup)
                ->url(static::getUrl('upcoming-appointments'))
                ->badge(Appointment::query()->where('doctor_id', $doctorId)->whereDate('appointment_date', '>', today())->count(),
                    color: 'warning')
                ->isActiveWhen(function (): bool {
                    $routeName = request()->route()?->getName() ?? '';
                    return str_ends_with($routeName, '.upcoming-appointments');
                })

                ->sort(2),
            NavigationItem::make('Past Appointments')
                ->group(static::$navigationGroup)
                ->url(static::getUrl('past-appointments'))
                ->badge(Appointment::query()->where('doctor_id', $doctorId)->where('appointment_date', '<', today())->count(),
                    color: 'danger')
                ->isActiveWhen(function (): bool {
                    $routeName = request()->route()?->getName() ?? '';
                    return str_ends_with($routeName, '.past-appointments');
                })

                ->sort(3),
        ];
        return [
            NavigationItem::make('Today\'s Appointments')
                ->group(static::$navigationGroup)
                ->url(fn(): string => ListTodayAppointments::getUrl())
                ->badge(
                    Appointment::query()->where('doctor_id', 5)->whereDate('appointment_date', today())->count(),
                    color: 'primary'
                )
                ->isActiveWhen(function (): bool {
                    $routeName = request()->route()?->getName() ?? '';
                    return str_ends_with($routeName, '.today-appointments');
                })

                ->sort(1),

            NavigationItem::make('Upcoming Appointments')
                ->group(static::$navigationGroup)
                ->url(static::getUrl('upcoming-appointments'))
                ->badge(Appointment::query()->where('doctor_id', auth()->id())->whereDate('appointment_date', '>', today())->count(),
                    color: 'warning')
                ->isActiveWhen(function (): bool {
                    $routeName = request()->route()?->getName() ?? '';
                    return str_ends_with($routeName, '.upcoming-appointments');
                })

                ->sort(2),
            NavigationItem::make('Past Appointments')
                ->group(static::$navigationGroup)
                ->url(static::getUrl('past-appointments'))
                ->badge(Appointment::query()->where('doctor_id', auth()->id())->where('appointment_date', '<', today())->count(),
                    color: 'danger')
                ->isActiveWhen(function (): bool {
                    $routeName = request()->route()?->getName() ?? '';
                    return str_ends_with($routeName, '.past-appointments');
                })

                ->sort(3),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAppointments::route('/'),
            'today-appointments' => ListTodayAppointments::route('/today-appointments'),
            'upcoming-appointments' => ListUpcomingAppointments::route('/upcoming-appointments'),
            'past-appointments' => ListPastAppointments::route('/past-appointments'),
            'view' => ViewAppointment::route('/{record}'),
            'diagnose' => DiagnoseAppointment::route('/{record}/diagnose'),
        ];
    }
}
