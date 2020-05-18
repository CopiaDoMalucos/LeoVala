<?php
/*
Code développé par la société Québec Servers. Copyright Quebec Servers, toute utilisation commerciale, redistribution, copie, modifications autres que pour votre usage sont proscrits.

Merci de votre coopération.
*/
$host = "95.130.9.156";
$port = "8000"; // 8000
$na = "na.gif"; // Image si le script ne trouve pas la pochette.
$rep = "pochettes"; // le fichier avec vos pochettes.



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


if (file_exists($rep."/".$par[0].".png")) {
echo "<img border=\"0\" src=\"".$rep."/".$par[0].".png\">";
    } else {
if (file_exists($rep."/".$par[0].".jpg")) {
echo "<img border=\"0\" src=\"".$rep."/".$par[0].".jpg\">";
    } else {
if (file_exists($rep."/".$par[0].".jpeg")) {
echo "<img border=\"0\" src=\"".$rep."/".$par[0].".jpeg\">";
    } else {
if (file_exists($rep."/".$par[0].".gif")) {
echo "<img border=\"0\" src=\"".$rep."/".$par[0].".gif\">";
    } else {
echo "<img border=\"0\" src=\"".$rep."/".$na."\">";
}
}}}}}

?>