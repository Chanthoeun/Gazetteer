<?php

namespace Database\Seeders;

use App\Imports\PlaceImporterWithoutQueue;
use Maatwebsite\Excel\Excel as ExcelService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class PlaceSeeder extends Seeder
{
    /**
     * @var ExcelService
     */
    protected ExcelService $excelService;

    public function __construct(ExcelService $excelService)
    {
        $this->excelService = $excelService;
    }

    public function run(): void
    {
        // The files are expected to be in public/data/places/
        $directoryPath = public_path('data/places');

        if (!File::isDirectory($directoryPath)) {
            $this->command->warn("Place data directory not found at '{$directoryPath}'. Skipping seeder.");
            return;
        }

        $files = File::files($directoryPath);

        if (empty($files)) {
            $this->command->info('No place files found in the directory to import.');
            return;
        }

        $this->command->info('Starting place data import from Excel files. This may take a while...');

        foreach ($files as $file) {
            $filePath = $file->getRealPath();
            $fileName = $file->getFilename();

            // Check for valid excel extensions to avoid processing other files
            $allowedExtensions = ['xlsx', 'xls', 'csv'];
            if (!in_array(strtolower($file->getExtension()), $allowedExtensions, true)) {
                $this->command->line("<fg=yellow>Skipping non-excel file:</> {$fileName}");
                continue;
            }

            $this->command->line("Importing places from: {$fileName}");
            // Using the injected Excel service instead of the facade.
            // This will still queue the import if LocationImporter implements ShouldQueue.
            $this->excelService->import(new PlaceImporterWithoutQueue(), $filePath);
        }

        $this->command->info('Place data import process completed.');
    }
}
