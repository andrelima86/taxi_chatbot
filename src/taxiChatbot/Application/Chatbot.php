<?php

namespace TaxiChatbot\Application;
use Monolog\Logger;

class Chatbot
{	
	
	public $message;
	public $intent;
	public $logger;

	public function __construct(Logger $log)
	{
		$this->logger = $log;
	}

	/*
	 * Check for web hook configuration request
	 */
	public function verify_webhook(array $REQUEST)
	{
		if (!isset($REQUEST['hub_challenge'])) 
            return false;

		$this->logger->addInfo('received request to verify web hook');
        $hubVerifyToken = null;
        $hubVerifyToken = $REQUEST['hub_verify_token'];
        $hubChallenge = $REQUEST['hub_challenge'];

        if (isset($hubChallenge) && $hubVerifyToken == CHATBOT_SECRET) 
        {
            echo $hubChallenge;
            exit();
        }
	}

	/*
	 * Extract data from fb request 
	 * Create message object
	 */
	public function get_message($data)
	{
		$data = json_decode($data, true);
		if (!is_array($data)) 
			throw new \Exception('Message not found: ' . $data);

		if (array_key_exists('error', $data)) 
			throw new \Exception('Error from fb: ' . print_r($data));
		

		if (!array_key_exists('entry', $data) || 
			!array_key_exists('message', $data['entry'][0]['messaging'][0])) 
			throw new \Exception('Message not found: ' . print_r($data));

		$this->message = new Message($data);
		return $this->message;
	}

	/**
	 * Post to NPU API to understan user message
	 *
	 */
	public function get_message_intent(Message $message)
	{	
		// set up parameters to send 
		$parameters = array(
			 'v' => WIT_AI_VERSION,
			 'q' => $message->text[0]
		);
		$query_string = http_build_query($parameters);
		$url = WIT_AI_BASE_URL . "?" . $query_string;
		$this->logger->addInfo('Request to wit is: ' . $url);

		// send the request
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, 
			array('Authorization: Bearer ' . WIT_AI_ACCESS_TOKEN));

		//$response = curl_exec($ch);
		$response = "{
						  \"msg_id\" : \"db45082c-9311-4fd5-b684-4803c5bd6568\",
						  \"_text\" : \"hello\",
						  \"entities\" : {
						    \"local_search_query\" : [ {
						      \"confidence\" : 0.7306822444917643,
						      \"type\" : \"value\",
						      \"value\" : \"hello\",
						      \"suggested\" : true
						    } ],
						    \"intent\" : [ {
						      \"confidence\" : 0.9782772967406987,
						      \"value\" : \"greeting\"
						    } ]
						  }
						}";
		$this->logger->addInfo('Response from wit ai: ' . $response);

		if(curl_errno($ch))
			throw new \Exception(curl_error($ch) . ' (' . curl_errno($ch) . ')');

		if(curl_errno($ch))
			throw new \Exception("HTTP error: (" . curl_getinfo($ch, CURLINFO_HTTP_CODE) . ')');

		curl_close($ch);

		$data = json_decode($response, true);
		if (!is_array($data)) 
			throw new \Exception('Unknown response: ' . $data);

		if (array_key_exists('error', $data)) 
			throw new \Exception('Error returned from wit ai: ' . $data['error']);

		if (!array_key_exists('entities', $data)) 
			throw new \Exception('No entites from wit ai: ' . print_r($data));

		$this->intent = new Intent($data);
		return $this->intent;
	}
	/**
	 * Generate response to user based on perceived message intent 
	 *
	 */
	public function generate_response(Intent $intent)
	{
		$response = new Response($intent);
		return $response;
	}
	/**
	 * Post response to fb
	 */
	public function send_reply(Response $response)
	{
		$user_id = $this->message->user_id;
		$text_message = $response->text;
		$url = FB_BASE_URL . '?access_token=' . $this->fb_access_token;
		$response_message = array(
			'recipient' => array( 'id' => $user_id ) ,
			'message' => array( 'text' => $text_message ) ,
		);

		// send responce to facebook
		$response_message = json_encode($response_message);
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $response_message);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_exec($ch);

		if(curl_errno($ch))
			throw new \Exception(curl_error($ch) . ' (' . curl_errno($ch) . ')');

		if(curl_errno($ch))
			throw new \Exception("HTTP error: (" . curl_getinfo($ch, CURLINFO_HTTP_CODE) . ')');

		curl_close($ch);	
	}	
}