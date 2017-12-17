<?php
	const TOKEN = "438238300:AAEdydlMXwY81qXWZb4njw7YLhquOeKx0sg";
	const DB_HOST = "localhost";
	const DB_USER = "yiacatal_user";
	const DB_PASS = "*7k]6Ex0nO4B";
	const DB_NAME = "yiacatal_base";
	const BOT_USERNEME = "CatalogiyaBot";
	const CHANNEL = "@catalogiya";
	const JOINCHAT = "https://t.me/joinchat/";
	const ADMIN = 108268232;
	const ADMINS_GROUP = '-1001239530999';
	const INVITE_BALL = 20;
	const LUCK_BALL = 200;
	const JOIN_BALL = 30;
	const ADD_BALL = 150;
	const B_ADD = "➕Kanal qo'shish";
	const B_CATEGORYS = '📒Katalog';
	const B_CONTACT = '🖌Bizga yozish';
	const B_BACK = 'Orqaga';
	const B_ACCOUNT = "💼Mening Profilim";
	const B_EXTRA = "📎Boshqalar";
	const B_ABOUT = "📋Bot haqida";
	const B_LUCK = "📦Omad qutisi";
	const B_LOTTERY = "🔹Lotoreya";
	const B_LIKE = "Like";
	const B_NEXT = "Keyingisi";
	const B_PREV = "Oldingisi";
	const B_THE = "🔹Aksiya";
	const B_THE_CHANNEL = "Eng ko'p like yig'gan kanal";
	const B_THE_USER = "Eng kop ochko toplagan obunachi";
	const B_COLLECT = "Ochko toplash";
	const B_INVITE = "👤Do'stlarni taklif qilish";
	const B_LOGO = "🔜Kanaldan olish";
	const B_NO = "yo'q";
	const B_LN = "🔢Omadli son";
	$CATEGORYS=[
		"🎵Musiqa",
		"👨‍🎓Fan va Ta'lim",
		"📚Kitoblar va Jurnallar",
		"🌎Tillar",
		"🍃Tabiat",
		"⚽️Sport va Fitnes",
		"🏁Avto va Moto",
		"🎮 Programmalar va Hackerlik",
		"❤️Sevgi va Muhabbat",
		"🎬Video va Filmlar",
		"🎭Hazil-mutoyiba",
		"🆕Yangiliklar va OAV",
		"👒Moda va Go'zallik",
		"🍔Ovqat va kulinariya",
		"🖼Foto va statuslar",
		"📝Aforizmlar",
		"🛍Oldi sotdi",
		"Sayt kanallari",
		"👥Jamoa kanallari",
		"Boshqalar"
	];
	$CATEGORYS_KEYBOARD=[
 		[$CATEGORYS[0],$CATEGORYS[1]],
 		[$CATEGORYS[2],$CATEGORYS[3]],
 		[$CATEGORYS[4],$CATEGORYS[5]],
 		[$CATEGORYS[6],$CATEGORYS[7]],
 		[$CATEGORYS[8],$CATEGORYS[9]],
 		[$CATEGORYS[10],$CATEGORYS[11]],
 		[$CATEGORYS[12],$CATEGORYS[13]],
 		[$CATEGORYS[14],$CATEGORYS[15]],
 		[$CATEGORYS[16],$CATEGORYS[17]],
 		[$CATEGORYS[18],$CATEGORYS[19]],
 		[B_BACK]
	];
	$CATEGORYS_INLINEKEYBOARD=[
 		[['text'=>$CATEGORYS[0],'callback_data'=>'8:0'],['text'=>$CATEGORYS[1],'callback_data'=>'8:1'],['text'=>$CATEGORYS[16],'callback_data'=>'8:16']],
 		[['text'=>$CATEGORYS[3],'callback_data'=>'8:3'],['text'=>$CATEGORYS[4],'callback_data'=>'8:4'],['text'=>$CATEGORYS[15],'callback_data'=>'8:15']],
 		[['text'=>$CATEGORYS[6],'callback_data'=>'8:6'],['text'=>$CATEGORYS[7],'callback_data'=>'8:7']],
 		[['text'=>$CATEGORYS[8],'callback_data'=>'8:8'],['text'=>$CATEGORYS[9],'callback_data'=>'8:9']],
 		[['text'=>$CATEGORYS[10],'callback_data'=>'8:10'],['text'=>$CATEGORYS[11],'callback_data'=>'8:11']],
 		[['text'=>$CATEGORYS[12],'callback_data'=>'8:12'],['text'=>$CATEGORYS[13],'callback_data'=>'8:13']],
 		[['text'=>$CATEGORYS[14],'callback_data'=>'8:14'],['text'=>$CATEGORYS[5],'callback_data'=>'8:5']],
 		[['text'=>$CATEGORYS[2],'callback_data'=>'8:2'],['text'=>$CATEGORYS[17],'callback_data'=>'8:17']],
 		[['text'=>$CATEGORYS[18],'callback_data'=>'8:18'],['text'=>$CATEGORYS[19],'callback_data'=>'8:19']]
	];
	$MAIN_KEYBOARD=[[B_THE,B_ACCOUNT],[B_ADD,B_CATEGORYS],[B_LOTTERY,B_LUCK],[B_EXTRA]];
	$EXTRA_KEYBOARD=[[B_LN],[B_CONTACT],[B_ABOUT],[B_BACK]];
	const T_THE_CHANNEL = '<a href="http://sn.uploads.im/CoQUn.jpg">🏆</a>'."<b>Eng ko'p LIKE yig'gan KANAL</b>
