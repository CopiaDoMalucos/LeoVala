<?php
############################################################
#######                                             ########
#######                                             ########
#######           brshares.com 2.0                  ########
#######                                             ########
#######                                             ########
############################################################
  
  require_once("backend/functions.php");
  dbconn();
  loggedinonly();
  
  if ($CURUSER["view_torrents"] == "no")
      show_error_msg(T_("ERROR"), T_("NO_TORRENT_VIEW"), 1); 

  $id = (int) $_GET["id"];
  
  if (isset($_COOKIE["reseed$id"]))
      show_error_msg(T_("ERROR"), "You have recently made a request for this reseed, please wait longer for another request.", 1);
      
  $res = SQL_Query_exec("SELECT `owner`, `banned`, `external` FROM `torrents` WHERE `id` = $id");
  $row = mysql_fetch_assoc($res);
  
  if (!$row || $row["banned"] == "yes" || $row["external"] == "yes")
       show_error_msg(T_("ERROR"), T_("TORRENT_NOT_FOUND"), 1);  
  
  $res2 = SQL_Query_exec("SELECT users.id FROM completed LEFT JOIN users ON completed.userid = users.id WHERE users.enabled = 'yes' AND users.status = 'confirmed' AND completed.torrentid = $id");

$message = $CURUSER['username'] . " solicitou que você volte a semear o torrent abaixo, pois o mesmo não possui seeders:\n\n" . $site_config["SITEURL"] . "/torrents-details.php?id=$id \n\n Caso não saiba como voltar a semear o arquivo veja o tutorial para lhe ajudar,\n\n http://www.brshares.com/forums.php?action=viewtopic&topicid=695 \n\n\n Obrigado! \n\n\n brshares.com \n Sempre Fazendo o melhor por você."; 
  
  while ( $row2 = mysql_fetch_assoc($res2) )
  {
      SQL_Query_exec("INSERT INTO `messages` (`subject`, `sender`, `receiver`, `added`, `msg`) VALUES ('Torrent Pedido Reseed', '".$CURUSER['id']."', '".$row2['id']."', '".get_date_time()."', ".sqlesc($message).")");
  }
  
  if ($row["owner"] && $row["owner"] != $CURUSER["id"])
      SQL_Query_exec("INSERT INTO `messages` (`subject`, `sender`, `receiver`, `added`, `msg`) VALUES ('Torrent Pedido Reseed', '".$CURUSER['id']."', '".$row['owner']."', '".get_date_time()."', ".sqlesc($message).")"); 
      
  setcookie("reseed$id", $id, time() + 86400, '/');
  
  show_error_msg("Completo", "Seu pedido de reseed foi enviado.", 1);
  
?>