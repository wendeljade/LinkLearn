<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

echo "1. GET /login on localhost...\n";
$request = Illuminate\Http\Request::create('http://localhost:8000/login', 'GET');
$response = $kernel->handle($request);
echo "Status: " . $response->getStatusCode() . "\n\n";

echo "2. POST /login on localhost with credentials...\n";
$request2 = Illuminate\Http\Request::create('http://localhost:8000/login', 'POST', [
    'email' => 'sti@gmail.com',
    'password' => 'password123'
]);
$response2 = $kernel->handle($request2);
echo "Status: " . $response2->getStatusCode() . "\n";
echo "Redirect: " . $response2->headers->get('Location') . "\n";
