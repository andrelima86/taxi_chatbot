<?php 

namespace TaxiChatbot\Application;

class Response
{	
	public $text;
	public $phone;
	public $user_id;
	public $timestamp;
	public $message_id;
	public $attachement;
	private $intent = array(
			'greeting' => array(
					'Hello my name is Alfred, where do you want to go?',
					'Hi, where are you headed?',
					'Where can we take you today?',

				),
		);

	public function __construct(Intent $intent)
	{
		$text = $this->get_response($intent->intent[0]);
	}

	private function get_response($intent, $parameters = null)
	{
		echo $this->intent[$intent][rand(0, 3)];
		return $this->intent[$intent][rand(0, 3)];
	}
}