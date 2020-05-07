<?php
begin_block("Avaliação");
$id = (int) $_GET["id"];
$scrape = (int)$_GET["scrape"];
if (!is_valid_id($id))
	show_error_msg("ERROR", T_("THATS_NOT_A_VALID_ID"), 1);


$res = SQL_Query_exec("SELECT torrents.anon, torrents.seeders, torrents.banned, torrents.leechers, torrents.info_hash, torrents.filename, torrents.nfo, torrents.last_action, torrents.numratings, torrents.name, torrents.owner, torrents.save_as, torrents.descr, torrents.visible, torrents.size, torrents.added, torrents.views, torrents.hits, torrents.times_completed, torrents.id, torrents.type, torrents.external, torrents.image1, torrents.image2, torrents.announce, torrents.numfiles, torrents.freeleech, IF(torrents.numratings < 0, NULL, ROUND(torrents.ratingsum / torrents.numratings, 0)) AS rating, torrents.numratings, categories.name AS cat_name, torrentlang.name AS lang_name, torrentlang.image AS lang_image, categories.parent_cat as cat_parent, users.username, users.privacy FROM torrents LEFT JOIN categories ON torrents.category = categories.id LEFT JOIN torrentlang ON torrents.torrentlang = torrentlang.id LEFT JOIN users ON torrents.owner = users.id WHERE torrents.id = $id");
$row = mysql_fetch_assoc($res);
// $srating IS RATING VARIABLE
		$srating = "";
		$srating .= "<b></b>";
		if (!isset($row["rating"])) {
				$srating .= "Nenhum voto";
		}else{
			$rpic = ratingpic($row["rating"]);
			if (!isset($rpic))
				$srating .= "invalid?";
			else
				$srating .= "$rpic <br>Nota " . $row["rating"] . " <br>Votos " . $row["numratings"] . "<br> ";
		}
		$srating .= "\n";
		if (!isset($CURUSER))
			$srating .= "(<a href=\"account-login.php?returnto=" . urlencode($_SERVER["REQUEST_URI"]) . "&amp;nowarn=1\">Log in</a> to rate it)";
		else {
			$ratings = array(
					5 => 'Excelente',
					4 => 'Ótimo',
					3 => 'Bom',
					2 => 'Ruim',
					1 => 'Horrível'
			);
			//if (!$owned || $moderator) {
				$xres = SQL_Query_exec("SELECT rating, added FROM ratings WHERE torrent = $id AND user = " . $CURUSER["id"]);
				$xrow = mysql_fetch_assoc($xres);
				if ($xrow)
					$srating .= "<br /><i>Sua avaliação: <br>\"" . $xrow["rating"] . " - " . $ratings[$xrow["rating"]] . "\"</i>";
				else {
					$srating .= "<form style=\"display:inline;\" method=\"post\" action=\"torrents-details.php?id=$id&amp;takerating=yes\"><input type=\"hidden\" name=\"id\" value=\"$id\" />\n";
					$srating .= "<select name=\"rating\">\n";
					$srating .= "<option value=\"0\">(Escolha)</option>\n";
					foreach ($ratings as $k => $v) {
						$srating .= "<option value=\"$k\">$k - $v</option>\n";
					}
					$srating .= "</select>\n";
					$srating .= "<br><input type=\"submit\" value=\"Avaliar\" />";
					$srating .= "</form>\n";
				}
			//}
		}
		$srating .= "";

print("<center>". $srating . "</center>");// rating


end_block();
?>