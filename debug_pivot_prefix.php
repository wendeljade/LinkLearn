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
    $tenantDb = config('database.connections.tenant.database');
    // Using dynamic database prefix for pivot table
    $room->belongsToMany(\App\Models\User::class, "$tenantDb.room_user")->attach($user->id);
    echo "Attach with prefixed DB worked!\n";
} catch (\Exception $e) {
    echo "Error attach prefixed: " . $e->getMessage() . "\n";
}

print_r(DB::connection('central')->getQueryLog());
