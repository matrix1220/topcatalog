<?php
	const TOKEN = "438238300:AAEdydlMXwY81qXWZb4njw7YLhquOeKx0sg";
	const DB_HOST = "localhost";
	const DB_USER = "anderson";
	const DB_PASS = "a43jh5fA85y46g";
	const DB_NAME = "andersoneo";
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
	const B_SUBSCRIBE = "A'zo bo'lish";
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
	$MAIN_KEYBOARD=[[B_THE,B_ACCOUNT],[B_ADD,B_CATEGORYS],[B_LOTTERY,B_LUCK],[B_LN,B_EXTRA]];
	$EXTRA_KEYBOARD=[[B_CONTACT],[B_ABOUT],[B_BACK]];
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
Eng sara kanallar Katalogi
🌀 @CaTaLoGiYa kanalining robotiga Xush kelibsiz
⚠️Botdan to'liq foydalanish uchun 🌀 @Catalogiya kanaliga obuna bo'ling

Siz botda ➕Kanal qo'shish orqali o'z kanalingizni Katalogga qo'shishingiz mumkin
📒Katalogda mavzulashtirilgan kanallar jamlanmasini ko'rishingiz mumkin
💼Mening Profilimda
Qo'shgan kanallaringiz,
Ochkolaringiz va Lotoreyaga chipta bor yoki yoqligini ko'rishingiz hamda
 ochko to'plash menyusiga o'tib ochko to'plashingiz mumkin

🔹Lotoreya ochko to'plash uchun o'tkaziladigan, g'olib esa qatnashuvchilar orasidan tasodifiy tanlanadigan tekin o'yin 📦Omad qutisi  hamda 
🔢Omadli son ochko to'plash uchun o'tkaziladigan to'liq avtomatlashgan o'yinlardir

Zerikmaysiz degan umiddamiz😁";
	}
	const T_ABOUT = "Hurmatli obunachilar bu bot         🌀 @YIAMEGA buyurtmasiga binoan 🔹 @UzProBoys jamoasi tomonidan yasalgan.
Bu botni yasashda
🔹 @Topcatalogbot 
🔹 @Reclamarobot
🔹 @gruppala_bot va boshqa botlardan andoza olindi hamda quyidagi botlarni yasagan insonlarga o'z minnatdorchiligimizni bildiramiz. Hurmat ila 
🔹 @CatalogiyaBot";
	const T_LOTTERY = "<b>💸Lotoreya</b>

🔸Bu shunchaki omadlilarga kulib boqadigan to'liq avtomatlashgan tekin o'yin bo'lib, bot barcha qatnashuvchilardan bittasini tasodifiy ravishda tanlashi orqali g'olib aniqlanadi hamda o'yin g'olibi  333 ochkoga ega bo'ladi
🔹O'yinda qatnashish uchun shunchaki CHIPTA oling, balkim bugun OMAD sizga kulib boqar

📋Natijalar har kuni 
  🕦 21:00 da e'lon qilinadi

⚠️O'yinda faqat 
🔹 @CaTaloGiya  kanaliga a'zo bo'lganlargina qatnasha oladi";
	const T_LOTTERY_1 = "Siz chipta oldingiz.";
	const T_LOTTERY_E1 = 'Afsuski chipta qomadi';
	const T_LOTTERY_E2 = 'Siz allaqachon chiptani olib bo\'lgansiz.';
	const T_LOTTERY_E3 = "Kanalimizga ".CHANNEL." a'zo bo'ling va qaytadan urinib ko'ring";

	const T_0 = "Bosh menyu:";

	const T_1 = "Kanaliz katalogga qo'shilgach
🌀 @Catalogiya kanalida qancha vaqt TOPda turishini tanlang👇
▪️1 soat top: 300 ochko
▪️2 soat top: 700 ochko
▪️Tungi(noch): 1500 ochko

⚠️Kanaliz qo'shilgach birinchi TOPda turadi, Top vaqti tugagach lentada qoladi
Kanaliz ma'lum sabablarsiz lentadan o'chirilmaydi";
	const B_TOP_1 = "1 soat";
	const B_TOP_2 = "2 soat";
	const B_TOP_NIGHT = "Tungi";
	const ADD_1_BALL = 300;
	const ADD_2_BALL = 700;
	const ADD_NIGHT_BALL = 1500;
	const T_1E_1 = "Sizda yetarli ochko mavjud emas";
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

O'yinda qatnashish uchun siz
 1 dan 30 gacha bo'lgan ixtiyoriy son tanlaysiz. Agar siz tanlagan son bot  tasodifiy tarzda  tanlagan son bilan bir xil bo'lsa siz o'yin g'olibi bo'lasiz
