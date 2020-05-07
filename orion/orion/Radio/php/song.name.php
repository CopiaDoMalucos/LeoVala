<?php

$timeout = "1";
$ip = $_GET["ip"];
$port = $_GET["port"];

$fp = @fsockopen($ip,$port,$errno,$errstr,$timeout);
if (!$fp)
{
	$result = "";
}
else
{
	
	fputs($fp, "GET /7.html HTTP/1.0\r\nUser-Agent: Mozilla\r\n\r\n");
	while (!feof($fp)) 
	{
		
		$info = fgets($fp);
		
	}
	
	$info = str_replace('<HTML><meta http-equiv="Pragma" content="no-cache"></head><body>', "", $info);
	$info = str_replace('</body></html>', "", $info);
	$stats = explode(',', $info);
	if (empty($stats[1]) )
	{
		$result = "";
	}
	else
	{
		if ($stats[1] == "1")
		{
			$result = $stats[6];
		}
	}
}

echo "&_result=".$result."&";

?>