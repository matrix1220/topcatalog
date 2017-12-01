<?php
// 438238300:AAEdydlMXwY81qXWZb4njw7YLhquOeKx0sg
// https://api.telegram.org/bot438238300:AAEdydlMXwY81qXWZb4njw7YLhquOeKx0sg/setWebhook?url=https://katalogiya.proboys.uz/topcatalog/AAEdydlMXwY81qXWZb4njw7YLhquOeKx0sg&max_connections=10
// https://katalogiya.proboys.uz/topcatalog/AAEdydlMXwY81qXWZb4njw7YLhquOeKx0sg
// dalfincmm
// 191674575895794
// TDHhRFHT0INFHG8jOkpWfB0Osow
// CLOUDINARY_URL=cloudinary://191674575895794:TDHhRFHT0INFHG8jOkpWfB0Osow@dalfincmm

require 'datebase.php';
require 'telegrambot.php';
require 'buttons.php';

header('Content-Type: application/json');
date_default_timezone_set('Asia/Tashkent');
//"Tizimda nosozlik! Boshqattan urinib ko'ring."
function dump($e) {ob_start(); var_dump($e); return ob_get_clean();}
set_error_handler(function($errno,$errstr,$errfile,$errline) {
	global $bot;
	$temp1=debug_backtrace(); array_shift($temp1); array_pop($temp1);
	$temp="Error: $errstr:".basename($errfile).":$errline\n";// array_shift($temp1);
	$temp.="\nDebug_backtrace:\n";
	foreach ($temp1 as $value) {
		$temp.="\nfunction: ".$value['function'];
		if(isset($value['file'])) $temp.="\nfile: ".basename($value['file']);
		if(isset($value['line'])) $temp.="\nline: ".$value['line'];
		if(isset($value['args'])) $temp.="\nargs: ".dump($value['args']);
		$temp.="\n";
	}
 	$bot->sendMessage(ADMIN,Telegrambot::HTML($temp));
 	return true;
 });
set_exception_handler(function($e) {
	global $bot;
	$temp1=$e->getTrace(); array_shift($temp1); $temp="Exception: ".$e->getMessage().":".basename($e->getFile()).":".$e->getLine()."\n";
	$temp.="\nDebug_backtrace:\n";
	foreach ($temp1 as $value) {
		$temp.="\nfunction: ".$value['function'];
		if(isset($value['file'])) $temp.="\nfile: ".basename($value['file']);
		if(isset($value['line'])) $temp.="\nline: ".$value['line'];
		if(isset($value['args'])) $temp.="\nargs: ".dump($value['args']);
		$temp.="\n";
	}
	$bot->sendMessage(ADMIN,Telegrambot::HTML($temp));
 });

$bot=new Telegrambot(TOKEN);
$db=new datebase('localhost','kripton','N80nvDIFwswCYPvv','topcatalog');

$input=json_decode(file_get_contents('php://input'));

