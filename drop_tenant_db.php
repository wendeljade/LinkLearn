<?php
require 'vendor/autoload.php';
require 'bootstrap/app.php';
$app = app();
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

Illuminate\Support\Facades\DB::statement("DROP DATABASE IF EXISTS `linklearn_org_bukidnon-state-university`");
echo "Dropped tenant DB.\n";
