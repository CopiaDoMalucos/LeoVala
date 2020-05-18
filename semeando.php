<?php
############################################################
#######                                             ########
#######                                             ########
#######           www.brshares.com 2.0              ########
#######                                             ########
#######                                             ########
############################################################
require_once("backend/functions.php");
dbconn();
loggedinonly();


stdhead("User CP");

$id = (int)$_GET["id"];

if (!is_valid_id($id))
  show_error_msg(T_("NO_SHOW_DETAILS"), "Bad ID.",1);

$r = @SQL_Query_exec("SELECT * FROM users WHERE id=$id");
$user = mysql_fetch_array($r) or  show_error_msg(T_("NO_SHOW_DETAILS"), T_("NO_USER_WITH_ID")." $id.",1);
if ( $CURUSER['id'] == $user['id'] || $CURUSER["level"]=="Administrador" || $CURUSER["level"]=="Moderador"  || $CURUSER["level"]=="Sysop") {
//add invites check here

if (($user["enabled"] == "no" || ($user["status"] == "pending")) && $CURUSER["edit_users"] == "no")
	show_error_msg(T_("ERROR"), T_("NO_ACCESS_ACCOUNT_DISABLED"), 1);

//get all vars first

//$country
$res = SQL_Query_exec("SELECT name FROM countries WHERE id=$user[country] LIMIT 1");
if (mysql_num_rows($res) == 1){
	$arr = mysql_fetch_assoc($res);
	$country = "$arr[name]";
}

if (!$country) $country = "<b>Unknown</b>";

//$ratio
if ($user["downloaded"] > 0) {
    $ratio = $user["uploaded"] / $user["downloaded"];
}else{
	$ratio = "---";
}

$numtorrents = get_row_count("torrents", "WHERE owner = $id");
$numcomments = get_row_count("comments", "WHERE user = $id");
$numforumposts = get_row_count("forum_posts", "WHERE userid = $id");

$avatar = htmlspecialchars($user["avatar"]);
	if (!$avatar) {
		$avatar = $site_config["SITEURL"]."/images/default_avatar.gif";
	}

function peerstable($res){
	$ret = "<table align='center' cellpadding=\"3\" cellspacing=\"0\" class=\"table_table\" width=\"100%\" border=\"1\"><tr><th class='table_head'>".T_("NAME")."</th><th class='table_head'>".T_("SIZE")."</th><th class='table_head'>" .T_("UPLOADED"). "</th>\n<th class='table_head'>" .T_("DOWNLOADED"). "</th><th class='table_head'>" .T_("RATIO"). "</th></tr>\n";




if ($CURUSER["ver_xxx"]!="yes") {
$wherea=array();
    $wherea[] = "torrents.category != '106'";
     $wherea[] = "torrents.category != '104'";
    $wherea[] = "torrents.category != '47'";
$where = implode(" AND ", $wherea);
}

	while ($arr = mysql_fetch_assoc($res)){
		$res2 = SQL_Query_exec("SELECT name,size FROM torrents WHERE id=$arr[torrent] AND ". $where ."  AND safe='yes' ORDER BY name");
		$arr2 = mysql_fetch_assoc($res2);
		if ($arr["downloaded"] > 0){
			$ratio = number_format($arr["uploaded"] / $arr["downloaded"], 2);
		}else{
			$ratio = "---";
		}
		$ret .= "<tr><td class='table_col1'><a href='torrents-details.php?id=$arr[torrent]&amp;hit=1'><b>" . htmlspecialchars($arr2["name"]) . "</b></a></td><td align='center' class='table_col2'>" . mksize($arr2["size"]) . "</td><td align='center' class='table_col1'>" . mksize($arr["uploaded"]) . "</td><td align='center' class='table_col2'>" . mksize($arr["downloaded"]) . "</td><td align='center' class='table_col1'>$ratio</td></tr>\n";
  }
  $ret .= "</table>\n";
  return $ret;
}



//Layout
stdhead(sprintf(T_("USER_DETAILS_FOR"), $user["username"]));





    
begin_framec("Semeando no momento");



	$res = SQL_Query_exec("SELECT DISTINCT torrent,uploaded,downloaded  FROM peers WHERE userid='$id' AND seeder='yes'");
	if (mysql_num_rows($res) > 0)
	  $seeding = peerstable($res);


	if ($seeding)
		print("<BR>$seeding<BR><BR>");


	if (!$seeding)
		print("<B>Este membro não tem atualmente nenhuma transferencia ativa<BR><BR>");


end_framec();

}else{
 show_error_msg("".T_("ERRO")."",'Ops acesso negado.', 1);
}





stdfoot();

?>