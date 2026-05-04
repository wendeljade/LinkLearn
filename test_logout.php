<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$user = \App\Models\User::where('email', 'sti@gmail.com')->first();
$cookies = [];

echo "1. Simulate login on localhost...\n";
$request = Illuminate\Http\Request::create('http://localhost:8000/login', 'POST', [
    'email' => 'sti@gmail.com',
    'password' => 'password123'
]);
// Skip CSRF by injecting a fake one or just logging in manually to the session.
// Wait, we bypassed CSRF for /login!
$response = $kernel->handle($request);
foreach ($response->headers->getCookies() as $cookie) {
    $cookies[$cookie->getName()] = $cookie->getValue();
}
echo "Redirect: " . $response->headers->get('Location') . "\n\n";

echo "2. Follow to Dashboard...\n";
$request = Illuminate\Http\Request::create('http://localhost:8000/dashboard', 'GET');
$request->cookies->add($cookies);
$response = $kernel->handle($request);
$magicLink = $response->headers->get('Location');
echo "Magic Link: $magicLink\n\n";

echo "3. Magic Login to Tenant...\n";
$request = Illuminate\Http\Request::create($magicLink, 'GET');
$response = $kernel->handle($request);
$tenantCookies = [];
foreach ($response->headers->getCookies() as $cookie) {
    $tenantCookies[$cookie->getName()] = $cookie->getValue();
}
echo "Tenant Redirect: " . $response->headers->get('Location') . "\n\n";

echo "4. Tenant Logout...\n";
$request = Illuminate\Http\Request::create('http://sti.localhost:8000/logout', 'POST');
$request->cookies->add($tenantCookies);
$response = $kernel->handle($request);
echo "Tenant Logout Redirect: " . $response->headers->get('Location') . "\n\n";

echo "5. Central Logout...\n";
$centralLogoutLink = $response->headers->get('Location');
$request = Illuminate\Http\Request::create($centralLogoutLink, 'GET');
// Provide the CENTRAL cookies!
$request->cookies->add($cookies);
$response = $kernel->handle($request);
foreach ($response->headers->getCookies() as $cookie) {
    $cookies[$cookie->getName()] = $cookie->getValue();
}
echo "Central Logout Redirect: " . $response->headers->get('Location') . "\n\n";

echo "6. GET /login again on localhost...\n";
$request = Illuminate\Http\Request::create('http://localhost:8000/login', 'GET');
$request->cookies->add($cookies);
$response = $kernel->handle($request);
echo "Status: " . $response->getStatusCode() . "\n";
echo "Redirect: " . $response->headers->get('Location') . "\n\n";

echo "7. GET /register again on localhost...\n";
$request = Illuminate\Http\Request::create('http://localhost:8000/register', 'GET');
$request->cookies->add($cookies);
$response = $kernel->handle($request);
echo "Status: " . $response->getStatusCode() . "\n";
echo "Redirect: " . $response->headers->get('Location') . "\n";
