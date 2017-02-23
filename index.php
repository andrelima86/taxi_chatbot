<?php

require_once __DIR__ . '/vendor/autoload.php';
use TaxiChatbot\Application\Chatbot;
use TaxiChatbot\Application\Session;
use TaxiChatbot\Database\Database;
use TaxiChatbot\ResponseEngine\Response_Engine;
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

	// instatiate database connection
	$db = new Database();
	
	// parse data being sent
	$data = file_get_contents('php://input');
	$message = $chatbot->get_message($data);

	// get session data
	$session = new Session($message, $db);
	// get message intent
	$intent = $chatbot->get_message_intent($message);

	// generate response to message 
	$response_engine = new Response_Engine($intent, $message, $session, $db);
	$response_engine->process_message();
	$response = $response_engine->get_response();


	// send message to user
	//$chatbot->send_reply($response);

	// update session data


	echo $response->text;
} catch (Exception $e) {
	
	$log->addInfo($e->getMessage());
	echo $e->getMessage();
}



