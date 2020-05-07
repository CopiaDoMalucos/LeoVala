<?php
############################################################
#######                                             ########
#######                                             ########
#######           malucos-share.org 2.0             ########
#######                                             ########
#######                                             ########
############################################################
require_once("backend/functions.php");
dbconn(false);
loggedinonly();  
//check permissions
if ($site_config["MEMBERSONLY"]){
	loggedinonly();

	if($CURUSER["view_torrents"]=="no")
		show_error_msg(T_("ERROR"), T_("NO_TORRENT_VIEW"), 1);
}

stdhead(T_("TODAYS_TORRENTS"));

begin_framec(T_("TODAYS_TORRENTS"));


$data2 = date('Y-m-d H:i:s');  
$data21 = date("d/m/Y", utc_to_tz_time($data2));  


	$catresult = SQL_Query_exec("SELECT id, name FROM categories ORDER BY sort_index");

		while($cat = mysql_fetch_assoc($catresult))
		{
			$orderby = "ORDER BY torrents.id DESC"; //Order
	$wherea=array();

$wherea[] = "visible = 'yes'";

if ($CURUSER["ver_xxx"]!="yes") {
    $wherea[] = "torrents.category != '106'";
     $wherea[] = "torrents.category != '104'";
    $wherea[] = "torrents.category != '47'";

}
$where = implode(" AND ", $wherea);

			$where = "WHERE banned = 'no' AND category='$cat[id]' AND visible='yes' AND safe='yes' AND ". $where ." ";
			$limit = "LIMIT 10"; //Limit

			$query = "SELECT torrents.id, torrents.anon, torrents.category, torrents.leechers, torrents.nfo, torrents.seeders, torrents.name, torrents.times_completed, torrents.size, torrents.added, torrents.comments, torrents.numfiles, torrents.filename, torrents.owner, torrents.external, torrents.freeleech, categories.name AS cat_name, categories.parent_cat AS cat_parent, categories.image AS cat_pic, users.username, users.privacy FROM torrents LEFT JOIN categories ON category = categories.id LEFT JOIN users ON torrents.owner = users.id $where AND date_format(torrents.added,'%d/%m/%Y')='$data21' $orderby $limit";

			$res = SQL_Query_exec($query);
			$numtor = mysql_num_rows($res);

			if ($numtor != 0) {
					echo "<b><a href='torrents.php?cat=".$cat["id"]."'>$cat[name]</a></b>";
					# Got to think of a nice way to display this.
                    #list($pagertop, $pagerbottom, $limit) = pager(1000, $count, "torrents.php"); //adjust pager to match LIMIT
					torrenttable($res);
					echo "<br />";
			}
		

		}
end_framec();
stdfoot();
?>