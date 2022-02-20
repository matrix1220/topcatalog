<?php
// 438238300:AAEdydlMXwY81qXWZb4njw7YLhquOeKx0sg
// https://api.telegram.org/bot438238300:AAEdydlMXwY81qXWZb4njw7YLhquOeKx0sg/setWebhook?url=https://tepa.ga/AAEdydlMXwY81qXWZb4njw7YLhquOeKx0sg&max_connections=10
// https://api.telegram.org/bot438238300:AAEdydlMXwY81qXWZb4njw7YLhquOeKx0sg/deletewebhook
// https://api.telegram.org/bot438238300:AAEdydlMXwY81qXWZb4njw7YLhquOeKx0sg/getwebhookinfo
// https://api.telegram.org/bot438238300:AAEdydlMXwY81qXWZb4njw7YLhquOeKx0sg/getUpdates?offset=-1
// https://tepa.ga/AAEdydlMXwY81qXWZb4njw7YLhquOeKx0sg
// dalfincmm
// 191674575895794
// TDHhRFHT0INFHG8jOkpWfB0Osow
// CLOUDINARY_URL=cloudinary://191674575895794:TDHhRFHT0INFHG8jOkpWfB0Osow@dalfincmm
//file_get_contents("http://api.telegram.org/bot438238300:AAEdydlMXwY81qXWZb4njw7YLhquOeKx0sg/sendMessage?chat_id=108268232&text=asd");
require 'datebase.php';
require 'telegrambot.php';
require 'buttons.php';
//anderson a43jh5fA85y46g
//header('Content-Type: application/json');
date_default_timezone_set('Asia/Tashkent');
mb_internal_encoding('UTF-8');
ini_set('display_errors', '1');
ini_set('error_log', './error_log');
//error_reporting(0);
//"Tizimda nosozlik! Boshqattan urinib ko'ring."
$request_time=time();
function dump($e) {ob_start(); var_dump($e); return ob_get_clean();}
function error_handler($str,$arg) {
	global $bot,$request_time;
	foreach ($arg as $key=>$value) if(isset($arg[$key]['file'])) $arg[$key]['file']=basename($value['file']);
	error_log("(".(time()-$request_time).") ".$str."\n".json_encode($arg, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
	// try {
	// 	$bot->sendMessage(ADMIN,Telegrambot::HTML($str."\n")."<code>".json_encode($arg, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT)."</code>");
	// } catch(Exception $e) {
	// 	error_log($str."\n".json_encode($arg, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
	// }
}
set_error_handler(function($errno,$errstr,$errfile,$errline) {
	$temp1=debug_backtrace(); //array_shift($temp1); array_pop($temp1);
	$temp="Error: $errstr:".basename($errfile).":$errline\n";// array_shift($temp1);
 	error_handler($temp,$temp1);
 	return true;
 });
set_exception_handler(function($e) {
	$temp1=$e->getTrace(); //array_shift($temp1);
	$temp="Exception: ".$e->getMessage().":".basename($e->getFile()).":".$e->getLine()."\n";
	error_handler($temp,$temp1);
 });

$bot=new Telegrambot(TOKEN);
$db=new datebase(DB_HOST,DB_USER,DB_PASS,DB_NAME);

$input=json_decode(file_get_contents('php://input'));

if(isset($input->message)) {
	$message=&$input->message;
	$from=&$message->from;
	$user=$db->select()->from('users')->where('id='.$from->id)->fetch();
	if($message->chat->type!='private') {
		include "commands.php";
	} elseif(isset($message->text) and substr($message->text,0,6)=='/start') {
		include "start.php";
	} elseif($user->valid()) {
		$user=$user->current();
		if($user->blocked=='1') $db->query('update users set blocked=NULL where id='.$from->id)->exec();
		$action=explode(':',$user->action);
		if(isset($message->text)) {
			include "message.php";
		} elseif(isset($message->photo)) {
			if($action[0]==5) {
				try {
					$temp=0; $temp1=0;
					foreach ($message->photo as $key=>$value) if($value->width>$temp1) {$temp=$key; $temp1=$value->width;}
					$temp=uploads($message->photo[$temp]->file_id);
					$db->update('users')->set(['action'=>'6:'.$action[1]])->where('id='.$from->id)->exec();
					$db->update('channels')->set(['cover'=>$temp->data->img_url,'thumb'=>$temp->data->thumb_url])->where('id='.$action[1])->exec();
					$temp=$db->select()->from('channels')->where('id='.$action[1])->fetch()->current();
					$bot->sendMessage($from->id,T_6."\n".CHANNEL_POST($temp),$bot->replyKeyboard([[B_6],[B_BACK]]));
				} catch(Exception $e) {
					$bot->sendMessage($from->id,"tizimida xatolik, iltimos bu haqida administratorga habar bering");
					throw new Exception($e->getMessage());
				}
			}
		}
	} else {
		$db->insert()->into('users')->set(['id'=>$message->from->id,'action'=>0,'ball'=>0])->exec();
		$bot->sendMessage($message->from->id,T_WELCOM($from->first_name),$bot->replyKeyboard($MAIN_KEYBOARD));
	}
} elseif(isset($input->callback_query)) {
	if(isset($input->callback_query->data)) {
		include "callback.php";
	}
} elseif(isset($input->cron)) {
	if($input->cron=='1') { // 24:00
		$ad_channels=[];
		foreach ($db->select()->from('ad_channels') as $value) $ad_channels[]=$value->channel;
		foreach ($db->select()->from('followers')->fetch() as $value) if(in_array($value->channel, $ad_channels)) {
			$temp=@$bot->method('getChatMember',['chat_id'=>$value->channel,'user_id'=>$value->user]);
			if($temp->ok and !in_array($temp->result->status,['member','creator','administrator'])) {
				$db->query('update users set ball=ball-'.JOIN_BALL.' where id='.$value->user)->exec();
				$db->delete()->from('followers')->where('user='.$value->user)->exec();
			}
		}
	} elseif($input->cron=='2') { // 21:00
		set_time_limit(0);
		ignore_user_abort(true);
		$temp=$db->query('select * from lottery order by id desc limit 1')->fetch();
		if($temp->valid()) {
			$all=$temp->current()->id;
			$winner_id=rand(0,$all);
			$temp=$db->select()->from('lottery')->where('id='.$winner_id)->fetch();
			if(!$temp->valid()) throw new Exception("Lotariyada tizimida xatolik");
			$temp=$temp->current();
			$winner=@$bot->method('getChat',['chat_id'=>$temp->user]);
			if($winner->ok) $winner=$winner->result->first_name;
			else $winner="Shu odam";
			$wtext="<i>Bugungi</i> <b>LOTOREYA</b> <i>o'ynimiz g'olibi</i> ".'<a href="tg://user?id='.$temp->user.'">'.$winner.'</a>'." <i>bo'ldi.</i>\n<b>G'olibimizni 🔹333 ochko yutib olganligi bilan tabriklaymiz👏👏</b>👏👏\n<i>Lotoreyada ".$all." ta odam qatnashdi</i>";
			$db->insert()->into('lottery_winners')->set(['date'=>time(),'user'=>$temp->user,'name'=>$winner])->exec();
			$bot->sendMessage(CHANNEL,$wtext);
			$db->query('update users set ball=ball+333 where id='.$temp->user)->exec();
			$i=0;
			$ids=[];
			foreach ($db->select()->from('lottery')->fetch() as $value) $ids[]=$value->user;
			$db->delete()->from('lottery')->exec();
			foreach ($ids as $value) {
				try {
					$bot->sendMessage($value,$wtext);
					$i++;
				} catch(Exception $e) {
					error_log($e->getMessage());
				}
			}
			$bot->sendMessage(ADMINS_GROUP,"Lotareya jonatildi: $i");
		} else {
			$bot->sendMessage(ADMINS_GROUP,'Lotariyada hechkim qatnashmadi.');
		}
	} elseif($input->cron=='3') { // 9:00
		if(!isset($input->worker)) $input->worker=0;
		if($input->worker==0) {
			$bot->sendMessage(CHANNEL,T_LOTTERY,$bot->inlineKeyboard([[['text'=>'Qatnashish','url'=>'https://telegram.me/CatalogiyaBot?start=3']]]));
		}
	} elseif($input->cron=='4') {
		set_time_limit(0);
		ignore_user_abort(true);
		$temp=$db->query('select * from jakpot_games order by id desc limit 1')->fetch();
		if($temp->valid()) $id=$temp->current()->id+1; else $id=1;
		$r=rand(1,30);
		$db->insert()->into('jakpot_games')->set(['id'=>$id,'number'=>$r,'time'=>time()])->exec();
		$winners=[];
		foreach ($db->select()->from('jakpot')->where('number='.$r)->fetch() as $value) $winners[]=$value->user;
		$text="🔘O'yin $id\n🔢Son $r\n";
		if(empty($winners)) {
			$text.="G'oliblar yo'q";
		} else {
			$p=round(500/count($winners));
			foreach ($winners as $value) {
				$db->query('update users set ball=ball+'.$p.' where id='.$value)->exec();
				try {
					$temp=@$bot->method("getChat",["chat_id"=>$value]);
					$temp=$temp->result->first_name;
				} catch(Exception $e) {
					$temp="Foydalanuvchi";
				}
				$text.='🎗G\'olib <a href="tg://user?id='.$value.'">'.Telegrambot::HTML($temp).'</a>'."\n";
				$db->insert()->into('jakpot_winners')->set(['id'=>$id,'name'=>$temp,'user'=>$value])->exec();
			}
			$text.="G'olib(lar)imizni qimmatli ochkolarga ega bo'lganligi bilan tabriklaymiz👏👏\nO'yinda ".($db->query('select count(*) as c from jakpot')->fetch()->current()->c)." odam qatnashdi";
		}
		$i=0;
		$ids=[];
		foreach ($db->select()->from('jakpot')->fetch() as $value) $ids[]=$value->user;
		$db->delete()->from('jakpot')->exec();
		foreach ($ids as $value) {
			try {
				$bot->sendMessage($value,$text);
				$i++;
			} catch(Exception $e) {
				error_log($e->getMessage());
			}
		}
		$bot->sendMessage(ADMINS_GROUP,"jakpot jonatildi: $i");
		$bot->sendMessage(ADMINS_GROUP,$text);
	} elseif($input->cron=='5') { // channel default
		$temp=$db->query('select * from `channels_history` where status=1')->fetch();
		if($temp->valid()) {
			$temp=$temp->current();
			if($temp->type=='1' or $temp->type=='0') {
				$db->update('channels_history')->set(['status'=>0])->where('id='.$temp->id)->exec();
				$temp=$db->select()->from('channels_history')->where('status=2 and type!=3')->fetch();
				if($temp->valid()) {
					$temp=$temp->current();
					$temp1=$db->select()->from('channels')->where('id='.$temp->channel)->fetch()->current();
					$bot->sendMessage(CHANNEL,CHANNEL_POST($temp1),$bot->inlineKeyboard([[$bot->inlineKeyboardButton(B_LIKE,'0:'.$temp1->id.':0')],[['text'=>B_SUBSCRIBE,'url'=>'https://telegram.me/'.substr($temp1->username,1)]]]));
					$db->update('channels_history')->set(['status'=>1])->where('id='.$temp->id)->exec();
				}
			} elseif($temp->type=='2') {
				if(isset($temp->cs) and $temp->cs=='1') {
					$db->update('channels_history')->set(['status'=>0])->where('id='.$temp->id)->exec();
					$temp=$db->select()->from('channels_history')->where('status=2 and type!=3')->fetch();
					if($temp->valid()) {
						$temp=$temp->current();
						$temp1=$db->select()->from('channels')->where('id='.$temp->channel)->fetch()->current();
						$bot->sendMessage(CHANNEL,CHANNEL_POST($temp1),$bot->inlineKeyboard([[$bot->inlineKeyboardButton(B_LIKE,'0:'.$temp1->id.':0')],[['text'=>B_SUBSCRIBE,'url'=>'https://telegram.me/'.substr($temp1->username,1)]]]));
						$db->update('channels_history')->set(['status'=>1])->where('id='.$temp->id)->exec();
					}
				} else {
					$db->update('channels_history')->set(['cs'=>'1'])->where('id='.$temp->id)->exec();
				}
			} elseif($temp->type=='3') {
				$db->update('channels_history')->set(['status'=>0])->where('id='.$temp->id)->exec();
				$temp=$db->select()->from('channels_history')->where('status=2 and type!=3')->fetch();
				if($temp->valid()) {
					$temp=$temp->current();
					$temp1=$db->select()->from('channels')->where('id='.$temp->channel)->fetch()->current();
					$bot->sendMessage(CHANNEL,CHANNEL_POST($temp1),$bot->inlineKeyboard([[$bot->inlineKeyboardButton(B_LIKE,'0:'.$temp1->id.':0')],[['text'=>B_SUBSCRIBE,'url'=>'https://telegram.me/'.substr($temp1->username,1)]]]));
					$db->update('channels_history')->set(['status'=>1])->where('id='.$temp->id)->exec();
				}
			}
		} else {
			$temp=$db->select()->from('channels_history')->where('status=2 and type!=3')->fetch();
			if($temp->valid()) {
				$temp=$temp->current();
				$temp1=$db->select()->from('channels')->where('id='.$temp->channel)->fetch()->current();
				$bot->sendMessage(CHANNEL,CHANNEL_POST($temp1),$bot->inlineKeyboard([[$bot->inlineKeyboardButton(B_LIKE,'0:'.$temp1->id.':0')],[['text'=>B_SUBSCRIBE,'url'=>'https://telegram.me/'.substr($temp1->username,1)]]]));
				$db->update('channels_history')->set(['status'=>1])->where('id='.$temp->id)->exec();
			}
		}
	} elseif($input->cron=='6') { // channel night
		$temp=$db->query('select * from `channels_history` where type=3 and status=2')->fetch();
		if($temp->valid()) {
			$temp=$temp->current();
			$temp1=$db->select()->from('channels')->where('id='.$temp->channel)->fetch()->current();
			$bot->sendMessage(CHANNEL,CHANNEL_POST($temp1),$bot->inlineKeyboard([[$bot->inlineKeyboardButton(B_LIKE,'0:'.$temp1->id.':0')],[['text'=>B_SUBSCRIBE,'url'=>'https://telegram.me/'.substr($temp1->username,1)]]]));
			$db->update('channels_history')->set(['status'=>1])->where('id='.$temp->id)->exec();
		}
	} elseif($input->cron=='7') {//1514989749
		set_time_limit(0);
		ignore_user_abort(true);
		$text="🌀 @YIAMEGA ning 🔹@Catalogiya kanalida o'tkazilgan
 ENG ENG AKSIYASI 
o'z nihoyasiga yetdi

Aksiya ikkita nominatsiya bo'yicha o'tkazildi

🔺Eng ko'p LIKE yig'gan kanal
<a href=\"https://t.me/CaTaloGiya/549\">🎗G'oliblarni ko'rish</a>
🔻Eng ko'p OCHKO to'plagan obunachi
<a href=\"https://t.me/CaTaloGiya/824\">🎗G'oliblarni ko'rish</a>

Aksiyada qatnashganlarga, aksiyani o'tkazishda yordam berganlarga hamda
 🌀 @YIAMEGA ning barcha a'zolariga o'z minnatdorchiligimizni bildiramiz👍

⚠️Qatnashib yuta olmaganlar aslo xafa bo'lmang 😁
Hali hammasi oldinda😎

🌀 @YIAMEGA 2017-2018";
		$temp=$db->query('SELECT * FROM users where (LSD<1514989749 OR LSD is NULL) AND blocked is NULL LIMIT 100')->fetch();
		$query="";
		foreach ($temp as $value) {
			$query.="UPDATE users SET LSD=".time()." WHERE id=".$value->id."; ";
			//$db->update('users')->set(['LSD'=>time()])->where('id='.$temp->current()->id)->exec();
			try {
				$bot->sendMessage($value->id,$text);
				$i++;
			} catch(Exception $e) {
				error_log($e->getMessage());
			}
		}
		$db->multiQuery($query);
		$bot->sendMessage(ADMINS_GROUP,"100 ta dan $i ta");
	}
} elseif(isset($input->inline_query)) {
	$inline=&$input->inline_query;
	$from=&$inline->from;
	if($inline->query=="0") {
		$bot->answerInlineQuery($inline->id,[['type'=>'article','id'=>'0','title'=>'Havolani tarqatish','description'=>'','input_message_content'=>['message_text'=>T_SUPPORT($from),'parse_mode'=>'HTML'],'reply_markup'=>['inline_keyboard'=>[[['text'=>"Do'stni qo'llab-quvvatlash",'url'=>'https://telegram.me/'.BOT_USERNEME.'?start=0-'.$from->id]]]]]],['is_personal'=>true]);
	} elseif($inline->query=="1") {
		// shu yerga cheklashni kiritish kerak
		$bot->answerInlineQuery($inline->id,[['type'=>'article','id'=>'0','title'=>'Ochko ulashish','description'=>'','input_message_content'=>['message_text'=>T_SUPPORT_2($from),'parse_mode'=>'HTML'],'reply_markup'=>['inline_keyboard'=>[[['text'=>"Ochkoni qabul qilish",'callback_data'=>'13:'.$from->id]]]]]],['is_personal'=>true]);
	} elseif(preg_match('/^\d+$/', $inline->query)) {
		$temp=$db->select()->from('channels')->where('id='.$inline->query.' AND status=2')->fetch();
		if($temp->valid()) {
			$temp=$temp->current();
			$bot->answerInlineQuery($inline->id,[['type'=>'article','id'=>$temp->id,'title'=>$temp->title,'thumb_url'=>$temp->thumb,'description'=>$temp->description,'input_message_content'=>['message_text'=>T_INLINE($temp),'parse_mode'=>'HTML'],'reply_markup'=>['inline_keyboard'=>[[['text'=>B_LIKE,'url'=>'https://telegram.me/'.BOT_USERNEME.'?start=1-'.$temp->id]]]]]]);
		}
	}
}
echo '{"ok":true}';
//test for git