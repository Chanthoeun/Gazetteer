<?php

namespace App\Filament\Resources\Places\Pages;

use App\Exports\PlacesExport;
use App\Filament\Resources\Places\PlaceResource;
use App\Imports\PlaceImporter;
use App\Models\Type;
use EightyNine\ExcelImport\ExcelImportAction;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Excel;

class ListPlaces extends ListRecords
{
    protected static string $resource = PlaceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ExcelImportAction::make()
                ->color('primary')
                ->icon(Heroicon::OutlinedArrowDownTray)
                ->use(PlaceImporter::class),
            Action::make('export')
                ->label(__('Export'))
                ->icon(Heroicon::OutlinedArrowUpTray)
                ->action(function () {
                    $fileName = 'places_export_' . now()->format('Ymd_His') . '.xlsx';
                    return Excel::download(new PlacesExport(), $fileName);
                }),
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $tabs = [];

        // Base query for "All" tab (respecting filters)
        $allQuery = \App\Models\Place::query();
        $this->applyFiltersToQuery($allQuery);

        $tabs['all'] = Tab::make(__('general.all'))
            ->badge($allQuery->count());

        $types = Type::get();

        foreach ($types as $type) {
            $typeQuery = \App\Models\Place::query()->where('type_id', $type->id);
            $this->applyFiltersToQuery($typeQuery);

            $tabs[Str::slug($type->name)] = Tab::make($type->name)
                ->badge($typeQuery->count())
                ->modifyQueryUsing(fn(Builder $query) => $query->where('type_id', $type->id));
        }

        return $tabs;
    }

    protected function applyFiltersToQuery(Builder $query): void
    {
        // Use property access for Livewire reactivity, fallback to request for initial load if needed
        $filters = $this->tableFilters['place'] ?? [];

        $placeId = $filters['village'] ?? $filters['commune'] ?? $filters['district'] ?? $filters['province'] ?? null;

        if ($placeId) {
            $descendantIds = \App\Models\Place::find($placeId)?->getAllDescendantIds() ?? [];
            $descendantIds[] = (int) $placeId;

            $query->whereIn('id', $descendantIds);
        }
    }

    /**
     * @throws ValidationException
     */
    public function updatedActiveLocale(string $newActiveLocale): void
    {
        if (blank($this->activeLocale)) {
            $this->activeLocale = $newActiveLocale;

            return;
        }

        $oldActiveLocale = $this->activeLocale;

        $this->resetValidation();

        $translatableAttributes = static::getResource()::getTranslatableAttributes();

        try {
            $this->otherLocaleData[$oldActiveLocale] = Arr::only($this->form->getState(), $translatableAttributes);

            $this->form->fill([
                ...Arr::except($this->form->getState(), $translatableAttributes),
                ...$this->otherLocaleData[$newActiveLocale] ?? [],
            ]);

            unset($this->otherLocaleData[$newActiveLocale]);

            $this->activeLocale = $newActiveLocale;
        } catch (ValidationException $e) {
            $this->activeLocale = $oldActiveLocale;

            throw $e;
        }
    }
}
