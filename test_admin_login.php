<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

echo "1. POST /login with admin credentials...\n";
$request = Illuminate\Http\Request::create('http://localhost:8000/login', 'POST', [
    'email' => 'admin@example.com',
    'password' => 'password123'
]);
$response = $kernel->handle($request);
echo "Status: " . $response->getStatusCode() . "\n";
echo "Redirect: " . $response->headers->get('Location') . "\n";

if ($response->getStatusCode() == 302) {
    $cookies = [];
    foreach ($response->headers->getCookies() as $cookie) {
        $cookies[$cookie->getName()] = $cookie->getValue();
    }
    
    $redirectUrl = $response->headers->get('Location');
    echo "\n2. Following redirect to $redirectUrl...\n";
    $request2 = Illuminate\Http\Request::create($redirectUrl, 'GET');
    $request2->cookies->add($cookies);
    
    // VERY IMPORTANT: Catch any exceptions that happen during rendering
    try {
        $response2 = $kernel->handle($request2);
        echo "Status: " . $response2->getStatusCode() . "\n";
        if ($response2->getStatusCode() == 500) {
            echo "Exception: " . $response2->exception->getMessage() . "\n";
            echo $response2->exception->getTraceAsString() . "\n";
        }
    } catch (\Throwable $e) {
        echo "Throwable Exception: " . $e->getMessage() . "\n";
        echo $e->getTraceAsString() . "\n";
    }
} else {
    // Maybe validation errors?
    echo "Content:\n" . substr($response->getContent(), 0, 500) . "\n";
}
