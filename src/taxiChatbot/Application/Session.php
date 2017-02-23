<?php 

namespace TaxiChatbot\Application;
use TaxiChatbot\Database\Database;
class Session
{
	public $session_id;
	public $session_array;
	public $db;

	public function __construct(Message $message, Database $db)
	{
		$this->session_id = $message->user_id;
		$this->db = $db;

		// check if session is present
		$this->session_array = $this->check_session($this->session_id);

		// create session if not found
		if(empty($this->session_array))
			$this->session_array = $this->create_session($this->session_id);
		
		$this->session_array = $this->session_array[0];
		$this->session_array['session_data'] = json_decode($this->session_array['session_data'],true);
	}

	private function check_session($session_id)
	{
		$query = "select * from tbl_sessions where session_id = :session_id limit 1";
		$params[':session_id'] = $session_id;
		
		try {
			$result = $this->db->rawQuery($params, $query);

			if (empty($result)) 
				return false;
			
			return $result;
		} catch (\Exception $e){
			throw new \Exception("DB error: " . $e->getMessage());
		}
	}

	private function create_session($session_id)
	{
		$session_array = array();
		$query = "insert into 
				  tbl_sessions(session_id, seesion_created, session_data, session_closed)
				  value(:session_id, now(), '[]', 'FALSE')";
		$params[':session_id'] = $session_id;
		try {
			
			$result = $this->db->rawQuery($params, $query);
		} catch (\Exception $e){
			throw new \Exception("DB error: " . $e->getMessage());
		}
	}

	public function __get($var_name)
	{

		if (!array_key_exists($var_name,$this->session_array['session_data']))
		{
          //this attribute is not defined!
          throw new \Exception("variable $var_name not defined");
      	}
      	else return $this->session_array['session_data'][$var_name];
	}

	public function update_session($name, $value)
	{
		// add entity that already exists excepts intent
		if(strcmp($name, 'intent') === 0)
			array_merge_recursive($this->session_array['session_data'], $value);

		// if intent then over write
		if(!array_key_exists($name, $this->session_array['session_data']))
			$this->session_array['session_data'][$name] = $value;

	}

	public function save_session()
	{
		$query = "update table tbl_sessions 
				  set session_data = :session_data 
				  where session_id = :session_id";
		$params[':session_id'] = $this->session_id;
		echo $params[':session_data'] = json_encode($this->session_array);
		try {
			
			$result = $this->db->insert($params, $query);
		} catch (\Exception $e){
			throw new \Exception("DB error: " . $e->getMessage());
		}
	}

	public function end_session()
	{

	}

}