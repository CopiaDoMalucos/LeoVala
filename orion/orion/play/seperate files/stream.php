<?php
// Mike Gieson
// www.wimpyplayer.com
// © 2006 Plaino
// v1.0


// Settings
$host = "pluto";				// Shoutcast Host
$port = "8090";					// Shoutcast Port
$mount = "/";					// Used for alternate path to "Streaming URL" -- leave as "/" for the default setup.


// Make socket connection
$errno = "errno";
$errstr = "errstr";
$fp = fsockopen($host, $port, $errno, $errstr, 30);


// Establish response headers
header("HTTP/1.0 200 OK");
header("Content-Type: audio/x-mpeg, audio/x-mpeg-3, audio/mpeg3");
header("Content-Transfer-Encoding: binary");


// Content-Length is required for Internet Explorer:
// - Set to a rediculous number
// = I think the limit is somewhere around 420 MB
header("Content-Length: 100000000");


// Create send headers
$out = "GET $mount HTTP/1.1\r\n";
$out .= "Host: $host\r\n";
$out .= "Connection: Close\r\n\r\n";


// Write the returned data back to the resource
fwrite($fp, $out);


// Read resource
while (!feof($fp)) {

	// Get data in 2048 chuncks
	$outData = fgets($fp, 2048);

	// Removing shoutcast headers.
	if (!stristr($outData, "icy") && !stristr($outData, "content")){
		echo $outData;
	}

}

fclose($fp);


?> 