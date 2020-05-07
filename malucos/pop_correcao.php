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

$user = (int)$_GET["user"];
$torrent = (int)$_GET["correid"];
$comment = (int)$_GET["comment"];
$forumid = (int)$_GET["forumid"];
$forumpost = (int)$_GET["forumpost"];	

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
    $res123 = SQL_Query_exec("SELECT name, owner FROM torrents WHERE id=$taketorrent");
$arr123 = mysql_fetch_array($res123);
	$res12 = SQL_Query_exec("SELECT id, username, ver_com FROM users WHERE id=$CURUSER[id]");
					$arr12 = MYSQL_FETCH_ARRAY($res12);

    $res = SQL_Query_exec("SELECT id FROM reports WHERE addedby = $CURUSER[id] AND votedfor = $taketorrent AND type = 'correcao'");
    if (mysql_num_rows($res) == 0){
	
	
	
        SQL_Query_exec("INSERT into reports (addedby,votedfor,type,reason) VALUES ($CURUSER[id],$taketorrent,'correcao', ".sqlesc($takereason).")");
$msg = "O usuário " . $arr12['username'] . " gostaria de pedir a correção do torrent [url=". $site_config[SITEURL]."/torrents-details.php?id=$taketorrent]" . $arr123[name] . "[/url] por motivo ".sqlesc($takereason)."" ;
             SQL_Query_exec("INSERT INTO messages (poster, sender, receiver, msg, added,subject) VALUES('0','0', " . $arr123['owner'] . ", " .sqlesc($msg) . ", '" . get_date_time() . "','correção!')") or die (mysql_error());
      
		
        print("Torrent: $taketorrent, Reason: ".htmlspecialchars($takereason)."<p>Successfully Reported</p>");

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
    print("<center><b>Sugerir correção</b><br >O torrent é duplicado? Tem alguma informação errada? Informe aqui possíveis erros. Eles serão prontamente corrigidos pela nossa equipe.<br ><a href='torrents-details.php?id=$torrent'><b>$arr[name]</b></a>?<br /></center>");
    print("<center><form method='post' action='pop_correcao.php'><input type='hidden' name='torrent' value='$torrent' /><textarea COLS=45 ROWS=9 name='reason'></textarea><br><input type='submit' value='Enviar correção' /></form></center>");

    die();
}


//error
if (($user !="") && ($torrent !="")){
    print("<h1>".T_("MISSING_INFO")."</h1>");

    die();
}

show_error_msg(T_("ERROR"), T_("MISSING_INFO").".", 0);


	

?>	