⚠️Agar g'oliblar bir nechta bo'lsa yutuq g'oliblar o'rtasida teng bo'linadi
🕛O'yin bir kunda har 3 soatdan 5marta o'tkaziladi
🏆Jekpot 500ochko

⚠️O'yinda faqat @Catalogiya kanaliga a'zo bo'lganlargina qatnasha oladi";
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
🔹 ".'<a href="tg://user?id='.$from->id.'">'.Telegrambot::HTML($from->first_name).'</a>'." ni qo'llab quvvatlang✔️

<i>Siz bu botda</i> <b>🔸LOTOREYA</b> <i>o'ynashingiz, o'z</i> <b>🔹OMAD</b><i>ingizni sinab ko'rishingiz hamda</i>
 <b>🔺100ming 💸so'm</b> <i>pul yutug'iga ega bo'lishingiz mumkin💯</i>
<b>Batafsil👇👇</b>";
	}
	function T_SUPPORT_2($from) {
		return "Do'stinggiz ".'<a href="tg://user?id='.$from->id.'">'.Telegrambot::HTML($from->first_name).'</a>'." dan ochko";
	}
	const B_SHARE = "Ochko ulashish";
	const T_SHARE = "👤Do'stlaringizga o'z ochkolaringizni  ulashing hamda ularni quvontiring😁
🔘Bir kunda 200 ochko o'tkazishingiz mumkin";
	const B_BUY = "Ochko sotib olish";
	const T_BUY = "🔹1000 ochkoni atigi 🔸10ming so'mga sotib oling hamda ochkolardan unumli foydalaning✔️
Tolov turi  Click yoki Payme";
	const B_BUY_1 = "Sotib olaman";
	const T_BUY_1 = "Ochko sotib olish uchun 
▪️ @Professor111 ga yozing";
	const B_ADS_CHANNEL = "Shu joyga kanal qo'shish";
	const T_ADS_CHANNEL = "▪️Sizning kanalingizga Kanallarga qo'shilish funksiyasi orqali 🔹100 ta obunachi qo'shib berish
 atigi 🔸15ming so'm
To'lov turi: Click yoki Payme

⚠️Botdan kanalingizga 100 ta obunachi qo'shilgach avtomatik obunachi qo'shish jarayoni to'xtatiladi, 100+ bo'lgandan keyingi yo'qotishlar o'rni avtomatik to'ldirib turiladi, yani sifat saqlanib qolinadi‼️

🌀 @CaTaloGiYaBot 🔸Sifat va 🔹Ishonch Kafolati";
	const T_ADS_CHANNEL_1 = "Kanal qo'shish uchun 
▪️ @Professor111 ga yozing";
 function jakpot_winners($winners) {
 	$temp= "🔢Omadli son o'yinimizning g'oliblari bo'lgan omadli insonlar bilan tanishing👇\n";
 	if(empty($winners)) {
 		$temp.="\nYo'q";
 	} else foreach ($winners as $value) {
 		$temp.="\n🔘O'yin ".$value['id']."\n🔢Son ".$value['number']."\n🎗G'olib(lar) ";
 		if(empty($value['winners'])) {
 			$temp.="Yo'q";
 		} else foreach ($value['winners'] as $value1) {
 			$temp.="\n".'- <a href="tg://user?id='.$value1['user'].'">'.Telegrambot::HTML($value1['name']).'</a>';
 		}
 	}
	$temp.="\n\n✔️O'yinda qatnashing balkim bugun omad sizga kulib boqar😉";
	return $temp;
 }
 function jakpot_winner() {
 	return "🔘O'yin nomeri
🔢Son nomer
🎗G'olib nom
G'olibimizni qimmatli ochkolarga ega bo'lganligi bilan tabriklaymiz👏👏
O'yinda nomer odam qatnashdi";
 }
 const B_WINNERS = "G'oliblar";
 function lottery_winners($winners) {
  $temp = "🔹Lotoreya o'yinimizning g'oliblari bo'lgan omadli insonlar bilan tanishing👇\n";
  foreach ($winners as $value) {
  	$temp.="\n".date("d.m.y",$value->date).' <a href="tg://user?id='.$value->user.'">'.Telegrambot::HTML($value->name).'</a>';
  }
  $temp.="\n\n✔️O'yinda qatnashing balkim bugun omad sizga kulib boqar😉";
  return $temp;
}
// ➕Kanal qo'shish

// Kanaliz 🌀 @Catalogiya kanalida qancha vaqt TOPda turishini tanlang👇
// ▪️1-soat top: 500 ochko
// ▪️2-soat top: 700 ochko
// ▪️Tungi (Noch):1500 ochko
?>