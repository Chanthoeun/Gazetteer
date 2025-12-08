<?php

namespace App\Filament\Resources\Places\Tables;

use App\Models\Place;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PlacesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('type.name')
                    ->label(__('type.single'))
                    ->searchable(),
                TextColumn::make('parent.khmer')
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
                Filter::make('place')
                    ->schema([
                        Select::make('province')
                            ->label(__('place.province'))
                            ->options(fn() => Place::whereIn('type_id', [1, 2])->get()->pluck(app()->getLocale() === 'en' ? 'latin' : 'khmer', 'id'))
                            ->live()
                            ->searchable(app()->getLocale() === 'en' ? 'latin' : 'khmer')
                            ->afterStateUpdated(function (Set $set) {
                                $set('district', null);
                                $set('commune', null);
                                $set('village', null);
                            }),
                        Select::make('district')
                            ->label(__('place.district'))
                            ->options(fn(Get $get): array => $get('province') ? Place::where('parent_id', $get('province'))->get()->pluck(app()->getLocale() === 'en' ? 'latin' : 'khmer', 'id')->toArray() : [])
                            ->live()
                            ->searchable(app()->getLocale() === 'en' ? 'latin' : 'khmer')
                            ->afterStateUpdated(function (Set $set) {
                                $set('commune', null);
                                $set('village', null);
                            }),
                        Select::make('commune')
                            ->label(__('place.commune'))
                            ->options(fn(Get $get): array => $get('district') ? Place::where('parent_id', $get('district'))->get()->pluck(app()->getLocale() === 'en' ? 'latin' : 'khmer', 'id')->toArray() : [])
                            ->live()
                            ->searchable(app()->getLocale() === 'en' ? 'latin' : 'khmer')
                            ->afterStateUpdated(fn(Set $set) => $set('village', null)),
                        Select::make('village')
                            ->label(__('place.village'))
                            ->options(fn(Get $get): array => $get('commune') ? Place::where('parent_id', $get('commune'))->get()->pluck(app()->getLocale() === 'en' ? 'latin' : 'khmer', 'id')->toArray() : [])
                            ->searchable(app()->getLocale() === 'en' ? 'latin' : 'khmer'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $placeId = $data['village'] ?? $data['commune'] ?? $data['district'] ?? $data['province'] ?? null;

                        if (!$placeId) {
                            return $query;
                        }

                        // If a village is selected, we only want to show that specific village.
                        if (!empty($data['village'])) {
                            return $query->where('id', $placeId);
                        }

                        $descendantIds = Place::find($placeId)?->getAllDescendantIds() ?? [];

                        // Include the selected place itself in the results.
                        $descendantIds[] = (int) $placeId;

                        return $query->whereIn('id', $descendantIds);
                    }),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('code');
    }
}
