<?php
if($_SERVER['REQUEST_URI']=='/AAEdydlMXwY81qXWZb4njw7YLhquOeKx0sg') {
	include 'access-bot.php';
} elseif($_SERVER['REQUEST_URI']=='/clearUpdates') {
	include "telegrambot.php";
	$bot=new Telegrambot("438238300:AAEdydlMXwY81qXWZb4njw7YLhquOeKx0sg");
	try {
		$bot->method("deleteWebHook");
	} catch(Exception $e) {

	}
	$bot->method("getUpdates",['offset'=>'-1']);
	$bot->method("setWebhook",['url'=>'https://yiacatalog.ga/AAEdydlMXwY81qXWZb4njw7YLhquOeKx0sg']);
} elseif($_SERVER['REQUEST_URI']=='/showlog') {
	$f=fopen('error_log','r');
	fseek($f, filesize('error_log')-1024*32);
	while ($temp=fread($f, 1024)) {
		echo $temp;
	}
} elseif($_SERVER['REQUEST_URI']=='/testuploads') {
	include "telegrambot.php";
	include "buttons.php";
	$bot=new Telegrambot(TOKEN);
	print_r(uploads('AgADAgADlqgxG-hIaUj1rSSHh4EsAzPYDw4ABCjTs5rypO-hk_0CAAEC'));
} elseif($_SERVER['REQUEST_URI']=='/dumpids') {
	include "buttons.php";
	require 'datebase.php';
	$db=new datebase(DB_HOST,DB_USER,DB_PASS,DB_NAME);
	set_time_limit(0);
	ob_implicit_flush(true);
	foreach ($db->select()->from('users')->fetch() as $value) {
		echo $value->id."\n";
	}
} else {
	echo 'Axmoq bo\'lsang olam seniki';
	//print_r($_SERVER);
	//error_log("asdas");
}
?>