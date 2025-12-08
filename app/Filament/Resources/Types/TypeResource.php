<?php

namespace App\Filament\Resources\Types;

use App\Filament\Resources\Types\Pages\CreateType;
use App\Filament\Resources\Types\Pages\EditType;
use App\Filament\Resources\Types\Pages\ListTypes;
use App\Filament\Resources\Types\Schemas\TypeForm;
use App\Filament\Resources\Types\Tables\TypesTable;
use App\Models\Type;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;

class TypeResource extends Resource
{
    use Translatable;

    protected static ?string $model = Type::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getModelLabel(): string
    {
        return __('type.single');
    }

    public static function getPluralModelLabel(): string
    {
        return __('type.plural');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('general.nav.administration');
    }

    public static function form(Schema $schema): Schema
    {
        return TypeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TypesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTypes::route('/'),
            'create' => CreateType::route('/create'),
            'edit' => EditType::route('/{record}/edit'),
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