<i>🥇1-o'rin 50ming so'm
🥈2-o'rin 30ming so'm
🥉3-o'rin 20ming so'm

Kanalingizni 📂Katalogga ➕Joylashtiring va </i><b>eng ko'p like👍</b><i> yig'ing. Hamda </i><b>50 ming som pul 💸yutuq</b><i> sohibiga aylaning✔️

📂Katalogga kanal qo'shganingizdan so'ng sizga silka beriladi. Shu silka orqali kanalingizga 👍like yig'asiz. Eng ko'p like yig'gan uchta kanal 🎗g'olib bo'ladi.</i>
<b>🗓Aksiya 13-noyabrdan
 1 dekabrgacha o'tkaziladi
G'oliblar 2-dekabr kuni elon qilinadi</b>";
	const T_THE_USER = '<a href="http://sn.uploads.im/iPLjx.jpg">🏆</a>'."<b>Eng ko'p OCHKO to'plagan obunachi</b>
<i>🥇1-o'rin 100ming so'm
🥈2-o'rin 60ming so'm
🥉3-o'rin 40ming so'm

Robotimizdagi qiziqarli mashqlarni bajaring</i>
<b>LOTOREYA</b><i> o'ynang
 O'z </i><b>OMAD</b><i>ingizzni sinang  hamda qimmatli </i><b>OCHKO</b><i>larga ega bo'ling</i>
<b>ENG KOP OCHKO to'plagan obunachi 100 ming so'mga ega bo'ladi</b>
<i>🗓Aksiya 13-noyabrdan 30-dekabrgacha o'tkaziladi
G'oliblar 31-dekabr kuni e'lon qilinadi</i>";
	const T_LUCK = "📦Omad qutisi

<i>🔹O'z omadingizni sinab ko'rishni istaysizmi?
🔸Buning uchun shunchaki </i><b>Omad</b> tugmasini bosing✔️ hamda turli ochkolarga ega bo'lib qanchalik omadli inson ekanligizni bilib oling😊
<b>Eng katta yutuq</b> 5000ochko
<b>O'yinda qatnashish</b> 200 ochko";
	const T_THE = "<b>🏆Eng ko'p OCHKO to'plagan obunachi</b>
<i>🥇1-o'rin 100ming so'm
🥈2-o'rin 60ming so'm
🥉3-o'rin 40ming so'm</i>
Batafsil👇";
	const T_LIKE = 'LIKE bosildi!';
	const T_LIKE_E = 'Siz allaqchon LIKE bosgansiz!';
	const T_CONTACT_1 = "Savollar bo'lsa yozib qoldiring\n\n<b>⚠️Faqat shu bot haqidagi savollarni yozib qoldiring‼️</b>";
	const T_CONTACT_2 = "Tez fursatlarda javob berishga harakat qilamiz";
	function T_WELCOM($name) {
		return "👋Salom <b>".Telegrambot::HTML($name)."</b>
<i>Eng sara kanallar</i> <b>Katalogi</b>
🌀 @CaTaLoGiYa <i>kanalining robotiga</i> <b>Xush kelibsiz</b>

<i>⚠️Botdan to'liq foydalanish uchun 🌀 @Catalogiya kanaliga obuna bo'ling hamda botdan qanday foydalanish haqida bilish uchun</i> 
/help <i>ni bosing</i>";
	}
	const T_ABOUT = "Hurmatli obunachilar bu bot         🌀 @YIAMEGA buyurtmasiga binoan 🔹 @UzProBoys jamoasi tomonidan yasalgan.
