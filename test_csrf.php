<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

echo "1. Getting Login Page (Central 127.0.0.1)...\n";
$request = Illuminate\Http\Request::create('http://127.0.0.1:8000/login', 'GET');
$response = $kernel->handle($request);
$content = $response->getContent();
preg_match('/name="_token" value="([^"]+)"/', $content, $matches);
$csrf = $matches[1] ?? null;

$cookies = [];
foreach ($response->headers->getCookies() as $cookie) {
    $cookies[$cookie->getName()] = $cookie->getValue();
}
echo "CSRF: $csrf\n";
echo "Cookies: " . implode(", ", array_keys($cookies)) . "\n\n";

$user = \App\Models\User::where('email', 'sti@gmail.com')->first();
auth()->login($user);

echo "2. POST Login (Central 127.0.0.1) SKIPPED, Forced Login...\n";
$loginRequest = Illuminate\Http\Request::create('http://127.0.0.1:8000/dashboard', 'GET');
$loginRequest->cookies->add($cookies);
$response = $kernel->handle($loginRequest);
foreach ($response->headers->getCookies() as $cookie) {
    $cookies[$cookie->getName()] = $cookie->getValue();
}
echo "Status: " . $response->getStatusCode() . "\n";
echo "Redirect: " . $response->headers->get('Location') . "\n\n";

echo "3. GET Dashboard (Central 127.0.0.1)...\n";
$dashRequest = Illuminate\Http\Request::create('http://127.0.0.1:8000/dashboard', 'GET');
$dashRequest->cookies->add($cookies);
$response = $kernel->handle($dashRequest);
$magicUrl = $response->headers->get('Location');
echo "Status: " . $response->getStatusCode() . "\n";
echo "Redirect: " . $magicUrl . "\n\n";

echo "4. GET Magic Login (Tenant)...\n";
$magicRequest = Illuminate\Http\Request::create($magicUrl, 'GET');
$response = $kernel->handle($magicRequest);
$tenantCookies = [];
foreach ($response->headers->getCookies() as $cookie) {
    $tenantCookies[$cookie->getName()] = $cookie->getValue();
}
echo "Status: " . $response->getStatusCode() . "\n";
echo "Redirect: " . $response->headers->get('Location') . "\n";
echo "Tenant Cookies: " . implode(", ", array_keys($tenantCookies)) . "\n\n";

echo "5. GET Dashboard (Tenant)...\n";
$tenantDashRequest = Illuminate\Http\Request::create('http://sti.localhost:8000/dashboard', 'GET');
$tenantDashRequest->cookies->add($tenantCookies);
$response = $kernel->handle($tenantDashRequest);
$content = $response->getContent();
preg_match('/name="_token" value="([^"]+)"/', $content, $matches);
$tenantCsrf = $matches[1] ?? null;
foreach ($response->headers->getCookies() as $cookie) {
    $tenantCookies[$cookie->getName()] = $cookie->getValue();
}
echo "Status: " . $response->getStatusCode() . "\n";
echo "Tenant CSRF: $tenantCsrf\n";
echo "Tenant Cookies: " . implode(", ", array_keys($tenantCookies)) . "\n\n";

echo "6. POST Logout (Tenant)...\n";
$logoutRequest = Illuminate\Http\Request::create('http://sti.localhost:8000/logout', 'POST', [
    '_token' => $tenantCsrf
]);
$logoutRequest->cookies->add($tenantCookies);
$response = $kernel->handle($logoutRequest);
echo "Status: " . $response->getStatusCode() . "\n";
if ($response->getStatusCode() == 419) {
    echo "419 PAGE EXPIRED ON TENANT LOGOUT!\n";
} else {
    echo "Redirect: " . $response->headers->get('Location') . "\n\n";
    
    echo "7. GET Central Logout (Central)...\n";
    $centralLogoutUrl = $response->headers->get('Location');
    $centralLogoutRequest = Illuminate\Http\Request::create($centralLogoutUrl, 'GET');
    $centralLogoutRequest->cookies->add($cookies); // MUST pass central cookies!
    $response = $kernel->handle($centralLogoutRequest);
    echo "Status: " . $response->getStatusCode() . "\n";
    echo "Redirect: " . $response->headers->get('Location') . "\n";
    foreach ($response->headers->getCookies() as $cookie) {
        $cookies[$cookie->getName()] = $cookie->getValue();
    }
    
    echo "8. GET Login Again (Central 127.0.0.1)...\n";
    $login2 = Illuminate\Http\Request::create('http://127.0.0.1:8000/login', 'GET');
    $login2->cookies->add($cookies);
    $response = $kernel->handle($login2);
    $content = $response->getContent();
    preg_match('/name="_token" value="([^"]+)"/', $content, $matches);
    $csrf2 = $matches[1] ?? null;
    echo "Status: " . $response->getStatusCode() . "\n";
    echo "CSRF2: $csrf2\n";
    
    echo "9. POST Login Again (Central 127.0.0.1)...\n";
    $loginRequest2 = Illuminate\Http\Request::create('http://127.0.0.1:8000/login', 'POST', [
        '_token' => $csrf2,
        'email' => 'sti@gmail.com',
        'password' => 'password123'
    ]);
    $loginRequest2->cookies->add($cookies);
    $response = $kernel->handle($loginRequest2);
    echo "Status: " . $response->getStatusCode() . "\n";
    if ($response->getStatusCode() == 419) {
        echo "419 PAGE EXPIRED ON SECOND LOGIN!\n";
    } else {
        echo "Redirect: " . $response->headers->get('Location') . "\n";
    }
}
