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

$Usuario = trim($_GET['Usuario']);
$class = (int) $_GET['class'];
$letter = trim($_GET['letter']);

if (!$class)
	unset($class);

$q = $query = null;
if ($Usuario) {
	$query = "username LIKE " . sqlesc("%$Usuario%") . " AND status='confirmed'";
	if ($Usuario) {
		$q = "Usuario=" . htmlspecialchars($Usuario);
	}
} elseif ($letter) {
	if (strlen($letter) > 1)
		unset($letter);
	if ($letter == "" || strpos("abcdefghijklmnopqrstuvwxyz", $letter) === false) {
		unset($letter);
	} else {
		$query = "username LIKE '$letter%' AND status='confirmed'";
	}
	$q = "letter=$letter";
}

if (!$query) {
	$query = "status='confirmed'";
}

if ($class) {
	$query .= " AND class=$class";
	$q .= ($q ? "&amp;" : "") . "class=$class";
}

stdhead(T_("USERS"));
begin_framec(T_("USERS"));
print("<br /><form method='get' action='memberlist.php'>\n");
print("<center>Pesquisar: <input type='text' size='30' name='Usuario'  value='".$Usuario."'>\n");
print("<select name='class'>\n");
print("<option value='-'>(Todas classe)</option></center>\n");
$res = SQL_Query_exec("SELECT group_id, level FROM groups");
while ($row = mysql_fetch_array($res)) {
	print("<option value='$row[group_id]'" . ($class && $class == $row['group_id'] ? " selected" : "") . ">".htmlspecialchars($row['level'])."</option>\n");
}
print("</select>\n");
print("<input type=submit value='Pesquisar'>\n");
print("</form>\n");

print("<p>\n");

print("<a href='memberlist.php'><b>".T_("ALL")."</b></a> - \n");
foreach (range("a", "z") as $l) {
	$L = strtoupper($l);
	if ($l == $letter)
		print("<b>$L</b>\n");
	else
		print("<a href='memberlist.php?letter=$l'><b>$L</b></a>\n");
}

print("</p>\n");

$page = (int) $_GET['page'];
$perpage = 100;

$res = SQL_Query_exec("SELECT COUNT(*) FROM users WHERE $query");
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
    $pagemenu .= "$i\n";
  else
    $pagemenu .= "<a href=?$q&page=$i>$i</a>\n";

if ($page == 1)
  $browsemenu .= "";
//  $browsemenu .= "[Prev]";
else
  $browsemenu .= "<a href=?$q&page=" . ($page - 1) . "><< Página Anterior</a>";

$browsemenu .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

if ($page == $pages)
  $browsemenu .= "";
//  $browsemenu .= "[Next]";
else
  $browsemenu .= "<a href='?$q&page=" . ($page + 1) . "'>Próxima Página >></a>";

$offset = ($page * $perpage) - $perpage;

$res = SQL_Query_exec("SELECT users.*, groups.level FROM users LEFT JOIN groups ON groups.group_id=users.class WHERE $query ORDER BY username LIMIT $offset,$perpage");

begin_table();
print("<tr><center><td class=ttable_head align=left>" . T_("USERNAME") . "</td><td class=ttable_head>" . T_("REGISTERED") . "</td><td class=ttable_head>" . T_("LAST_ACCESS") . "</td><td class=ttable_head>" . T_("CLASS") . "</td><td class=ttable_head>Estado</td><td class=ttable_head>" . T_("COUNTRY") . "</td></center></tr>\n");
while ($arr = mysql_fetch_assoc($res)) {
	if ($arr['country']) {
		$cres = SQL_Query_exec("SELECT name,flagpic FROM countries WHERE id=$arr[country]");

		if ($carr = mysql_fetch_assoc($cres)) {
			$country = "<td align=\"center\" class=table_col1 style='padding: 0px' align='center'><img src='$site_config[SITEURL]/images/flag/$carr[flagpic]' title='".htmlspecialchars($carr['name'])."' alt='".htmlspecialchars($carr['name'])."'/>";
		} else {
			$country = "<td align=\"center\"  class=table_col1 style='padding: 0px' align='center'><img src='$site_config[SITEURL]/images/flag/unknown.gif' alt='Unknown'/>";
		}
	}
	if ($arr['estado']) {
		$cres1 = SQL_Query_exec("SELECT name,flagpic FROM estados WHERE id=$arr[estado]");

		if ($carr1 = mysql_fetch_assoc($cres1)) {
			$estado = "<img src='$site_config[SITEURL]/images/estado/$carr1[flagpic]' title='".htmlspecialchars($carr1['name'])."' alt='".htmlspecialchars($carr1['name'])."'/>";
		} else {
			$estado = "<img src='$site_config[SITEURL]/images/estado/unknown.gif' alt='Unknown'/>";
		}
	}
/*	if ($arr['added'] == '0000-00-00 00:00:00')
		$arr['added'] = '-';
	if ($arr['last_access'] == '0000-00-00 00:00:00')
		$arr['last_access'] = T_("NEVER");*/

  print("<tr><td class=table_col1 align=left><a href=account-details.php?id=$arr[id]><b>$arr[username]</b></a>" .($arr["donated"] > 0 ? "<img src=$site_config[SITEURL]/images/star.gif border=0 alt='Donated'>" : "")."</td>" .
  "<td align=\"center\" class=table_col1>". date(utc_to_tz($arr["added"]))."</td><td align=\"center\" class=table_col1>". date(utc_to_tz($arr["last_access"]))."</td>".
    "<td class=table_col1 align=center>" . T_($arr["level"]) . "<td class=table_col1 align=center>$estado</td>$country</td></tr>\n");
}
end_table();

print("<p>$pagemenu<br />$browsemenu</p>");
end_framec();
stdfoot();

?>