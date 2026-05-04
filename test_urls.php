<?php
$urls = [
    'http://127.0.0.1:8000/',
    'http://127.0.0.1:8000/login',
    'http://127.0.0.1:8000/register-organization'
];

foreach ($urls as $url) {
    echo "Testing $url...\n";
    $headers = @get_headers($url);
    if ($headers) {
        echo $headers[0] . "\n";
    } else {
        echo "Failed to connect.\n";
    }
}
