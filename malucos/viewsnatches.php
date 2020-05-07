<?php
require_once("backend/functions.php");
dbconn(false);
loggedinonly();
$id = 0 + $_GET["id"];
if (!is_valid_id($id))
                show_error_msg (T_("ERROR"), ("No Torrent ID, Please dont direct link to this page without an torrent ID"),1);

stdhead("Detalhes para histórico");

$res3 = SQL_Query_exec("select count(snatched.id) from snatched inner join users on snatched.userid = users.id inner join torrents on snatched.torrentid = torrents.id where snatched.torrentid =" . $_GET[id]);
$row = mysql_fetch_array($res3);
$count = $row[0];
$perpage = 50;

$res6 = SQL_Query_exec("select name, category from torrents where id = $_GET[id]");
$arr6 = mysql_fetch_assoc($res6);
$dt = gmtime() - 180;
$dt = sqlesc(get_date_time($dt));
list($pagertop, $pagerbottom, $limit) = pager($perpage, $count, $_SERVER["PHP_SELF"] . "?id=" . $_GET[id] . "&" );
begin_framec("Histórico <a href=torrents-details.php?id=$_GET[id]><b>" . $arr6["name"] . "</b></a>");
print("<p align=center>Os usuários no histórico, mais recentemente,</p>");
print("<table width=100% border=1 cellspacing=0 cellpadding=5 align=center class=table_table>\n");
print("<tr><td class=table_table align=center colspan=11>Detalhes para histórico: $arr6[name]</td></tr>");
print("<tr>");
print("<td class=table_head align=center><b>Upload</b></td>");
print("<td class=table_head align=center><b>Download</b></td>");
print("<td class=table_head align=center><b>Ratio</b></td>");
 

print("<td class=table_head align=center><b>Data início</b></td>");
print("<td class=table_head align=center><b>Data final</b></td>");
print("<td class=table_head align=center><b>Última acção</b></td>");
print("<td class=table_head align=center><b>Completado</b></td>");
print("<td class=table_head align=center><b></b>Última vez de seed</td>");
print("<td class=table_head align=center><b>Terminado</b></td>");
print("<td class=table_head align=center><b>Semeando</b></td>");
print("</tr>");
$res = SQL_Query_exec("select users.id, users.username, users.uploaded, users.downloaded, snatched.userid, UNIX_TIMESTAMP(snatched.start_date) AS st, UNIX_TIMESTAMP(snatched.last_action) AS lt, UNIX_TIMESTAMP(snatched.complete_date) AS ct, UNIX_TIMESTAMP(snatched.seed_end) AS se from snatched inner join users on snatched.userid = users.id inner join torrents on snatched.torrentid = torrents.id where snatched.torrentid =" . $_GET[id] . " ORDER BY snatched.id desc $limit");
while ($arr = mysql_fetch_assoc($res)) {  
$res2 = SQL_Query_exec("SELECT id,donated,title,enabled,warned,last_access FROM users WHERE id=$arr[userid]") or sqlerr(__FILE__, __LINE__);  
$arr2 = mysql_fetch_assoc($res2);  
$res3 = SQL_Query_exec("SELECT * FROM peers WHERE torrent=$_GET[id] AND userid=$arr[userid]");  
$arr3 = mysql_fetch_assoc($res3);  
if ($arr["downloaded"] > 0) {   
$ratio = number_format($arr["uploaded"] / $arr["downloaded"], 3);       
$ratio = "<font color=" . get_ratio_color($ratio) . ">$ratio</font>";  
}  else if ($arr["uploaded"] > 0)         $ratio = "Inf.";
else      $ratio = "---";
$uploaded =mksize($arr["uploaded"]);
$downloaded = mksize($arr["downloaded"]);
$res5 = SQL_Query_exec("SELECT * FROM snatched WHERE torrentid = " . $_GET[id] . " AND userid = ". $arr[userid] ."");  
while ($arr5 = mysql_fetch_assoc($res5)) {      
if ($arr5["downloaded"] > 0) {    
$ratio1 = number_format($arr5["uploaded"] / $arr5["downloaded"], 3);      
$ratio1 = "<font color=" . get_ratio_color($ratio1) . ">$ratio1</font>";        
}       else      if ($arr5["uploaded"] > 0)            $ratio1 = "Inf.";
else            $ratio1 = "---";
$stime = mkprettytime($arr[se] - $arr[ct]);
print("<tr>");  

print("<td class=table_col2 align=center><font class=small><font color=green><u>Global:</u></font></font><br>$uploaded<br><font class=small><font color=green><u>Torrent:</u></font></font><br>". mksize($arr5[uploaded]) ."</td>");
print("<td class=table_col1 align=center><font class=small><font color=green><u>Global:</u></font></font><br>$downloaded<br><font class=small><font color=green><u>Torrent:</u></font></font><br>". mksize($arr5[downloaded]) ."</td>");
print("<td class=table_col2 align=center><font class=small><font color=green><u>Global:</u></font></font><br>$ratio<br><font class=small><font color=green><u>Torrent:</u></font></font><br>$ratio1</td>");
        


print("<td class=table_col1 align=center>". $arr5[start_date] ."</td>");
print("<td class=table_col2 align=center>". $arr5[complete_date] ."</td>");
print("<td class=table_col1 align=center>". $arr5[last_action] ."</td>");

if ($arr3["seeder"] == "yes")
print("<td class=table_col2 align=center><font color=green>Espera<br>para<br>Estatísticas</font></td>");
else
print("<td class=table_col1 align=center>". $arr5[seed_end] ."</td>");
if ($arr3["seeder"] == "yes")
print("<td class=table_col2 align=center><font color=green>Espera<br>para<br>Estatísticas</font></td>");
else
print("<td class=table_col1 align=center>$stime</td>");
print("<td class=table_col2 align=center>" . ($arr5["finished"] == "yes" ? "<b><font color=green>Yes</font>" : "<font color=red>No</font></b>") . "</td>");
print("<td class=table_col1 align=center>" . ($arr3["seeder"] == "yes" ? "<b><font color=green>Yes</font>" : "<font color=red>No</font></b>") . "</td>");
print("</tr>\n");

print("</tr>");  
}
}
print("</table>");
print("$pagerbottom");
end_framec();
stdfoot();
?>