Bu botni yasashda
🔹 @Topcatalogbot 
🔹 @Reclamarobot
🔹 @gruppala_bot va boshqa botlardan andoza olindi hamda quyidagi botlarni yasagan insonlarga o'z minnatdorchiligimizni bildiramiz. Hurmat ila 
🔹 @CatalogiyaBot";
	const T_LOTTERY = "<b>🔹Lotoreya</b>

<i>🔸Bu shunchaki omadlilarga kulib boqadigan to'liq avtomatlashgan tekin o'yin bo'lib, g'olib tasodifiy ravishda aniqlanadi hamda o'yin g'olibi </i><b>555 ochkoga</b><i> ega bo'ladi
🔹O'yinda qatnashing balkim bugun </i><b>OMAD</b><i> sizga kulib boqar</i>

<b>📋Natijalar har kuni 
  🕦 21:00 da e'lon qilinadi</b>";
	const T_LOTTERY_1 = "Siz chipta oldingiz.";
	const T_LOTTERY_E1 = 'Afsuski chipta qomadi';
	const T_LOTTERY_E2 = 'Siz allaqachon chiptani olib bo\'lgansiz.';
	const T_LOTTERY_E3 = "Kanalimizga ".CHANNEL." a'zo bo'ling va qaytadan urinib ko'ring";

	const T_0 = "Bosh menyu:";

	const T_1 = "Kanal qo'shish ".ADD_BALL." ochko davom etasizmi?";
	const T_1E_1 = "Kanal qo'shish ".ADD_BALL." ochko\nSizda yetarli ochko mavjud emas";
	const T_1E_2 = "Kanalimizga ".CHANNEL." a'zo bo'ling va qaytadan urinib ko'ring";
	const B_YES = "Ha";

	const T_2 = "<b>Kanal @username sini kiriting</b>\n\n<i>Masalan:\n".CHANNEL."\n@YIAMEGA</i>";
	const T_2E_1 = "Bunday kanal mavjud emas!";
	const T_2E_2 = "Bu kanal qo'shilgan!";
	const T_2E_3 = "Bu kanalni boshqa foydalanuvchi qo'shmoqda!";

	const T_3 = "<b>Kanal mavzusini tanlang</b>";
	const T_3E = "Tanlang!";

	const T_4 = "<b>Kanal haqida qisqacha ma'lumot yozing</b>\n\n<i>30 ta simvoldan kam yoki 150 ta simvoldan ko'p bo'lmasligi kerak</i>";
	const T_4E = "Noto'g'ri kiritdinggiz";

	const T_5 = "<b>Kanal logosi(rasmi)ni yuboring</b>";
	const T_5E_1 = "Iltimos rasm jo'nating!";
	const T_5E_2 = "Kanal logosi mavjud emas.";

	const T_6 = "Tasdiqlang:";
	const T_6E = "Tanlang!";
	const B_6 = "👌To'g'ri";
	const T_6F = "Qabul qilindi. Javobni kuting!";

	const T_15 = "🔢Omadli son

