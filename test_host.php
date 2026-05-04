<?php
$url = 'http://127.0.0.1:8000/';
$options = [
    'http' => [
        'header' => "Host: bukidnon-state-university.localhost\r\n"
    ]
];
$context = stream_context_create($options);
$headers = @get_headers($url, 1, $context);

if ($headers) {
    echo "Response for $url with Host: bukidnon-state-university.localhost\n";
    print_r($headers);
} else {
    echo "Failed to connect to $url\n";
}
