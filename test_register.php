<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$user = \App\Models\User::first();
auth()->login($user);

echo "--- GET /login (Authenticated) ---\n";
$request = Illuminate\Http\Request::create('http://localhost:8000/login', 'GET');
$response = $kernel->handle($request);
echo "Status: " . $response->getStatusCode() . "\n";
echo "Redirect: " . $response->headers->get('Location') . "\n\n";

echo "--- GET /register (Authenticated) ---\n";
$request2 = Illuminate\Http\Request::create('http://localhost:8000/register', 'GET');
$response2 = $kernel->handle($request2);
echo "Status: " . $response2->getStatusCode() . "\n";
echo "Redirect: " . $response2->headers->get('Location') . "\n";
