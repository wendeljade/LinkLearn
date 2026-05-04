<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$org = App\Models\Organization::where('id', 1)->first();
echo 'Found by id=1: ' . ($org ? $org->slug : 'NULL') . "\n";
