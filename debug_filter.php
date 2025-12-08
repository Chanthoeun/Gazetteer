<?php

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Place;
use Illuminate\Support\Arr;

// 1. Simulate selecting a Province (e.g., ID 1)
$data = [
    'province' => 1,
    'district' => null,
    'commune' => null,
    'village' => null,
];

echo "Simulating Filter with Province ID: 1\n";

// 2. Resolve placeId logic
$placeId = $data['village'] ?? $data['commune'] ?? $data['district'] ?? $data['province'] ?? null;
echo "Resolved Place ID: " . $placeId . "\n";

if (!$placeId) {
    echo "No Place ID resolved.\n";
    exit;
}

// 3. Logic from PlacesTable
$descendantIds = Place::find($placeId)?->getAllDescendantIds() ?? [];
echo "Descendant IDs count: " . count($descendantIds) . "\n";

// 4. Simulate modifying query
if (!empty($data['village'])) {
    echo "Filtering by Village only.\n";
} elseif (!empty($data['commune'])) {
    echo "Filtering by Commune (Descendants only).\n";
} else {
    echo "Filtering by Province/District (Descendants + Self).\n";
    $descendantIds[] = (int) $placeId;
}

echo "Final IDs count for WhereIn: " . count($descendantIds) . "\n";

// 5. Run actual query count
$count = Place::whereIn('id', $descendantIds)->count();
echo "Database Query Result Count: " . $count . "\n";

// 6. Check if Parent (ID 1) is in the list
$hasParent = in_array(1, $descendantIds);
echo "Contains Parent ID 1: " . ($hasParent ? "Yes" : "No") . "\n";
