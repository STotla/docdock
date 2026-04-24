<?php

namespace App\Filament\Resources\Specializations\Schemas;

use Dompdf\FrameDecorator\Image;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class SpecializationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('description')
                    ->nullable(),

                FileUpload::make('icon')
                    ->label('Icon')
                    ->image()
                    ->disk('public')
                    ->directory('doctor-icons')
                    ->maxSize(4096)
                    ->required(),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
