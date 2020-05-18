<?php
if (!$site_config["MEMBERSONLY"] || $CURUSER) {
	begin_block(T_("LATEST_TORRENTS"));

	$expire = 10; // time in seconds

$wherea=array();
$wherea[] = "safe = 'yes'";


if ($CURUSER["ver_xxx"]!="yes") {
    $wherea[] = "torrents.category != '106'";
     $wherea[] = "torrents.category != '104'";
    $wherea[] = "torrents.category != '47'";

}
$where = implode(" AND ", $wherea);
//Mod Hide XXX
	if (($latestuploadsrecords = $TTCache->Get("latestuploadsblock", $expire)) === false) {
		$latestuploadsquery = SQL_Query_exec("SELECT id, name, size, seeders, leechers FROM torrents WHERE banned='no' AND visible = 'yes' AND ". $where ." ORDER BY id DESC LIMIT 7");

		$latestuploadsrecords = array();
		while ($latestuploadsrecord = mysql_fetch_assoc($latestuploadsquery))
			$latestuploadsrecords[] = $latestuploadsrecord;
		$TTCache->Set("latestuploadsblock", $latestuploadsrecords, $expire);
	}

	if ($latestuploadsrecords) {
		foreach ($latestuploadsrecords as $row) { 
			$char1 = 100; //cut length 
			$smallname = htmlspecialchars(CutName($row["name"], $char1));
		
	?>

			<div style="padding: 14px 3px 14px 3px; border-bottom: #dddddd 1px solid;;">

			<?php
		
			echo "<a href='torrents-details.php?id=$row[id]' title='".htmlspecialchars($row["name"])."'>$smallname</a><br />\n";
			echo "- [".T_("SIZE").": ".mksize($row["size"])."]<br /><br />\n";
					?>
				</div>
			<?php
		}
	} else {
		print("<center>".T_("NOTHING_FOUND")."</center>\n");
	}
	end_block();
}
?>