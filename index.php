<?
//C0=21232f297a57a5a743894a0e4a801fc3; C1=d88309b8d08d8581cb8a56a84e1af9a5
foreach (scandir('.') as $file) if(substr($file,0,7)=='access-') if(is_file($file)) if((include $file)===true) exit;

$file=substr($_SERVER['REQUEST_URI'],1);
$temp=strpos($file,'?');
if($temp===false) {$temp=strlen($file);}
$file=substr($file,0,$temp);
if(is_file('public/'.$file)) include 'public/'.$file;
else echo 'Salom Dunyo';
?>