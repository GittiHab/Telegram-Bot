<?php
class Session {
	private $db, $data, $name, $chat;
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
	public function get($key) {
		return (isset($this->data[$key]))? $this->data[$key]['Content']:false;
	}
	public function set($key, $value) {
		if (isset($this->data[$key])) {
			return $this->update($this->data[$key]['ID'], $value);
		}
		return $this->insert($key, $value);
	}
	private function insert($key, $content) {
		$array = (is_array($content))? 1:0;
		$content = ($array === 1)? json_encode($content):content;
		return $this->db->query('INSERT INTO `Session` (`Bot`, `Chat`, `Key`, `Content`, `Array`) VALUES ("' . $this->db->esc($this->name) . '", "' . $this->db->esc($this->chat) . '", "' . $this->db->esc($key) . '", "' . $this->db->esc($content) . '", "' . $array . '")');
	}
	private function update($id, $content) {
		$array = (is_array($content))? 1:0;
		$content = ($array === 1)? json_encode($content):content;
		return $this->db->query('UPDATE `Session` SET `Content` = "' . $this->db->esc($content) . '", `Array` = "' . $array . '" WHERE `ID` = "' . $this->db->esc($id) . '"');
	}
}