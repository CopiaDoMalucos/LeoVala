<?php


$ip = "95.130.9.156"; 
// Mettre ici l'IP ou host de votre serveur Shoutcast. Ne pas mettre http://
// devant 
$port = "8000"; 
// placer ici le port du serveur Shoutcast. // 
$na = "defaut.jpg"; // Image si le script ne trouve pas la pochette.
$rep = "pochettes"; // le fichier avec vos pochettes.
// 
// 
// fin de la configuration // 


$fp = @fsockopen("$ip", "$port", $errno, $errstr, 30); 

if(!$fp) 
{ 
$auditeurs = "0"; 
$places = "0"; 
$etat = "Serveur d&eacute;connect&eacute;."; 
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
$etat = "Nenhum servidor de fonte ligada."; 
} 

else 
{ 

// Serveur connecté stream ok // 

$auditeurs = $option[0]; 
$pointe = $option[2]; 
$places = $option[3]; 
$bitrate = $option[5]; 
$titre = $option[6]; 
$etat = "Fonte servidor conectado conectado Ok !"; 
} 
} 

$titre = str_replace("&yuml;","&eacute;",$titre); 




echo "<b><SMALL><font color='navy'>$titre</font></small></b>";
 


// Place values from source into variable names
list($current,$status,$peak,$max,$reported,$bit,$song) = explode(",", $fp_data, 
7);

$trackpattern = "/^[0-9][0-9] /";
$trackreplace = "";
$song = preg_replace($trackpattern, $trackreplace, $song);

if ($status == "1") {
$par=explode(' - ', $song, 2);


if (file_exists($rep."/".$song.".png")) {
echo "<img border=\"0\" src=\"".$rep."/".$song.".png\">"; 
    } else {
if (file_exists($rep."/".$song.".jpg")) {
echo "<img border=\"0\" src=\"".$rep."/".$song.".jpg\">"; 
    } else {
if (file_exists($rep."/".$song.".jpeg")) {
echo "<img border=\"0\" src=\"".$rep."/".$song.".jpeg\">"; 
    } else {
if (file_exists($rep."/".$song.".gif")) {
echo "<img border=\"0\" src=\"".$rep."/".$song.".gif\">"; 
    } else {
echo "<img border=\"0\" src=\"".$rep."/".$na."\">"; 
}
}}}}





?>

