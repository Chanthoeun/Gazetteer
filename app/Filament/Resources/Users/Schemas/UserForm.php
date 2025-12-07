<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components(self::getForm());
    }

    public static function getForm(): array
    {
        return [
            Section::make()
                ->schema([
                    TextInput::make('name')
                        ->label(__('user.name'))
                        ->required()
                        ->maxLength(255),

                    TextInput::make('email')
                        ->label(__('user.email'))
                        ->email()
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true),

                    TextInput::make('password')
                        ->label(__('user.password'))
                        ->password()
                        ->revealable()
                        ->dehydrated(fn(?string $state): bool => filled($state))
                        ->required(fn(string $operation): bool => $operation === 'create'),

                    TextInput::make('password_confirmation')
                        ->label(__('user.password_confirmation'))
                        ->password()
                        ->revealable()
                        ->same('password')
                        ->dehydrated(false)
                        ->required(fn(string $operation): bool => $operation === 'create'),

                    Select::make('roles')
                        ->label(__('role.plural'))
                        ->relationship('roles', 'name')
                        ->multiple()
                        ->preload()
                        ->searchable(),
                ])->columns(2),
        ];
    }

}
