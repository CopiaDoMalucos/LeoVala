<?php
############################################################
#######                                             ########
#######                                             ########
#######           malucos-share.org 2.0             ########
#######                                             ########
#######                                             ########
############################################################
require_once("backend/functions.php");
require_once("mailbox-functions.php");
dbconn(false);

loggedinonly();

$id = (int)$_GET["id"];

$res = SQL_Query_exec("SELECT username FROM users WHERE id = ".$CURUSER['id']."") or die (mysql_error());
$arr = mysql_fetch_array($res);

$dt = get_date_time(gmtime() - 180);
$username = htmlspecialchars($arr['username'], ENT_QUOTES);

stdhead("".friends_of." " . $username);
if ($_SERVER["REQUEST_METHOD"] == "POST"){
        
        if (!@count($_POST["sup_valid"]))
		
		 show_error_msg("".T_("ERROR")."", " ".T_("FRIEND_AMIGOS_NIN")."  <a href='friend.php'>".T_("DONATE_VOLTAR")." </a>", 1);       
        
        $suppression = implode(",", array_map("intval", $_POST["sup_valid"]));
        
        SQL_Query_exec("DELETE FROM `friends` WHERE `id` IN ($suppression)");
        header("Refresh: 3; url=friend.php"); 
        show_error_msg("".T_("_DEL_")."", " ".T_("FRIEND_AMIGOS_EXCLUI")."  <a href='friend.php'>".T_("DONATE_VOLTAR")." </a>", 1);    
    									}
