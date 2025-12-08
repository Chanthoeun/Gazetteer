<?php

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Place;

// Check Province 1
$province = Place::find(1);
echo "Place 1: " . ($province->latin ?? 'Null') . " Code: " . $province->code . "\n";

// Check if any place has parent_id = 1
$children = Place::where('parent_id', 1)->get();
echo "Direct children count: " . $children->count() . "\n";

if ($children->count() > 0) {
    foreach ($children->take(3) as $child) {
        echo "Child: " . $child->latin . " Code: " . $child->code . " ParentID: " . $child->parent_id . "\n";
    }
} else {
    // Check if there are ANY places with parent_id != null
    $anyChild = Place::whereNotNull('parent_id')->first();
    if ($anyChild) {
        echo "Found a child: " . $anyChild->latin . " ParentID: " . $anyChild->parent_id . "\n";
        $parent = Place::find($anyChild->parent_id);
        echo "Its parent: " . ($parent ? $parent->latin . " (ID: " . $parent->id . ")" : "Not Found") . "\n";
    } else {
        echo "No places with parent_id found in the entire table.\n";
    }
}
