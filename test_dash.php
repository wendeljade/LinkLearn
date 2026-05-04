<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

$user = App\Models\User::find(7);
Auth::login($user);

$req = Request::create('/dashboard', 'GET');
$req->setSession(app('session')->driver());

$res = app()->handle($req);

echo "Status Code: " . $res->getStatusCode() . "\n";
echo "Location: " . $res->headers->get('Location') . "\n";
