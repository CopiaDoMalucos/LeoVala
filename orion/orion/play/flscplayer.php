<html><style type="text/css">
<!--
body {
	background-color: #000000;
}
-->
</style>
<div align="center">
  <?php
//The next lines is for randomizing the URL to prevent caching of the player in the browser
srand(time());
$random = (rand());


include ("radio.php");

//Connecting and creating stats from streaming server
$open = fsockopen($radio , $port); 
if ($open) { 
fputs($open,"GET /7.html HTTP/1.1\nUser-Agent:Mozilla\n\n"); 
$read = fread($open,1000); 
$text = explode("content-type:text/html",$read); 
$text = explode(",",$text[1]);
} else { $er="Conexão negada!"; }




$host = "malucos-share.net";
$port = "8000"; // 8000
$na = "pasdispo.jpg"; // Image si le script ne trouve pas la pochette.
$rep = "pochettes"; // le fichier avec vos pochettes.

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


}
}

$titre = str_replace("ÿ","é",$titre); // on remplace comme on a dit précédement dans le topic //











?>
  
<br><b><font color="#ff0000">Rádio Crazys</font><br>

  </b><br>

  <object width="550" height="400">
    <param name="movie" value="flscplayer_2.3b.swf&amp;volume=50">
    <embed src="flscplayer_2.3b.swf?cachekiller<?php print $random;?>&amp;volume=50" width="550" height="400">
    </embed>
  </object>
</div>
</html>