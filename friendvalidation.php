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
stdhead();


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
 
 
 
  print("<center><b><a href='account-details.php?id=".$arr[userid]."'>$pseudo[username]</a></b> agora é seu amigo.<BR></center>");
  
?>
  <br><p align="center"><a href="amigos.php"><?php echo 'Continuar' ?></a></p><br>
  
<?php

}
else{
 print("Erro entre em contato com a Staff");

  echo"<br><p align='center'><a href='amigos.php'>Continuar</a></p><br>";
}

end_framec();
stdhead();
stdfoot();

?>