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
                     
  if ($site_config["MEMBERSONLY"]) {
      loggedinonly();
      
      if ($CURUSER["view_torrents"] == "no")
          show_error_msg(T_("ERROR"), T_("NO_TORRENT_VIEW"), 1);
  }

if($CURUSER["level"]=="Administrador" || $CURUSER["level"]=="Moderador" || $CURUSER["level"]=="Liberador" || $CURUSER["level"]=="Sysop" ){

	
  $id = (int) $_GET["id"];

  if (!is_valid_id($id))
	show_error_msg("ERROR", T_("THATS_NOT_A_VALID_ID"), 1);
	
	stdhead("Desbloqueado");

begin_framec("Desbloqueado");
  ?>

  

  
  <?php
	   mysql_query("UPDATE `torrents` SET `banned` = 'no' WHERE `id` = $id ");
	   	mysql_query("DELETE FROM apppbloq WHERE infohash = $id");
	   print("<CENTER>Este torrent foi desbloqueado para novos downloads!</CENTER><br><a href=torrents-details.php?id=$id;><font color=#FF0000><CENTER><b>[ Voltar para a página do Torrent ]</b></CENTER></font></a>");
	

	


 end_framec();
 }
else
{
	show_error_msg(T_("ERROR"), 'Desculpe você não tem acesso!', 1);
 }
  stdfoot();
  
?>