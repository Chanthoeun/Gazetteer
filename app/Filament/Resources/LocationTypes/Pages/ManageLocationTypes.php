<?php

namespace App\Filament\Resources\LocationTypes\Pages;

use App\Filament\Resources\LocationTypes\LocationTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageLocationTypes extends ManageRecords
{
    protected static string $resource = LocationTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->mutateDataUsing(function (array $data): array {
                    $data['created_by'] = auth()->user()->name;

                    return $data;
                }),
        ];
    }
}
