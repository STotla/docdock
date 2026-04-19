<?php

namespace App\Filament\Doctor\Pages;

use App\Models\DoctorWeeklySession;
use App\Services\DoctorSessionInstanceGenerator;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Support\Facades\DB;
use UnitEnum;

class Roster extends Page implements HasForms
{
    use InteractsWithForms;

    /**
     * Filament Pages require static $view.
     */
    public  string $view = 'filament.doctor.pages.roster';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationLabel = 'Roster';
    protected static string|UnitEnum|null $navigationGroup = 'Schedule';
    protected static ?int $navigationSort = 1;

    protected static ?string $title = 'Roster';

    public array $data = [
        'days' => [
            1 => [], 2 => [], 3 => [], 4 => [], 5 => [], 6 => [], 7 => [],
        ],
    ];

    public function mount(): void
    {
        $doctor = auth()->user()?->doctor;
        abort_unless($doctor, 403);

        $sessionsByDay = DoctorWeeklySession::query()
            ->where('doctor_id', $doctor->id)
            ->where('is_enabled', true)
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get()
            ->groupBy('day_of_week');

        $this->data['days'] = collect(range(1, 7))->mapWithKeys(function (int $day) use ($sessionsByDay) {
            $sessions = $sessionsByDay->get($day, collect())
                ->map(fn (DoctorWeeklySession $s) => [
                    'start_time' => $s->start_time,
                    'end_time' => $s->end_time,
                    'slots_count' => $s->slots_count,
                ])
                ->values()
                ->all();

            return [$day => $sessions];
        })->all();

        $this->form->fill($this->data);
    }

    protected function getFormSchema(): array
    {
        return [
            Tabs::make('Weekly roster')
                ->tabs([
                    $this->dayTab(1, 'Monday'),
                    $this->dayTab(2, 'Tuesday'),
                    $this->dayTab(3, 'Wednesday'),
                    $this->dayTab(4, 'Thursday'),
                    $this->dayTab(5, 'Friday'),
                    $this->dayTab(6, 'Saturday'),
                    $this->dayTab(7, 'Sunday'),
                ]),
        ];
    }

    protected function getFormStatePath(): string
    {
        return 'data';
    }

    protected function dayTab(int $day, string $label): Tab
    {
        return Tab::make($label)->schema([
            Repeater::make("days.$day")
                ->label('Sessions')
                ->addActionLabel('Add session')
                ->schema([
                    TimePicker::make('start_time')
                        ->label('Start')
                        ->seconds(false)
                        ->required(),

                    TimePicker::make('end_time')
                        ->label('End')
                        ->seconds(false)
                        ->required()
                        ->after('start_time'),

                    TextInput::make('slots_count')
                        ->label('Slots (patients)')
                        ->numeric()
                        ->minValue(1)
                        ->required(),
                ])
                ->columns(3)
                ->defaultItems(0),
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('Save')
                ->color('primary')
                ->action('save'),
        ];
    }

    public function save(): void
    {
        $doctor = auth()->user()?->doctor;
        abort_unless($doctor, 403);

        $state = $this->form->getState();
       
        $days = $state['days'] ?? [];

        // 1) Save weekly roster rules
        DB::transaction(function () use ($doctor, $days) {
            DoctorWeeklySession::query()
                ->where('doctor_id', $doctor->id)
                ->delete();

            $rows = [];

            foreach ($days as $dayOfWeek => $sessions) {
                foreach (($sessions ?? []) as $s) {
                    if (empty($s['start_time']) || empty($s['end_time']) || empty($s['slots_count'])) {
                        continue;
                    }

                    $rows[] = [
                        'doctor_id' => $doctor->id,
                        'day_of_week' => (int) $dayOfWeek,
                        'start_time' => $s['start_time'],
                        'end_time' => $s['end_time'],
                        'slots_count' => (int) $s['slots_count'],
                        'is_enabled' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            if (! empty($rows)) {
                DoctorWeeklySession::insert($rows);
            }
        });

        DoctorWeeklySession::updateInstanceTable($doctor);

        // 2) Generate next 60 days of bookable session instances (patients book these)
        app(DoctorSessionInstanceGenerator::class)
            ->generateForNextDays($doctor, 60, regenerate: true);

        Notification::make()
            ->title('Roster saved')
            ->body('Your weekly timing sessions have been updated and availability was generated for the next 60 days.')
            ->success()
            ->send();
    }
}