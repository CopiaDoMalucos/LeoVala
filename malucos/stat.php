<?php
############################################################
#######                                             ########
#######                                             ########
#######           malucos-share.org 2.0             ########
#######                                             ########
#######                                             ########
############################################################
$host = "96.47.227.156";
$port = "9402"; // 8000
$na = "pasdispo.jpg"; // Image si le script ne trouve pas la pochette.
$rep = "pochettes"; // le fichier avec vos pochettes.
 $content = null;
$fp=@fsockopen($host,$port,&$errno,&$errstr,10);
if (!$fp)
{
$auditeurs = "0";
$places = "0";
$etat = "Impossible de se connecter au serveur !";
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
$etat = "<img src=./style/images/down.gif></a>";
}

else
{

// Serveur connecter stream ok //

$auditeurs = $option[0];
$pointe = $option[2];
$places = $option[3];
$bitrate = $option[5];
$titre = $option[6];
$etat = "<img src=./style/images/up.gif></a>";
}
}




echo"$etat";






?>
