<?php
namespace TaxiChatbot\Database;
class Database {
	private $connection;
	private $dbType = DB_TYPE;
	private $dbHost = DB_HOST;
	private $dbPort = DB_PORT;
	private $dbName = DB_SCHEMA;
	private $dbUser = DB_USER_NAME;
	private $dbPass = DB_PASSWORD;
	public function __construct() {
		$this->makeConnection ();
	}
	public function makeConnection() {
		$this->connection = new \PDO ( "$this->dbType:host=$this->dbHost;port=$this->dbPort;dbname=$this->dbName", $this->dbUser, $this->dbPass );
		$this->connection->setAttribute ( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
		$this->connection->setAttribute ( \PDO::ATTR_EMULATE_PREPARES,false) ;
	}
	public function closeConnection() {
		unset ( $this->connection );
	}
	public function insert(array $values, $sql) {
		$stm = $this->connection->prepare ( $sql );
		
		foreach ( $values as $key => $value ) {
			$stm->bindValue( $key, $value );
		}
		return $stm->execute ();
		
	}
	public function rawQuery(array $values, $sql) {
		$stm = $this->connection->prepare ( $sql );
		foreach ( $values as $key => $value ) {
			$stm->bindValue( $key, $value );
		}
		if (! $stm->execute ()) {
			
			return false;
		}
		return $result = $stm->fetchAll ( \PDO::FETCH_ASSOC );
	}
	
	public function getLastQueryID(){
		return $this->connection->lastInsertId ();
	}
}















