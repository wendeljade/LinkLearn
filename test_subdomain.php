<?php
$url = 'http://bukidnon-state-university.localhost:8000/';
$headers = @get_headers($url);
if ($headers) {
    echo "Response for $url:\n";
    print_r($headers);
} else {
    echo "Failed to connect to $url\n";
}