if(isset($input->message)) {
	$message=&$input->message;
	$from=&$message->from;
	$user=$db->select()->from('users')->where('id='.$from->id)->fetch();
	if($message->chat->type!='private') {
		if($message->chat->id!=ADMINS_GROUP) {
			$bot->method('leaveChat',['chat_id'=>$message->chat->id]);
		} elseif($message->text=="/top_10_channels") {
			$user->valid();
			$temp="Top 10 ta kanal:";
			$temp1=$db->query('select likes,username,title from channels order by likes desc limit 10')->fetch();
			foreach ($temp1 as $value) {
				$temp.="\n".$value->username." ".$value->likes." ta like";
			}
			$bot->sendMessage(ADMINS_GROUP,$temp);
		} elseif($message->text=="/top_50_channels") {
			$user->valid();
			$temp="Top 50 ta kanal:";
			$temp1=$db->query('select likes,username,title from channels order by likes desc limit 50')->fetch();
			foreach ($temp1 as $value) {
				$temp.="\n".$value->username." ".$value->likes." ta like";
			}
			$bot->sendMessage(ADMINS_GROUP,$temp);
		} elseif($message->text=="/statistics") {
			$user->valid();
			$temp="Foydalanuvchilar soni:".$db->query('select count(*) as c from users')->fetch()->current()->c;
			$bot->sendMessage(ADMINS_GROUP,$temp);
		} elseif($message->text=="/ad_channels") {
			$user->valid();
			$temp="Botdan kanalga o'tgan obunachilar:";
			$temp1=$db->query('select count(user) as u,channel as ch from followers group by ch order by u desc')->fetch();
			foreach ($temp1 as $value) {
				$temp.="\n".$value->ch." ga ".$value->u." ta obunachi";
			}
			$bot->sendMessage(ADMINS_GROUP,$temp);
		} elseif($message->text=="/top_users") {
			$user->valid();
			$temp="Top 10 ta Foydalanuvchilar:";
			$temp1=$db->query('select id,ball from users order by ball desc limit 10')->fetch();
			foreach ($temp1 as $value) {
				$temp.="\n".'<a href="tg://user?id='.$value->id.'">Foydalanuvchi</a> '.$value->ball." ochko";
			}
			$bot->sendMessage(ADMINS_GROUP,$temp);
		} elseif($message->text=="/top_users_with_names") {
			$user->valid();
			$temp="Top 10 ta Foydalanuvchilar:";
			$temp1=$db->query('select id,ball from users order by ball desc limit 10')->fetch();
			foreach ($temp1 as $value) {
				$temp.="\n".'<a href="tg://user?id='.$value->id.'">'.$bot->method('getChat',['chat_id'=>$value->id])->result->first_name.'</a> '.$value->ball." ochko";
			}
			$bot->sendMessage(ADMINS_GROUP,$temp);
		} elseif($message->text=="/top_admins") {
			$user->valid();
			$temp="Top 10 ta Admin:";
			$temp1=$db->query('select count(likes.user) as c,channels.username as ch,channels.added as ad from likes inner join channels on channels.id=likes.channel group by likes.channel order by c desc limit 10')->fetch();
			foreach ($temp1 as $value) {
				$temp.="\n".'<a href="tg://user?id='.$value->ad.'">Admin</a> ('.$value->ch.') '.$value->c." ta like";
			}
			$bot->sendMessage(ADMINS_GROUP,$temp);
		} elseif($message->text=="/test_exception") {
			throw new Exception("Error Processing Request");
		} elseif($message->text=="/test_time") {
			$user->valid();
			header("Content-Length: 0", true);
			header("Connection: close",true);
			flush();
			set_time_limit(0);
			ignore_user_abort(true);
			$i=0;
			while (true) {
				$bot->sendMessage(ADMINS_GROUP,($i*20).'-sekund, connection_status:'.connection_status());
				if($i==20) break;
				$i++;
				sleep(20);
			}
		} elseif($message->text=="/current_time") {
			$bot->sendMessage(ADMINS_GROUP,date("H:i:s"));
		}
	} elseif(isset($message->text) and substr($message->text,0,6)=='/start') {
		if($user->valid()) {
			$temp=explode(':',$user->current()->action);
			if(in_array($temp[0], ['2','3','4','5','6'])) {
				$db->query('update users set ball=ball+'.ADD_BALL.' where id='.$from->id)->exec();
				if($temp[0]!='2') $db->update('channels')->set(['status'=>4])->where('id='.$db->escape($temp[1]))->exec();
			}
		}
		if(strlen($message->text)>7) {
			$temp=explode('-',substr($message->text,7));
			if(count($temp)==2 and $temp[0]=='0') {
				if($user->valid()) {
					$db->update('users')->set(['action'=>0])->where('id='.$from->id)->exec();
				} else {
					$db->insert()->into('users')->set(['id'=>$from->id,'action'=>0,'ball'=>0])->exec();
					$user=$db->select()->from('users')->where('id='.$db->escape($temp[1]))->fetch();
					if($user->valid()) {
						$user=$user->current();
						$db->update('users')->set(['ball'=>$user->ball+INVITE_BALL])->where('id='.$user->id)->exec();
					}
				}
				$bot->sendMessage($from->id,T_WELCOM($from->first_name),$bot->replyKeyboard($MAIN_KEYBOARD));
				$bot->method('sendPhoto',['chat_id'=>$from->id,"photo"=>"AgADAgADlqgxG-hIaUj1rSSHh4EsAzPYDw4ABCjTs5rypO-hk_0CAAEC"]);
			} elseif(count($temp)==2 and $temp[0]=='1') {
				$channel=$db->select()->from('channels')->where('id='.$db->escape($temp[1]))->fetch();
				if($user->valid() and !$channel->valid()) {
					$db->update('users')->set(['action'=>0])->where('id='.$from->id)->exec();
					$bot->sendMessage($from->id,T_WELCOM($from->first_name),$bot->replyKeyboard($MAIN_KEYBOARD));
				} elseif(!$user->valid() and !$channel->valid()) {
					$db->insert()->into('users')->set(['id'=>$from->id,'action'=>0,'ball'=>0])->exec();
					$bot->sendMessage($from->id,T_WELCOM($from->first_name),$bot->replyKeyboard($MAIN_KEYBOARD));
				} elseif (!$user->valid() and $channel->valid()) {
					$db->insert()->into('users')->set(['id'=>$from->id,'action'=>'8:'.$temp[1],'ball'=>0])->exec();
					$bot->sendMessage($from->id,T_WELCOM($from->first_name));
					$bot->sendMessage($from->id,CHANNEL_POST($channel->current()),$bot->replyKeyboard([[B_LIKE],[B_BACK]]));
				} elseif ($user->valid() and $channel->valid()) {
					$db->update('users')->set(['action'=>'8:'.$temp[1]])->where('id='.$from->id)->exec();
					$bot->sendMessage($from->id,CHANNEL_POST($channel->current()),$bot->replyKeyboard([[B_LIKE],[B_BACK]]));
				}
			} elseif(count($temp)==2 and $temp[0]=='2') {
				if(@$bot->method('getChatMember',['chat_id'=>ADMINS_GROUP,'user_id'=>$from->id])->ok) {
					$temp1=$db->select()->from('questions')->where('from_id='.$temp[1])->fetch();
					if($temp1->valid()) {
						$temp1=$temp1->current();
						$db->update('users')->set(['action'=>'10:'.$temp[1]])->where('id='.$from->id)->exec();
						$bot->sendMessage($from->id,'<a href="tg://user?id='.$temp[1].'">Foydalanuvchi</a>'."ning savoliga javob bering:<i>\n".Telegrambot::HTML($temp1->question)."</i>",$bot->replyKeyboard([[B_BACK]]));
					} else {
						$db->update('users')->set(['action'=>0])->where('id='.$from->id)->exec();
						$bot->sendMessage($from->id,T_0,$bot->replyKeyboard($MAIN_KEYBOARD));
					}
				} else {
					$db->update('users')->set(['action'=>0])->where('id='.$from->id)->exec();
					$bot->sendMessage($from->id,T_0,$bot->replyKeyboard($MAIN_KEYBOARD));
				}
			} elseif(count($temp)==1 and $temp[0]=='3') {
				if($user->valid()) {
					$db->update('users')->set(['action'=>0])->where('id='.$from->id)->exec();
				} else {
					$db->insert()->into('users')->set(['id'=>$from->id,'action'=>0,'ball'=>0])->exec();
				}
				$bot->sendMessage($from->id,T_WELCOM($from->first_name),$bot->replyKeyboard($MAIN_KEYBOARD));
				$bot->sendMessage($from->id,T_LOTTERY,$bot->inlineKeyboard([[$bot->inlineKeyboardButton('Qatnashish','6')]]));
			} elseif(count($temp)==2 and $temp[0]=='4') {
				$user->valid();
				if(@$bot->method('getChatMember',['chat_id'=>ADMINS_GROUP,'user_id'=>$from->id])->ok) {
					$temp1=$db->select()->from('channels')->where('id='.$temp[1].' and status=1')->fetch();
					if($temp1->valid()) {
						$temp1=$temp1->current();
						$db->update('users')->set(['action'=>'11:'.$temp[1]])->where('id='.$from->id)->exec();
						$bot->sendMessage($from->id,$temp1->username." kanali admininig ochkosi qaytarib berilsinmi?",$bot->replyKeyboard([[B_YES],[B_NO]]));
					} else {
						$db->update('users')->set(['action'=>0])->where('id='.$from->id)->exec();
						$bot->sendMessage($from->id,T_0,$bot->replyKeyboard($MAIN_KEYBOARD));
					}
				} else {
					$db->update('users')->set(['action'=>0])->where('id='.$from->id)->exec();
					$bot->sendMessage($from->id,T_0,$bot->replyKeyboard($MAIN_KEYBOARD));
				}
			} 
		} else {
			if($user->valid()) {
				$db->update('users')->set(['action'=>0])->where('id='.$from->id)->exec();
			} else {
				$db->insert()->into('users')->set(['id'=>$from->id,'action'=>0,'ball'=>0])->exec();
			}
			$bot->sendMessage($from->id,T_WELCOM($from->first_name),$bot->replyKeyboard($MAIN_KEYBOARD));
			$bot->method('sendPhoto',['chat_id'=>$from->id,"photo"=>"AgADAgADlqgxG-hIaUj1rSSHh4EsAzPYDw4ABCjTs5rypO-hk_0CAAEC"]);
		}
	} elseif($user->valid()) {
		$user=$user->current();
		$action=explode(':',$user->action);
		if(isset($message->text)) {
			if($action[0]==0) {
				if($message->text==B_CATEGORYS) {
					//$db->update('users')->set(['action'=>2])->where('id='.$from->id)->exec();
					$bot->sendMessage($from->id,'Tanlang:',$bot->inlineKeyboard($CATEGORYS_INLINEKEYBOARD));
					$bot->sendMessage($from->id,'Katalogni tanlang!');
				} elseif($message->text==B_ADD) {
					$temp=@$bot->method('getChatMember',['chat_id'=>CHANNEL,'user_id'=>$from->id]);
					if($temp->ok and in_array($temp->result->status,['member','creator','administrator'])) {
						if($db->select()->from('users')->where('id='.$from->id)->fetch()->current()->ball>=ADD_BALL) {
							$db->update('users')->set(['action'=>1])->where('id='.$from->id)->exec();
							$bot->sendMessage($from->id,T_1,$bot->replyKeyboard([[B_YES],[B_BACK]]));
						} else {
							$bot->sendMessage($from->id,T_1E_1,$bot->inlineKeyboard([[$bot->inlineKeyboardButton(B_COLLECT,'7:4')]]));
						}
					} else {
						$bot->sendMessage($from->id,T_1E_2);
					}
				} elseif($message->text==B_ACCOUNT) {
					$temp1=[];
					$temp2=0;
					foreach ($db->select()->from('channels')->where('added='.$from->id.' and status=2')->fetch() as $value) {
						$temp1[]=[urlencode(substr($value->username,1)),Telegrambot::HTML($value->title),$value->likes,$value->id];
						$temp2++;
					}
					$bot->sendMessage($from->id,T_ACCOUNT($temp2,$db->select()->from('users')->where('id='.$from->id)->fetch()->current()->ball,$db->select()->from('lottery')->where('user='.$from->id)->fetch()->valid(),$temp1),$bot->inlineKeyboard([[$bot->inlineKeyboardButton(B_COLLECT,'7:4')]])+['disable_web_page_preview'=>true]);
				} elseif($message->text==B_EXTRA) {
					$db->update('users')->set(['action'=>13])->where('id='.$from->id)->exec();
					$bot->sendMessage($from->id,B_EXTRA,$bot->replyKeyboard($EXTRA_KEYBOARD));
				} elseif($message->text==B_LOTTERY) {
					$bot->sendMessage($from->id,T_LOTTERY,$bot->inlineKeyboard([[$bot->inlineKeyboardButton('Qatnashish','6')]]));
				} elseif($message->text==B_THE) {
					//$db->update('users')->set(['action'=>9])->where('id='.$from->id)->exec();
					$bot->sendMessage($from->id,T_THE,$bot->inlineKeyboard([[$bot->inlineKeyboardButton(B_THE_CHANNEL,'7:1')],[$bot->inlineKeyboardButton(B_THE_USER,'7:2')]]));
				} elseif($message->text==B_LUCK) {
					$bot->sendMessage($from->id,T_LUCK,$bot->inlineKeyboard([[$bot->inlineKeyboardButton('Omad','5')]]));
				} else {
					$bot->sendMessage($from->id,"Tanlang:",$bot->replyKeyboard($MAIN_KEYBOARD));
				}
			} elseif($action[0]==13) {
				if($message->text==B_CONTACT) {
					$db->update('users')->set(['action'=>9])->where('id='.$from->id)->exec();
					$bot->sendMessage($from->id,T_CONTACT_1,$bot->replyKeyboard([[B_BACK]]));
				} elseif($message->text==B_ABOUT) {
					$bot->sendMessage($from->id,T_ABOUT);
				} elseif($message->text==B_BACK) {
					$db->update('users')->set(['action'=>0])->where('id='.$from->id)->exec();
					$bot->sendMessage($from->id,T_0,$bot->replyKeyboard($MAIN_KEYBOARD));
				} elseif($message->text==B_LN) {
					$bot->sendMessage($from->id,T_15,$bot->inlineKeyboard([[$bot->inlineKeyboardButton("Son tanlash","10")]]));
				} else {
					$bot->sendMessage($from->id,"Tanlang:",$bot->replyKeyboard($EXTRA_KEYBOARD));
				}
			} elseif($action[0]==1) {
				if($message->text==B_BACK) {
					$db->update('users')->set(['action'=>0])->where('id='.$from->id)->exec();
					$bot->sendMessage($from->id,T_0,$bot->replyKeyboard($MAIN_KEYBOARD));
				} elseif($message->text==B_YES) {
					$db->query('update users set action=2,ball=ball-'.ADD_BALL.' where id='.$from->id)->exec();
					$bot->sendMessage($from->id,T_2,$bot->replyKeyboard([[B_BACK]]));
				} else {
					$bot->sendMessage($from->id,"Noto'g'ri tanlandi",$bot->replyKeyboard([[B_YES],[B_BACK]]));
				}
			} elseif($action[0]==2) {
				if($message->text==B_BACK) {
					$db->query('update users set action=0,ball=ball+'.ADD_BALL.' where id='.$from->id)->exec();
					$bot->sendMessage($message->from->id,T_0,$bot->replyKeyboard($MAIN_KEYBOARD));
				} else {
                    try {
                        $temp = $bot->method('getChat', ['chat_id' => $message->text]);
                    } catch(Exception $e) {
                        $temp=false;
                    }
					if($temp and $temp->ok) {
						$temp1=$db->select()->from('channels')->where('telegram_id='.$db->escape($temp->result->id))->fetch();
						if($temp1->valid()) {
							$temp1=$temp1->current();
							if(in_array($temp1->status,['2','1'])) {
								if($temp1->added==$from->id) {
									$bot->sendMessage($from->id,T_2E_2,$bot->inlineKeyboard([[$bot->inlineKeyboardButton("Qayta qo'shish",'9:'.$temp1->username.":".$temp1->id)]]));
								} else {
									$bot->sendMessage($from->id,T_2E_2);
								}
							} elseif($temp1->status==0) {
								$bot->sendMessage($from->id,T_2E_3);
							} else {
								$temp1=$temp1->id;
								$db->update('channels')->set(['added'=>$from->id,'username'=>$message->text,'status'=>0,'telegram_id'=>$temp->result->id,'title'=>$temp->result->title])->where('id='.$temp1)->exec();
								$db->update('users')->set(['action'=>'3:'.$temp1])->where('id='.$from->id)->exec();
								$bot->sendMessage($from->id,T_3,$bot->replyKeyboard($CATEGORYS_KEYBOARD));
							}
						} else {
							$temp1=$db->query('select id from channels order by id desc limit 1')->fetch();
							if($temp1->valid()) $temp1=$temp1->current()->id+1;
							else $temp1=1;
							$db->insert()->into('channels')->set(['id'=>$temp1,'added'=>$from->id,'username'=>$message->text,'status'=>0,'telegram_id'=>$temp->result->id,'title'=>$temp->result->title])->exec();
							$db->update('users')->set(['action'=>'3:'.$temp1])->where('id='.$from->id)->exec();
							$bot->sendMessage($from->id,T_3,$bot->replyKeyboard($CATEGORYS_KEYBOARD));
						}
					} else {
						$bot->sendMessage($from->id,T_2E_1);
					}
				}
			} elseif($action[0]==3) {
				if($message->text==B_BACK) {
					$db->update('users')->set(['action'=>'2'])->where('id='.$from->id)->exec();
					$db->update('channels')->set(['status'=>4])->where('id='.$action[1])->exec();
					$bot->sendMessage($from->id,T_2,$bot->replyKeyboard([[B_BACK]]));
				} else {
					foreach ($CATEGORYS as $key => $value) if($message->text==$value) {$value=true;break;}
					if($value===true) {
							$db->update('users')->set(['action'=>'4:'.$action[1]])->where('id='.$from->id)->exec();
							$db->update('channels')->set(['category'=>$key])->where('id='.$action[1])->exec();
							$temp2=T_4;
							$temp=$db->select()->from('channels')->where('id='.$action[1])->fetch();
							if($temp->valid()) {
								$temp1=@$bot->method('getChat',['chat_id'=>$temp->current()->username]);
								if($temp1->ok and isset($temp1->result->description)) $temp2.="\n\n<code>".substr($temp1->result->description,0,150)."</code>"; 
							}
							$bot->sendMessage($from->id,$temp2,$bot->replyKeyboard([[B_BACK]]));
					} else {
						$bot->sendMessage($from->id,T_3E);
					}
				}
			} elseif($action[0]==4) {
				if($message->text==B_BACK) {
					$db->update('users')->set(['action'=>'3:'.$action[1]])->where('id='.$from->id)->exec();
					$bot->sendMessage($from->id,T_3,$bot->replyKeyboard($CATEGORYS_KEYBOARD));
				} else {
					if(strlen($message->text)<512 and strlen($message->text)>12) {
							$db->update('users')->set(['action'=>'5:'.$action[1]])->where('id='.$from->id)->exec();
							$db->update('channels')->set(['description'=>$message->text])->where('id='.$action[1])->exec();
							$temp2=[[B_BACK]];
							$temp=$db->select()->from('channels')->where('id='.$action[1])->fetch();
							if($temp->valid()) {
								$temp1=@$bot->method('getChat',['chat_id'=>$temp->current()->username]);
								if($temp1->ok and isset($temp1->result->photo)) $temp2=[[B_LOGO],[B_BACK]]; 
							}
							$bot->sendMessage($from->id,T_5,$bot->replyKeyboard($temp2));
					} else {
						$bot->sendMessage($from->id,T_4E);
					}
				}
			} elseif($action[0]==5) {
				if($message->text==B_BACK) {
					$db->update('users')->set(['action'=>'4:'.$action[1]])->where('id='.$from->id)->exec();
					$bot->sendMessage($from->id,T_4,$bot->replyKeyboard([[B_BACK]]));
				} elseif($message->text==B_LOGO) {
					$temp2=false;
					$temp=$db->select()->from('channels')->where('id='.$action[1])->fetch();
					if($temp->valid()) {
						$temp1=@$bot->method('getChat',['chat_id'=>$temp->current()->username]);
						if($temp1->ok and isset($temp1->result->photo)) $temp2=$temp1->result->photo->big_file_id; 
					}
					if($temp2!==false) {
						try {
							$temp=@$bot->method('getFile',['file_id'=>$temp2]);
							$temp=file_get_contents('http://uploads.im/api?upload=https://api.telegram.org/file/bot'.TOKEN.'/'.$temp->result->file_path);
							$temp=json_decode($temp);
							if($temp->status_code!=200) throw new Exception('uploads.im:'.$temp->status_txt);
							$db->update('users')->set(['action'=>'6:'.$action[1]])->where('id='.$from->id)->exec();
							$db->update('channels')->set(['cover'=>$temp->data->img_url,'thumb'=>$temp->data->thumb_url])->where('id='.$action[1])->exec();
							$temp=$db->select()->from('channels')->where('id='.$action[1])->fetch()->current();
							$bot->sendMessage($from->id,T_6."\n".CHANNEL_POST($temp),$bot->replyKeyboard([[B_6],[B_BACK]]));
						} catch(Exception $e) {
							$bot->sendMessage($from->id,"tizimida xatolik, iltimos bu haqida administratorga habar bering");
							throw new Exception($e->getMessage());
						}
					} else {
						$bot->sendMessage($from->id,T_5E_2,$bot->replyKeyboard([[B_BACK]]));
					}
				} else {
					$bot->sendMessage($from->id,T_5E_1,$bot->replyKeyboard([[B_BACK]]));
				}
			} elseif($action[0]==6) {
				if($message->text==B_BACK) {
					$db->update('users')->set(['action'=>'5:'.$action[1]])->where('id='.$from->id)->exec();
					$bot->sendMessage($from->id,T_5,$bot->replyKeyboard([[B_BACK]]));
				} elseif($message->text==B_6) {
					$db->update('users')->set(['action'=>'0'])->where('id='.$from->id)->exec();
					$temp=$db->select()->from('channels')->where('id='.$action[1])->fetch()->current();
					$bot->sendMessage($from->id,T_6F,$bot->replyKeyboard($MAIN_KEYBOARD));
					$temp1=$bot->sendMessage(ADMINS_GROUP,T_6R($temp),$bot->inlineKeyboard([[$bot->inlineKeyboardButton('Qabul qilish','2:'.$action[1]),['text'=>'Rad etish','url'=>'https://t.me/CatalogiyaBot?start=4-'.$action[1]]]])+['parse_mode'=>'HTML']);
					$db->update('channels')->set(['status'=>1,'msg_id'=>$temp1->message_id])->where('id='.$action[1])->exec();
				} else {
					$bot->sendMessage($from->id,T_6E);
				}
			} elseif($action[0]==8) {
				if($message->text==B_LIKE) {
					$temp=$db->select()->from('likes')->where('user='.$from->id.' AND channel='.$action[1])->fetch();
					if($temp->valid()) {
						$bot->sendMessage($from->id,T_LIKE_E);
					} else {
						$db->insert()->into('likes')->set(['user'=>$from->id,'channel'=>$action[1]])->exec();
						$db->query('update channels set likes=likes+1 where id='.$db->escape($action[1]))->exec();
						$bot->sendMessage($from->id,T_LIKE);
					}
				}
				$db->update('users')->set(['action'=>0])->where('id='.$from->id)->exec();
				$bot->sendMessage($from->id,T_0,$bot->replyKeyboard($MAIN_KEYBOARD));
			} elseif($action[0]==9) {
				$db->update('users')->set(['action'=>0])->where('id='.$from->id)->exec();
				if($message->text==B_BACK) {
					$bot->sendMessage($from->id,T_0,$bot->replyKeyboard($MAIN_KEYBOARD));
				} else {
					$db->insert()->into('questions')->set(['from_id'=>$from->id,'question'=>$message->text])->exec();
					$temp=$bot->sendMessage(ADMINS_GROUP,'<a href="tg://user?id='.$from->id.'">Foydalanuvchi</a>'."dan savol qabul qilindi:\n<i>".Telegrambot::HTML($message->text)."</i>",$bot->inlineKeyboard([[['text'=>'Javob berish','url'=>'https://telegram.me/CatalogiyaBot?start=2-'.$from->id]]]));
					$db->update('questions')->set(['message_id'=>$temp->message_id])->where('from_id='.$from->id)->exec();
					$bot->sendMessage($from->id,T_CONTACT_2,$bot->replyKeyboard($MAIN_KEYBOARD));
				}
			} elseif($action[0]==10) {
				$db->update('users')->set(['action'=>0])->where('id='.$from->id)->exec();
				if($message->text==B_BACK) {
					$bot->sendMessage($from->id,T_0,$bot->replyKeyboard($MAIN_KEYBOARD));
				} else {
					$temp1=$db->select()->from('questions')->where('from_id='.$action[1])->fetch();
					if($temp1->valid()) {
						$temp1=$temp1->current();
						$bot->sendMessage($action[1],"Savolinggiz:<i>\n".Telegrambot::HTML($temp1->question)."</i>\n\nJavobi:<i>\n".Telegrambot::HTML($message->text)."</i>");
						$bot->editMessageText(ADMINS_GROUP,$temp1->message_id,'<a href="tg://user?id='.$action[1].'">Foydalanuvchi</a>'."dan savol qabul qilindi:<i>\n".Telegrambot::HTML($temp1->question)."</i>\n\n".'<a href="tg://user?id='.$from->id.'">Administrator</a> javobi:'."<i>\n".Telegrambot::HTML($message->text).'</i>');
						$bot->sendMessage($from->id,"Javob berildi",$bot->replyKeyboard($MAIN_KEYBOARD));
						$db->delete()->from('questions')->where('from_id='.$action[1])->exec();
					} else {
						$bot->sendMessage($from->id,"Bu savolning javobi berilgan",$bot->replyKeyboard($MAIN_KEYBOARD));
					}
				}
			} elseif($action[0]==11) {
				if($message->text==B_YES) {
					$db->update('users')->set(['action'=>'12:1:'.$action[1]])->where('id='.$from->id)->exec();
					$bot->sendMessage($from->id,'Sababini yozing:',$bot->replyKeyboard([[B_BACK]]));
				} elseif($message->text==B_NO) {
					$db->update('users')->set(['action'=>'12:0:'.$action[1]])->where('id='.$from->id)->exec();
					$bot->sendMessage($from->id,'Sababini yozing:',$bot->replyKeyboard([[B_BACK]]));
				} elseif($message->text==B_BACK) {
					$db->update('users')->set(['action'=>0])->where('id='.$from->id)->exec();
						$bot->sendMessage($from->id,T_0,$bot->replyKeyboard($MAIN_KEYBOARD));
				} else {
					$bot->sendMessage($from->id,'Noto\'g\'ri tanlandi');
				}
			} elseif($action[0]==12) {
				if($message->text==B_BACK) {
					$db->update('users')->set(['action'=>0])->where('id='.$from->id)->exec();
					$bot->sendMessage($from->id,T_0,$bot->replyKeyboard($MAIN_KEYBOARD));
				} else {
					$db->update('channels')->set(['status'=>3])->where('id='.$action[2])->exec();
					$temp=$db->select()->from('channels')->where('id='.$action[2])->fetch()->current();
					if($action[1]=='1') $db->query('update users set action=0,ball=ball+'.ADD_BALL.' where id='.$temp->added)->exec();
					$bot->editMessageText(ADMINS_GROUP,$temp->msg_id,T_6R($temp)."\n".'<a href="tg://user?id='.$from->id.'">Administrator</a> qabul qilmadi'."\n[<i>".Telegrambot::HTML($message->text)."</i>]");
					$bot->sendMessage($temp->added,"Kanalingiz qabul qilinmadi. Sabab:\n<i>".Telegrambot::HTML($message->text)."</i>");
					$db->update('users')->set(['action'=>0])->where('id='.$from->id)->exec();
						$bot->sendMessage($from->id,"Bajarildi",$bot->replyKeyboard($MAIN_KEYBOARD));
				}
			} elseif($action[0]==14) {
				if($message->text==B_BACK) {
					$db->update('users')->set(['action'=>0])->where('id='.$from->id)->exec();
					$bot->sendMessage($from->id,T_0,$bot->replyKeyboard($MAIN_KEYBOARD));
				} else {

				}
			} elseif($action[0]==15) {
				if($message->text==B_BACK) {
					$db->update('users')->set(['action'=>0])->where('id='.$from->id)->exec();
					$bot->sendMessage($from->id,T_0,$bot->replyKeyboard($MAIN_KEYBOARD));
				} else {
					if(in_array($message->text,range(1, 30))) {
						$db->query('update users set ball=ball-50,action=0 where id='.$from->id)->exec();
						$db->insert()->into('jakpot')->set(['user'=>$from->id,'number'=>$message->text])->exec();
						$bot->sendMessage($from->id,T_15_2(),$bot->replyKeyboard($MAIN_KEYBOARD));
					} else {
						$bot->sendMessage($from->id,T_15_1);
					}
				}
			}
		} elseif(isset($message->photo)) {
			if($action[0]==5) {
				try {
					$temp=0; $temp1=0;
					foreach ($message->photo as $key=>$value) if($value->width>$temp1) {$temp=$key; $temp1=$value->width;}
					$temp=@$bot->method('getFile',['file_id'=>$message->photo[$temp]->file_id]);
					if(!$temp->ok) throw new Exception($temp->description);
					$temp=file_get_contents('http://uploads.im/api?upload=https://api.telegram.org/file/bot'.TOKEN.'/'.$temp->result->file_path);
					$temp=json_decode($temp);
					if($temp->status_code!=200) throw new Exception('uploads.im:'.$temp->status_txt);
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
		$callback=&$input->callback_query;
		$from=&$callback->from;
		$message=&$callback->message;
		$data=explode(':',$callback->data);
		if($data[0]==='0') { // like
			$temp=$db->select()->from('likes')->where('user='.$from->id.' AND channel='.$db->escape($data[1]))->fetch();
			if($temp->valid()) {
				$bot->answerCallbackQuery($callback->id,['text'=>T_LIKE_E],true,300);
				if($message->chat->type=='channel' and isset($data[2])) {
					$channel=$db->select()->from('channels')->where('id='.$db->escape($data[1]))->fetch();
					if(!$channel->valid()) throw new Exception("Like bosishda xatolik");
					$channel=$channel->current();
					if($channel->likes!=$data[2]) {
						$bot->editMessageReplyMarkup(CHANNEL,$message->message_id,[[$bot->inlineKeyboardButton(B_LIKE.' ('.$channel->likes.')','0:'.$data[1].':'.$channel->likes)],[['text'=>'A\'zo bo\'lish','url'=>'https://telegram.me/'.substr($channel->username,1)]]]);
					}
				}
			} else {
				$db->insert()->into('likes')->set(['user'=>$from->id,'channel'=>$data[1]])->exec();
				$db->query('update channels set likes=likes+1 where id='.$db->escape($data[1]))->exec();
				$bot->answerCallbackQuery($callback->id,['text'=>T_LIKE],false,300);
				if($message->chat->type=='channel') {
					$channel=$db->select()->from('channels')->where('id='.$db->escape($data[1]))->fetch();
					if(!$channel->valid()) throw new Exception("Like bosishda xatolik");
					$channel=$channel->current();
					$bot->editMessageReplyMarkup(CHANNEL,$message->message_id,[[$bot->inlineKeyboardButton(B_LIKE.' ('.$channel->likes.')','0:'.$data[1].':'.$channel->likes)],[['text'=>'A\'zo bo\'lish','url'=>'https://telegram.me/'.substr($channel->username,1)]]]);
				}
			}
		} elseif($data[0]==='1') { // next
			$temp=$db->query('select * from channels where category='.$data[1].' and id<'.$data[2].' and status=2 order by id desc')->fetch();
			if($temp->valid()) {
				$temp=$temp->current();
				try {
					$bot->editMessageText($from->id,$message->message_id,CHANNEL_POST($temp),$bot->inlineKeyboard([[$bot->inlineKeyboardButton('Oldingisi','4:'.$data[1].':'.$temp->id),$bot->inlineKeyboardButton(B_NEXT,'1:'.$data[1].':'.$temp->id)],[$bot->inlineKeyboardButton(B_LIKE,'0:'.$temp->id)]]));
				} catch(Exception $e) {}
				//$bot->answerCallbackQuery($callback->id,['text'=>'Kanal jo\'natildi']);
			} else {
				$bot->answerCallbackQuery($callback->id,['text'=>'Afsuski hozircha bu yo\'nalishda boshqa kanal yo\'q'],true);
			}
		} elseif($data[0]==='2') { // qabul qilish
			$temp=$db->select()->from('channels')->where('id='.$data[1].' and status=1')->fetch();
			if($temp->valid()) {
				$temp=$temp->current();
				$db->update('channels')->set(['status'=>2,'likes'=>0])->where('id='.$db->escape($data[1]))->exec();
				$bot->editMessageText(ADMINS_GROUP,$message->message_id,T_6R($temp)."\n".'<a href="tg://user?id='.$from->id.'">Administrator</a> qabul qildi');
				$bot->sendMessage($temp->added,"▪️Kanalingiz qabul qilindi
Shu havola orqali
https://t.me/CatalogiyaBot?start=1-".$temp->id." yoki Havolani tarqatish orqali kanalingizga Like yig'ing va 50ming 💸so'm Pul yutug'iga ega bo'ling 
<b>Batafsil👇👇</b>",$bot->inlineKeyboard([[['text'=>'Tarqatish','switch_inline_query'=>$temp->id]]]));
				$bot->sendMessage(CHANNEL,CHANNEL_POST($temp),$bot->inlineKeyboard([[$bot->inlineKeyboardButton(B_LIKE,'0:'.$temp->id.':0')],[['text'=>'A\'zo bo\'lish','url'=>'https://telegram.me/'.substr($temp->username,1)]]]));
			} else {
				$bot->answerCallbackQuery($callback->id,['text'=>'Bu kanal boshqa holatda']);
			}
		} elseif($data[0]==='3') { // rad etish
		} elseif($data[0]==='4') { // prev
			$temp=$db->query('select * from channels where category='.$data[1].' and id>'.$data[2].' and status=2 order by id asc')->fetch();
			if($temp->valid()) {
				$temp=$temp->current();
				try {
					$bot->editMessageText($from->id,$message->message_id,CHANNEL_POST($temp),$bot->inlineKeyboard([[$bot->inlineKeyboardButton('Oldingisi','4:'.$data[1].':'.$temp->id),$bot->inlineKeyboardButton(B_NEXT,'1:'.$data[1].':'.$temp->id)],[$bot->inlineKeyboardButton(B_LIKE,'0:'.$temp->id)]]));
				} catch(Exception $e) {}
				//$bot->answerCallbackQuery($callback->id,['text'=>'Kanal jo\'natildi']);
			} else {
				$bot->answerCallbackQuery($callback->id,['text'=>'Afsuski hozircha bu yo\'nalishda boshqa kanal yo\'q'],true);
			}
		} elseif($data[0]==='5') { // omad
			$user=$db->select()->from('users')->where('id='.$from->id)->fetch();
			if($user->valid()) {
				$user=$user->current();
				if($user->ball>=LUCK_BALL) {
					$currentball=$user->ball-LUCK_BALL;
					$temp=rand(0,1000);
					if($temp==1) $temp=5000;
					elseif(in_array($temp,[2,3])) $temp=2000;
					elseif(in_array($temp,[4,5,6])) $temp=1000;
					elseif(in_array($temp,[7,8,9,10])) $temp=0;
					elseif($temp>10 and $temp<=20) $temp=500;
					elseif($temp>20 and $temp<=40) $temp=300;
					elseif($temp>40 and $temp<=100) $temp=50;
					elseif($temp>100 and $temp<=200) $temp=250;
					elseif($temp>200 and $temp<=400) $temp=100;
					elseif($temp>400 and $temp<=600) $temp=150;
					elseif($temp>600 and $temp<=1000) $temp=200;
					$currentball+=$temp;
					$bot->answerCallbackQuery($callback->id,['text'=>'Siz '.$temp.' ochko yutdingiz.'],true);
					$db->update('users')->set(['ball'=>$currentball])->where('id='.$from->id)->exec();
				} else {
					$bot->answerCallbackQuery($callback->id,['text'=>'Hisobingizda yetarli ochko mavjud emas'],true);
				}
			} else {
				$bot->answerCallbackQuery($callback->id,['text'=>'Tizimda nosozlik, iltimos bu haqida administratorga habar bering.'],true);
			}
		} elseif($data[0]==='6') { // lottery
			$temp=@$bot->method('getChatMember',['chat_id'=>CHANNEL,'user_id'=>$from->id]);
			if($temp->ok and in_array($temp->result->status,['member','creator','administrator'])) {
				$temp=$db->query('select * from lottery order by id desc limit 1')->fetch();
				if($temp->valid()) {
					$temp=$temp->current()->id+1;
				} else {
					$temp=0;
				}
				if($temp==5555) {
					$bot->answerCallbackQuery($callback->id,['text'=>T_LOTTERY_E1],true);
				} else {
					$temp1=$db->select()->from('lottery')->where('user='.$from->id)->fetch();
					if($temp1->valid()) {
						$bot->answerCallbackQuery($callback->id,['text'=>T_LOTTERY_E2],true);
					} else {
						$db->insert()->into('lottery')->set(['id'=>$temp,'user'=>$from->id])->exec();
						$bot->answerCallbackQuery($callback->id,['text'=>T_LOTTERY_1],true);
					}
				}
			} else {
				$bot->answerCallbackQuery($callback->id,['text'=>'Kanalimizga a\'zo bo\'ling']);
				$bot->sendMessage($from->id,T_LOTTERY_E3);
			}
		} elseif($data[0]==='7') { // eng eng eng
			try {
			if($data[1]=='0') {
				$bot->answerCallbackQuery($callback->id);
				$bot->editMessageText($from->id,$message->message_id,T_THE,$bot->inlineKeyboard([[$bot->inlineKeyboardButton(B_THE_CHANNEL,'7:1')],[$bot->inlineKeyboardButton(B_THE_USER,'7:2')]]));
			} elseif($data[1]=='1') {
				$bot->answerCallbackQuery($callback->id);
				$bot->editMessageText($from->id,$message->message_id,T_THE_CHANNEL,$bot->inlineKeyboard([[$bot->inlineKeyboardButton(B_ADD,'12')],[$bot->inlineKeyboardButton(B_BACK,'7:0')]]));
			} elseif($data[1]=='2') {
				$bot->answerCallbackQuery($callback->id);
				$bot->editMessageText($from->id,$message->message_id,T_THE_USER,$bot->inlineKeyboard([[$bot->inlineKeyboardButton(B_COLLECT,'7:4')],[$bot->inlineKeyboardButton(B_BACK,'7:0')]]));
			} elseif($data[1]=='3') {
			} elseif($data[1]=='4') {
				$bot->answerCallbackQuery($callback->id);
				$temp=$db->select()->from('users')->where('id='.$from->id)->fetch()->current()->ball;
				$bot->editMessageText($from->id,$message->message_id,"<i>Sizda</i>      <b>💎ochko</b> ".$temp." 

<b>Eng ko'p ochko</b> to'plab <b>🔺100ming so'm</b> 💸pul yutug'iga ega bo'ling💯
<b>👇Ochko to'plash uchun👇</b>",$bot->inlineKeyboard([[$bot->inlineKeyboardButton(B_INVITE,'7:5')],[$bot->inlineKeyboardButton(B_LOTTERY,'7:6'),$bot->inlineKeyboardButton(B_LUCK,'7:7')],[$bot->inlineKeyboardButton("Kanallarga qo'shilish",'11')],[$bot->inlineKeyboardButton(B_BACK,'7:2')]]));
			} elseif($data[1]=='5') {
				$bot->answerCallbackQuery($callback->id);
				$bot->editMessageText($from->id,$message->message_id,"<i>👤bu botga</i> <b>a'zo bo'lmagan</b> <i>Do'stlaringizni</i> <b>taklif qiling</b> <i>hamda qimmatli</i> <b>".INVITE_BALL." ochkoga</b> <i>ega bo'ling

Do'stlarni taklif qilish uchun havola(silka)</i>\n👉 t.me/".BOT_USERNEME."?start=0-".$from->id,$bot->inlineKeyboard([[['text'=>'Havolani tarqatish','switch_inline_query'=>'0']],[$bot->inlineKeyboardButton(B_BACK,'7:4')]]));
			} elseif($data[1]=='6') {
				$bot->answerCallbackQuery($callback->id);
				$bot->editMessageText($from->id,$message->message_id,T_LOTTERY,$bot->inlineKeyboard([[$bot->inlineKeyboardButton('Qatnashish','6')],[$bot->inlineKeyboardButton(B_BACK,'7:4')]]));
			} elseif($data[1]=='7') {
				$bot->answerCallbackQuery($callback->id);
				$bot->editMessageText($from->id,$message->message_id,T_LUCK,$bot->inlineKeyboard([[$bot->inlineKeyboardButton('Omad','5')],[$bot->inlineKeyboardButton(B_BACK,'7:4')]]));
			} elseif($data[1]=='8') {

			}
			} catch(Exception $e) {
				//
			}
		} elseif($data[0]==='8') { // katalog
			$temp=$db->query('select * from channels where category='.$data[1].' and status=2 order by id desc')->fetch();
			if($temp->valid()) {
				$temp=$temp->current();
				try {
					$bot->editMessageText($from->id,$message->message_id+1,CHANNEL_POST($temp),$bot->inlineKeyboard([[['text'=>B_LIKE,'callback_data'=>'0:'.$temp->id],['text'=>B_NEXT,'callback_data'=>'1:'.$data[1].':'.$temp->id]]]));
					$bot->answerCallbackQuery($callback->id);
				} catch(Exception $e) {
					$bot->answerCallbackQuery($callback->id,['text'=>'Tanlandi']);
				}
			} else {
				$bot->answerCallbackQuery($callback->id,['text'=>'Afsuski hozircha '.$CATEGORYS[$data[1]].' yo\'nalishida kanal yo\'q'],true);
			}
		} elseif($data[0]==='9') {
			//$bot->answerCallbackQuery($callback->id,['text'=>'Bu funksiya o\'chirildi']);
			$bot->editMessageReplyMarkup($from->id,$message->message_id);
			$temp = $bot->method('getChat', ['chat_id' => '@'.$data[1]]);
			$db->update('channels')->set(['added'=>$from->id,'username'=>'@'.$temp->result->username,'status'=>0,'title'=>$temp->result->title])->where('id='.$data[2])->exec();
			$db->update('users')->set(['action'=>'3:'.$data[2]])->where('id='.$from->id)->exec();
			$bot->sendMessage($from->id,T_3,$bot->replyKeyboard($CATEGORYS_KEYBOARD));
		} elseif($data[0]==='10') {
			$temp=$db->select()->from('jakpot')->where('user='.$from->id)->fetch();
			if($temp->valid()) {
				$bot->answerCallbackQuery($callback->id,['text'=>T_15E()],true);
			} else {
				$temp=$db->select()->from('users')->where('id='.$from->id)->fetch();
				if($temp->valid() and $temp->current()->ball>=50) {
					$bot->answerCallbackQuery($callback->id,['text'=>T_15_1]);
					$db->update('users')->set(['action'=>15])->where('id='.$from->id)->exec();
					$bot->sendMessage($from->id,T_15_1,$bot->removeReplyKeyboard());
				} else {
					$bot->answerCallbackQuery($callback->id,['text'=>"Sizda yetarli ochko mavjud emas"],true);
				}
			}
		} elseif($data[0]==='11') {
			$jchannels=[]; // qo'shilgan
			$njchannels=[]; // hozir qo'shilgan
			$djchannels=[]; // qo'shilmagan
			$ndjchannels=[]; //  hozir chiqib ketgan
			$temp=[];
			$ball=0;
			foreach ($db->select()->from('followers')->where('user='.$db->escape($from->id))->fetch() as $value) $temp[$value->channel]=true;
			//$bot->sendMessage(ADMIN,print_r($temp,true));
			foreach ($db->select()->from('ad_channels')->fetch() as $value) {
				$temp2=$db->query('select count(*) as c from followers where channel='.$db->escape($value->channel))->fetch()->current()->c;
				try {
    				$temp1=$bot->method('getChatMember',['chat_id'=>$value->channel,'user_id'=>$from->id]);
    			} catch(Exception $e) {
    				$temp1=false;
    			}
				if($temp1 and $temp1->ok) {
					if(in_array($temp1->result->status,['member','creator','administrator'])) {
						if(isset($temp[$value->channel])) {
							$jchannels[]=$value->channel;
						} else {
							$njchannels[]=$value->channel;
							$ball+=JOIN_BALL;
							$db->insert()->into('followers')->set(['channel'=>$value->channel,'user'=>$from->id])->exec();
						}
					} else {
						if(isset($temp[$value->channel])) {
							$ndjchannels[]=$value->channel;
							$ball-=JOIN_BALL;
							$db->delete()->from('followers')->where('user='.$db->escape($from->id).' AND channel='.$db->escape($value->channel))->exec();
						} else {
							if($value->bfollowers>$temp2) $djchannels[]=$value->channel;
						}
					}
				} //else throw new Exception("Bot ".$value->username." kanaliga admin emas.");
			}
			if($ball!=0) {
				$temp=$db->select()->from('users')->where('id='.$from->id)->fetch()->current()->ball+$ball;
				$db->update('users')->set(['ball'=>$temp])->where('id='.$from->id)->exec();
			}
			//$bot->sendMessage(ADMIN,print_r([$jchannels,$njchannels,$djchannels,$ndjchannels],true));
			$text="<i>👇Quyidagi  har bir kanalga qo'shilish orqali 30 ochko ishlashingiz mumkin:</i>\n";
			if(empty($djchannels)) $text.="<i>Siz barcha kanallarga qo'shilgan ekansiz✔️</i>\n";
			else {
				foreach ($djchannels as $value) $text.="🔹".$value."\n";
				foreach ($ndjchannels as $value) $text.="🔹".$value."\n";
			}
			if(!empty($jchannels)) {
				$text.="\n<b>➕Siz qo'shilgan kanallar:</b>\n";
				foreach ($jchannels as $value) $text.="🔹".$value."\n";
				foreach ($njchannels as $value) $text.="🔹".$value."\n";
			}
			// if(!empty($njchannels)) {
			// 	$text.="\nSiz hozir qo'shilgan kanallar:\n";
			// 	foreach ($njchannels as $value) $text.="🔹".$value."\n";
			// }
			// if(!empty($ndjchannels)) {
			// 	$text.="\nSiz hozir chiqib ketgan kanallar:\n";
			// 	foreach ($ndjchannels as $value) $text.="🔹".$value."\n";
			// }
			$text.="\n<b>Kanalga a'zo bo'lgach yangilashni bosing</b>";
			try {
				$bot->editMessageText($from->id,$message->message_id,$text,$bot->inlineKeyboard([[$bot->inlineKeyboardButton("Yangilash",'11')],[$bot->inlineKeyboardButton(B_BACK,'7:4')]]));
				$bot->answerCallbackQuery($callback->id);
			} catch(Exception $e) {
				$bot->answerCallbackQuery($callback->id,['text'=>'Yangilandi']);
			}
		} elseif($data[0]==='12') {
			$bot->answerCallbackQuery($callback->id,['text'=>B_ADD]);
			$temp=@$bot->method('getChatMember',['chat_id'=>CHANNEL,'user_id'=>$from->id]);
			if($temp->ok and in_array($temp->result->status,['member','creator','administrator'])) {
				if($db->select()->from('users')->where('id='.$from->id)->fetch()->current()->ball>=ADD_BALL) {
					$db->update('users')->set(['action'=>1])->where('id='.$from->id)->exec();
					$bot->sendMessage($from->id,T_1,$bot->replyKeyboard([[B_YES],[B_BACK]]));
				} else {
					$bot->sendMessage($from->id,T_1E_1,$bot->inlineKeyboard([[$bot->inlineKeyboardButton(B_COLLECT,'7:4')]]));
				}
			} else {
				$bot->sendMessage($from->id,T_1E_2);
			}
		}
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
		$temp=$db->query('select * from lottery order by id desc limit 1')->fetch();
		if($temp->valid()) {
			$temp3=$temp->current()->id;
			$temp=$db->select()->from('lottery')->where('id='.rand(0,$temp3))->fetch();
			if(!$temp->valid()) throw new Exception("Lotariyada tizimida xatolik");
			$temp=$temp->current();
			$temp1=@$bot->method('getChat',['chat_id'=>$temp->user]);
			if($temp1->ok) $temp1=$temp1->result->first_name;
			else $temp1="Shu odam";
			$bot->sendMessage(CHANNEL,"<i>Bugungi</i> <b>LOTOREYA</b> <i>o'ynimiz g'olibi</i> ".'<a href="tg://user?id='.$temp->user.'">'.$temp1.'</a>'." <i>bo'ldi.</i>
<b>G'olibimizni 555 ochkoga ega bo'lganligi bilan tabriklaymiz</b>👏👏\n<i>Lotoreyada ".$temp3." ta odam qatnashadi</i>");
			$db->query('update users set ball=ball+555 where id='.$temp->user)->exec();
			$db->delete()->from('lottery')->exec();
		} else {
			$bot->sendMessage(ADMINS_GROUP,'Lotariyada hechkim qatnashmadi.');
		}
	} elseif($input->cron=='3') { // 9:00
		$bot->sendMessage(CHANNEL,T_LOTTERY,$bot->inlineKeyboard([[['text'=>'Qatnashish','url'=>'https://telegram.me/CatalogiyaBot?start=3']]]));
	} elseif($input->cron=='4') {
		$r=rand(1,30);
		$winners=[];
		foreach ($db->select()->from('jakpot')->where('number='.$r)->fetch() as $value) $winners[]=$value->user;
		if(empty($winners)) {
			$text="jakpot da g'oliblar ($r):\nyo'q";
		} else {
			$p=round(1000/count($winners));
			$text="jakpot da g'oliblar ($r) ($p):\n";
			foreach ($winners as $value) {
				$db->query('update users set ball=ball+'.$p.' where id='.$value)->exec();
				$temp=@$bot->method("getChat",["chat_id"=>$value]);
				if($temp->ok) $temp=$temp->result->first_name;
				else $temp="Foydalanuvchi";
				$text.='<a href="tg://user?id='.$value.'">'.$temp.'</a>'."\n";
			}
		}
		$db->delete()->from('jakpot')->exec();
		$bot->sendMessage(ADMINS_GROUP,$text);
	}
} elseif(isset($input->inline_query)) {
	$inline=&$input->inline_query;
	$from=&$inline->from;
	if($inline->query=="0") {
		$bot->answerInlineQuery($inline->id,[['type'=>'article','id'=>'0','title'=>'Havolani tarqatish','description'=>'','input_message_content'=>['message_text'=>T_SUPPORT($from),'parse_mode'=>'HTML'],'reply_markup'=>['inline_keyboard'=>[[['text'=>"Do'stni qo'llab-quvvatlash",'url'=>'https://telegram.me/'.BOT_USERNEME.'?start=0-'.$from->id]]]]]],['is_personal'=>true]);
	} elseif(preg_match('/^\d+$/', $inline->query)) {
		$temp=$db->select()->from('channels')->where('id='.$inline->query.' AND status=2')->fetch();
		if($temp->valid()) {
			$temp=$temp->current();
			$bot->answerInlineQuery($inline->id,[['type'=>'article','id'=>$temp->id,'title'=>$temp->title,'thumb_url'=>$temp->thumb,'description'=>$temp->description,'input_message_content'=>['message_text'=>T_INLINE($temp),'parse_mode'=>'HTML'],'reply_markup'=>['inline_keyboard'=>[[['text'=>B_LIKE,'url'=>'https://telegram.me/'.BOT_USERNEME.'?start=1-'.$temp->id]]]]]]);
		}
	}
}
return true;
//test for git