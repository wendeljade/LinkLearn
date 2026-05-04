<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    Illuminate\Support\Facades\DB::statement("DROP DATABASE IF EXISTS `linklearn_org_testschool`");
    echo "Database dropped.\n";
} catch (\Exception $e) {
    echo "Error dropping DB: " . $e->getMessage() . "\n";
}

$storagePath = storage_path('tenanttestschool');
if (file_exists($storagePath)) {
    // recursively delete directory in php
    $dir = new RecursiveDirectoryIterator($storagePath, FilesystemIterator::SKIP_DOTS);
    $files = new RecursiveIteratorIterator($dir, RecursiveIteratorIterator::CHILD_FIRST);
    foreach ($files as $file) {
        $file->isDir() ? rmdir($file->getRealPath()) : unlink($file->getRealPath());
    }
    rmdir($storagePath);
    echo "Storage deleted.\n";
}
