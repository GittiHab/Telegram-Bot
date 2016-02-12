<?php
namespace System\Database;

/**
 * Processes all DB requests
 */
class MySQL {
	
	public $MySQLiObj = null;
	public $lastSQLQuery = null;
	public $lastSQLStatus = null;
	
	/**
	 * Connect to DB
	 */
	function __construct($server, $user, $password, $db, $port = '3306') {
		$this->MySQLiObj = new \mysqli($server, $user, $password, $db, $port);
		
		if (mysqli_connect_errno()) {
			echo "No connection possible. What went wrong?";
			trigger_error("MySQL-Connection-Error", E_USER_ERROR);
			die();
		}
		
		$this->query("SET NAMES utf8");
	}
	
	/**
	 * Close connection
	 */
	public function __destruct() {
		$this->MySQLiObj->close();
	}
	
	/**
	 * Do a SQL request
	 */
	public function query($sqlQuery, $resultset = false) {
		$this->lastSQLQuery = $sqlQuery;
		
		$result = $this->MySQLiObj->query($sqlQuery);
		
		if ($resultset == true) {
			if ($result == false) {
				$this->lastSQLStatus = false;
			} else {
				$this->lastSQLStatus = true;
			}
			
			return $result;
		}
		
		$return = $this->makeArrayResult($result);
		
		return $return;
	}
	public function insert_id() {
		$result = $this->MySQLiObj->insert_id;
		return $result;
	}
	
	public function last_id() { // Alias for insert_id because I keep typing it
		return $this->insert_id();
	}
	
	/**
	 * Error of last SQL request
	 */
	public function lastSQLError() {
		return $this->MySQLiObj->error;
	}
	
	/**
	 * Mask a parameter for use with SQL request
	 */
	public function escapeString($value) {
		return $this->MySQLiObj->real_escape_string($value);
	}
	
	/**
	 * Alias function for escapeString
	 */
	public function esc($value) {
		return $this->escapeString($value);
	}
	/**
	 * Array-structure for request
	 */
	private function makeArrayResult($ResultObj) {
		if ($ResultObj === false) {
			// Error (z.B. Primary exists)
			$this->lastSQLStatus = false;
			return false;
		} else if ($ResultObj === true) {
			// UPDATE- INSERT etc. result: TRUE.
			$this->lastSQLStatus = true;
			return true;
		} else if ($ResultObj->num_rows == 0) {
			// No result SELECT, SHOW, DESCRIBE or EXPLAIN-Statements
			$this->lastSQLStatus = true;
			return array();
		} else {
			
			$array = array();
			
			while ( $line = $ResultObj->fetch_array(MYSQLI_ASSOC) ) {
				array_push($array, $line);
			}
			
			$this->lastSQLStatus = true;
			
			return $array;
		}
	}
}
?>