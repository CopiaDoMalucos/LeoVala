<?php

include "config.php";

ini_set("display_errors", "0");

$scfp = fsockopen($scip, $scport, &$errno, &$errstr, 1);
 if(!$scfp) {
  $scsuccs=1;
 }
if($scsuccs!=1){
 fputs($scfp,"GET /admin.cgi?pass=$scpass&mode=viewxml HTTP/1.0\r\nUser-Agent: SHOUTcast Song Status (Mozilla Compatible)\r\n\r\n");
 while(!feof($scfp)) {
  $page .= fgets($scfp, 1000);
 }


 $loop = array("STREAMSTATUS", "BITRATE", "SERVERTITLE", "CURRENTLISTENERS", "MAXLISTENERS", "BITRATE");
 $y=0;
 while($loop[$y]!=''){
  $pageed = ereg_replace(".*<$loop[$y]>", "", $page);
  $scphp = strtolower($loop[$y]);
  $$scphp = ereg_replace("</$loop[$y]>.*", "", $pageed);
  if($loop[$y]==SERVERGENRE || $loop[$y]==SERVERTITLE || $loop[$y]==SONGTITLE || $loop[$y]==SERVERTITLE)
   $$scphp = urldecode($$scphp);

  $y++;
 }

 $pageed = ereg_replace(".*<SONGHISTORY>", "", $page);
 $pageed = ereg_replace("<SONGHISTORY>.*", "", $pageed);
 $songatime = explode("<SONG>", $pageed);
 $r=1;
 while($songatime[$r]!=""){
  $t=$r-1;
  $playedat[$t] = ereg_replace(".*<PLAYEDAT>", "", $songatime[$r]);
  $playedat[$t] = ereg_replace("</PLAYEDAT>.*", "", $playedat[$t]);
  $song[$t] = ereg_replace(".*<TITLE>", "", $songatime[$r]);
  $song[$t] = ereg_replace("</TITLE>.*", "", $song[$t]);
  $song[$t] = urldecode($song[$t]);
  $dj[$t] = ereg_replace(".*<SERVERTITLE>", "", $page);
  $dj[$t] = ereg_replace("</SERVERTITLE>.*", "", $pageed);
$r++;
 }

fclose($scfp);
}

if(isset($_GET['status'])){
  $status = $_GET['status'];
} else{
  $status = "stop";
}
if(isset($_GET['z'])){
  $z = $_GET['z'];
} else{
  $z = "wmp";
}

if ($z == 'wmp'){
  $mode = 'real';
} elseif ($z == 'real'){
  $mode = 'wmp';
}
?>