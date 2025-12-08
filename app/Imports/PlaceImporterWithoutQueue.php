<?php

namespace App\Imports;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\Place;
use App\Models\Type;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Events\BeforeImport;
use Maatwebsite\Excel\Events\ImportFailed;
use Filament\Notifications\Notification;

/**
 * PlaceImporter handles the import of place data from a spreadsheet.
 *
 * It implements several Laravel Excel concerns:
 * - ToModel: Each row in the spreadsheet will be mapped to an Eloquent model.
 * - WithHeadingRow: The first row of the sheet is treated as headings.
 * - WithChunkReading: The import is processed in chunks to manage memory usage.
 * - WithEvents: Allows registering event listeners for the import lifecycle.
 * - ShouldQueue: The import job will be pushed to a queue for background processing.
 */
class PlaceImporterWithoutQueue implements ToModel, WithHeadingRow, WithChunkReading, WithEvents
{
    /**
     * The user who initiated the import.
     * This is captured in the constructor to be available in the queued job.
     *
     * @var \App\Models\User
     */
    public ?\App\Models\User $user;

    /**
     * A static cache to store parent place IDs.
     * This avoids redundant database queries for the same parent code.
     *
     * @var array
     */
    private static array $parentCache = [];

    /**
     * A static cache to store type IDs.
     * This avoids redundant database queries for the same type name.
     *
     * @var array
     */
    private static array $typeCache = [];

    /**
     * The constructor captures the currently authenticated user.
     * This is necessary because when the job is processed in the queue,
     * there is no active user session.
     */
    public function __construct(?\App\Models\User $user = null)
    {
        $this->user = $user ?? Auth::user();
    }

    /**
     * This method is called for each row of the spreadsheet.
     * It processes the row data and creates or updates a Place model.
     *
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // parent
        // Normalize the location code. If the code has an odd length,
        // prepend a '0' to make it even. This is often done for hierarchical codes.
        $code = (string) $row['code'];
        if (strlen($code) % 2 !== 0) {
            $code = '0' . $code;
        }
        // Determine the parent code by removing the last two characters from the normalized code.
        $parentCode = substr($code, 0, strlen($code) - 2);

        // Look up the parent_id.
        $parentId = null;
        if (!empty($parentCode)) {
            // First, check the local cache to avoid a database query.
            if (array_key_exists($parentCode, self::$parentCache)) {
                $parentId = self::$parentCache[$parentCode];
            } else {
                // If not in cache, query the database for the parent's ID using its code.
                // Using value() is more efficient as it doesn't hydrate a full model.
                $parentId = Place::where('code', $parentCode)->value('id');
                // Store the result in the cache for subsequent rows.
                self::$parentCache[$parentCode] = $parentId;
            }
        }

        // Look up the type_id based on the 'type' column from the spreadsheet.
        $typeName = $row['type'];
        $typeId = null;
        if (!empty($typeName)) {
            // Check the local cache for the type ID.
            if (array_key_exists($typeName, self::$typeCache)) {
                $typeId = self::$typeCache[$typeName];
            } else {
                // If not in cache, query the database.
                // `whereTranslation` is used because the 'name' attribute is translatable (spatie/laravel-translatable).
                $typeId = Type::where(function ($query) use ($typeName) {
                    $query->whereRaw("name::json->>'en' = ?", [$typeName])
                        ->orWhereRaw("name::json->>'km' = ?", [$typeName]);
                })->value('id');
                // Cache the result.
                self::$typeCache[$typeName] = $typeId;
            }
        }

        // Create or update the Place model.
        // `updateOrCreate` finds a place by 'code' or creates a new one if it doesn't exist.
        return Place::updateOrCreate(
            ['code' => $code], // The attributes to find the model by.
            [
                // The attributes to update or create the model with.
                'parent_id' => $parentId,
                'type_id' => $typeId,
                'khmer' => $row['name_khmer'],
                'latin' => $row['name_latin'],
                'reference' => $row['reference'],
                'official_note' => $row['note_by_checker'],
            ]
        );
    }

    /**
     * Defines the size of the chunks the spreadsheet is divided into.
     * Processing in chunks helps to keep memory usage low.
     *
     * @return int
     */
    public function chunkSize(): int
    {
        return 1000;
    }

    /**
     * Registers event listeners for the import process.
     * This is used to send notifications to the user about the import status.
     *
     * @return array
     */
    public function registerEvents(): array
    {
        $user = $this->user;
        return [
                // This event is fired before the import process begins.
            BeforeImport::class => function (BeforeImport $event) use ($user) {
                // Get the total number of rows to be imported.
                $totalRows = collect($event->reader->getTotalRows())->first();
                // Only send a notification if a user is associated with the import.
                if ($this->user) {
                    // Send a database notification to the user indicating the import is in progress.
                    Notification::make()
                        ->title(__('place.action.notification.label.in_progress', ['label' => __('place.action.import')]))
                        ->body(__('place.action.notification.msg.in_progress', ['name' => strtolower(__('place.action.importing')), 'count' => $totalRows]))
                        ->success()
                        ->icon('heroicon-o-clock')
                        ->sendToDatabase($this->user);
                }
            },

                // This event is fired after the import process has successfully completed.
            AfterImport::class => function (AfterImport $event) use ($user) {
                // Clear the static caches to free up memory and prevent stale data on subsequent imports.
                self::$parentCache = [];
                self::$typeCache = [];
                $totalRows = collect($event->reader->getTotalRows())->first();
                if ($this->user) {
                    // Send a success notification to the user.
                    Notification::make()
                        ->title(__('place.action.notification.label.imported', ['label' => __('place.single')]))
                        ->body(__('place.action.notification.msg.imported', ['name' => __('place.single'), 'count' => $totalRows]))
                        ->success()
                        ->icon('heroicon-o-check-circle')
                        ->send()
                        ->sendToDatabase($this->user);
                }
            },
                // This event is fired if the import process fails.
            ImportFailed::class => function (ImportFailed $event) use ($user) {
                // Also clear the caches on failure.
                self::$parentCache = [];
                self::$typeCache = [];
                if ($this->user) {
                    // Send a failure notification to the user.
                    Notification::make()
                        ->title(__('place.action.notification.label.failed', ['label' => __('place.action.import')]))
                        ->body(__('place.action.notification.msg.failed', ['name' => __('place.single'), 'action' => __('place.action.importing')]))
                        ->danger()
                        ->icon('heroicon-o-x-circle')
                        ->send()
                        ->sendToDatabase($this->user);
                }
            },
        ];
    }
}