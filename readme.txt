users
 id [primary]
 action
 ball


channels
 category
 id
 telegram_id
 title
 added
 username
 cover
 description
 tags
 status [0 - adding, 1 - added, 2 - checked, 3 - unaccepted, 4 - canceled], 5 - aksiya

likes
 user
 channel

lottery
 id
 user

followers
 user
 channel

ad_channels
 channel
 bfollowers

 Actions:
  0 - bosh menyu:
   1 - kanal qo'shish:(username)
    3 = yo'nalish
    4 = batafsil
    5 = logo
    6 = tasdiqlash
   2 - katalog
   8 - like
   # - biz bilan aloqa

 /start
  Salom, hushkelibsiz!
   Kanal qo'shish
    Bizning @yiacatalog kanalimizga azo bo'ling
    Kanal @username sini yozing
     @username
      Kanal yo'nalishini tanlang
       Dastur va o'yinlar
        Kanal haqida batafsil yozing
         Eng sara o'yin va dasturlar (apk)
          Endi kamida 3 ta teg yozing
           app game apk
            Barchasi to'g'rimi
             Ha  
   Biz haqimizda
    @gruppamizga kiring
   Yo'nalishlar
    Tanlang
     Dasturlar va o'yinlar

Yo'nalishlar:
 Dastur va o'yinlar
 Musiqa
 Tabiat
 Kitob va jurnallar
 Texnologiya


- siz azo bo'lmagan kanallar
- siz azo bo'lgan kallar
- hozir azo bo'lgan kanallar
- hozir chiqib ketgan kanallar



channels_history
id
channel
type [1,2,3]
status [0-jo'natilgan,1-activ,2-activ emas]

lottery_winners
 date
 user
 name