<?php

namespace App\Filament\Doctor\Pages;

use App\Models\Doctor;
use App\Models\Country;
use App\Models\State;
use App\Models\DoctorCertificate;
use App\Models\Specialization;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Actions\ActionGroup;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Dotswan\MapPicker\Fields\Map;
use Fahiem\FilamentPinpoint\Pinpoint;
use Filament\Schemas\Components\Grid;

class CompleteProfile extends Page implements HasForms
{
    use InteractsWithForms;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-identification';

    protected static bool $shouldRegisterNavigation = false;

    public string $view = 'filament.doctor.pages.complete-profile';

    public array $data = [];

    public Doctor $doctor;

    public function mount(): void
    {

        $user = auth()->user();

        abort_unless($user && $user->hasRole('doctor'), 403);

        $this->doctor = Doctor::firstOrCreate(
            ['user_id' => $user->id],
            ['specialization_id' => 1],
            ['profile_status' => 'pending'],
        );

        $this->form->fill([
            'specialization_id' => $this->doctor->specialization_id,
            'profile_img_url' => $this->normalizeUploadPath($this->doctor->profile_img_url),
            'registration_no' => $this->doctor->registration_no,
            'qualification' => $this->doctor->qualification,
            'experience' => $this->doctor->experience,
            'phone' => $this->doctor->phone,
            'bio' => $this->doctor->bio,
            'clinic_name' => $this->doctor->clinic_name,
            'clinic_address' => $this->doctor->clinic_address,
            'city' => $this->doctor->city,
            'state' => $this->doctor->state,
            'latitude' => $this->doctor->latitude,
            'longitude' => $this->doctor->longitude,
            'consultation_fee' => $this->doctor->consultation_fee,
            'currency' => $this->doctor->currency ?? 'USD',
            'certificates' => $this->doctor->certificates
                ->map(fn(DoctorCertificate $certificate): array => [
                    'title' => $certificate->title,
                    'file_path' => $this->normalizeUploadPath($certificate->file_path),
                    'issued_date' => $certificate->issued_date,
                    'expiry_date' => $certificate->expiry_date,
                ])
                ->whenEmpty(fn($collection) => $collection->push([
                    'title' => '',
                    'file_path' => null,
                    'issued_date' => null,
                    'expiry_date' => null,
                ]))
                ->values()
                ->all(),
        ]);
    }

