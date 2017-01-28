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

$user_id = $input['entry'][0]['messaging'][0]['sender']['id'];
$received_message = $input['entry'][0]['messaging'][0]['message']['text'];
$response_message = array(
	'recipient' => array( 'id' => $user_id, ) ,
	'message' => array( 'text' => 'Hello world', ) ,
);

// send responce to facebook
/*$ch = curl_init();

$header = array(
    'Content-Type: application/json',
);
$response_message = http_build_query($response_message);

$options = array(
    CURLOPT_URL => $url,
    CURLOPT_HTTPHEADER, $header,
    CURLOPT_POSTFIELDS => $response_message,
    CURLOPT_POST => true,
);

curl_setopt_array($ch, $options);

$response = curl_exec($ch);*/
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $response_message);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curl_errno = curl_errno($ch);
$curl_error = curl_error($ch);
file_put_contents('log.txt', 'http code: ' . $http_code . ' curl error: ' . print_r($curl_error, true));



