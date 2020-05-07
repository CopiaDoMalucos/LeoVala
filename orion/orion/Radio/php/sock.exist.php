<?php
	if(function_exists("fsockopen"))
	{
		$result = "&result=1&";
	}
	else
	{
		$result = "&result=0&";
	}
	echo $result;
?>