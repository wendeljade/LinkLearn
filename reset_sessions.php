<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\DB;

// Reset password for STI and all org_admins
$users = User::where('role', 'org_admin')->get();
foreach ($users as $u) {
    $u->password = bcrypt('password123');
    $u->save();
    echo "Reset password for: {$u->email}\n";
}

// Wipe ALL sessions so no stale loops remain
DB::connection('central')->table('sessions')->truncate();
echo "All sessions cleared.\n";

// Wipe cache magic tokens
DB::connection('central')->table('cache')->where('key', 'like', '%magic_login_%')->delete();
echo "Magic login tokens cleared.\n";
