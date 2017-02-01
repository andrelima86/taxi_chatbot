<?php

require_once __DIR__ . '/vendor/autoload.php';
use TaxiChatbot\Application\Chatbot;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

date_default_timezone_set(TIMEZONE);
$log = new Logger('logger');
$log->pushHandler(new StreamHandler(LOG_PATH, Logger::DEBUG));
$log->addInfo('received request in chatbot application');

$chatbot = new Chatbot($log);

try {

	// check if request is to setup web hook
	$chatbot->verify_webhook($_REQUEST);

	// parse data being sent
	$data = file_get_contents('php://input');
	$message = $chatbot->get_message($data);

	// get message intent
	$intent = $chatbot->get_message_intent($message);

	// generate response to message 
	$response = $chatbot->generate_response($intent);

	// send message to user
	$chatbot->send_reply($response);

} catch (Exception $e) {
	
	$log->addInfo('Error: ' . $e->getMessage());
	echo 'Error: ' . $e->getMessage();
}



