<?php

namespace TaxiChatbot\ResponseEngine;
use TaxiChatbot\Application\Session;
use TaxiChatbot\Application\Intent;
use TaxiChatbot\Application\Message;
use TaxiChatbot\Database\Database;

class Response_Engine
{
	public $intent;
	public $session;
	public $message;
	public $response;
	public $db;

	public function __construct(Intent $intent, Message $message, Session $session, Database $db)
	{
		$this->db = $db;
		$this->intent = $intent;
		$this->session = $session;
		$this->message = $message;
		$this->response = null;
	}

	public function process_message()
	{	
		// update session with intent data
		$intent_array = $this->intent->entities;
		foreach ($intent_array as $key => $value) 
			$this->session->update_session($key, $value);

		// fetch data for the intents provided
		$intent_data = array();
		foreach ($this->session->intent as $intent) 
			array_push($intent_data, $this->fetch_intent_data($intent));

		// determine response templates to fetch 
		$rsp_templates = array();
		$rsp_templates = $this->determine_response($this->session, $intent_data);

		// no response template for this message respond I don't understand
		if(empty($rsp_templates))
			$this->response = new Response();
		
		// fetch intent response templates
		$intent_response_templates = array();
		foreach ($rsp_templates as $response_name) 
			$intent_response_templates[$response_name] = $this->fetch_message_templates($response_name);

		// select a specific template to use
		$template_array = $this->chose_template($intent_response_templates);

		// populate template variables
		$text_response = $this->process_template($template_array, $this->session);

		// create response object
		$this->response = new Response($text_response, null, $this->message->user_id, null);
	}

	public function get_response()
	{
		if(is_null($this->response))
			throw new \Exception("Error there is no response");

		return $this->response;
	}

	/**
	 * fetch requirements to achieve current intent
	 */
	private function fetch_intent_data($intent_name)
	{
		$query = "select * from tbl_intents where intent_name = :intent_name limit 1";
		$params[':intent_name'] = $intent_name;
		
		try {
			$result = $this->db->rawQuery($params, $query);
			foreach ($result as $key => $value) 
			{
				 $result[$key]['intent_params'] = json_decode($value['intent_params'], true);
			}
			if (empty($result)) 
				return false;

			return $result[0];
		} catch (\Exception $e){
			throw new \Exception("DB error: " . $e->getMessage());
		}
	}

	/**
	 * calculate what is outstanding to reach goal (get taxi)
	 * this is based on current message intent and session data
	 */
	private function determine_response(Session $session, array $intent_data)
	{
		// if no intent found show I don't understand message
		if(empty($intent_data))
			return array();
		

		// determine array of parameters found
		$params_for_each_intent = array();
		foreach ($intent_data as $intent_key => $intent_value) 
		{
			$params_for_each_intent[$intent_value['intent_name']] = array();
			$individual_param_array = $intent_value['intent_params'];
			foreach ($session->session_array['session_data'] as $session_key => $session_value) 
			{
				if(in_array($session_key, $individual_param_array))
					array_push($params_for_each_intent[$intent_value['intent_name']], $session_key);
			}
		}
		// arrange the parameters alphabetically
		foreach ($params_for_each_intent as $intent => $params)
		{
			sort($params);
			$params_for_each_intent[$intent] = $params;
		}

		// determine the name of response template
		$response_template_names = array();
		foreach ($params_for_each_intent as $intent => $params) 
		{
			$sting_of_params = '';
			foreach ($params as $param) 
				$sting_of_params .= '_' . $param;

			array_push($response_template_names, $intent . $sting_of_params);
		}

		return $response_template_names;
	}

	/**
	 * pick a template from available
	 */
	private function chose_template(array $templates)
	{
		$templates_chosen = array();
		foreach ($templates as $intent => $intent_templates) 
		{
			$random = rand(0, count($intent_templates) -1 );
			$templates_chosen[$intent] = $intent_templates[$random]['response_template'];
		}

		return $templates_chosen;
	}

	/**
	 * fill necessary variables in message template
	 */
	private function process_template(array $chosen_templates, Session $session)
	{
		$array_of_text_responses = array();
		$final_text_responses = "";
		foreach ($chosen_templates as $key => $template) 
		{
			$intent_entity_array = explode('_', $key);
			$num_intent_entity = count($intent_entity_array);
			if ($num_intent_entity > 0) 
			{
				$intent = $intent_entity_array['0'];
				array_shift($intent_entity_array);
				if (!empty($intent_entity_array)) 
				{
					foreach ($intent_entity_array as $entity) 
					{
						$text = str_replace('('. $entity .')', $session->$entity['0'], $template);
						array_push($array_of_text_responses, $text);
						$final_text_responses .= $text . "\n";
					}
				}	
			} 

			else 
			{
				// no intent was found display can't understand message
				$final_text_responses = 'I am a simple programe. I can only help you with getting a taxi. Unfortunately I do not know what you mean by: ' . $this->message->text['0'];
			}
			
		}

		return $final_text_responses;
	}

	/**
	 * get message templates from db
	 */
	private function fetch_message_templates($response_name)
	{
		$query = "select response_template 
				  from tbl_responses 
				  where response_name = :response_name";

		$params[':response_name'] = $response_name;
		
		try {
			$result = $this->db->rawQuery($params, $query);

			if (empty($result)) 
				throw new \Exception("No response template found for: $response_name");
				
			
			return $result;
		} catch (\Exception $e){
			throw new \Exception("DB error: " . $e->getMessage());
		}
	}


}