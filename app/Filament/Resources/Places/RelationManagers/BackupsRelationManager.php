<?php

namespace App\Filament\Resources\Places\RelationManagers;

use App\Models\Backup;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BackupsRelationManager extends RelationManager
{
    protected static string $relationship = 'backups';

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('type.name')
                    ->label(__('type.single')),
                TextEntry::make('parent.id')
                    ->label(__('place.parent'))
                    ->placeholder('-'),
                TextEntry::make('code')
                    ->label(__('place.code')),
                TextEntry::make('khmer')
                    ->label(__('place.khmer')),
                TextEntry::make('latin')
                    ->label(__('place.latin')),
                TextEntry::make('postal_code')
                    ->label(__('place.postal_code')),
                TextEntry::make('geo_location')
                    ->label(__('place.geo_location')),
                TextEntry::make('geo_boundary')
                    ->label(__('place.geo_boundary')),
                TextEntry::make('reference')
                    ->label(__('place.reference')),
                TextEntry::make('issued_date')
                    ->label(__('place.issued_date'))
                    ->date(),
                TextEntry::make('official_note')
                    ->label(__('place.official_note'))
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
                    ->visible(fn(Backup $record): bool => $record->trashed()),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('code')
            ->columns([
                TextColumn::make('type.name')
                    ->label(__('type.single'))
                    ->searchable(),
                TextColumn::make('parent.id')
                    ->label(__('place.parent'))
                    ->searchable(),
                TextColumn::make('code')
                    ->label(__('place.code'))
                    ->searchable(),
                TextColumn::make('khmer')
                    ->label(__('place.khmer'))
                    ->searchable(),
                TextColumn::make('latin')
                    ->label(__('place.latin'))
                    ->searchable(),
                TextColumn::make('postal_code')
                    ->label(__('place.postal_code'))
                    ->searchable(),
                TextColumn::make('geo_location')
                    ->label(__('place.geo_location')),
                TextColumn::make('geo_boundary')
                    ->label(__('place.geo_boundary')),
                TextColumn::make('reference')
                    ->label(__('place.reference'))
                    ->searchable(),
                TextColumn::make('issued_date')
                    ->label(__('place.issued_date'))
                    ->date()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(__('general.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__('general.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->label(__('general.deleted_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->modifyQueryUsing(fn(Builder $query) => $query
                ->withoutGlobalScopes([
                    SoftDeletingScope::class,
                ]));
    }
}
