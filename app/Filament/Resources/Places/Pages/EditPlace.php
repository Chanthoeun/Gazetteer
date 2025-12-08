<?php

namespace App\Filament\Resources\Places\Pages;

use App\Filament\Resources\Places\PlaceResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPlace extends EditRecord
{
    protected static string $resource = PlaceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    public function getRelationManagers(): array
    {
        return [];
    }

    protected function beforeSave(): void
    {
        $data = $this->getRecord()->toArray();
        $data['place_id'] = $this->getRecord()->id;
        unset($data['id']);
        unset($data['created_at']);
        unset($data['updated_at']);
        unset($data['deleted_at']);

        \App\Models\Backup::create($data);
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
