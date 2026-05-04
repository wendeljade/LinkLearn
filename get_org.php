<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$org = App\Models\Organization::first();
if ($org) {
    echo "Slug: " . $org->slug . "\n";
    echo "Status: " . $org->status . "\n";
    echo "Domain: " . ($org->domains->first() ? $org->domains->first()->domain : 'none') . "\n";
} else {
    echo "No orgs found.\n";
}
