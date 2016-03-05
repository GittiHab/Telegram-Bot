<?php
// Published under the MIT License, Copyright (c) 2015 Pius Ladenburger

class Session {
	private $db, $data, $name, $chat;
	
	/**
	* Create a new Bot Session object to manage session storage with Telegram Bots
	* @param $name The Name of the Bot, this will be the key for the values in the database
	* @param $chat The Chat which session variables should be retrieved
	* @param $db The Database object to work with
	*/
	public function __construct($name, $chat, $db) {
		$this->db = $db;
		$this->name = $name;
		$this->chat = $chat;
		
		$data = $db->query('SELECT * FROM `Session` WHERE `Bot` = "' . $db->esc($name) . '" AND `Chat` = "' . $db->esc($chat) . '"');
		foreach ( $data as $d ) {
			$k = $d['Key'];
			unset($d['Key']);
			$this->data[$k] = $d;
		}
	}
	
	/**
	* Retrieve a value
	* @param $key The index of the value
	*/
	public function get($key) {
		return (isset($this->data[$key]))? $this->data[$key]['Content']:false;
	}
	
	/**
	* Set a new value at a given index. If the index doesn't exist a new row is created in the database.
	* @param The index of the value
	* @param The new value for this index
	*/
	public function set($key, $value) {
		if (isset($this->data[$key])) {
			return $this->update($this->data[$key]['ID'], $value);
		}
		return $this->insert($key, $value);
	}
	
	/**
	* Helper function to insert rows
	*/
	private function insert($key, $content) {
		$array = (is_array($content))? 1:0;
		$content = ($array === 1)? json_encode($content):content;
		return $this->db->query('INSERT INTO `Session` (`Bot`, `Chat`, `Key`, `Content`, `Array`) VALUES ("' . $this->db->esc($this->name) . '", "' . $this->db->esc($this->chat) . '", "' . $this->db->esc($key) . '", "' . $this->db->esc($content) . '", "' . $array . '")');
	}
	
	/**
	* Helper function to update rows
	*/
	private function update($id, $content) {
		$array = (is_array($content))? 1:0;
		$content = ($array === 1)? json_encode($content):content;
		return $this->db->query('UPDATE `Session` SET `Content` = "' . $this->db->esc($content) . '", `Array` = "' . $array . '" WHERE `ID` = "' . $this->db->esc($id) . '"');
	}
}