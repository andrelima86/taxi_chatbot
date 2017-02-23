<?php 

namespace TaxiChatbot\ResponseEngine;

class Response
{	
	public $text;
	public $phone;
	public $user_id;
	public $payload;

	public function __construct($text = null, $phone = null, $user_id = null, $payload = null)
	{
		$this->user_id = $user_id;
		$this->text = $text;
		$this->phone = $phone;
		$this->payload = $payload;
	}

	private function get_response($intent)
	{
		return $this->intent[$intent][rand(0, 3)];
	}
}