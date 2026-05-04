<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// 1. Initial Login on localhost
$request = Illuminate\Http\Request::create('/login', 'GET');
$response = $kernel->handle($request);
$content = $response->getContent();
preg_match('/name="_token" value="([^"]+)"/', $content, $matches);
$csrf = $matches[1] ?? null;

$cookies = [];
foreach ($response->headers->getCookies() as $cookie) {
    $cookies[$cookie->getName()] = $cookie->getValue();
}

$loginRequest = Illuminate\Http\Request::create('/login', 'POST', [
    '_token' => $csrf,
    'email' => 'student1@sti.edu',
    'password' => 'password123'
]);
$loginRequest->cookies->add($cookies);
$response = $kernel->handle($loginRequest);

foreach ($response->headers->getCookies() as $cookie) {
    $cookies[$cookie->getName()] = $cookie->getValue();
}

// 2. Access dashboard
$dashRequest = Illuminate\Http\Request::create('/dashboard', 'GET');
$dashRequest->cookies->add($cookies);
$response = $kernel->handle($dashRequest);

// Extract magic token redirect
$magicUrl = $response->headers->get('Location');
if (!$magicUrl) die("No magic redirect");

// 3. Follow magic login on sti.localhost
$magicRequest = Illuminate\Http\Request::create($magicUrl, 'GET');
$response = $kernel->handle($magicRequest);
$tenantCookies = [];
foreach ($response->headers->getCookies() as $cookie) {
    $tenantCookies[$cookie->getName()] = $cookie->getValue();
}

// 4. Hit logout on sti.localhost
$logoutRequest = Illuminate\Http\Request::create('http://sti.localhost:8000/logout', 'POST');
// MUST include CSRF for logout! But we don't have it.
// To bypass CSRF for testing, we could simulate the controller.
// Actually, let's just GET /central-logout directly on localhost to see if it breaks the next login!

$centralLogoutRequest = Illuminate\Http\Request::create('http://localhost:8000/central-logout', 'GET');
$centralLogoutRequest->cookies->add($cookies);
$response = $kernel->handle($centralLogoutRequest);

foreach ($response->headers->getCookies() as $cookie) {
    $cookies[$cookie->getName()] = $cookie->getValue(); // THIS WILL BE THE NEW SESSION COOKIE!
}

// 5. Try to load login page again
$loginPageRequest = Illuminate\Http\Request::create('http://localhost:8000/login', 'GET');
$loginPageRequest->cookies->add($cookies);
$response = $kernel->handle($loginPageRequest);

$content = $response->getContent();
preg_match('/name="_token" value="([^"]+)"/', $content, $matches);
$newCsrf = $matches[1] ?? null;

foreach ($response->headers->getCookies() as $cookie) {
    $cookies[$cookie->getName()] = $cookie->getValue(); // update cookie again
}

// 6. Try to POST login again
$loginRequest2 = Illuminate\Http\Request::create('http://localhost:8000/login', 'POST', [
    '_token' => $newCsrf,
    'email' => 'student1@sti.edu',
    'password' => 'password123'
]);
$loginRequest2->cookies->add($cookies);
$response = $kernel->handle($loginRequest2);

echo "Final Login Status: " . $response->getStatusCode() . "\n";
echo "Final Login Redirect: " . $response->headers->get('Location') . "\n";
if ($response->getStatusCode() == 419) {
    echo "FAILED WITH 419!\n";
}

