<?php

namespace App\Filament\Resources\Doctors\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DoctorInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Doctor Information')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('user.name')
                            ->label('Doctor Name'),
                        TextEntry::make('specialization.name')
                            ->label('Specialization')
                            ->placeholder('-'),
                        TextEntry::make('registration_no')
                            ->placeholder('-'),
                        TextEntry::make('qualification')
                            ->placeholder('-'),
                        TextEntry::make('experience')
                            ->label('Experience (years)')
                            ->numeric()
                            ->placeholder('-'),
                        TextEntry::make('phone')
                            ->placeholder('-'),
                        TextEntry::make('bio')
                            ->placeholder('-')
                            ->columnSpanFull(),
                        ImageEntry::make('profile_img_url')
                            ->label('Profile Image')
                            ->disk('public')
                            ->columnSpanFull(),
                    ]),

                Section::make('Clinic Information')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('clinic_name')
                            ->placeholder('-'),
                        TextEntry::make('clinic_address')
                            ->placeholder('-'),
                        TextEntry::make('city')
                            ->placeholder('-'),
                        TextEntry::make('state')
                            ->placeholder('-'),
                        TextEntry::make('consultation_fee')
                            ->numeric()
                            ->placeholder('-'),
                        TextEntry::make('currency'),
                    ]),

                Section::make('Status')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('profile_status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'approved' => 'success',
                                'rejected' => 'danger',
                                'under_review' => 'warning',
                                default => 'gray',
                            }),
                        IconEntry::make('is_active')
                            ->boolean(),
                        TextEntry::make('submitted_at')
                            ->date()
                            ->placeholder('-'),
                        TextEntry::make('approved_at')
                            ->date()
                            ->placeholder('-'),
                        TextEntry::make('rejected_at')
                            ->date()
                            ->placeholder('-'),
                        TextEntry::make('created_at')
                            ->dateTime()
                            ->placeholder('-'),
                    ]),
            ]);
    }
}
