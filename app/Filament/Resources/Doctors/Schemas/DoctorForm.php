<?php

namespace App\Filament\Resources\Doctors\Schemas;

use Filament\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\IconEntry;
use Filament\Schemas\Schema;
use Filament\Infolists\Components\ImageEntry;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Storage;

class DoctorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Doctor Information')
                    ->columns(2)
                    ->schema([
                        Placeholder::make('doctor_name')
                            ->label('Doctor Name')
                            ->content(fn($record) => $record->user?->name ?? '-'),
                        ImageEntry::make('profile_img_url')
                            ->disk('public')
                            ->hiddenLabel()
                            ->circular(),
                        Placeholder::make('specialization')
                            ->label('Specialization')
                            ->content(fn($record) => $record->specialization?->name ?? '-'),
                        Placeholder::make('registration_no')
                            ->label('Registration No.')
                            ->content(fn($record) => $record->registration_no ?? '-'),
                        Placeholder::make('qualification')
                            ->label('Qualification')
                            ->content(fn($record) => $record->qualification ?? '-'),
                        Placeholder::make('experience')
                            ->label('Experience (years)')
                            ->content(fn($record) => $record->experience ?? '-'),
                        Placeholder::make('phone')
                            ->label('Phone')
                            ->content(fn($record) => $record->phone ?? '-'),
                        Placeholder::make('bio')
                            ->label('Bio')
                            ->content(fn($record) => $record->bio ?? '-')
                            ->columnSpanFull(),
                    ]),



                Section::make('Clinic Information')
                    ->columns(2)
                    ->schema([
                        Placeholder::make('clinic_name')
                            ->label('Clinic Name')
                            ->content(fn($record) => $record->clinic_name ?? '-'),
                        Placeholder::make('clinic_address')
                            ->label('Clinic Address')
                            ->content(fn($record) => $record->clinic_address ?? '-'),
                        Placeholder::make('city')
                            ->label('City')
                            ->content(fn($record) => $record->city ?? '-'),
                        Placeholder::make('state')
                            ->label('State')
                            ->content(fn($record) => $record->state ?? '-'),
                        Placeholder::make('consultation_fee')
                            ->label('Consultation Fee')
                            ->content(fn($record) => ($record->consultation_fee ?? '-') . ' ' . ($record->currency ?? '')),
                    ]),
                Section::make('Contact Details')
                    ->columns(2)
                    ->schema([
                        Placeholder::make('email')
                            ->label('Email')
                            ->content(fn($record) => $record->user->email ?? '-'),
                        Placeholder::make('Phone')
                            ->label('Phone')
                            ->content(fn($record) => $record->phone ?? '-'),

                    ]),

                Section::make('Certificates')
                    ->columns(1)
                    ->schema([
                        Repeater::make('certificates')
                            ->relationship('certificates')
                            ->label('Certificates')
                            ->disableItemCreation()
                            ->disableItemDeletion()
                            ->disableItemMovement()
                            ->columns(2)
                            ->schema([

                                Placeholder::make('certificate_name')
                                    ->label('Certificate Name')
                                    ->content(fn(Get $get) => $get('title') ?: '-'),

                                Placeholder::make('file_path')
                                    ->label('File')
                                    ->url(fn(Get $get) => Storage::url($get('file_path'))),
                                Placeholder::make('issued_date')
                                    ->columns(2),
                                Placeholder::make('expiry_date')
                                    ->columns(2),
                                Action::make('verify')
                                    ->label('Verify')
                                    ->color('success')
                                    ->icon('heroicon-o-check-badge')
                                    ->requiresConfirmation()
                                    ->visible(fn($record) => ! $record->is_verified)
                                    ->action(function ($record): void {
                                        $record->update([
                                            'is_verified' => true,
                                            'verified_at' => now(),
                                        ]);
                                    }),
                                Action::make('unverify')
                                    ->label('Unverify')
                                    ->color('danger')
                                    ->icon('heroicon-o-x-circle')
                                    ->requiresConfirmation()
                                    ->visible(fn($record) => $record->is_verified)
                                    ->action(function ($record): void {
                                        $record->update([
                                            'is_verified' => false,
                                            'verified_at' => null,
                                        ]);
                                    }),


                                IconEntry::make('is_verified')
                                    ->label('Verification Status')
                                    ->hiddenLabel()
                                    ->iconPosition(IconPosition::After)
                                    ->icon(
                                        fn($state) => (bool) $state
                                            ? 'heroicon-o-check-badge'
                                            : 'heroicon-o-x-circle'
                                    )
                                    ->color(fn($state) => (bool) $state ? 'success' : 'danger')
                                    ->tooltip(fn($state) => (bool) $state ? 'Verified' : 'Not verified')
                            ]),

                    ]),


                Section::make('Review')
                    ->schema([
                        Select::make('profile_status')
                            ->label('Profile Status')
                            ->options([
                                'draft' => 'Draft',
                                'under review' => 'Under Review',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                            ])
                            ->required()
                            ->native(false)
                            ->live(),
                        Textarea::make('reject_note')
                            ->label('Reject note')
                            ->rows(4)
                            ->placeholder('Explain why the profile was rejected...')
                            ->visible(fn(callable $get) => $get('profile_status') === 'rejected')
                            ->required(fn(callable $get) => $get('profile_status') === 'rejected'),
                    ]),
            ]);
    }
}
