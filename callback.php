<?php
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
					$bot->sendMessage(ADMINS,$channel->likes.":".$data[2]);
					$bot->editMessageReplyMarkup(CHANNEL,$message->message_id,[[$bot->inlineKeyboardButton(B_LIKE.' ('.$channel->likes.')','0:'.$data[1].':'.$channel->likes)],[['text'=>B_SUBSCRIBE,'url'=>'https://telegram.me/'.substr($channel->username,1)]]]);
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
				$bot->editMessageReplyMarkup(CHANNEL,$message->message_id,[[$bot->inlineKeyboardButton(B_LIKE.' ('.$channel->likes.')','0:'.$data[1].':'.$channel->likes)],[['text'=>B_SUBSCRIBE,'url'=>'https://telegram.me/'.substr($channel->username,1)]]]);
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
			$db->update('channels')->set(['status'=>2,'likes'=>0,'added_time'=>time()])->where('id='.$db->escape($data[1]))->exec();
			$bot->editMessageText(ADMINS_GROUP,$message->message_id,T_6R($temp)."\n".'<a href="tg://user?id='.$from->id.'">Administrator</a> qabul qildi');
			$bot->sendMessage($temp->added,"▪️Kanalingiz qabul qilindi
Shu havola orqali
https://t.me/CatalogiyaBot?start=1-".$temp->id." yoki Havolani tarqatish orqali kanalingizga Like yig'ing va kanalingizni eng zo'rligini isbotlang
<b>Batafsil👇👇</b>",$bot->inlineKeyboard([[['text'=>'Tarqatish','switch_inline_query'=>$temp->id]]]));
			$bot->sendMessage(CHANNEL,CHANNEL_POST($temp),$bot->inlineKeyboard([[$bot->inlineKeyboardButton(B_LIKE,'0:'.$temp->id.':0')],[['text'=>B_SUBSCRIBE,'url'=>'https://telegram.me/'.substr($temp->username,1)]]]));
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
				$bot->editMessageText($from->id,$message->message_id,T_THE,$bot->inlineKeyboard([[$bot->inlineKeyboardButton(B_THE_USER,'7:2')]]));
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
	<b>👇Ochko to'plash uchun👇</b>",$bot->inlineKeyboard([
					[$bot->inlineKeyboardButton(B_INVITE,'7:5')],
					[$bot->inlineKeyboardButton(B_LOTTERY,'7:6'),$bot->inlineKeyboardButton(B_LUCK,'7:7')],
					[$bot->inlineKeyboardButton("Kanallarga qo'shilish",'11')],
					[$bot->inlineKeyboardButton(B_BUY,'7:9'),$bot->inlineKeyboardButton(B_SHARE,'7:8')],
					[$bot->inlineKeyboardButton(B_BACK,'7:2')]
				]));
			} elseif($data[1]=='5') {
				$bot->answerCallbackQuery($callback->id);
				$bot->editMessageText($from->id,$message->message_id,"<i>👤bu botga</i> <b>a'zo bo'lmagan</b> <i>Do'stlaringizni</i> <b>taklif qiling</b> <i>hamda qimmatli</i> <b>".INVITE_BALL." ochkoga</b> <i>ega bo'ling

	Do'stlarni taklif qilish uchun havola(silka)</i>\n👉 t.me/".BOT_USERNEME."?start=0-".$from->id,$bot->inlineKeyboard([[['text'=>'Havolani tarqatish','switch_inline_query'=>'0']],[$bot->inlineKeyboardButton(B_BACK,'7:4')]]));
			} elseif($data[1]=='6') {
				$bot->answerCallbackQuery($callback->id);
				$bot->editMessageText($from->id,$message->message_id,T_LOTTERY,$bot->inlineKeyboard([[$bot->inlineKeyboardButton('Qatnashish','6')],[$bot->inlineKeyboardButton(B_WINNERS,'7:13')],[$bot->inlineKeyboardButton(B_BACK,'7:4')]]));
			} elseif($data[1]=='7') {
				$bot->answerCallbackQuery($callback->id);
				$bot->editMessageText($from->id,$message->message_id,T_LUCK,$bot->inlineKeyboard([[$bot->inlineKeyboardButton('Omad','5')],[$bot->inlineKeyboardButton(B_BACK,'7:4')]]));
			} elseif($data[1]=='8') {
				$bot->answerCallbackQuery($callback->id);
				$bot->editMessageText($from->id,$message->message_id,T_SHARE,$bot->inlineKeyboard([[['text'=>B_SHARE,'switch_inline_query'=>'1']],[$bot->inlineKeyboardButton(B_BACK,'7:4')]]));
			} elseif($data[1]=='9') {
				$bot->answerCallbackQuery($callback->id);
				$bot->editMessageText($from->id,$message->message_id,T_BUY,$bot->inlineKeyboard([[$bot->inlineKeyboardButton(B_BUY_1,'7:10')],[$bot->inlineKeyboardButton(B_BACK,'7:4')]]));
			} elseif($data[1]=='10') {
				$bot->answerCallbackQuery($callback->id);
				$bot->editMessageText($from->id,$message->message_id,T_BUY_1,$bot->inlineKeyboard([[$bot->inlineKeyboardButton(B_BACK,'7:9')]]));
			} elseif($data[1]=='11') {
				$bot->answerCallbackQuery($callback->id);
				$bot->editMessageText($from->id,$message->message_id,T_ADS_CHANNEL,$bot->inlineKeyboard([[$bot->inlineKeyboardButton(B_ADD,'7:12')],[$bot->inlineKeyboardButton(B_BACK,'11')]]));
			} elseif($data[1]=='12') {
				$bot->answerCallbackQuery($callback->id);
				$bot->editMessageText($from->id,$message->message_id,T_ADS_CHANNEL_1,$bot->inlineKeyboard([[$bot->inlineKeyboardButton(B_BACK,'7:11')]]));
			} elseif($data[1]=='13') {
				$temp=$db->query('select * from lottery_winners order by `date` desc limit 10')->fetch();
				$bot->answerCallbackQuery($callback->id);
				$bot->editMessageText($from->id,$message->message_id,lottery_winners($temp),$bot->inlineKeyboard([[$bot->inlineKeyboardButton(B_BACK,'7:6')]]));
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
			$bot->editMessageText($from->id,$message->message_id,$text,$bot->inlineKeyboard([[$bot->inlineKeyboardButton("Yangilash",'11')],[$bot->inlineKeyboardButton(B_ADS_CHANNEL,'7:11')],[$bot->inlineKeyboardButton(B_BACK,'7:4')]]));
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
	} elseif($data[0]==='13') {
		if($from->id!=$data[1]) {
			$user=$db->select()->from('users')->where('id='.$db->escape($data[1]))->fetch();
			if($user->valid()) {
				$user=$user->current();
				if($user->ball>210) {
					$p=$db->select()->from('users')->where('id='.$from->id)->fetch();
					if($p->valid()) {
						$p=$p->current();
						if($user->shtime===null or $user->shtime<=time()-3600*24) {
							$db->query('update users set ball=ball-210,shtime='.time().' where id='.$user->id)->exec();
							$db->query('update users set ball=ball+200 where id='.$p->id)->exec();
							$bot->answerCallbackQuery($callback->id);
							$bot->editInlineMessageText($callback->inline_message_id,"Muvaffaqiyatli bajarildi");
						} else {
							$bot->answerCallbackQuery($callback->id,['text'=>"24 soat dan keyin urinib ko'ring"],true,3600);
						}
					} else {
						$bot->answerCallbackQuery($callback->id,['text'=>"Ochkoni qabul qilish uchun ro'yxatdan o'ting"],true);
					}
				} else {
					$bot->answerCallbackQuery($callback->id,['text'=>"Do'stinggizning ochkosi yetarli emas"],true);
				}
			} else {
				$bot->answerCallbackQuery($callback->id,['text'=>"Tizimda xatolik"],true);
			}
		} else {
			$bot->answerCallbackQuery($callback->id,['text'=>"O'zinggizning ochkoinggizni ololmaysiz"],true,3600);
		}
	} elseif($data[0]==='14') {
		if(isset($data[1])) $current=$data[1]; else $current=mktime(0,0,0);
		$next=$current-3600*24; $prev=$current+3600*24;
		$temp=$db->query("select * from jakpot_winners where `time`>=".$current.' and `time`<'.$prev)->fetch();
		// if($temp->valid()) {
			$bot->answerCallbackQuery($callback->id);
			try {
				$bot->editMessageText($from->id,$message->message_id,date("d.m.y",$current)." ".jakpot_winners($temp),$bot->inlineKeyboard([[$bot->inlineKeyboardButton(B_PREV,'14:'.$prev),$bot->inlineKeyboardButton(B_NEXT,'14:'.$next)]]));
			} catch(Exception $e) {
				
			}
		// } else {
		// 	if(!isset($data[1])) {
		// 		$bot->answerCallbackQuery($callback->id);
		// 	} else {
		// 		$bot->answerCallbackQuery($callback->id,['text'=>'boshqa g\'oliblar yo\'q'],true,60);
		// 	}
		// }
		//
	} elseif($data[0]==='15') {
	}
?>