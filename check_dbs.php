<?php
require 'vendor/autoload.php';
require 'bootstrap/app.php';
$app = app();
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$dbs = Illuminate\Support\Facades\DB::select("SHOW DATABASES LIKE 'linklearn_org_%'");
print_r($dbs);
