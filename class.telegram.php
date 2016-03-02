<?php
class Telegram {
	private $token, $name;
	
	/**
	* Make a new Telegram Object which communicates with the Telegram Bot API
	* @param $token The Bot API token received from Telegram
	* @param $name The Name of this Bot
	*/
	public function __construct($token, $name) {
		$this->name = $name;
		$this->token = $token;
	}
	
	/**
	* Send a message to a chat.
	* @param String $chat The ID of the chat which should receive the message
	* @param String $content The content of the message
	* @param String $reply The ID of the message this message replies to
	* @param String $keyboard JSON-Encoded object of the keyboard
	*/
	public function sendMessage($chat, $content, $reply = null, $keyboard = null) {
		return $this->makeRequest('sendMessage', array(
				'chat_id' => $chat,
				'text' => $content,
				'reply_to_message_id' => $reply,
				'reply_markup' => $keyboard
		));
	}
	
	/**
	* Send a sticker to a chat
	* @param String $chat The ID of the chat which should receive the sticker
	* @param String $sticker_id The ID of the sticker
	* @param String $reply The ID of the message this sticker replies to
	*/
	public function sendSticker($chat, $sticker_id, $reply = null) {
		return $this->makeRequest('sendSticker', array(
				'chat_id' => $chat,
				'sticker' => $sticker_id,
				'reply_to_message_id' => $reply
		));
	}
	
	/**
	* Initial call to register this bot to telegram
	* @param String $url The URL where the bot can be reached from in the internet
	*/
	public function setWebhook($url) {
		return $this->makeRequest('setWebhook', array(
				'url' => $url 
		));
	}
	private function makeRequest($method, $data, $post = true) {
		$url = 'https://api.telegram.org/bot' . $this->token . '/' . $method;
		
		$method_post = ($post)? 'GET':'POST';
		$options = array(
				'http' => array(
						'header' => "Content-type: application/x-www-form-urlencoded\r\n",
						'method' => $method_post 
				) 
		);
		if ($post) { // POST request
			$options['http']['content'] = http_build_query($data);
		} else { // GET Request
			$url .= '?' . http_build_query($data);
		}
		$context = stream_context_create($options);
		return file_get_contents($url, false, $context);
	}
}