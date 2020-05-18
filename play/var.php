<? 
include ("radio.php");
$open = fsockopen($radio , $port);  
if ($open) { 
fputs($open,"GET /7.html HTTP/1.1\nUser-Agent:Mozilla\n\n"); 
$read = fread($open,1000); 
$text = explode("content-type:text/html",$read); 
$text = explode(",",$text[1]); 
} else { $er="Conexão negada!"; } 
$spilles = "$text[6]";
echo "&spilles=$spilles";


?> 