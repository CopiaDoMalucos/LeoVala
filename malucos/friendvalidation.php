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

$res = SQL_Query_exec("SELECT userid, friendid FROM friends WHERE id =$id") or sqlerr();
 $arr = mysql_fetch_assoc($res);
if (!$arr)
	show_error_msg("".Error."", "O convite já foi excluído pelo requerente",1);
$nom = SQL_Query_exec("SELECT username FROM users WHERE id =$arr[userid]") or sqlerr();
$pseudo = mysql_fetch_assoc($nom);

if ($CURUSER[id] == $arr[friendid])
{

 @SQL_Query_exec("UPDATE friends SET valider='oui' WHERE id =$id") or sqlerr();
 SQL_Query_exec("INSERT INTO friends (userid, friendid, valider) VALUES (".$CURUSER['id'].", ".$arr['userid'].",'oui')") or die (mysql_error());
 
autolink("friend.php","<font color=red><a href='account-details.php?id=".$arr[userid]."'> $pseudo[username]</Font> </a>agora é seu amigo.<br/><br/><center><font color=#ff0000>Redirecionamento daqui 3 segundos...<br/></font>Voltar,<BR>Clique aqui Para voltar:<a href='friend.php'>Voltar</a></center>");
}
else
 print("Erro entre em contato com a Staff");
autolink("friend.php","Redirecionamento daqui 3 segundos.");

end_framec();

?>