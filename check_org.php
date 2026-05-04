<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$org = App\Models\Organization::where('slug', 'bukidnon-state-university')->first();
echo "Org ID: " . ($org ? $org->id : 'NOT FOUND') . "\n";
echo "Teacher Org ID: " . App\Models\User::find(6)->organization_id . "\n";
echo "Student Org ID: " . App\Models\User::find(7)->organization_id . "\n";
