<?php
############################################################
#######                                             ########
#######                                             ########
#######           malucos-share.org 2.0             ########
#######                                             ########
#######                                             ########
############################################################
require_once("backend/functions.php");
dbconn();
loggedinonly();
$id = (int)$_GET["id"];

$res = mysql_query("SELECT userid, friendid FROM friends WHERE id =$id") or sqlerr();
 $arr = mysql_fetch_assoc($res);
if (!$arr)
	show_error_msg("".T_("ERROR")."", " ".T_("FRIENDREFUSER_EXCLUIDO")."",1);
	
$message = "$CURUSER[username] ".T_("FRIENDREFUSER_RECUSOU")."";
	
if ($CURUSER[id] == $arr[friendid])
{
	mysql_query("INSERT INTO messages (poster, sender, receiver, added, msg) VALUES ('0', '0', ".$fid.", 'NOW()', ".sqlesc($friendmsg).")");
	mysql_query ("DELETE FROM friends WHERE id =". $id) or sqlerr();
	show_error_msg("".T_("ERROR")."", " ".T_("FRIENDREFUSER_EXCLUIDO")."",1);
 show_error_msg("".T_("FRIENDREFUSER_EXCLUIDO_CONVITE")."!","".T_("FRIENDREFUSER_EXCLUIDO_PM")."",1);
 header("Refresh:3;url=mailbox.php?inbox");
}

?>
