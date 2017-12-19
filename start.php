<?php
		if($user->valid()) {
			$temp=explode(':',$user->current()->action);
			if(in_array($temp[0], ['2','3','4','5','6'])) {
				$db->query('update users set ball=ball+'.ADD_BALL.' where id='.$from->id)->exec();
				if($temp[0]!='2') $db->update('channels')->set(['status'=>4])->where('id='.$db->escape($temp[1]))->exec();
			}
			$db->query('update users set blocked=NULL where id='.$from->id)->exec();
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
				//$bot->method('sendPhoto',['chat_id'=>$from->id,"photo"=>"AgADAgADlqgxG-hIaUj1rSSHh4EsAzPYDw4ABCjTs5rypO-hk_0CAAEC"]);
				$bot->sendMessage($from->id,T_LOTTERY,$bot->inlineKeyboard([[$bot->inlineKeyboardButton('Qatnashish','6')],[$bot->inlineKeyboardButton(B_WINNERS,'7:13')]]));
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
			//$bot->method('sendPhoto',['chat_id'=>$from->id,"photo"=>"AgADAgADlqgxG-hIaUj1rSSHh4EsAzPYDw4ABCjTs5rypO-hk_0CAAEC"]);
			$bot->sendMessage($from->id,T_LOTTERY,$bot->inlineKeyboard([[$bot->inlineKeyboardButton('Qatnashish','6')],[$bot->inlineKeyboardButton(B_WINNERS,'7:13')]]));
		}
?>