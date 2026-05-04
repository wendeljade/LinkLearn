<?php
require 'vendor/autoload.php';
require 'bootstrap/app.php';
$app = app();
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Save in central cache
Illuminate\Support\Facades\Cache::put('magic_test', 'works', 60);

// Initialize tenancy
$tenant = App\Models\Organization::where('slug', 'bukidnon-state-university')->first();
tenancy()->initialize($tenant);

// Read from cache
$val = Illuminate\Support\Facades\Cache::pull('magic_test');
echo "Tenant Cache Test: " . ($val ?? 'FAILED - NULL') . "\n";
