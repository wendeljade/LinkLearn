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

DB::connection('tenant')->enableQueryLog();

try {
    $user->joinedRooms()->attach($room->id);
    echo "Inverse attach worked!\n";
} catch (\Exception $e) {
    echo "Error attach: " . $e->getMessage() . "\n";
}

print_r(DB::connection('tenant')->getQueryLog());
