<?php
$url = 'http://localhost:8888/chatbot/';
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
									'text' => "Hello",
									'mid' => '0',
									'seq' => '0'
								),
								'timestamp' => '0',
								'sender' => array(
									'id' => '0'
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