<?php

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Place;
use App\Models\Type;

// Determine Province Type ID
$provinceType = Type::whereRaw("name::json->>'en' = 'Province'")->first();
$provinceTypeId = $provinceType ? $provinceType->id : 1;

// Find a province (Banteay Meanchey usually ID 1)
$province = Place::where('type_id', $provinceTypeId)->first();

if (!$province) {
    echo "No province found.\n";
    exit;
}

echo "Testing with Province: " . ($province->latin ?? $province->khmer) . " (ID: " . $province->id . ")\n";

// Get all descendants
$descendantIds = $province->getAllDescendantIds();
echo "Total Descendants: " . count($descendantIds) . "\n";

if (count($descendantIds) === 0) {
    exit;
}

// Group by Type
$places = Place::whereIn('id', $descendantIds)->get()->groupBy('type_id');

foreach ($places as $typeId => $group) {
    $type = Type::find($typeId);
    $typeName = $type ? ($type->name['en'] ?? json_encode($type->name)) : "Unknown Type $typeId";
    echo "Type: $typeName (ID: $typeId) - Count: " . $group->count() . "\n";
}
