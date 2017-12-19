<?php
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
					$bot->sendMessage($from->id,T_LOTTERY,$bot->inlineKeyboard([[$bot->inlineKeyboardButton('Qatnashish','6')],[$bot->inlineKeyboardButton(B_WINNERS,'7:13')]]));
				} elseif($message->text==B_THE) {
					//$db->update('users')->set(['action'=>9])->where('id='.$from->id)->exec();
					$bot->sendMessage($from->id,T_THE,$bot->inlineKeyboard([[$bot->inlineKeyboardButton(B_THE_USER,'7:2')]]));
				} elseif($message->text==B_LUCK) {
					$bot->sendMessage($from->id,T_LUCK,$bot->inlineKeyboard([[$bot->inlineKeyboardButton('Omad','5')]]));
				} elseif($message->text==B_LN) {
					$bot->sendMessage($from->id,T_15,$bot->inlineKeyboard([[$bot->inlineKeyboardButton("Son tanlash","10")],[$bot->inlineKeyboardButton(B_WINNERS,'14')]]));
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
							$temp=uploads($temp2);
							$db->update('users')->set(['action'=>'6:'.$action[1]])->where('id='.$from->id)->exec();
							$db->update('channels')->set(['cover'=>$temp->data->img_url,'thumb'=>$temp->data->thumb_url])->where('id='.$action[1])->exec();
							$temp=$db->select()->from('channels')->where('id='.$action[1])->fetch()->current();
							$bot->sendMessage($from->id,T_6."\n".CHANNEL_POST($temp),$bot->replyKeyboard([[B_6],[B_BACK]]));
						} catch(Exception $e) {
							$bot->sendMessage($from->id,"Tizimida xatolik, iltimos bu haqida administratorga habar bering");
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
?>