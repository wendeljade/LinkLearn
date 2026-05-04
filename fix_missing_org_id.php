<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$org = App\Models\Organization::where('slug', 'bukidnon-state-university')->first();
if ($org) {
    App\Models\User::whereIn('email', ['2301104851@student.buksu.edu.ph', '2301105510@student.buksu.edu.ph'])->update(['organization_id' => $org->id]);
    echo 'Fixed users successfully.';
} else {
    echo 'Org not found.';
}
