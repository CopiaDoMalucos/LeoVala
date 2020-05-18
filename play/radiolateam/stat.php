<?php
$host = "88.171.19.27";
$port = "8000"; // 8000
$na = "pasdispo.jpg"; // Image si le script ne trouve pas la pochette.
$rep = "pochettes"; // le fichier avec vos pochettes.

$fp=@fsockopen($host,$port,&$errno,&$errstr,10);
if (!$fp)
{
$auditeurs = "0";
$places = "0";
$etat = "Não é possível conectar ao servidor !";
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

$titre = str_replace("ÿ","é",$titre); // on remplace comme on a dit précédement dans le topic //


echo"<SMALL><font color='white'> Actuellement $auditeurs auditeur(s) sur $places places.<br \>";

echo"Pointe de $pointe auditeur(s) sur ce serveur.<br \>";

echo"Actuellement stream a : $bitrate kbps.<br \>";

echo"<u>Etat du serveur :</u><br>$etat<br \></font></small>";




?>
