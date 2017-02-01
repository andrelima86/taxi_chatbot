<?php 

namespace TaxiChatbot\Application;

class Message
{	
	public $seq = array();
	public $text = array();
	public $echo = array();
	public $user_id = array();
	public $timestamp = array();
	public $message_id = array();
	public $attachement = array();
	public $num_messaged = 0;

	public function __construct(array $data)
	{
		foreach ($data['entry'][0]['messaging'] as $message) {
			if(array_key_exists('is_echo', $message['message']))
			{
				exit();
			}
			array_push($this->seq, $message['message']['seq']);
			array_push($this->text, $message['message']['text']);
			array_push($this->echo, false);
			array_push($this->user_id, $message['sender']['id']);
			array_push($this->timestamp, $message['timestamp']);
			array_push($this->message_id, $message['message']['mid']);
			$this->num_messaged++;
		}
	}

}