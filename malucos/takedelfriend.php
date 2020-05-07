<?php 
require_once("backend/functions.php");
dbconn();
loggedinonly();

$delid = htmlspecialchars($_GET['delid'], ENT_QUOTES);

$res2 = mysql_query ("SELECT id, userid FROM friends WHERE friendid =".$delid) or sqlerr();


while ($arr = mysql_fetch_assoc($res2))
{

     if ($arr['userid'] = $CURUSER['id']) {
	
	$pseudo = $CURUSER['username'];
 	$message = "$pseudo eliminou de sua lista de amigos é automaticamente excluído da sua";
		
		mysql_query("INSERT INTO messages (poster, sender, receiver, added, subject, msg) VALUES ('0', '0', $delid, NOW(),'Convite', " . sqlesc($message) . ")") or sqlerr();
		mysql_query ("DELETE FROM friends WHERE friendid =". $delid) or sqlerr();
				
		mysql_query ("DELETE FROM friends WHERE userid =". $delid ." AND friendid = ".$arr[userid]."") or sqlerr();
		
	}else {
		show_error_msg("error!","Não está em sua lista de amigos...",1);
	}
}

		

header("Refresh: 0; url=" . $_SERVER['HTTP_REFERER']);
?>