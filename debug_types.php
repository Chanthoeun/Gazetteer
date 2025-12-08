<?php

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Type;

$types = Type::all();
foreach ($types as $type) {
    // Handling translatable name, might be array or string if cast was removed/added
    $name = $type->name;
    // If it's an array/json, json_encode it for display
    if (!is_string($name)) {
        $name = json_encode($name);
    }
    echo "ID: " . $type->id . " Name: " . $name . "\n";
}
