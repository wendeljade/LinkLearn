<?php
$cookieFile = __DIR__ . '/cookie.txt';
if (file_exists($cookieFile)) {
    unlink($cookieFile);
}

function request($url, $post = null, $cookies = true) {
    global $cookieFile;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    // Don't follow redirects automatically so we can trace them
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    
    if ($post !== null) {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
    }
    
    if ($cookies) {
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
    }
    
    $response = curl_exec($ch);
    $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $headerStr = substr($response, 0, $headerSize);
    $body = substr($response, $headerSize);
    
    $headers = [];
    foreach (explode("\r\n", $headerStr) as $line) {
        if (strpos($line, ': ') !== false) {
            list($key, $val) = explode(': ', $line, 2);
            $headers[strtolower($key)] = $val;
        }
    }
    
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return [
        'status' => $status,
        'headers' => $headers,
        'body' => $body
    ];
}

echo "1. GET /login\n";
$res = request('http://localhost:8000/login');
preg_match('/name="_token" value="(.*?)"/', $res['body'], $matches);
$csrf = $matches[1] ?? '';
echo "CSRF: $csrf\n\n";

echo "2. POST /login\n";
$res = request('http://localhost:8000/login', [
    '_token' => $csrf,
    'email' => 'sti@gmail.com',
    'password' => 'password123'
]);
echo "Status: {$res['status']}\n";
echo "Location: " . ($res['headers']['location'] ?? 'N/A') . "\n\n";

$nextUrl = $res['headers']['location'] ?? null;
$step = 3;

while ($nextUrl && $step <= 10) {
    echo "$step. GET $nextUrl\n";
    $res = request($nextUrl);
    echo "Status: {$res['status']}\n";
    echo "Location: " . ($res['headers']['location'] ?? 'N/A') . "\n\n";
    $nextUrl = $res['headers']['location'] ?? null;
    $step++;
}
