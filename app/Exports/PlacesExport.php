<?php

namespace App\Exports;

use App\Models\Place;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class PlacesExport implements FromQuery, WithHeadings, WithMapping, WithChunkReading
{
    /**
     * Build the query for the export. Scopes to current Filament tenant when available.
     */
    public function query()
    {
        $query = Place::query()->select([
            'code',
            'khmer',
            'latin',
            'postal_code',
            'reference',
            'official_note',
            'note',
            'geo_location',
            'geo_boundary',
            'issued_date',
        ]);

        return $query->orderBy('id');
    }

    /**
     * Headings for the exported sheet.
     */
    public function headings(): array
    {
        return [
            'code',
            'khmer',
            'latin',
            'postal_code',
            'reference',
            'official_note',
            'note',
            'geo_location',
            'geo_boundary',
            'issued_date',
        ];
    }

    /**
     * Map a Location model into a row for the sheet.
     */
    public function map($place): array
    {
        return [
            $place->code,
            $place->khmer,
            $place->latin,
            $place->postal_code,
            $place->reference,
            $place->official_note,
            $place->note,
            $place->geo_location,
            $place->geo_boundary,
            $place->issued_date,
        ];
    }

    /**
     * Chunk size for query reading.
     */
    public function chunkSize(): int
    {
        return 1000;
    }
}
