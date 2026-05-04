<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

try {
    // Manually connect to the tenant database
    config(['database.connections.tenant.database' => 'linklearn_org_bukidnon-state-university']);
    DB::purge('tenant');
    DB::reconnect('tenant');
    Schema::connection('tenant')->disableForeignKeyConstraints();

    $conn = Schema::connection('tenant');

    if ($conn->hasTable('rooms')) {
        echo "Dropping FK on rooms...\n";
        try {
            $conn->table('rooms', function($table) {
                $table->dropForeign('rooms_tutor_id_foreign');
            });
            echo "Success.\n";
        } catch (\Exception $e) { echo $e->getMessage() . "\n"; }
    }

    if ($conn->hasTable('room_user')) {
        echo "Dropping FK on room_user...\n";
        try {
            $conn->table('room_user', function($table) {
                $table->dropForeign('room_user_user_id_foreign');
            });
            echo "Success.\n";
        } catch (\Exception $e) { echo $e->getMessage() . "\n"; }
    }

    if ($conn->hasTable('submissions')) {
        echo "Dropping FK on submissions...\n";
        try {
            $conn->table('submissions', function($table) {
                $table->dropForeign('submissions_student_id_foreign');
            });
            echo "Success.\n";
        } catch (\Exception $e) { echo $e->getMessage() . "\n"; }
    }

    if ($conn->hasTable('file_purchases')) {
        echo "Dropping FK on file_purchases...\n";
        try {
            $conn->table('file_purchases', function($table) {
                $table->dropForeign('file_purchases_user_id_foreign');
            });
            echo "Success.\n";
        } catch (\Exception $e) { echo $e->getMessage() . "\n"; }
    }

    // Drop unnecessary users tables
    echo "Dropping users table...\n";
    $conn->dropIfExists('users');
    $conn->dropIfExists('password_reset_tokens');

    Schema::connection('tenant')->enableForeignKeyConstraints();
    echo "Done resolving existing database constraints.\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
