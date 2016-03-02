<?php
/* Telegram Bot Info */
define('TOKEN', 'TOKE_RECEIVED_FROM_TELEGRAM');
define('NAME', 'BOT_NAME');
define('MAX_CALLS', 7); // maximum calls per hour
/* MySQL Database Info */
define('MYSQL_HOST', 'localhost');
define('MYSQL_USER', 'USERNAME');
define('MYSQL_PASSWORD', 'PASSWORD');
define('MYSQL_NAME', 'BOT');

require './advanced/class.DB.php';
require './advanced/class.session.php';

require './class.telegram.php';
$bot = new Telegram(TOKEN, NAME);
// $DB = new \System\Database\MySQL(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_NAME);

// Incoming update
$request = json_decode(file_get_contents('php://input'), true);
if ($request['update_id']) {
	// Message received
	
	// BOT CODE HERE
	
	// API: https://core.telegram.org/bots/api#getting-updates
}

if ($_GET['setHook']) {
	// uncomment, type in your webhook domain and visit example.com/path/to/bot/?setHook=1 to register the hook
	echo $bot->setWebhook('https://example.com/telegram_bot');
} else {
	// uncomment if you need some troubleshooting
	// include ('./advanced/debug.php');
}