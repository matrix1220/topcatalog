<?php
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
			$temp="Foydalanuvchilar soni:".$db->query('select count(*) as c from users')->fetch()->current()->c."\n";
			$temp.="Blocklaganlar soni:".$db->query('select count(*) as c from users where blocked=1')->fetch()->current()->c;
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
			exit();
			$user->valid();
			header("Connection: close",true);
			ob_start();
			echo '{"ok":true}';
			header("Content-Length: ".ob_get_length(), true);
			ob_end_flush();
			flush();
			ob_end_clean();
			set_time_limit(0);
			ignore_user_abort(true);
			$i=0;
			while (true) {
				$bot->sendMessage(ADMINS_GROUP,($i*20).'-sekund, connection_status:'.connection_status());
				if($i==5) break;
				$i++;
				sleep(20);
			}
			exit();
		} elseif($message->text=="/current_time") {
			$bot->sendMessage(ADMINS_GROUP,date("H:i:s"));
		} elseif($message->text=="/display_errors") {
			$bot->sendMessage(ADMINS_GROUP,ini_get('display_errors').'asd');
		}
?>