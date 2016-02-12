<?php
class Telegram {
	private $token, $name;
	public function __construct($token, $name) {
		$this->name = $name;
		$this->token = $token;
	}
	public function sendMessage($chat, $content, $reply = null, $keyboard = null) {
		return $this->makeRequest('sendMessage', array(
				'chat_id' => $chat,
				'text' => $content,
				'reply_to_message_id' => $reply,
				'reply_markup' => $keyboard
		));
	}
	
	public function sendSticker($chat, $sticker_id, $reply = null) {
		return $this->makeRequest('sendSticker', array(
				'chat_id' => $chat,
				'sticker' => $sticker_id,
				'reply_to_message_id' => $reply
		));
	}
	
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