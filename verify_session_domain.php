<?php
// Test script to see if Laravel can set a session domain of .localhost
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "SESSION_DOMAIN config: " . config('session.domain') . "\n";
