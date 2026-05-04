<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Organization;
use Illuminate\Support\Facades\DB;

echo "=== Tenant DB Provisioning Test ===" . PHP_EOL;

// 1. Verify central DB connection
try {
    $tables = DB::connection('central')->select('SHOW TABLES');
    echo "✅ Central DB connected. Tables: " . count($tables) . PHP_EOL;
} catch (\Exception $e) {
    echo "❌ Central DB failed: " . $e->getMessage() . PHP_EOL;
    exit(1);
}

// 2. Check if test org already exists (clean up first)
$existing = Organization::where('slug', 'testschool')->first();
if ($existing) {
    echo "⚠  Cleaning up existing 'testschool' org..." . PHP_EOL;
    $existing->domains()->delete();
    // deleteQuietly() skips model events so TenantDeleted won't try to DROP a DB that may not exist
    $existing->deleteQuietly();
    try {
        DB::connection('central')->statement('DROP DATABASE IF EXISTS `linklearn_org_testschool`');
    } catch (\Exception $e) {}
}

// 3. Create the organization (does NOT provision DB — approval-gated)
$org = Organization::create([
    'user_id'               => 1,
    'name'                  => 'Test School',
    'slug'                  => 'testschool',
    'status'                => 'pending_approval',
    'subscription_paid_at'  => now(),
]);
echo "✅ Organization created: ID={$org->id}, Slug={$org->slug}" . PHP_EOL;

// 4. Verify no tenant DB exists yet
$dbCheck = DB::connection('central')->select(
    "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?",
    ['linklearn_org_testschool']
);
echo ($dbCheck ? "⚠  Tenant DB exists before approval (unexpected)" : "✅ Tenant DB does NOT exist yet (correct — approval-gated)") . PHP_EOL;

// 5. Simulate admin approval — create domain + fire TenantCreated
$org->domains()->create(['domain' => $org->slug]);
echo "✅ Domain record created: {$org->slug}" . PHP_EOL;

try {
    event(new \Stancl\Tenancy\Events\TenantCreated($org));
    echo "✅ TenantCreated event fired — DB creation + migration triggered" . PHP_EOL;
} catch (\Exception $e) {
    echo "❌ TenantCreated event failed: " . $e->getMessage() . PHP_EOL;
    exit(1);
}

// 6. Verify tenant DB was created
$dbCheck = DB::connection('central')->select(
    "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?",
    ['linklearn_org_testschool']
);
echo ($dbCheck ? "✅ Tenant DB 'linklearn_org_testschool' created successfully!" : "❌ Tenant DB was NOT created") . PHP_EOL;

// 7. Verify tenant DB has the expected tables (direct query, no tenancy init needed)
try {
    $tenantTables = DB::connection('central')->select("SHOW TABLES FROM `linklearn_org_testschool`");
    $tableNames = array_map(fn($t) => array_values((array)$t)[0], $tenantTables);
    sort($tableNames);
    echo "✅ Tenant DB tables (" . count($tableNames) . "): " . implode(', ', $tableNames) . PHP_EOL;
} catch (\Exception $e) {
    echo "❌ Could not read tenant DB tables: " . $e->getMessage() . PHP_EOL;
}

echo PHP_EOL . "=== Test Complete ===" . PHP_EOL;
