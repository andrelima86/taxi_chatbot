<?php

$secret_token = 'test_token';

if(isset($_REQUEST['hub_challenge'])){
	$challenge = $_REQUEST['hub_challenge'];
	$received_secret_token = $_REQUEST['hub_verify_token'];	
}

if(strcmp($secret_token, $received_secret_token) === 0){
	echo $challenge;
	exit();
}

echo 'Hello world';