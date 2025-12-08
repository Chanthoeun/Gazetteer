<?php

namespace App\Filament\Resources\Types\Pages;

use App\Filament\Resources\Types\TypeResource;
use Filament\Resources\Pages\CreateRecord;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\CreateRecord\Concerns\Translatable;

class CreateType extends CreateRecord
{
    use Translatable;

    protected static string $resource = TypeResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make(),
        ];
    }
}
