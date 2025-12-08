<?php

namespace App\Filament\Resources\Places\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PlaceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columns(2)
                    ->columnSpanFull()
                    ->components([
                        Select::make('type_id')
                            ->label(__('type.single'))
                            ->relationship('type', 'name')
                            ->required(),
                        Select::make('parent_id')
                            ->label(__('place.parent'))
                            ->relationship('parent', 'id')
                            ->required(),
                        TextInput::make('code')
                            ->label(__('place.code'))
                            ->required(),
                        TextInput::make('khmer')
                            ->label(__('place.khmer'))
                            ->required(),
                        TextInput::make('latin')
                            ->label(__('place.latin')),
                        TextInput::make('postal_code')
                            ->label(__('place.postal_code')),
                        TextInput::make('geo_location')
                            ->label(__('place.geo_location')),
                        TextInput::make('geo_boundary')
                            ->label(__('place.geo_boundary')),
                        TextInput::make('reference')
                            ->label(__('place.reference')),
                        DatePicker::make('issued_date')
                            ->label(__('place.issued_date')),
                        Textarea::make('official_note')
                            ->label(__('place.note'))
                            ->columnSpanFull(),
                    ])
            ]);
    }
}
