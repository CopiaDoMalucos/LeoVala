<?php
############################################################
#######                                             ########
#######                                             ########
#######           www.brshares.com 2.0              ########
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
	
	$res2 = SQL_Query_exec("SELECT COUNT(*) FROM friends LEFT JOIN users ON friends.friendid = users.id WHERE userid = ".$CURUSER['id']." AND valider ='oui'");
	$row = mysql_fetch_array($res2);
	$count = $row[0];

	$perpage = 10;
		list($pagertop, $pagerbottom, $limit) = pager($perpage, $count, "friend1.php?".$param);
	$res = SQL_Query_exec("SELECT friends.friendid,users.username FROM friends LEFT JOIN users ON friends.friendid = users.id WHERE userid = ".$CURUSER['id']." AND valider ='oui' ORDER BY users.id DESC $limit ") or die (mysql_error());



begin_framec("Lista de Amigos");
echo"<br>";
	echo $pagertop;
print("	<table class='tab1' cellpadding='0' cellspacing='1' align='center' width='100%' border='0'><br>
			<tr>
				<td class='tab1_cab1' ><b><center>". ("Nick")."</center></b></td>
				<td class='tab1_cab1' ><b><center>Ratio</center></b></td>
				<td class='tab1_cab1' ><center><b>". ("Último Acesso")."</b></center></td>
				<td class='tab1_cab1' ><center><b>". ("MP")."</b></center></td>
				<td class='tab1_cab1' ><center><b>". ("Retirar")."</b></center></td>
			</tr>
		");	
		
					
while ($arr = mysql_fetch_assoc($res))
    {
$res1 = SQL_Query_exec("SELECT  username, last_access, avatar, class, uploaded, downloaded  FROM users WHERE id = '$arr[friendid]' ORDER BY id ") or die (mysql_error());
$arr1 = mysql_fetch_assoc($res1);

$class = get_user_class_name($arr1['class']);

 if ($arr1["downloaded"] > 0){
				$userratio = number_format($arr1["uploaded"] / $arr1["downloaded"], 2);
		}else{
				if ($arr1["uploaded"] > 0)
					$userratio = "Inf.";
				else
					$userratio = "NA";
		}
$fname = htmlspecialchars($arr1['username'], ENT_QUOTES);

print("		<tr>
				<td class='tab1_col3' ><a href=account-details.php?id=$arr[friendid]><b>$fname</b></a></td>
				<td class='tab1_col3' ><center>$userratio</center></td>
				<td class='tab1_col3' ><center>$arr1[last_access]</center></td>
				<td class='tab1_col3' ><center> <img 
				<td class='tab1_col3' ><center><a href=enviarmp.php?receiver=".$arr["username"]."><img src=images/button_pm.gif border=0></a></center></td>
				<td class='tab1_col3' ><center><a href=\"takedelfriend.php?delid=$arr[friendid]\" onclick=\"return confirm('".T_("FRIEND_AMIGOS_EXCLUI_AMIGO")."?');\">".("Retirar")."</a></center></td>
			</tr>
	");
			
	}//while
print("	</table><br>
	");
end_framec();
begin_framec("Pesquisar usuário");

	print("<br><form action='memberlist.php' method='get'>
Pesquisar: <input type='text' name='Usuario' size='30'>
<input type='hidden' value='-' name='class'>

<input type='submit' value='Pesquisar'>
</form>");
end_framec();
		}////!$action
stdfoot();
?>