<?php

require("backend/functions.php");

dbconn();
loggedinonly();
//loggedinorreturn();

if (strlen($_GET['q']) > 3) {
	$q = str_replace(" ",".",sqlesc("%".$_GET['q']."%"));
	$q2 = str_replace("."," ",sqlesc("%".$_GET['q']."%"));
	$result = mysql_query("SELECT torrents.name,torrents.id, torrents.size FROM torrents WHERE torrents.name LIKE {$q} OR torrents.name LIKE {$q2} ORDER BY id DESC LIMIT 0,10;");

	if (mysql_numrows($result) > 0) {
		for ($i = 0; $i < mysql_numrows($result); $i++) {
			$name = mysql_result($result,$i,"name");
			$name = trim(str_replace("\t","",$name));
			$id = mysql_result($result,$i,"id");
		        $size = mysql_result($result,$i,"size");

			$teste = "<a href=torrents-details.php?id=$id  target='_blank'><u><b>$name</b></u></a>[" . mksize($size) . "]";
			print $teste;
			if ($i != mysql_numrows($result)-1) {
				print "\r\n";
			}
		}
	}
}

?>
