<?php

namespace App\Filament\Resources\Places\Pages;

use App\Filament\Resources\Places\PlaceResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePlace extends CreateRecord
{
    protected static string $resource = PlaceResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
