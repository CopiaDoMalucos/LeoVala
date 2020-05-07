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
if ($user["ver_baixados"] == "yes" || $CURUSER['id'] == $user['id'] || $CURUSER["level"]=="Administrador" || $CURUSER["level"]=="Moderador" || $CURUSER["level"]=="S.Moderador" || $CURUSER["level"]=="Liberador"  || $CURUSER["level"]=="Sysop") {


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




if ($CURUSER["ver_xxx"]!="yes") {
$wherea=array();
    $wherea[] = "torrents.category != '106'";
     $wherea[] = "torrents.category != '104'";
    $wherea[] = "torrents.category != '47'";
$where = implode(" AND ", $wherea);
}






//Layout






    
   $query=SQL_Query_exec("select completed.date, completed.torrentid, torrents.id, torrents.name, torrents.comments, torrents.seeders, torrents.leechers, torrents.category, torrents.times_completed, categories.name AS cat_name, categories.image AS cat_img, categories.parent_cat AS cat_parent from completed LEFT JOIN torrents on completed.torrentid=torrents.id LEFT JOIN categories ON torrents.category = categories.id where completed.userid='".$id."' order by completed.date desc ")or die(mysql_error());
stdhead("Torrents baixados");
begin_framec("Torrents baixados ".$user["username"]."");

echo"<br>";
$numcomp=mysql_num_rows($query);
if ($numcomp==0) {
    echo("<center><b>Não há torrentes baixados.</b></center>");
} else {?>

<table align="center" cellpadding="0" cellspacing="0" width="100%">
<tr>
<td class="ttable_head" width='60'>Tipo</td>
<td class="ttable_head"><?php echo "Nome";?></td>
<td class="ttable_head">Com</td>
<td class="ttable_head">Seed</td>
<td class="ttable_head">Leech</td>
<td class="ttable_head">Comp</td>
<td class="ttable_head">Data de Conclusão</td>

</tr>
<?php    while ($row=mysql_fetch_array($query)) {
        $char1 = 35; //cut length
        $smallname = CutName(htmlspecialchars($row["name"]), $char1);
        if ($row["cat_img"]!="") {
        echo "<tr><td class=table_col1 align=center><img src=images/categories/$row[cat_img] border=0></td><td class=table_col1 align=left><a href='torrents-details.php?id=$row[id]'>$smallname</A></td><td class=table_col1 align=center><a href=comments.php?type=torrent&id=$row[id]>$row[comments]</a></td><td class=table_col1 align=center>$row[seeders]</td><td class=table_col1 align=center>$row[leechers]</td><td class=table_col1 align=center>$row[times_completed]</td><td class=table_col1 align=center>".date('d-m-Y H:i:s', utc_to_tz_time($row['date']))."</td><tr>\n";
        }
    }
    echo "</table><BR>";
}


end_framec();

}else{
 show_error_msg("".T_("ERRO")."",'Este usuário nao quer mostrar seu torrents baixados.', 1);
}





stdfoot();

?>