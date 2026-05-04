<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$t = App\Models\Organization::where('slug', 'bukidnon-state-university')->first();
tenancy()->initialize($t);
$room = App\Models\Room::first();
echo "Cover photo path: " . $room->cover_photo . "\n";
echo "Asset URL: " . tenant_asset($room->cover_photo) . "\n";
