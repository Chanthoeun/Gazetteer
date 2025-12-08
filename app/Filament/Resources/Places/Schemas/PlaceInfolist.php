<?php

namespace App\Filament\Resources\Places\Schemas;

use App\Models\Place;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PlaceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columns(2)
                    ->columnSpanFull()
                    ->components([
                        TextEntry::make('type.name')
                            ->label(__('type.single')),
                        TextEntry::make('parent.id')
                            ->label(__('place.parent')),
                        TextEntry::make('code')
                            ->label(__('place.code')),
                        TextEntry::make('khmer')
                            ->label(__('place.khmer')),
                        TextEntry::make('latin')
                            ->label(__('place.latin'))
                            ->placeholder('-'),
                        TextEntry::make('postal_code')
                            ->label(__('place.postal_code'))
                            ->placeholder('-'),
                        TextEntry::make('geo_location')
                            ->label(__('place.geo_location'))
                            ->placeholder('-'),
                        TextEntry::make('geo_boundary')
                            ->label(__('place.geo_boundary'))
                            ->placeholder('-'),
                        TextEntry::make('reference')
                            ->label(__('place.reference'))
                            ->placeholder('-'),
                        TextEntry::make('issued_date')
                            ->label(__('place.issued_date'))
                            ->date()
                            ->placeholder('-'),
                        TextEntry::make('official_note')
                            ->label(__('place.note'))
                            ->placeholder('-')
                            ->columnSpanFull(),
                        TextEntry::make('created_at')
                            ->label(__('general.created_at'))
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('updated_at')
                            ->label(__('general.updated_at'))
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('deleted_at')
                            ->label(__('general.deleted_at'))
                            ->dateTime()
                            ->visible(fn(Place $record): bool => $record->trashed()),
                    ])
            ]);
    }
}
