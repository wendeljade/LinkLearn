<?php
require 'vendor/autoload.php';
require 'bootstrap/app.php';
$app = app();
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Organization;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Stancl\Tenancy\Database\Models\Domain;

echo "--- Starting Database Sync ---\n";

// 1. Ensure Super Admin exists
$admin = User::where('email', 'admin@example.com')->first();
if ($admin) {
    $admin->update(['role' => 'super_admin']);
    echo "Fixed: admin@example.com is now super_admin.\n";
} else {
    User::create([
        'name' => 'Super Admin',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
        'role' => 'super_admin'
    ]);
    echo "Created: super_admin (admin@example.com / password).\n";
}

// 2. Sync Organizations and Domains
$orgs = Organization::all();
foreach ($orgs as $org) {
    echo "Processing Org: {$org->slug}...\n";
    
    // Ensure owner has correct role and organization_id
    if ($org->owner) {
        $org->owner->update([
            'role' => 'org_admin',
            'organization_id' => $org->id
        ]);
        echo " - Updated owner: {$org->owner->email} to org_admin.\n";
    }

    // Ensure domain record exists (Required for subdomain identification)
    $domainExists = DB::table('domains')->where('domain', $org->slug)->exists();
    if (!$domainExists) {
        DB::table('domains')->insert([
            'domain' => $org->slug,
            'tenant_id' => $org->slug,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        echo " - Created domain record for '{$org->slug}'.\n";
    }
}

echo "--- Sync Complete ---\n";
