<?php

namespace App\Filament\Resources\Types\Pages;

use App\Filament\Resources\Types\TypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use LaraZeus\SpatieTranslatable\Resources\Pages\ListRecords\Concerns\Translatable;

class ListTypes extends ListRecords
{
    use Translatable;

    protected static string $resource = TypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
