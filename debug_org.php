<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Organization;
use Illuminate\Support\Facades\DB;

// Cleanup
DB::connection('central')->statement('DELETE FROM domains WHERE tenant_id = ?', ['testschool2']);
DB::connection('central')->statement('DELETE FROM organizations WHERE slug = ?', ['testschool2']);
DB::connection('central')->statement('DROP DATABASE IF EXISTS `linklearn_org_testschool2`');

echo "PK name: " . (new Organization)->getKeyName() . PHP_EOL;
echo "Incrementing: " . ((new Organization)->getIncrementing() ? 'true' : 'false') . PHP_EOL;
echo "KeyType: " . (new Organization)->getKeyType() . PHP_EOL;
echo "TenantKey: " . (new Organization)->getTenantKeyName() . PHP_EOL;

$org = Organization::create([
    'user_id' => 1,
    'name'    => 'Debug Org',
    'slug'    => 'testschool2',
    'status'  => 'pending_approval',
    'subscription_paid_at' => now(),
]);

echo "After create - attributes dump:" . PHP_EOL;
var_dump($org->getAttributes());
echo "slug via ->slug: " . $org->slug . PHP_EOL;
echo "key via getKey(): " . $org->getKey() . PHP_EOL;
