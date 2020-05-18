<?php
############################################################
#######                                             ########
#######                                             ########
#######           www.brshares.com 2.0              ########
#######                                             ########
#######                                             ########
############################################################
error_reporting(0);
require_once("backend/functions.php");
dbconn(false);
?>
<script language="JavaScript">
<!--
function radio_player() {
props=window.open('radio.php', 'poppage', 'toolbars=0, scrollbars=0, location=0, statusbars=0, menubars=0, resizable=0, width=350, height=100 left = 100, top = 100');
}
-->
</script>
<?php
error_reporting(0);
error_reporting(0);
require_once("backend/functions.php");
dbconn(false);
$scdef = "spank-d-monkey RADIO";
$scip = "74.222.3.252"; // IP OR DOMAIN
$scport = "14554 "; // PORT
$scpass = "##123malucos";


$bgcolor = "#000000";



$scfp = fsockopen("$scip", $scport, &$errno, &$errstr, 30);
if(!$scfp) {
$scsuccs=1;
echo''.$scdef.' is Offline';
}
if($scsuccs!=1){
fputs($scfp,"GET /admin.cgi?pass=$scpass&mode=viewxml HTTP/1.1\r\nUser-Agent: SHOUTcast Song Status (Mozilla Compatible)\r\n\r\n");
while(!feof($scfp)) {
$page .= fgets($scfp, 1000);
}




$loop = array("STREAMSTATUS", "BITRATE", "SERVERTITLE", "CURRENTLISTENERS");
$y=0;
while($loop[$y]!=''){
$pageed = ereg_replace(".*<$loop[$y]>", "", $page);
$scphp = strtolower($loop[$y]);
$$scphp = ereg_replace("</$loop[$y]>.*", "", $pageed);
if($loop[$y]==SERVERGENRE || $loop[$y]==SERVERTITLE || $loop[$y]==SONGTITLE || $loop[$y]==SERVERTITLE)
$$scphp = urldecode($$scphp);

// uncomment the next line to see all variables
//echo'$'.$scphp.' = '.$$scphp.'<br>';
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
if ($showradioinfo=="on"){
if ($streamstatus == "1"){
$rstatus = "Playing";}
else { $rstatus = "Stopped";}

echo "<hr>Radio Status : ".$rstatus."<br>";
echo "Current Listeners ".$currentlisteners."<br>";
echo "Listening to......<br> ".$song[0];
echo "<br><a href'myradio.php'>Listen Now</a>";}

if ($showradioinfo=="page") {
if ($streamstatus == "1"){
$radioimg = "radio/radio-online.png";}
else{
$radioimg = "radio/radio-offline.png";}}

?>