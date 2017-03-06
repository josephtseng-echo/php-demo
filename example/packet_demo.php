<?php
include(__DIR__.'/../spl_autoload.php');

use Package\Write;

$pw = new Package\Write();
$pw->writeBegin("123");
$pw->writeString("abc");
$pw->writeEnd();
$buff = $pw->getPacketBuffer();



$pr = new Package\Read();
if($pr->readPackageBuffer($buff) == true){
	//print_r($pr->getHeaderInfo());
	//echo $pr->getCmd();
}

//test class EncryptDecrypt
$ed = new Package\EncryptDecrypt();
$str = "123";
echo $str."\n";
$buff = pack("N", $str);
echo $buff."\n";;
$e = $ed->encryptBuffer($buff, 0, strlen($buff));
echo $e."\n";
$d = $ed->decryptBuffer($buff, strlen($buff), $e);
var_dump($d)."\n";
echo $buff."\n";
$ua = unpack('Na', $buff);
echo $ua['a']."\n";