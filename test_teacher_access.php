<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Find the teacher
$teacher = App\Models\User::where('role', 'teacher')->orWhere('role', 'tutor')->first();
if (!$teacher) {
    echo "ERROR: No teacher found in central DB!\n";
    exit(1);
}
echo "=== Teacher Account ===\n";
echo "ID:              {$teacher->id}\n";
echo "Name:            {$teacher->name}\n";
echo "Email:           {$teacher->email}\n";
echo "Role:            {$teacher->role}\n";
echo "organization_id: " . ($teacher->organization_id ?? 'NULL') . "\n\n";

// Get all active orgs
$prefix = config('tenancy.database.prefix', 'linklearn_org_');
$orgs   = App\Models\Organization::where('status', 'active')->get();

echo "=== Active Organizations (" . $orgs->count() . ") ===\n";
foreach ($orgs as $org) {
    $tenantDb = $prefix . $org->slug;
    $exists = Illuminate\Support\Facades\DB::select(
        "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?",
        [$tenantDb]
    );

    echo "\nOrg: {$org->name} (slug={$org->slug}, id={$org->id})\n";
    echo "  DB: {$tenantDb} -> " . (empty($exists) ? "DOES NOT EXIST" : "EXISTS") . "\n";

    if (empty($exists)) continue;

    // Check rooms table
    try {
        $rooms = Illuminate\Support\Facades\DB::select("SELECT id, subject_name, tutor_id FROM `{$tenantDb}`.`rooms`");
        echo "  Rooms (" . count($rooms) . "):\n";
        foreach ($rooms as $r) {
            $isTutor = $r->tutor_id == $teacher->id ? " <-- TEACHER IS TUTOR" : "";
            echo "    - Room #{$r->id}: '{$r->subject_name}' (tutor_id={$r->tutor_id}){$isTutor}\n";
        }

        // Check room_user for students that might be the teacher enrolled as student
        $roomUser = Illuminate\Support\Facades\DB::select(
            "SELECT room_id FROM `{$tenantDb}`.`room_user` WHERE user_id = ?",
            [$teacher->id]
        );
        echo "  Teacher in room_user: " . count($roomUser) . " entries\n";
        foreach ($roomUser as $ru) {
            echo "    - room_id={$ru->room_id}\n";
        }
    } catch (Exception $e) {
        echo "  ERROR querying DB: " . $e->getMessage() . "\n";
    }
}

// Now simulate allTaughtRooms
echo "\n=== Simulating allTaughtRooms() ===\n";
$result = $teacher->allTaughtRooms();
echo "Found " . $result->count() . " rooms total\n";
foreach ($result as $r) {
    echo "  - Room #{$r->id}: '{$r->subject_name}' in org={$r->org_slug}\n";
}

// Check DashboardController logic
echo "\n=== isTeacher() check ===\n";
echo "isTeacher: " . ($teacher->isTeacher() ? 'TRUE' : 'FALSE') . "\n";
echo "isStudent: " . ($teacher->isStudent() ? 'TRUE' : 'FALSE') . "\n";

// Simulate DashboardController condition
$skipRedirect = !$teacher->isStudent() && !$teacher->isTeacher() && ($teacher->isAdmin() || $teacher->role === 'org_admin' || $teacher->organization_id);
echo "Would be redirected to subdomain (skipped=false means YES): " . ($skipRedirect ? 'YES - BLOCKED' : 'NO - stays on central domain') . "\n";
