<body onload="window.setTimeout ('history.go (0)',20000)">
<center>
<?php
$host = "malucos-share.net";
$port = "8000"; // 8000
$na = "pasdispo.jpg"; // Image si le script ne trouve pas la pochette.
$rep = "pochettes"; // le fichier avec vos pochettes.

$fp = @fsockopen("$ip", "$port", $errno, $errstr, 30);

if(!$fp)
{
$auditeurs = "0";
$places = "0";
$etat = "Serveur connecté.";
}

else
{
fputs($fp, "GET /7.html HTTP/1.0\r\nUser-Agent: Mozilla/4.0\r\n\r\n");

while(!feof($fp))
{
$content .= fgets($fp,128);
}

fclose($fp);
$debut = strpos($content, '<body>') + strlen('<body>');
$fin = strpos($content, '</body>', $debut);
$servi = substr($content, $debut, $fin - $debut);
$option = explode(',', $servi);

if ($option[1] == 0)
{

// Serveur connecter aucune source stream //

$auditeurs = "0";
$places = "0";
$etat = "<img src=down.gif></a>";
}

else
{

// Serveur connecter stream ok //

$auditeurs = $option[0];
$pointe = $option[2];
$places = $option[3];
$bitrate = $option[5];
$titre = $option[6];
$etat = "<img src=up.gif></a>";
}
}

$titre = str_replace("ÿ","é",$titre); // on remplace comme on a dit précédement dans le topic //


echo"Actuellement $auditeurs auditeur(s) sur $places places.<br \>";

echo"Pointe de $pointe auditeur(s) sur ce serveur.<br \>";

echo"Actuellement stream a : $bitrate kbps.<br \>";

echo"<u>Etat du serveur :</u><br>$etat<br \>";

// Connect to server
$fp=@fsockopen($host,$port,&$errno,&$errstr,10);
if (!$fp) {
    echo "";
  } else {

// Get data from server
fputs($fp,"GET /7 HTTP/1.1\nUser-Agent:Mozilla\n\n");

// exit if connection broken
for($i=0; $i<1; $i++) {
if(feof($fp)) break;
$fp_data=fread($fp,31337);
usleep(500000);
}

// Strip useless junk from source data
$fp_data=ereg_replace("^.*<body>","",$fp_data);
$fp_data=ereg_replace("</body>.*","",$fp_data);

// Place values from source into variable names
list($current,$status,$peak,$max,$reported,$bit,$song) = explode(",", $fp_data, 7);

$trackpattern = "/^[0-9][0-9] /";
$trackreplace = "";
$song = preg_replace($trackpattern, $trackreplace, $song);

if ($status == "1") {
$par=explode(' - ', $song, 2);


if (file_exists($rep."/".$song.".png")) {
echo "<img border=\"0\" src=\"".$rep."/".$song.".png\"><br>";
    } else {
if (file_exists($rep."/".$song.".jpg")) {
echo "<img border=\"0\" src=\"".$rep."/".$song.".jpg\"><br>";
    } else {
if (file_exists($rep."/".$song.".jpeg")) {
echo "<img border=\"0\" src=\"".$rep."/".$song.".jpeg\"><br>";
    } else {
if (file_exists($rep."/".$song.".gif")) {
echo "<img border=\"0\" src=\"".$rep."/".$song.".gif\"><br>";
    } else {
echo "<img border=\"0\" src=\"".$rep."/".$na."\"><br>";
}
}}}}}
echo"<u>Actuellement :</u><br> $song "


?>
</center>