<?
require_once("backend/functions.php"); 
dbconn(false); 

// check access and rights
//if ($site_config["MEMBERSONLY"])
//{
//	loggedinonly();
//}

$search = trim($HTTP_GET_VARS['search']);

if ($search != '' || $class)
{
  $query = "name LIKE " . sqlesc("%$search%") . "";
	if ($search)
		  $q = "search=" . htmlspecialchars($search);
}
else
{
	$letter = trim($_GET["letter"]);
  if (strlen($letter) > 1)
    die;

  if ($letter == "" || strpos("abcdefghijklmnopqrstuvwxyz", $letter) === false)
    $letter = "a";
  $query = "name LIKE '$letter%'";
  $q = "letter=$letter";
}
stdhead("Catalogue");

print("<center>");
print("<form method=get action=?>\n");
print("recherche <input type=text size=30 name=search>\n");
print("<input type=submit value='Rechercher'>\n");
print("</form>\n");
print("<p>\n");

for ($i = 97; $i < 123; ++$i)
{
	$l = chr($i);
	$L = chr($i - 32);
	if ($l == $letter)
    print("<b>$L</b>\n");
	else
    print("<a href=?letter=$l><b>$L</b></a>\n");
}



print("</p>\n");


$page = $_GET['page'];
$perpage = 10;

$res = mysql_query("SELECT COUNT(*) FROM torrents WHERE $query") or sqlerr();
$arr = mysql_fetch_row($res);
$pages = floor($arr[0] / $perpage);
if ($pages * $perpage < $arr[0])
  ++$pages;

if ($page < 1)
  $page = 1;
else
  if ($page > $pages)
    $page = $pages;

for ($i = 1; $i <= $pages; ++$i)
  if ($i == $page)
    $pagemenu .= "<b>$i</b>\n";
  else
    $pagemenu .= "<a href=?$q&page=$i><b>$i</b></a>\n";

if ($page == 1)
  $menu .= "<b>&lt;&lt; Précédent</b>";
else
  $menu .= "<a href=?$q&page=" . ($page - 1) . "><b>&lt;&lt; Précédent</b></a>";

$menu .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

if ($page == $pages)
  $menu .= "<b>Suivant &gt;&gt;</b>";
else
  $menu .= "<a href=?$q&page=" . ($page + 1) . "><b>Suivant &gt;&gt;</b></a>";

print("<p>$menu<br>$pagemenu</p>");

$offset = ($page * $perpage) - $perpage;

$res = mysql_query("SELECT * FROM torrents WHERE $query ORDER BY name ASC LIMIT $offset,$perpage") or sqlerr();
$num = mysql_num_rows($res);


