<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Organization;
use Illuminate\Support\Facades\Route;

// Find super admin
$admin = User::where('role', 'super_admin')->first();
if (!$admin) {
    echo "No super admin found!\n";
    exit;
}

auth()->login($admin);

echo "Logged in as: " . $admin->email . " with role: " . $admin->role . "\n";

// Let's find an organization that is pending approval
$org = Organization::where('status', 'pending_approval')->first();
if (!$org) {
    echo "No pending org found. Looking for any org...\n";
    $org = Organization::first();
}

if (!$org) {
    echo "No orgs found at all!\n";
    exit;
}

echo "Testing org: " . $org->slug . "\n";

$request = \Illuminate\Http\Request::create('/admin/organizations/' . $org->slug . '/approve', 'POST');
$request->setLaravelSession(session());

try {
    $response = app()->handle($request);
    echo "Status code for approve: " . $response->getStatusCode() . "\n";
    if ($response->getStatusCode() == 403) {
        echo "Response content:\n" . substr($response->getContent(), 0, 500) . "\n";
    }
} catch (\Exception $e) {
    echo "Exception on approve: " . get_class($e) . " - " . $e->getMessage() . "\n";
}

$request2 = \Illuminate\Http\Request::create('/admin/proofs/test.png', 'GET');
$request2->setLaravelSession(session());

try {
    $response2 = app()->handle($request2);
    echo "Status code for viewProof: " . $response2->getStatusCode() . "\n";
    if ($response2->getStatusCode() == 403) {
        echo "Response content:\n" . substr($response2->getContent(), 0, 500) . "\n";
    }
} catch (\Exception $e) {
    echo "Exception on viewProof: " . get_class($e) . " - " . $e->getMessage() . "\n";
}
