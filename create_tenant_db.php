<?php
require 'vendor/autoload.php';
require 'bootstrap/app.php';
$app = app();
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

Illuminate\Support\Facades\DB::statement("CREATE DATABASE `linklearn_org_bukidnon-state-university`");
echo "Created tenant DB.\n";

Illuminate\Support\Facades\Artisan::call('tenants:migrate');
echo Illuminate\Support\Facades\Artisan::output();
