<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Cache;
use App\Models\User;

// 1. Simulate storing a magic token (like DashboardController does)
$token = 'test_debug_token_12345';
$user = User::where('role', 'org_admin')->first();
echo "User: " . ($user ? $user->email . " (id={$user->id})" : "NOT FOUND") . "\n";

Cache::put('magic_login_' . $token, $user->id, now()->addSeconds(120));
echo "Token stored. Cache driver: " . config('cache.default') . "\n";
echo "Cache connection: " . config('cache.stores.database.connection') . "\n";

// 2. Simulate what magicLogin does — retrieve it
$retrieved = Cache::pull('magic_login_' . $token);
echo "Retrieved userId: " . ($retrieved ?? "NULL - CACHE MISS!") . "\n";

// 3. Check if cache table exists in central DB
try {
    $count = \Illuminate\Support\Facades\DB::connection('central')->table('cache')->count();
    echo "Cache table rows in central DB: $count\n";
} catch (\Exception $e) {
    echo "Cache table error: " . $e->getMessage() . "\n";
}

// 4. Simulate tenant context switching (what happens on sti.localhost)
echo "\n--- Simulating tenant context ---\n";
$org = App\Models\Organization::where('slug', 'sti')->first();
if ($org) {
    tenancy()->initialize($org);
    echo "Tenancy initialized for: " . $org->slug . "\n";
    echo "Default DB connection now: " . \Illuminate\Support\Facades\DB::getDefaultConnection() . "\n";

    // Store token again
    Cache::put('magic_login_tenant_test', $user->id, now()->addSeconds(120));
    
    // Now retrieve it (same as magicLogin does while tenant context is active)
    $retrievedInTenant = Cache::pull('magic_login_tenant_test');
    echo "Retrieved in tenant context: " . ($retrievedInTenant ?? "NULL - CACHE MISS IN TENANT CONTEXT!") . "\n";

    tenancy()->end();
} else {
    echo "STI org not found!\n";
}
