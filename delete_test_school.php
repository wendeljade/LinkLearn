<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$org = App\Models\Organization::where('name', 'Test School')->orWhere('slug', 'testschool')->first();
if ($org) {
    try {
        $org->delete();
        echo "Successfully deleted Test School.\n";
    } catch (\Exception $e) {
        echo "Error deleting: " . $e->getMessage() . "\n";
    }
} else {
    echo "Test School not found.\n";
}
