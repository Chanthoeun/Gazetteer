<?php

namespace App\Filament\Resources\Locations\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class LocationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        Select::make('location_type_id')
                            ->relationship('locationType', 'name', modifyQueryUsing: fn(Builder $query) => $query->orderBy('id', 'asc'))
                            ->required(),
                        Select::make('parent_id')
                            ->relationship('parent', 'name_kh')
                            ->preload()
                            ->searchable(['name_kh', 'name_en', 'code']),
                        TextInput::make('name_kh')
                            ->required()
                            ->unique(ignoreRecord: true),
                        TextInput::make('name_en')                            
                            ->unique(ignoreRecord: true),
                        Grid::make(3)
                            ->columnSpanFull()
                            ->schema([
                                TextInput::make('code')
                                    ->required()
                                    ->unique(ignoreRecord: true),
                                TextInput::make('postal_code')
                                    ->unique(ignoreRecord: true),
                                TextInput::make('coordination'),
                            ]),
                        Textarea::make('reference'),
                        Textarea::make('note'),
                    ])

                
            ]);
    }
}
