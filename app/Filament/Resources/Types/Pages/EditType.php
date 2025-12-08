<?php

namespace App\Filament\Resources\Types\Pages;

use App\Filament\Resources\Types\TypeResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\EditRecord\Concerns\Translatable;

class EditType extends EditRecord
{
    use Translatable;

    protected static string $resource = TypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
