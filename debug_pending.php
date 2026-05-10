<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$prefix = config('tenancy.database.prefix', 'linklearn_org_');
$centralDb = DB::connection('central')->getDatabaseName();
echo "Central DB: $centralDb\n";
echo "Prefix: $prefix\n";

$org = App\Models\Organization::where('slug', 'bukidnon-state-university')->first();
$tenantDb = $prefix . $org->slug;
echo "Tenant DB: $tenantDb\n";

// Test 1 — exact query from allPendingJoinRequests()
echo "\n--- Test 1: Exact query from method, teacher_id=6 ---\n";
$rows = DB::connection('central')->select(
    "SELECT ru.room_id, ru.user_id, ru.status,
            r.subject_name AS room_name,
            u.name AS student_name, u.email AS student_email, u.profile_picture AS student_profile,
            ? AS org_slug, ? AS org_name, ? AS org_id
     FROM `{$tenantDb}`.`room_user` ru
     INNER JOIN `{$tenantDb}`.`rooms` r ON r.id = ru.room_id
     LEFT JOIN `{$centralDb}`.`users` u ON u.id = ru.user_id
     WHERE r.tutor_id = ? AND ru.status = 'pending'",
    [$org->slug, $org->name, $org->id, 6]
);
echo "Rows: " . count($rows) . "\n";
foreach ($rows as $r) {
    echo "  student: {$r->student_name}, room: {$r->room_name}\n";
}

// Test 2 — check what the INFORMATION_SCHEMA check returns
echo "\n--- Test 2: Schema check ---\n";
$exists = DB::connection('central')->select(
    "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?",
    [$tenantDb]
);
echo "Schema found: " . (empty($exists) ? 'NO' : 'YES') . "\n";

// Test 3 — org status
echo "\n--- Test 3: Org status ---\n";
echo "Org status: {$org->status}\n";

// Test 4 — check all active orgs
echo "\n--- Test 4: All active orgs ---\n";
$activeOrgs = App\Models\Organization::where('status', 'active')->get(['id', 'name', 'slug', 'status']);
foreach ($activeOrgs as $o) {
    echo "  {$o->name} ({$o->slug}) [{$o->status}]\n";
}
