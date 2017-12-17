<?php
/**
* @author The Matrix (https://t.me/matrix_1220)
* @version 2.0
* Telegram Bot Client
*/
class TelegrambotException extends Exception {
	protected $description;
	protected $error_code;
	protected $method;
	protected $data;
	public function __construct($description, $error_code = 0, $method = "Unknown", $data = []) {
		$this->description=$description;
		$this->error_code=$error_code;
		$this->method=$method;
		$this->data=$data;
		parent::__construct("$method method: $description ($error_code) ".json_encode($data), $error_code);
	}
	function getDescription() {
		return $this->description;
	}
	function getErrorCode() {
		return $this->error_code;
	}
	function getMethod() {
		return $this->method;
	}
	function getData() {
		return $this->data;
	}
	// public function __toString() {
	//	return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
	// }
}
class Telegrambot {
	private $token;
	function __construct($token) {
		$this->token=$token;
	}
	function method($method,$data_array=[]) {
		$sock = fsockopen("ssl://api.telegram.org", 443, $errno, $errstr, 30);
		if (!$sock) throw new Exception("$errstr ($errno)");
		$data = json_encode($data_array);
		fwrite($sock, "POST /bot".$this->token."/".$method." HTTP/1.0\r\n");
		fwrite($sock, "Host: api.telegram.org\r\n");
		fwrite($sock, "Content-type: application/json\r\n");
		fwrite($sock, "Content-length: " . strlen($data) . "\r\n");
		fwrite($sock, "\r\n");
		fwrite($sock, $data);
		$body = ""; while (!feof($sock)) $body .= fread($sock, 4096);
		$body = trim(substr($body, strpos($body, "\r\n\r\n")));
		fclose($sock);
		$result=json_decode($body);
		if($result===null) throw new Exception($body);
		if(!$result->ok) throw new TelegrambotException($result->description,$result->error_code,$method,$data_array);
		return $result;//->result;
	}
	function sendMessage($id,$text,$options=[]) {
		if(!isset($options['parse_mode'])) $options['parse_mode']='HTML';
		$options['chat_id']=$id;
		$options['text']=$text;
		try {
			$temp=$this->method('sendMessage',$options);
		} catch(TelegrambotException $e) {
			if($e->getErrorCode()=='403' or $e->getErrorCode()=='400') {
				$GLOBALS['db']->update('users')->set(['blocked'=>'1'])->where('id='.$id)->exec();
				return false;
			} else throw $e;
		}
		return $temp->result;
	}
	function replyKeyboard($keys) {
		return ['reply_markup'=>['keyboard'=>$keys,'resize_keyboard'=>true]];
	}
	function removeReplyKeyboard() {
		return ['reply_markup'=>['remove_keyboard'=>true]];
	}
	function inlineKeyboard($keys) {
		return ['reply_markup'=>['inline_keyboard'=>$keys]];
	}
	function inlineKeyboardButton($text,$data) {
		return ['text'=>$text,'callback_data'=>$data];
	}
	function answerCallbackQuery($id,$options=[],$show_alert=false,$cache_time=0) {
		$temp=$this->method('answerCallbackQuery',['callback_query_id'=>$id]+$options+['show_alert'=>$show_alert,'cache_time'=>$cache_time]);
		if($temp->ok) return $temp->result;
	}
	function editMessageText($chat_id,$message_id,$text,$options=[]) {
		if(!isset($options['parse_mode'])) $options['parse_mode']='HTML';
		$temp=$this->method('editMessageText',['chat_id'=>$chat_id,'message_id'=>$message_id,'text'=>$text]+$options);
		if($temp->ok) return $temp->result;
	}
	function editInlineMessageText($inline_message_id,$text,$options=[]) {
		if(!isset($options['parse_mode'])) $options['parse_mode']='HTML';
		$temp=$this->method('editMessageText',['inline_message_id'=>$inline_message_id,'text'=>$text]+$options);
		if($temp->ok) return $temp->result;
	}
	function editMessageReplyMarkup($chat_id,$message_id,$reply_markup=null) {
		$temp=['chat_id'=>$chat_id,'message_id'=>$message_id];
		if(isset($reply_markup)) $temp['reply_markup']=['inline_keyboard'=>$reply_markup];
		$temp=$this->method('editMessageReplyMarkup',$temp);
		if(isset($temp->ok) and  $temp->ok) $temp->result;
	}
	static function HTML($t) {
		return str_replace(['&','<','>'],['&amp;','&lt;','&gt;'],$t);
	}
	function answerInlineQuery($id,$results,$options=[]) {
		$temp=$this->method('answerInlineQuery',['inline_query_id'=>$id,'results'=>$results]+$options);
		if($temp->ok) return $temp->result;
	}
}
function uploads($file_id) {
	global $bot;
	$sock = fsockopen("uploads.im", 80, $errno, $errstr, 10);
	if (!$sock) throw new Exception("$errstr ($errno)");
	$url='https://api.telegram.org/file/bot'.TOKEN.'/'.$bot->method("getFile",['file_id'=>$file_id])->result->file_path;
	fwrite($sock, "GET /api?upload=$url HTTP/1.0\r\n");
	fwrite($sock, "Host: uploads.im\r\n");
	fwrite($sock, "User-Agent: Catalogiyabot\r\n");
	//fwrite($sock, "Accept-Language: en-us\r\n");
	//fwrite($sock, "Accept-Encoding: gzip, deflate\r\n");
	//fwrite($sock, "Connection: Keep-Alive\r\n");
	fwrite($sock, "\r\n");
	$body = ""; while (!feof($sock)) $body .= fread($sock, 4096);
	$body = trim(substr($body, strpos($body, "\r\n\r\n")));
	fclose($sock);
	$result=json_decode($body);
	if($result===null) throw new Exception("uploads.im: ".$body);
	if($result->status_code!=200) throw new Exception("uploads.im: ".$body);

	return $result;
}
?>