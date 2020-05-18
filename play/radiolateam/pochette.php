<?php
$host = "187.45.245.85";
$port = "8000"; // 8000
$na = "pasdispo.jpg"; // Image si le script ne trouve pas la pochette.
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

if (file_exists($rep."/".$song.".png")) {
echo "<div id=\"pochette\"><img border=\"0\" height=\"130\" width=\"130\" src=\"".$rep."/".$song.".png\"><br></div>";
    } else {
if (file_exists($rep."/".$song.".jpg")) {
echo "<div id=\"pochette\"><img border=\"0\" height=\"130\" width=\"130\" src=\"".$rep."/".$song.".jpg\"><br></div>";
    } else {
if (file_exists($rep."/".$song.".jpeg")) {
echo "<div id=\"pochette\"><img border=\"0\" height=\"130\" width=\"130\" src=\"".$rep."/".$song.".jpeg\"><br></div>";
    } else {
if (file_exists($rep."/".$song.".gif")) {
echo "<div id=\"pochette\"><img border=\"0\" height=\"130\" width=\"130\" src=\"".$rep."/".$song.".gif\"><br></div>";
    } else {
echo "<div id=\"pochette\"><img border=\"0\" height=\"130\" width=\"130\" src=\"".$rep."/".$na."\"><br></div>";
}
}}}}}




?>
