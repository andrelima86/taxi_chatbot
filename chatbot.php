<?php
// git add . git commit -am ""; git push heroku master
// configurations
$secret_token = 'test_token';
$fb_access_token = 'EAAJ2HjwuKgoBANfPntPbhnvUXkbzOWHbqoZAEnvfFcGJccgEK7CZCAnvET7i5B5HA1VLCLuNwejEZC4mtchKZABSuPewCcscTltF7ItqzI13RTo7BKU0h1XuNMQgBbONBDKyxPCWHGAp8ZCOCyVTQQhObBy7hDMmHGCn5CIQNeAZDZD';
$url = "https://graph.facebook.com/v2.6/me/messages?access_token=$fb_access_token";
$received_secret_token = '';

// check if request is for challenge
if(isset($_REQUEST['hub_challenge'])){
	$challenge = $_REQUEST['hub_challenge'];
	$received_secret_token = $_REQUEST['hub_verify_token'];	
}

if(strcmp($secret_token, $received_secret_token) === 0){
	echo $challenge;
	exit();
}

// process chat request
$input = json_decode(file_get_contents('php://input') , true);
file_put_contents('log.txt', $input);
$user_id = $input['entry'][0]['messaging'][0]['sender']['id'];
$received_message = $input['entry'][0]['messaging'][0]['message']['text'];
$response_message = array(
	'recipient' => array( 'id' => $user_id, ) ,
	'message' => array( 'text' => 'Hello world', ) ,
);

// send responce to facebook
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

$response = curl_exec($ch);
