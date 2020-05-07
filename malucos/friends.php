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
stdhead();

if (!isset($_GET['user'])) {
      show_error_msg("".Error."", "Nenhum usuário selecionados", "1");
	  stdfoot();
	  exit;
	}

$fid = htmlspecialchars($_GET['user'], ENT_QUOTES);

$ress = SQL_Query_exec("SELECT username FROM users WHERE id = '".$fid."' ")  or die (mysql_error());
$arrow = mysql_fetch_assoc($ress);

$fusername = htmlspecialchars($arrow['username'], ENT_QUOTES);

if ((get_row_count("friends", "WHERE userid=".$CURUSER['id']." AND friendid = ".$fid."")) > 0) {
      show_error_msg("".Error."", "<a href='account-details.php?id=".$fid."'>".$fusername."</a> já está em sua lista", "1");
	  stdfoot();
	  exit;
} elseif ($CURUSER['id'] == $fid) {
	    show_error_msg("".Error."", "Colocar você mesmo em sua lista de amigos, não faz sentido!", 0);
        stdfoot();
    exit;
}

SQL_Query_exec("INSERT INTO friends (userid, friendid) VALUES (".$CURUSER['id'].", ".$fid.")") or die (mysql_error());
$id = mysql_insert_id();


$friendmsg = "Olá $fusername,

O usuário $CURUSER[username] adicionou você a lista de amigos dele, escolha abaixo uma opção para confirmar ou recusar o mesmo como amigo.

 [url=$site_config[SITEURL]/friendvalidation.php?id=" . $id . "][size=2][color=green]Aceitar o convite[/color][/size][/url] ou [url=$site_config[SITEURL]/friendrefuser.php?id=" . $id . "][size=2][color=red]Recusar o convite[/color][/size][/url] 


 Malucos Share

 Mais que um site, uma família.

 Equipe MS";

SQL_Query_exec("INSERT INTO messages (poster, sender, receiver, added, subject, msg) VALUES ('0', '0', $fid, NOW(),'Convite', " . sqlesc($friendmsg) . ")");

 autolink("friend.php","Uma mensagem foi enviada para:<b><a href='account-details.php?id=".$fid."'>".$fusername."</a></b> para que o mesmo aceite o seu covite de amizade .<BR><br/><br/><font color=#ff0000>Redirecionamento daqui 5 segundos...<br/></font>Clique aqui Para voltar:<a href='friend.php'>Voltar</a></center>");

end_framec();
stdhead();
stdfoot();
?>