if (!$num){
print("<table width=100% cellpadding=5 class=table_table><tr><td class=table_head><b>Catalogue</b></td></tr>".
"<tr><td>Aucun trouvé avec la lettre demandé $letter</td></tr>".
"</table>");
print("</center>");
}else {
	
begin_frame("Catalogue");	

print("<table align=center width=100% cellpadding=5 class=table_table><tr><td colspan=2 class=table_head><b>Catalogue</b></td></tr>");
for ($i = 0; $i < $num; ++$i)
{
  $arr = mysql_fetch_assoc($res);
  {	
  $id = $arr["id"];		
		
		
  $ret = mysql_query("SELECT seeder, ip, port, uploaded, downloaded, to_go, connectable, client, UNIX_TIMESTAMP(started) AS st, UNIX_TIMESTAMP(last_action) AS la, userid FROM peers WHERE torrent = $id AND seeder = 'yes' ORDER BY to_go ASC LIMIT 5");
  $nul = mysql_num_rows($ret);
  
    
  if (!$nul) {
$s = "<br><b>&nbsp;Aucune information</b>";
  } else {	
  $s = "<table width=100% cellspacing=0 cellpadding=5 border=1>";
  $s .= "<tr class=table_head><td>Pseudo</td><td align=right>Uploadé</td><td align=right>Télécharger</td><td align=right>client</td><td align=right>Vitesse</td><td align=right>Port</td>";
  $s .= "<td align=right>Ratio</td><td align=right>seeders</td><td align=right>connecté</td><td align=right>Completé</td></tr>";

  for ($m = 0; $m < $nul; ++$m)
  {
  $arrs = mysql_fetch_assoc($ret);
  {
  $cres = mysql_query("SELECT username FROM users WHERE id=$arrs[userid]");
  $cros = mysql_fetch_assoc($cres);
  $username = $cros[username];
  $now = time();
  $secs = max(1, ($now - $arrs["st"]) - ($now - $arrs["la"]));
  $revived = $arrs["revived"] == "yes";
  
  // Calculate local torrent speed test
if ($row["leechers"] >= 1 && $row["seeders"] >= 1 && $row["external"]!='yes')
{
	$speedQ = mysql_query("SELECT (SUM(p.downloaded)) / (UNIX_TIMESTAMP('".get_date_time()."') - UNIX_TIMESTAMP(added)) AS totalspeed FROM torrents AS t LEFT JOIN peers AS p ON t.id = p.torrent WHERE p.seeder = 'no' AND p.torrent = '$id' GROUP BY t.id ORDER BY added ASC LIMIT 15") or die(mysql_error());
	$a = mysql_fetch_assoc($speedQ);
	$totalspeed = mksize($a["totalspeed"]) . "/s";
}
else $totalspeed = "Aucune activité";


   $s .= "<tr><td class=ttable_col1><a href=account-details.php?id=$arrs[userid]><b>$username</b></a></td>";
   $s .= "<td align=right class=table_col1><nobr>" . mksize($arrs[uploaded]) . "</nobr></td>";
   $s .= "<td align=right class=table_col1><nobr>" . mksize($arrs[downloaded]) . "</nobr></td>";
 
  if ($arrs["seeder"] == "no") 
  $s .= "<td align=right class=ttable_col1><nobr>" . mksize(($arrs["downloaded"] - $arrs["downloadoffset"]) / $secs) . "/s</nobr></td>";
  else
	$s .= "<td align=right class=table_col1><nobr>$arrs[client]</nobr></td>";  
  $s .= "<td align=right class=table_col1><nobr>" . $totalspeed . "</nobr></td>";
  $s .= "<td align=right class=ttable_col1><nobr>".$arrs["port"]."</nobr></td>";

  //$ratio
  
  if ($cros["downloaded"] > 0)
		{
			$ratio = number_format($cros["uploaded"] / $cros["downloaded"], 2);
		}
		else
		{
			$ratio = "---";
		}

{
  $s .= "<td align=right class=ttable_col1><font color=" . get_ratio_color($ratio) . ">$ratio</font></td>";
}
$s .= "<td align=right class=ttable_col1>$arrs[seeder]</td>";
$s .= "<td align=right class=ttable_col1>$arrs[connectable]</td>";

 $s .= "<td align=right class=table_col1>" . sprintf("%.2f%%", 100 * (1 - ($arrs["to_go"] / $arr["size"]))) . "</td>";

  }
  }
  $s .= "</table>";
  }
  
  $bah = mysql_query("SELECT COUNT(*) FROM peers WHERE seeder = 'yes' AND torrent = $id");
  $count = mysql_fetch_row($bah);
  $seeders = $count[0];
  $bah1 = mysql_query("SELECT COUNT(*) FROM peers WHERE seeder = 'no' AND torrent = $id");
  $count1 = mysql_fetch_row($bah1);  
  $leechers = $count1[0];
  
  $info = "<td valign=top><table class=table_table width=100% cellpadding=3><tr><td class=ttable_col1>" . htmlspecialchars($arr[name]) . "</td><td align=right class=ttable_col1 width=95><a href=torrents-details.php?id=$arr[id]&hit=1>Voir le Torrent</a></td></tr></table><br><br>" .
	      "" . substr(format_comment($arr[descr]),0,250) . "...<br><br><br><table class=table_table width=100% cellpadding=3><tr><td class=ttable_col1 valign=bottom>Infos Seeder (Top 5 Seeders)</td><td align=right class=ttable_col1 width=175>$seeders Seeder(s) | $leechers Leecher(s)</td></tr></table>$s</td>";
  
  }
  
  $user = mysql_fetch_assoc(mysql_query("SELECT id, username, avatar FROM users WHERE id = $arr[owner]")) or sqlerr();
  $owner = "Upploader par: <a href=account-details.php?id=$user[id]><font color=#FFFFFF>$user[username]</font></a>";
  
  $avatar = htmlspecialchars($user["avatar"]);
if (!$avatar) 
{
	$avatar = "".$site_config["SITEURL"]."/images/default_avatar.gif";
}
//$ratio
  
  if ($cros["downloaded"] > 0)
		{
			$ratio = number_format($cros["uploaded"] / $cros["downloaded"], 2);
		}
		else
		{
			$ratio = "---";
		}
				
print("<tr><td valign=top width=150><table class=table_table cellpadding=3><tr><td class=ttable_col1 align=left>$owner<br><img src=$avatar width=80 height=80 border=0><br>Complété: $arr[times_completed]<br>downloaded: " . mksize($user["downloaded"]) . "<br>uploaded: " . mksize($user["uploaded"]) . "<br>Ratio: $ratio</td></tr></table></td>$info</tr>");  
}


print("</table>");
}
end_frame();
stdfoot();
?>