    public function form(Schema $schema): Schema
    {

        return $schema
            ->schema([
                Section::make('Profile')
                    ->schema([
                        Select::make('specialization_id')
                            ->label('Specialization')
                            ->options(fn() => Specialization::query()
                                ->where('is_active', true)
                                ->orderBy('name')
                                ->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('registration_no')
                            ->label(' Medical Registration Number')
                            ->maxLength(255)
                            ->required(),
                        TextInput::make('qualification')
                            ->label('Qualification')
                            ->maxLength(255)
                            ->required(),
                        TextInput::make('experience')
                            ->label('Experience (years)')
                            ->numeric()
                            ->minValue(0)
                            ->required(),
                    ])
                    ->columns(2),
                Section::make('Personal Information')
                    ->schema([

                        FileUpload::make('profile_img_url')
                            ->label('Profile Image')
                            ->image()
                            ->disk('public')
                            ->directory('doctor-profiles')
                            ->maxSize(4096)
                            ->required(),

                        Textarea::make('bio')
                            ->rows(5)
                            ->required(),
                        TextInput::make('phone')
                            ->mask('99999-99999')
                            ->placeholder('00000-00000')
                            ->prefix('+91')
                            ->tel()
                            ->maxLength(50),
                    ])
                    ->columns(2),
                Section::make('Clinic Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Pinpoint::make('location')
                                    ->label('Location')
                                    ->defaultLocation($this->doctor->latitude ?? 20.5937, $this->doctor->longitude ?? 78.9629)
                                    ->shortAddressField('clinic_address')
                                    ->cityField('city')
                                    ->provinceField('state')
                                    ->latField('latitude')
                                    ->lngField('longitude')
                                    ->columnSpan(1),
                                Grid::make(1)
                                    ->schema([
                                        TextInput::make('clinic_name')
                                            ->maxLength(255)
                                            ->columnSpan(2)
                                            ->required(),
                                        Textarea::make('clinic_address')
                                            ->rows(3)
                                            ->columnSpan(2)
                                            ->required(),
                                        TextInput::make('state')
                                            ->label('State')
                                            ->columnSpan(1)
                                            ->required(),
                                        TextInput::make('city')
                                            ->label('City')
                                            ->columnSpan(1)
                                            ->required(),

                                        TextInput::make('latitude')
                                            ->label('Latitude')
                                            ->extraAttributes(['style' => 'display: none;']),
                                        TextInput::make('longitude')
                                            ->label('Longitude')
                                            ->extraAttributes(['style' => 'display: none;']),
                                    ])
                                    ->columnSpan(1)
                                    ->columns(2),
                            ]),
                    ]),

                Section::make('Consultation Information')
                    ->schema([
                        TextInput::make('consultation_fee')
                            ->label('Consultation Fee')
                            ->numeric()
                            ->minValue(0)
                            ->required(),

                        Select::make('currency')
                            ->options([

                                'INR' => 'INR',

                            ])
                            ->default('INR')
                            ->required(),

                    ])->columns(2),

                Section::make('Certificates')
                    ->schema([
                        Repeater::make('certificates')
                            ->label('Certificate Details')
                            ->minItems(1)
                            ->defaultItems(1)
                            ->required()
                            ->schema([
                                TextInput::make('title')
                                    ->label('Title')
                                    ->maxLength(255)
                                    ->required(),

                                FileUpload::make('file_path')
                                    ->label('Certificate File')
                                    ->disk('public')
                                    ->directory('doctor-certificates')
                                    ->required(),

                                DatePicker::make('issued_date')
                                    ->label('Date of Issue')
                                    ->native(false)
                                    ->required(),

                                DatePicker::make('expiry_date')
                                    ->label('Date of Expiry')
                                    ->native(false)
                                    ->required()
                                    ->afterOrEqual('issued_date'),
                            ])
                            ->columns(2),
                    ]),
            ])
            ->statePath('data');
    }


    /**
     * Exposed for Blade: returns form actions with Livewire context bound.
     *
     * @return array<Action | ActionGroup>
     */

    public function saveDraft(): void
    {
        $data = $this->form->getState();
        $certificates = $data['certificates'] ?? [];
        unset($data['certificates']);

        $data['profile_img_url'] = $this->normalizeUploadPath($data['profile_img_url'] ?? null);
        $this->doctor->update([
            ...$data,
            'profile_status' => $this->doctor->profile_status === 'rejected'
                ? 'pending'
                : $this->doctor->profile_status,
            'rejected_at' => null,
        ]);

        $this->syncCertificates($certificates);
    }

    public function submitForReview(): void
    {
        $data = $this->form->getState();
        $data['profile_status'] = "under review";
        $certificates = $data['certificates'] ?? [];
        unset($data['certificates']);

        $data['profile_img_url'] = $this->normalizeUploadPath($data['profile_img_url'] ?? null);

        if (empty($data['bio']) || empty($data['clinic_name'])) {
            Notification::make()
                ->title('Please complete required fields.')
                ->danger()
                ->send();
            return;
        }


        $this->doctor->update([
            ...$data,
            'submitted_at' => Carbon::now(),
            'approved_at' => null,
            'rejected_at' => null,
        ]);

        $this->syncCertificates($certificates);

        Notification::make()
            ->title('Submitted for review. Admin will approve your profile soon.')
            ->success()
            ->send();
    }

    protected function syncCertificates(array $certificates): void
    {
        $rows = collect($certificates)
            ->map(fn(array $certificate): array => [
                'title' => $certificate['title'] ?? '',
                'file_path' => $this->normalizeUploadPath($certificate['file_path'] ?? null) ?? '',
                'issued_date' => $certificate['issued_date'] ?? null,
                'expiry_date' => $certificate['expiry_date'] ?? null,
            ])
            ->filter(fn(array $certificate): bool => $certificate['title'] !== '' && $certificate['file_path'] !== '')
            ->values()
            ->all();

        $this->doctor->certificates()->delete();

        if ($rows !== []) {
            $this->doctor->certificates()->createMany($rows);
        }
    }

    protected function normalizeUploadPath(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        $normalized = str_replace('\\', '/', trim($path));

        if (str_starts_with($normalized, 'storage/')) {
            return substr($normalized, strlen('storage/'));
        }

        if (str_starts_with($normalized, 'public/')) {
            return substr($normalized, strlen('public/'));
        }

        return $normalized;
    }

    public function getStatesByCountry(string $countryCode): array
    {
        $country = Country::where('iso2', $countryCode)->first();
        $states = $country->states;
        foreach ($states as $state) {
            $stateNames[$state->name] = Str::ucfirst($state->name);
        }
        return $stateNames ?? [];
    }

    public function getCitiesByState($stateName): array
    {

        if (!$stateName) {
            return [];
        }
        $stateName = strtolower($stateName);

        $stateObject = State::where('name', $stateName)->first();
        $cities = $stateObject->cities;
        foreach ($cities as $city) {
            $cityNames[$city->name] = Str::ucfirst($city->name);
        }
        return $cityNames ?? [];
    }
}
