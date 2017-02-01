<?php

namespace TaxiChatbot\Application;

class Intent
{	
	public $message;
	public $message_id;
	public $entities = array();

	public function __construct(array $wit_response)
	{
		$this->filter($wit_response['entities'], WIT_AI_PERCENTAGE);
		$this->message = $wit_response['_text'];
		$this->message_id = $wit_response['msg_id'];
	}

	private function filter(array $wit_entites, $percentage)
	{
		foreach ($wit_entites as $individual_entities_array) 
		{
			$final_array = array();
			foreach ($individual_entities_array as $key => $value) 
			{
				if ($individual_entities_array[$key]['confidence'] >= $percentage) 
					array_push($final_array, $value['value']);
				
			}
			$this->entities[key($wit_entites)] = $final_array;
		}
	}

	public function __get($var_name)
	{

		if (!array_key_exists($var_name,$this->entities))
		{
          //this attribute is not defined!
          throw new Exception("variable $var_name not defined");
      	}
      	else return $this->entities[$var_name];
	}

}