if (!$_GET['action'])	{
	
	$res = SQL_Query_exec("SELECT friends.friendid,users.username FROM friends LEFT JOIN users ON friends.friendid = users.id WHERE userid = ".$CURUSER['id']." AND valider ='oui' ORDER BY users.username ") or die (mysql_error());


begin_framec(T_("FRIEND_AMIGOS_MEUS"));
print("<center><a href=friend.php?action=attente>".T_("FRIEND_AMIGOS_PENDENTES")."</a>   |   <a href=friend.php?action=ajouter>".T_("FRIEND_AMIGOS_ADD")."</a></center>");

print("	<table border='1' BORDERCOLOR='#000000' cellpadding='1' cellspacing='1' align='center' width='75%'>
			<tr>
				<td width='20%' bgcolor='999999'><center><b><font color=#ffffff>".T_("AVATAR")."</font></b></center></td>
				<td width='25%' bgcolor='999999'><center><b><font color=#ffffff>".T_("USERNAME")."</font></b></center></td>
				<td width='25%' bgcolor='999999'><center><b><font color=#ffffff>".T_("LAST_ACCESS")."</font></b></center></td>
				<td width='10%' bgcolor='999999'><center><b><font color=#ffffff>".T_("ON_LINE")."</font></b></center></td>
				<td width='10%' bgcolor='999999'><center><b><font color=#ffffff>".T_("SHOUTBOX_TEM")."</font></b></center></td>
				<td width='10%' bgcolor='999999'><center><b><font color=#ffffff>".T_("_DEL_")."</font></b></center></td>
			</tr>
		");	
		
					
while ($arr = mysql_fetch_assoc($res))
    {
$res1 = SQL_Query_exec("SELECT  username, last_access, avatar, class  FROM users WHERE id = '$arr[friendid]'") or die (mysql_error());
$arr1 = mysql_fetch_assoc($res1);

$class = get_user_class_name($arr1['class']);

$avatar = htmlspecialchars($arr1['avatar'], ENT_QUOTES);
if (!$avatar)
$avatar = "images/default_avatar.gif";

$fname = htmlspecialchars($arr1['username'], ENT_QUOTES);

print("		<tr>
				<td class='table_col1'><center><img src=\"$avatar\" height=\"50\" width=\"50\"></center></td>		
				<td class='table_col2'><center><a href=account-details.php?id=$arr[friendid]><b>$fname</b>($class)</a></center></td>
				<td class='table_col2'><center>$arr1[last_access]</center></td>
				<td class='table_col1'><center> <img 
src=images/button_o".($arr1[last_access]>$dt?"n":"ff")."line.gif></center></td>
				<td class='table_col1'><center><a href=mailbox.php?compose&id=".$arr[friendid]."><img src=images/button_pm.gif border=0></a></center></td>
				<td class='table_col2'><center><a href=\"takedelfriend.php?delid=$arr[friendid]\" onclick=\"return confirm('".T_("FRIEND_AMIGOS_EXCLUI_AMIGO")."?');\"><img src=images/delete.png border=0 title='".T_("FRIEND_AMIGOS_EXCLUI_AMIGO")."' alt='".T_("FRIEND_AMIGOS_EXCLUI_AMIGO")."'></a></center></td>
			</tr>
	");
			
	}//while
print("	</table>
	");
end_framec();

		}////!$action
		
///en attente	
if ($_GET['action'] == "attente") {	

	

$attente = SQL_Query_exec("SELECT id,friendid FROM friends WHERE userid = ".$CURUSER['id']." AND valider ='non'ORDER BY id") or die (mysql_error());


		begin_framec("<font color=2fdceb>".T_("FRIEND_AMIGOS_PENDENTES_VALIDA")."</font>", "center");
print("<center><a href=friend.php>".T_("FRIEND_AMIGOS_MEUS")."</a>   |   <a href=friend.php?action=ajouter>".T_("FRIEND_AMIGOS_ADD")."</a></center>");	
if (mysql_num_rows($attente) > 0)	{	
print("<form method='post' action='friend.php'>");
print("	<table border='1' BORDERCOLOR='#000000' cellpadding='1' cellspacing='1' align='center' width='75%'>
			<tr>
				<td width='25%' bgcolor='999999'><center><b><font color=#ffffff>".T_("USERNAME")."</font></b></center></td>
				<td width='25%' bgcolor='999999'><center><b><font color=#ffffff>".T_("LAST_ACCESS")."</font></b></center></td>
				<td width='10%' bgcolor='999999'><center><b><font color=#ffffff>".T_("_DEL_")."</font></b></center></td>
			</tr>
		");	

while ($en_attente = mysql_fetch_assoc($attente))
		{
	$donnee = SQL_Query_exec("SELECT  username, last_access FROM users WHERE id = '$en_attente[friendid]'") or die (mysql_error());
	$donnee1 = mysql_fetch_assoc($donnee);	
			
print("		<tr>
				<td class='table_col1'><center>$donnee1[username]</center></td>		
				<td class='table_col2'><center>$donnee1[last_access]</center></td>
				<td class='table_col2'><center><input type=\"checkbox\" name=\"sup_valid[]\" value=\"" . $en_attente[id] . "\" /></center></td>
				
			</tr>
	");
		}///while
print("		<tr>
				<td>
				</td>
				<td>
				</td>
				<td align=left><input type='button' value='".T_("FRIEND_AMIGOS_VERIFICA")."' onclick='this.value=check(form)'><input type='submit' value='".T_("_DEL_")."'>
				</td>
			</tr>
		</table>
		</form>");
									}
									else{
print("<center><h3>".T_("FRIEND_AMIGOS_PENDENTES_AGUARD")."</h3></center>");										
										}
	end_framec();
									
						}//$action=attente
						
///ajoutet un ami		
if ($_GET['action'] == "ajouter") {					                       
$res = SQL_Query_exec("SELECT users.id, users.username FROM users  WHERE users.id != '$CURUSER[id]' AND users.id NOT IN(SELECT friendid from friends where userid = '$CURUSER[id]') ORDER BY users.username");								
	begin_framec("Convide um amigo");
print("<center><a href=friend.php>".T_("FRIEND_AMIGOS_MEUS")."</a>   |<a href=friend.php?action=attente>".T_("FRIEND_AMIGOS_PENDENTES")." </a></center>");
if (mysql_num_rows($res) > 0)	{
print("<center><form name=Convite method=get action=friends.php>
		<table width=500 cellpadding=5>
			<tr>
				<td width=100></td>
				<td>
	");

$res = SQL_Query_exec("SELECT users.id, users.username FROM users  WHERE  users.class<'4' AND users.privacy != 'strong' AND users.id != '$CURUSER[id]' AND users.id NOT IN(SELECT friendid from friends where userid = '$CURUSER[id]') ORDER BY users.username");
   
     $to = "<select name=\"user\">\n";
     while ($arr = mysql_fetch_assoc($res)) $to .= "<option value=\"$arr[id]\">$arr[username]</option>\n";
     $to .= "</select>\n";
  tr2("".T_("FRIEND_AMIGOS_ADD_ESCOLHA").":", $to, 1);
 
print("   		</td>
			</tr>
			<tr>
				<td colspan=2><center><input name=submit type=submit value='".T_("FRIEND_AMIGOS_ADD")."'></center></td>
			</tr>
		</table>
				</form>
	</center>
	");
							}
							else {
print("<center><h3>".T_("FRIEND_AMIGOS_ERRO")."</h3></center>");		
								}
end_framec();	
								
							}///$action=ajouter
stdfoot();

?>