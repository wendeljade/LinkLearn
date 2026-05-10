<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$teacher = App\Models\User::find(6);
$requests = $teacher->allPendingJoinRequests();
echo "\nallPendingJoinRequests() count: " . $requests->count() . "\n";
foreach($requests as $r) {
    echo "  - {$r->student_name} wants to join {$r->room_name} in {$r->org_name}\n";
}
