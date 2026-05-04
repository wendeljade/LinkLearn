<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$t = App\Models\Organization::where('slug', 'bukidnon-state-university')->first();
tenancy()->initialize($t);

$room = App\Models\Room::first();
$user = App\Models\User::find(7);

DB::connection('central')->enableQueryLog();

try {
    // Override relation table on the fly
    $tenantDb = config('database.connections.tenant.database');
    $room->students()->newPivotStatementForId($user->id)->insert([
        'room_id' => $room->id,
        'user_id' => $user->id,
    ]);
    echo "Manual insert worked!\n";
} catch (\Exception $e) {
    echo "Error manual: " . $e->getMessage() . "\n";
}

try {
    $room->students()->attach($user->id);
    echo "Attach worked!\n";
} catch (\Exception $e) {
    echo "Error attach: " . $e->getMessage() . "\n";
}

print_r(DB::connection('central')->getQueryLog());
