<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Organization;
use Illuminate\Support\Facades\DB;

// Fetch all organizations
$orgs = Organization::all();

foreach ($orgs as $org) {
    echo "Deleting organization: {$org->slug}...\n";
    
    // Drop the tenant database manually since DeleteDatabase job requires queue usually
    $dbName = 'linklearn_org_' . $org->slug;
    try {
        DB::connection('central')->statement("DROP DATABASE IF EXISTS `{$dbName}`");
        echo "Dropped DB: {$dbName}\n";
    } catch (\Exception $e) {
        echo "Failed to drop DB: " . $e->getMessage() . "\n";
    }

    // Delete domains manually just to be safe
    $org->domains()->delete();

    // Delete the organization
    $org->delete();
}

// Ensure the superadmin is unlinked from any organization and role is clean
use App\Models\User;
$superAdmin = User::where('email', 'admin@example.com')->first();
if ($superAdmin) {
    $superAdmin->organization_id = null;
    $superAdmin->role = 'super_admin';
    $superAdmin->save();
    echo "Superadmin reset.\n";
}

// Reset any other users that were org admins
User::where('role', 'org_admin')->update(['organization_id' => null, 'role' => 'student']);

echo "Cleanup complete!\n";
