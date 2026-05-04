<?php
require 'vendor/autoload.php';
require 'bootstrap/app.php';
$app = app();
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Organization;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

echo "--- EMERGENCY REPAIR START ---\n";

try {
    // 1. Reset Super Admin
    $email = 'admin@example.com';
    $password = 'password';
    $user = User::where('email', $email)->first();
    
    if (!$user) {
        $user = User::create([
            'name' => 'Super Admin',
            'email' => $email,
            'password' => Hash::make($password),
            'role' => 'super_admin'
        ]);
        echo "CREATED: Super Admin ($email / $password)\n";
    } else {
        $user->update([
            'password' => Hash::make($password),
            'role' => 'super_admin'
        ]);
        echo "UPDATED: Super Admin password reset to '$password'\n";
    }

    // 2. Sync Domains (IMPORTANTE para sa Subdomains)
    $orgs = Organization::all();
    foreach ($orgs as $org) {
        $domain = $org->slug;
        $exists = DB::table('domains')->where('domain', $domain)->exists();
        if (!$exists) {
            DB::table('domains')->insert([
                'domain' => $domain,
                'tenant_id' => $org->slug,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            echo "LINKED: Organization '{$org->name}' to subdomain '{$domain}.localhost'\n";
        }
    }

    echo "--- REPAIR COMPLETE ---\n";
    echo "PAG-SULOD DIRE: http://localhost:8000/login\n";
    echo "USER: $email\n";
    echo "PASS: $password\n";

} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Siguroha nga nag-dagan imong MySQL (XAMPP/Laragon).\n";
}
