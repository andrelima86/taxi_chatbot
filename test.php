<?php

$ch = curl_init();

$header = array(
    'Content-Type: application/json',
);
$response_message = json_encode($response_message);

$options = array(
    CURLOPT_URL => $url,
    CURLOPT_HTTPHEADER, $header,
    CURLOPT_POSTFIELDS => $response_message,
    CURLOPT_POST => true,
);

curl_setopt_array($ch, $options);

echo $response = curl_exec($ch);