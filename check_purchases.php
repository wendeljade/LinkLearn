<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$student = App\Models\User::where('role', 'student')->first();
if (!$student) { echo "No student found.\n"; exit; }
echo "Student: {$student->name} (ID: {$student->id})\n";

$all = App\Models\FilePurchase::where('user_id', $student->id)->get();
echo "All purchases: " . $all->count() . "\n";
foreach ($all as $p) {
    echo "  - ID:{$p->id} file_id:{$p->file_id} status:{$p->status}\n";
}

// Also check if there are any purchases at all in the DB
$totalPurchases = App\Models\FilePurchase::count();
echo "\nTotal FilePurchase records in DB: {$totalPurchases}\n";
$statuses = App\Models\FilePurchase::select('status')->distinct()->pluck('status');
echo "Distinct statuses: " . $statuses->implode(', ') . "\n";
