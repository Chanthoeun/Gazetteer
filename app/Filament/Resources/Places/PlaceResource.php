<?php

namespace App\Filament\Resources\Places;

use App\Filament\Resources\Places\Pages\CreatePlace;
use App\Filament\Resources\Places\Pages\EditPlace;
use App\Filament\Resources\Places\Pages\ListPlaces;
use App\Filament\Resources\Places\Pages\ViewPlace;
use App\Filament\Resources\Places\Schemas\PlaceForm;
use App\Filament\Resources\Places\Schemas\PlaceInfolist;
use App\Filament\Resources\Places\Tables\PlacesTable;
use App\Models\Place;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PlaceResource extends Resource
{
    protected static ?string $model = Place::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Map;

    protected static ?string $recordTitleAttribute = 'khmer';

    public static function getModelLabel(): string
    {
        return __('place.single');
    }

    public static function getPluralModelLabel(): string
    {
        return __('place.plural');
    }

    public static function form(Schema $schema): Schema
    {
        return PlaceForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PlaceInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PlacesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\BackupsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPlaces::route('/'),
            'create' => CreatePlace::route('/create'),
            'view' => ViewPlace::route('/{record}'),
            'edit' => EditPlace::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
