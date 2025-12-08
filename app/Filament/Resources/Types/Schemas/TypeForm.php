<?php

namespace App\Filament\Resources\Types\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columns(1)
                    ->columnSpanFull()
                    ->components([
                        TextInput::make('name')
                            ->label(__('type.name'))
                            ->required(),
                    ]),
            ]);
    }
}