O'yinda qatnashish uchun siz 1dan 30 gacha bo'lgan ixtiyoriy son tanlaysiz. Agar siz tanlagan son bot   tasodifiy tarzda  tanlagan son bilan bilan bir  xil.bo'lsa siz o'yin g'olibi bo'lasiz
⚠️Agar g'oliblar bir nechta bo'lsa yutuq g'oliblar o'rtasida teng bo'linadi
🕛O'yin bir kunda har 3 soatdan 5marta o'tkaziladi
🏆Jekpot 1000ochko
🔸O'yinda qatnashish 50ochko";
	const T_15_1 = "1dan 30 gacha bo'lgan son tanlang";
	function T_15_2() {
		$temp=date("G");
		if($temp<9) {$temp=9;} elseif($temp<12) {$temp=12;} elseif($temp<15) {$temp=15;} elseif($temp<18) {$temp=18;}
		elseif($temp<21) {$temp=21;} elseif($temp<24) {$temp=0;}
		return "Son tanlandi. Natijalar ".$temp.":00 da e'lon qilinadi";
	}
	function T_15E() {
		$temp=date("G");
		if($temp<9) {$temp=9;} elseif($temp<12) {$temp=12;} elseif($temp<15) {$temp=15;} elseif($temp<18) {$temp=18;}
		elseif($temp<21) {$temp=21;} elseif($temp<24) {$temp=0;}
		return "Siz sonni tanlab bo'lgansiz. Natijalar ".$temp.":00 da e'lon qilinadi";
	}

	function T_6R($channel) {
		return '<a href="'.$channel->cover.'">▪️</a><b>Kanal</b> <a href="https://telegram.me/'.urlencode(substr($channel->username,1)).'">'.Telegrambot::HTML($channel->title)."</a>\n\n<b>📋Info:</b> <i>".Telegrambot::HTML($channel->description)."</i>\n\n<b>🔜Yo'nalish</b> <i>".Telegrambot::HTML($GLOBALS['CATEGORYS'][$channel->category])."</i>"."\n".'<a href="tg://user?id='.$channel->added.'">foydalanuvchi</a> qo\'shdi.';
	}
	function T_ACCOUNT($channel,$ball,$lottery,$channels) {
		$temp="Sizning Profilingizda\n\n▪️Kanal: ".$channel."\n💎Ochko: ".$ball."\n💸Lotoreyaga chipta: ".($lottery?"Bor":"yoq")."\n\nSiz qo'shgan kanallar:\n";
		$temp1=true;
		foreach ($channels as $value) {
			if($temp1) $temp1=false;
			$temp.='<a href="https://telegram.me/'.$value[0].'">🔹 '.$value[1].'</a> ('.$value[2]." ta like)\nt.me/".BOT_USERNEME."?start=1-".$value[3]."\n\n";
		}
		if($temp1) $temp.="yo'q";
		return $temp;
	}
	function CHANNEL_POST($channel) {
		return '<a href="'.$channel->cover.'">▪️</a><b>Kanal</b> <a href="https://telegram.me/'.urlencode(substr($channel->username,1)).'">'.Telegrambot::HTML($channel->title)."</a>\n\n<b>📋Info:</b> <i>".Telegrambot::HTML($channel->description)."</i>\n\n<b>🔜Yo'nalish</b> <i>".Telegrambot::HTML($GLOBALS['CATEGORYS'][$channel->category])."</i>";
	}
	function T_INLINE($channel) { //'<a href="http://sn.uploads.im/iPLjx.jpg">👉</a>'
		return "Shu havola
👉 https://t.me/CatalogiyaBot?start=1-".$channel->id."
Orqali  🔹 ".$channel->username."   kanaliga <b>LIKE</b> bosib, 🔅sevimli kanalingizni qo'llab quvvatlang✔️

<i>Siz bu botda</i> <b>🔸LOTOREYA</b> <i>o'ynashingiz, o'z</i> <b>🔹OMAD</b><i>ingizni sinab ko'rishingiz mumkin💯</i>
<b>Batafsil👇👇</b>";
	}
	function T_SUPPORT($from) {
		return "Shu havola 
".'<a href="http://sn.uploads.im/iPLjx.jpg">👉</a>'." https://t.me/CatalogiyaBot?start=0-".$from->id."
Orqali kirib do'stingiz
🔹 ".'<a href="tg://user?id='.$from->id.'">'.$from->first_name.'</a>'." ni qo'llab quvvatlang✔️

<i>Siz bu botda</i> <b>🔸LOTOREYA</b> <i>o'ynashingiz, o'z</i> <b>🔹OMAD</b><i>ingizni sinab ko'rishingiz hamda</i>
 <b>🔺100ming 💸so'm</b> <i>pul yutug'iga ega bo'lishingiz mumkin💯</i>
<b>Batafsil👇👇</b>";
	}
	function T_SUPPORT_2($from) {
		return "Do'stinggiz ".'<a href="tg://user?id='.$from->id.'">'.$from->first_name.'</a>'." dan ochko";
	}
?>