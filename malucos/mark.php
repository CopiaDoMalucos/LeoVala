<?php
require_once("backend/functions.php");
dbconn();

if($CURUSER["level"]=="Administrador" || $CURUSER["level"]=="Sysop" || $CURUSER["level"]=="Moderador" || $CURUSER["level"]=="Liberador"){

$id = 0 + $_GET['id'];
// Check if user has already marked it
$torrentid = $id;


$res = mysql_query("SELECT * FROM torrents WHERE safe='yes' AND id=$id") or sqlerr(__FILE__, __LINE__);
$arr = mysql_fetch_assoc($res);
$marked = $arr;


			
if ($marked) {
stdhead("Woopsie!");
?>
<h1>Este torrent já está aprovado!</h1>
<p>Parece que alguém foi mais rápido que você .. hehehe</p>
<?php
}
	$testeuser = mysql_query("SELECT * from moderation WHERE infohash=$id");
            $testeapro = mysql_fetch_array($testeuser);
	$testeuser1 = mysql_query("SELECT * from moderation WHERE moderar='yes' AND infohash=$id");
            $testeapro1 = mysql_fetch_array($testeuser1);


if($testeapro['uid']==$CURUSER["id"] || mysql_num_rows($testeuser)==0  || $CURUSER["id"] == "269" || $CURUSER["id"] == "1" || mysql_num_rows($testeuser1) == 1  ) {
$res1 = mysql_query("SELECT torrents.id, torrents.size, torrents.safe, torrents.name, torrents.owner FROM torrents WHERE id='".$id."' AND safe='no'");
$arr1 = mysql_fetch_assoc($res1);
$owner = $arr1["owner"];
$torrentname = $arr1["name"];


$msg = "Parabéns!\n\nSeu torrent [size=2][color=green]$torrentname [/color][/size]foi aprovado!!! \n\n Agora estará visível na HOME do site para downloads.\n
Obrigado!!\n\n Torrents liberados devem ficar de seed no horário especificado no campo horário de seed na hora do lançamento.\n\n Torrents sem seed a 15 dias seram deletados sem aviso prévio.\n\n\n Equipe MS";
$sql = "INSERT INTO messages (sender, receiver, msg, added, subject) VALUES (0, $owner, \"". stripslashes ($msg)."\", '".get_date_time()."', ' Torrent Liberado')";

mysql_query($sql);
mysql_query("UPDATE torrents SET safe='yes', markedby='$CURUSER[username]' WHERE id=$id") or die(mysql_error());
mysql_query("UPDATE torrents SET safe='yes', markdate='".get_date_time()."' WHERE id=$id") or die(mysql_error());
 
 				$res_qualidade = mysql_query("SELECT filmeresolucao, filmeresolucalt, category FROM torrents WHERE id = $id");
                $row_qualidade = mysql_fetch_array($res_qualidade);
				    if ( $row_qualidade["category"] == 95 ){
               if ($row_qualidade["filmeresolucao"] > 1200 ||  $row_qualidade["filmeresolucalt"] > 720 )
 {
			   
		mysql_query("UPDATE torrents SET freeleech='1' WHERE id=$id") or die(mysql_error());
        }	
 }
 if($arr1["size"] >= 4294967296){
					mysql_query("UPDATE torrents SET freeleech='1' WHERE id=$id") or die(mysql_error());
				     
						}		

	
		
					
					
					if($admin_config['ranking']['ativo'] = 'S'){
					$pontos = (int) 0;
						if($arr1["size"]< 10485760) $pontos = "1";
						if($arr1["size"] >= 10485760 && $tor["size"] < 53477375) $pontos = "1";
						if($arr1["size"] >= 53477376 && $tor["size"] < 157286399) $pontos = "3";
						if($arr1["size"] >= 157286400 && $tor["size"] < 524288000) $pontos = "6";
						if($arr1["size"] >= 524288001 && $tor["size"] < 734003200) $pontos = "8";
						if($arr1["size"] >= 734003201 && $tor["size"] < 1610612735) $pontos = '12';
						if($arr1["size"] >= 1610612736 && $tor["size"] < 4294967295) $pontos = "15";
						if($arr1["size"] >= 4294967296 && $tor["size"] < 6442450943) $pontos = "20";
						if($arr1["size"] >= 6442450944 && $tor["size"] < 16106127359) $pontos = "30";
						if($arr1["size"] > 16106127360)  $pontos = "40";
				
						mysql_query("UPDATE users SET seedbonus=seedbonus+{$pontos} WHERE id={$arr1["owner"]}");

						write_loguser("Torrents-liberados","#FF0000","O torrent [url=http://www.malucos-share.org/torrents-details.php?id=".$id."]".$torrentname."[/url] foi liberado por [url=http://www.malucos-share.org/account-details.php?id=".$CURUSER["id"]."]".$CURUSER["username"]."[/url] \n");

						$ts="INSERT INTO `apppprovar` (`uid`, `app`, `aprovado`, `added`, `infohash`) VALUES ('".$CURUSER['id']."', '$torrentname', '1','".get_date_time()."','$id')";
                         @mysql_query($ts);
						 $torupado="INSERT INTO `torrentlancado` (`uid`, `app`, `aprovado`, `added`, `infohash`) VALUES ('".$arr1["owner"]."', '$torrentname', '1','".get_date_time()."','$id')";
                         @mysql_query($torupado);
					}
					

	
	
;
header("Refresh: 0; url=app.php");
}
else
{

	header("Refresh: 2;url=app.php");
		stdhead();
			show_error_msg("Error", "Este torrent já esta sendo moderado...", 1);
		stdfoot();
		die;



}
}
else{


show_error_msg("STOP", "Desculpe esta página é para os liberadores +");
end_framec();
}
?>