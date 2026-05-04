<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

echo "1. POST /login with WRONG credentials...\n";
$request = Illuminate\Http\Request::create('http://localhost:8000/login', 'POST', [
    'email' => 'admin@example.com',
    'password' => 'WRONGPASSWORD'
]);
$response = $kernel->handle($request);
echo "Status: " . $response->getStatusCode() . "\n";
echo "Redirect: " . $response->headers->get('Location') . "\n";

$cookies = [];
foreach ($response->headers->getCookies() as $cookie) {
    $cookies[$cookie->getName()] = $cookie->getValue();
}

$redirectUrl = $response->headers->get('Location');
echo "\n2. Following redirect to $redirectUrl...\n";
$request2 = Illuminate\Http\Request::create($redirectUrl, 'GET');
$request2->cookies->add($cookies);

$response2 = $kernel->handle($request2);
echo "Status: " . $response2->getStatusCode() . "\n";
$content = $response2->getContent();
if (strpos($content, 'credentials do not match') !== false) {
    echo "ERROR MESSAGE FOUND IN HTML!\n";
} else {
    echo "ERROR MESSAGE NOT FOUND!\n";
}
