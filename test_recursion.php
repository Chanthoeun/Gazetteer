<?php

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Place;
use App\Models\Type;

// Determine Province Type ID dynamically if possible, or assume 1 or 2
$provinceType = Type::whereRaw("name::json->>'en' = 'Province'")->first();
$provinceTypeId = $provinceType ? $provinceType->id : 1;

// Find a province
$province = Place::where('type_id', $provinceTypeId)->first();

if (!$province) {
    echo "No province found.\n";
    exit;
}
echo "Testing with Province: " . ($province->latin ?? $province->khmer) . " (ID: " . $province->id . ")\n";

// Helper logic is now in the model
$allDescendantIds = $province->getAllDescendantIds();

echo "Found " . count($allDescendantIds) . " descendants.\n";

if (count($allDescendantIds) > 0) {
    echo "First 5 descendants IDs: " . implode(', ', array_slice($allDescendantIds, 0, 5)) . "\n";
    // Check level of first child
    $child = Place::find($allDescendantIds[0]);
    echo "First Child Type ID: " . $child->type_id . "\n";
}
