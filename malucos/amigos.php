<?php 

require_once("backend/functions.php");

dbconn(false);

loggedinonly();

$id = (int)$_GET["id"];

$res = mysql_query("SELECT username FROM users WHERE id = ".$CURUSER['id']."") or die (mysql_error());
$arr = mysql_fetch_array($res);

$dt = get_date_time(gmtime() - 180);
$username = htmlspecialchars($arr['username'], ENT_QUOTES);

stdhead("Amigos de " . $username);

$res = mysql_query("SELECT friendid FROM friends WHERE userid = ".$CURUSER['id']." ORDER BY id") or die (mysql_error());

$i = "1";

if(mysql_query("SELECT username FROM users WHERE id = ".$CURUSER['id']."") == ""){
	show_error_msg("Erro", "Você ainda não tem nenhum amigo. Para adicionar vá até o perfil do usuário e clique em 'Adicionar Como Amigo'");
}else{

begin_framec("<font color=2fdceb>Meus amigos</font>", "center");

while ($arr = mysql_fetch_assoc($res))
    {
$res1 = mysql_query("SELECT avatar, username, last_access, class FROM users WHERE id = '$arr[friendid]'") or die (mysql_error());
$arr1 = mysql_fetch_assoc($res1);

$class = get_user_class_name($arr1['class']);

$avatar = htmlspecialchars($arr1['avatar'], ENT_QUOTES);
if (!$avatar)
$avatar = "images/default_avatar.gif";

$fname = htmlspecialchars($arr1['username'], ENT_QUOTES);

$content1 = "<table style=\"padding: 0px;\" width=\"100%\"><tbody><tr><td class=\"bottom\" style=\"padding: 5px;\" 
align=\"center\" width=\"50%\"><table class=\"main\" height=\"75\" width=\"100%\"><tbody><tr valign=\"top\"><td 
style=\"padding: 0px;\" align=\"center\" width=\"75\"><div style=\"overflow: hidden; width: 75px; height: 75px;\"><img 
src=\"$avatar\" width=\"75\"></div></td><td>
	<table class=\"main\"><tbody><tr><td class=\"embedded\" style=\"padding: 5px;\" width=\"80%\"><a 
href=\"account-details.php?id=$arr[friendid]\"><b>$arr1[username]</b></a> <img 
src=images/button_o".($arr1[last_access]>$dt?"n":"ff")."line.gif> ($class)<br><br>Última vez visto em 
$arr1[last_access]<br>&nbsp;</td>
	<td class=\"embedded\" style=\"padding: 5px;\" width=\"20%\"><br><a 
href=\"deletaramigo.php?delid=$arr[friendid]\">Deletar Amigo</a><br><br><a 
href=mailbox.php?compose&id=".$arr[friendid].">Enviar MP</a><BR></td></tr>
	</tbody></table></td></tr></tbody></table>
	</td>";

$content2 = "<td class=\"bottom\" style=\"padding: 5px;\" align=\"center\" width=\"50%\"><table class=\"main\" height=\"75\" 
width=\"100%\"><tbody><tr valign=\"top\"><td style=\"padding: 0px;\" align=\"center\" width=\"75\"><div style=\"overflow: 
hidden; width: 75px; height: 75px;\"><img src=\"$avatar\" width=\"75\"></div></td><td>
	<table class=\"main\"><tbody><tr><td class=\"embedded\" style=\"padding: 5px;\" width=\"80%\"><a 
href=\"account-details.php?id=$arr[friendid]\"><b>$arr1[username]</b></a> <img 
src=images/button_o".($arr1[last_access]>$dt?"n":"ff")."line.gif> ($class)<br><br>Last seen on 
$arr1[last_access]<br>&nbsp;</td>
	<td class=\"embedded\" style=\"padding: 5px;\" width=\"20%\"><br>
	<a href=\"deletaramigo.php?delid=$arr[friendid]\">Deletar Amigo</a><br><br>
	<a href=mailbox.php?compose&id=".$arr[friendid].">Enviar MP</a></td></tr>
	</tbody></table></td></tr></tbody></table></td></tr></tbody></table>";

if ($i == "1") {
echo $content1;
$i = "2";
}else {
echo $content2;
$i = "1";
}

}

if ($i == "2") {
echo "<td class=\"bottom\" width=\"50%\">&nbsp;</td></tr></tbody></table>";
/*
?>
<p><br><br><!-- a href="extras-users.php"><b>Search for a member</b></a --></p>
<?if ($INVITEONLY) {?>
<p><a href="invite.php"><b>Send an invitation</b></a> <b>(<?echo $CURUSER['invites'];?> remaining)</b></p>
<?}
*/
end_framec();
}else {
/*
?>
<p align="center"><br><br><!-- a href="extras-users.php"><b>Search for a member</b></a --></p>
<?if ($INVITEONLY) {?>
<p align="center"><!-- a href="invite.php"><b>Send an invitation</b></a --> <b>(<?echo $CURUSER['invites'];?> remaining)</b></p>
<?

}
*/
end_framec();
}

stdfoot();
}
?>