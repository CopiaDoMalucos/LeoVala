<?php


// Entrez L'IP de votre serveur et sont PORT (exemple : xx.xxx.xxx.xx:8000).
$host = "95.130.9.156";
$port = "8000";

// Connection au Serveur
$fp=@fsockopen($host,$port,&$errno,&$errstr,10);
if (!$fp) {
echo "N�o � poss�vel conectar ao servidor !";
} else {

// On obient les donn�es du serveur
fputs($fp,"GET /7 HTTP/1.1\nUser-Agent:Mozilla\n\n");

// Sortie au cas o� il y a plus de connection
for($i=0; $i<1; $i++) {
if(feof($fp)) break;
$fp_data=fread($fp,31337);
usleep(500000);
}

// Bande de donn�es ind�sirables. Inutiles � la source
$fp_data=ereg_replace("^.*<body>","",$fp_data);
$fp_data=ereg_replace("</body>.*","",$fp_data);

// On place les valeurs de la source dans les noms de variables
list($current,$status,$peak,$max,$reported,$bit,$song) = explode(",", $fp_data, 
7);

if ($status == "1") {
// Pour utiliser l'une des sorties ci-dessous. D�commenter (enlever le double
// slash) de cette ligne.



echo 
"<html>\n<head>\n<title></title>\n</head>\n<body>\nNombre D'auditeurs :" .
" $current<br>\nStatut du Serveur : $status<br>\nNombre D'auditeurs Maximum :" .
" $max<br>\nQualit� de Diffusion : $bit Kb/s<br>$genre<br></body>\n</html>";


} else {
echo "R�dio est� offline !";
} }

?>

