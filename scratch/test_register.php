<?php

$url = 'http://localhost:8000/api/register';
$data = [
    'name' => 'Test',
    'phone' => '923111222',
    'password' => 'password',
    'password_confirmation' => 'password',
    'role' => 'client'
];

$options = [
    'http' => [
        'header'  => "Content-type: application/json\r\nAccept: application/json\r\n",
        'method'  => 'POST',
        'content' => json_encode($data),
        'ignore_errors' => true
    ],
];

$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);

echo "Response Status: " . $http_response_header[0] . "\n";
echo "Response Body:\n" . json_encode(json_decode($result), JSON_PRETTY_PRINT) . "\n";
