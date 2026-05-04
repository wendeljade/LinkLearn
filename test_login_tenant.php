<?php
$url = 'http://127.0.0.1:8000/login';
$options = [
    'http' => [
        'header' => "Host: bukidnon-state-university.localhost\r\n",
        'max_redirects' => 0,
        'ignore_errors' => true
    ]
];
$context = stream_context_create($options);
$headers = @get_headers($url, 1, $context);

if ($headers) {
    print_r($headers);
} else {
    echo "Failed to connect to $url\n";
}
