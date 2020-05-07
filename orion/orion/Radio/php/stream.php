<?php

	set_time_limit(0);

	$url = urldecode($_GET["url"]);
	
	if($_GET["ping"]=="true")
		$ping = true;
	else
		$ping = false;

	$tmp = parse_url($url);

	$streamname = $tmp["host"]; 
	$port = (int)($tmp["port"]);
	$path = $tmp["path"];
		
	if(empty($path)) $path = "/";
	
	if($ping)
		$time_out = 1;
	else
		$time_out = 5;
	
	
	$sock = @fsockopen($streamname, $port, $errno, $errstr, $time_out);
	
	if($sock)
	{
		@fputs($sock, "GET $path HTTP/1.0\r\n");
		@fputs($sock, "Host: $streamname\r\n");
		@fputs($sock, "User-Agent: WinampMPEG/2.8\r\n");

		@fputs($sock, "Connection: close\r\n\r\n");
		
		
		
        if($ping)
		{
			$contents = @fread($sock, 8);
			echo $contents;
		}
		else
		{
			while($contents = @fread($sock, 524))
			{
				echo $contents;
			}
		}
		fclose($sock);
	}
?>