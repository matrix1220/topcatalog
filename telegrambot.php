<?
/**
* @author The Matrix (https://t.me/matrix_1220)
* @version 2.0
* Telegram Bot Client
*/
class Telegrambot {
	private $token;
	function __construct($token) {
		$this->token=$token;
	}
	function method($method,$data=[]) {
		$sock = fsockopen("ssl://api.telegram.org", 443, $errno, $errstr, 30);
		if (!$sock) throw new Exception("$errstr ($errno)");
		$data = json_encode($data);
		fwrite($sock, "POST /bot".$this->token."/".$method." HTTP/1.0\r\n");
		fwrite($sock, "Host: api.telegram.org\r\n");
		fwrite($sock, "Content-type: application/json\r\n");
		fwrite($sock, "Content-length: " . strlen($data) . "\r\n");
		fwrite($sock, "\r\n");
		fwrite($sock, $data);
		$body = ""; while (!feof($sock)) $body .= fgets($sock, 4096);
		$body = trim(substr($body, strpos($body, "\r\n\r\n")));
		fclose($sock);
		$result=json_decode($body);
		if($result===null) throw new Exception($data."\n".$body);
		if(!$result->ok) throw new Exception($data."\n".$body);
		return $result;//->result;
	}
	function sendMessage($id,$text,$options=[]) {
		if(!isset($options['parse_mode'])) $options['parse_mode']='HTML';
		$temp=$this->method('sendMessage',['chat_id'=>$id,'text'=>$text]+$options);
		if($temp->ok) return $temp->result;
		else throw new Exception($temp->description);
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
	$sock = fsockopen("tcp://uploads.im", 443, $errno, $errstr, 30);
	if (!$sock) throw new Exception("$errstr ($errno)");
	$url='https://api.telegram.org/file/bot'.TOKEN.'/'.$bot->method("getFile",['file_id'=>$file_id])->result->file_path;
	fwrite($sock, "POST /?upload=".urlencode($url)." HTTP/1.0\r\n");
	fwrite($sock, "Host: uploads.im\r\n");
	fwrite($sock, "\r\n");
	$body = ""; while (!feof($sock)) $body .= fgets($sock, 4096);
	$body = trim(substr($body, strpos($body, "\r\n\r\n")));
	fclose($sock);
	$result=json_decode($body);
	if($result===null) throw new Exception($body);
	if(!$result->status_code==200) throw new Exception($body);

	return $result;//->result;sdfsdfsf
}
?>