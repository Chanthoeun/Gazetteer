<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        TextEntry::make('name')
                            ->label(__('user.name')),
                        TextEntry::make('email')
                            ->label(__('user.email')),
                        TextEntry::make('roles.name')
                            ->label(__('role.plural'))
                            ->badge()
                            ->formatStateUsing(fn($state): string => ucwords(str_replace('_', ' ', $state))),
                        TextEntry::make('created_at')
                            ->label(__('general.created_at'))
                            ->dateTime(),
                        TextEntry::make('updated_at')
                            ->label(__('general.updated_at'))
                            ->dateTime(),
                    ])->columns(2),
            ]);
    }
}
