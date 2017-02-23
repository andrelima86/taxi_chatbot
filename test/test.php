<?php

$text = "I need to go to 9 Dundee rd Eastlea from town";

// for local server ui tests
if (isset($_POST['user_msg'])) 
	$text = $_POST['user_msg'];

$url = 'http://127.0.0.1/chatbot/index.php';
$header = array(
    'Content-Type: application/json',
);

//$input['entry'][0]['messaging'][0]['message']['text']
$message_array = array(
		'object' => 'page',
		'entry' => array(
			array(
				'id' => 'asa434y65ui5etdfgdb',
				'time' => '0',
				'messaging' => array(
						array(
								'message' => array(
									'text' => $text,
									'mid' => '0',
									'seq' => '0'
								),
								'timestamp' => '0',
								'sender' => array(
									'id' => '12345678'
								),
								'recipient' => array(
									'id' => '0'
								)
							)
					)
				)
		 )
	);
$message = json_encode($message_array, JSON_PRETTY_PRINT);
$ch = curl_init($url);

curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $message);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_exec($ch);

echo $response = curl_exec($ch);