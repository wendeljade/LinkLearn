<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::first();
$token = \Illuminate\Support\Str::random(40);
\Illuminate\Support\Facades\Cache::put('magic_login_' . $token, $user->id, now()->addSeconds(120));

// Simulate tenant environment
$org = App\Models\Organization::first();
tenancy()->initialize($org);

$pulledId = \Illuminate\Support\Facades\Cache::pull('magic_login_' . $token);
echo "User ID: " . $user->id . "\n";
echo "Pulled ID: " . $pulledId . "\n";
