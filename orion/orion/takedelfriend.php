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

$delid = htmlspecialchars($_GET['delid'], ENT_QUOTES);

$res2 = mysql_query ("SELECT id, userid FROM friends WHERE friendid =".$delid) or sqlerr();


$arr = mysql_fetch_assoc($res2);


     if ($arr['userid'] = $CURUSER['id']) {
	

		mysql_query ("DELETE FROM friends WHERE friendid =". $delid) or sqlerr();
				
		mysql_query ("DELETE FROM friends WHERE userid =". $delid ." AND friendid = ".$arr[userid]."") or sqlerr();
		
	}else {
		show_error_msg("error!","Não está em sua lista de amigos...",1);
	}

		

header("Refresh: 0; url=" . $_SERVER['HTTP_REFERER']);
?>