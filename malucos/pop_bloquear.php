<?php
############################################################
#######                                             ########
#######                                             ########
#######           malucos-share.org 2.0             ########
#######                                             ########
#######                                             ########
############################################################
require_once("backend/smilies.php");
require_once("backend/functions.php");
require_once("backend/config.php");


dbconn();
$takeuser = (int) $_POST["user"];
$taketorrent = (int) $_POST["torrent"];
$takeforumid = (int) $_POST["forumid"];
$takecomment = (int) $_POST["comment"];
$takeforumpost = (int) $_POST["forumpost"];
$takereason = $_POST["reason"];


$torrent = (int)$_GET["bloquearid"];
if($CURUSER["level"]=="Administrador" || $CURUSER["level"]=="Moderador" || $CURUSER["level"]=="Liberador" || $CURUSER["level"]=="Sysop" ){

	?>
	<head>
	<style type="text/css">
body{

background-color:#F4F4F4;
}
</style>

</head>	
	

	  <?php


	

//take report torrent
if (($taketorrent !="") && ($takereason !="")){
    if (!$takereason){
        show_error_msg(T_("ERROR"), T_("YOU_MUST_ENTER_A_REASON"), 0);
  
        die;
    }
	    $res123 = mysql_query("SELECT name, owner, banned FROM torrents WHERE id=$taketorrent ");

$arr123 = mysql_fetch_array($res123);

	    $res1234 = mysql_query("SELECT name, owner, banned FROM torrents WHERE id=$taketorrent AND banned='yes' ");

    if (mysql_num_rows($res1234) == 0){
	
	
									   mysql_query("UPDATE `torrents` SET `banned` = 'yes' WHERE `id` = $taketorrent ");
	                         
	$ts="INSERT INTO `apppbloq` (`uid`, `bloqueado`, `addedb`, `infohash`, `motivo`) VALUES ('$CURUSER[id]', '1','".get_date_time()."','$taketorrent','$takereason')";
     @mysql_query($ts);
						 
$msg = "O usuário $CURUSER[id] Bloqueo o torrent [url=". $site_config[SITEURL]."/torrents-details.php?id=$taketorrent]$arr123[name][/url] pelo seguinte motivo ".sqlesc($takereason)."" ;
             SQL_Query_exec("INSERT INTO messages (poster, sender, receiver, msg, added,subject) VALUES('0','0', " . $arr123['owner'] . ", " .sqlesc($msg) . ", '" . get_date_time() . "','Bloqueado')") or die (mysql_error());
      
		
        print("Torrent: $taketorrent, Motivo: ".htmlspecialchars($takereason)."<p>Bloqueado com sucesso</p>");

        die();
    }else{
        print(T_("YOU_HAVE_ALREADY_REPORTED")." torrent $taketorrent");

        die();
    }
}



//report torrent form
if ($torrent !=""){
    $res = SQL_Query_exec("SELECT name FROM torrents WHERE id=$torrent");

    if (mysql_num_rows($res) == 0){
        print("Invalid TorrentID");

        die();
    }

    $arr = mysql_fetch_array($res);
    print("<center><b>Bloquear</b><br >O torrent é duplicado? Tem alguma informação errada? Informe aqui possíveis erros.<br ><a href='torrents-details.php?id=$torrent'><b>$arr[name]</b></a>?<br /></center>");
    print("<center><form method='post' action='pop_bloquear.php'><input type='hidden' name='torrent' value='$torrent' /><textarea COLS=45 ROWS=9 name='reason'></textarea><br><input type='submit' value='Bloquear?' /></form></center>");

    die();
}


//error
if (($user !="") && ($torrent !="")){
    print("<h1>".T_("MISSING_INFO")."</h1>");

    die();
}

show_error_msg(T_("ERROR"), T_("MISSING_INFO").".", 0);

}
else
{
show_error_msg("STOP", "Desculpe você não tem acesso ");
}	



	

?>