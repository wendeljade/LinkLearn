<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$u = App\Models\User::where('name', 'like', '%JOSEMARI%')->first();
echo "Student ID: " . ($u ? $u->id : 'NOT FOUND') . "\n";
echo "Student Org ID: " . ($u ? $u->organization_id : 'NOT FOUND') . "\n";
