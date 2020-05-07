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


$convite = $_GET["convite"];
$userid = $CURUSER["id"] ;

 	 $sql1 = mysql_query("SELECT * FROM `grupoaceita` WHERE `invite` = '" . $convite . "' AND iduser=".$CURUSER['id']."");
     $row1 = mysql_fetch_array($sql1);
	 


if (!$row1){
	show_error_msg("".Error."", "Convite inexistente",1);
}
else

{
$userid = $CURUSER["id"] ;
 $teamid = $row1["idteam"] ;
    $joined = sqlesc(get_date_time()); 
	  $sql = mysql_query("SELECT * FROM `teams` WHERE `id` = " . $teamid . "");
             $row = mysql_fetch_array($sql);
	$res123 = mysql_query("SELECT id, class FROM users WHERE id=".$CURUSER['id']." LIMIT 1") or die(mysql_error());
						$user = mysql_fetch_array($res123);
                $message = "Seja Bem Vindo o grupo ".htmlspecialchars($row['name'])." !!!";
                            $joined = sqlesc(get_date_time()); 
                        $sub = sqlesc($row['name'] . ' Grupo');
                        $msg = sqlesc($message);
                        mysql_query("INSERT INTO messages (sender, receiver, added, msg, poster, subject) VALUES(0, $userid, $joined, $msg, 0, $sub)");

						if ($user["class"] == 1)
                              {
		
                                  mysql_query("UPDATE users SET class='55', team=$teamid WHERE id=".$CURUSER['id']."");
								  mysql_query("INSERT INTO usergroups (gid, uid, status, joined, chave) VALUES(".$teamid.", ".$userid.", 'membrogrupo', ".$joined." , '1')");
								  $delretira = "DELETE FROM `grupoaceita` WHERE `idteam` = $teamid AND simounao = 'yes' AND  `iduser` = $userid  ";
							      $delretirav = mysql_query($delretira);
								show_error_msg("Ops.", "Update sucesso!!!",1);   
                     }else{
				
						mysql_query("UPDATE users SET team=$id WHERE id=$key");	  
						mysql_query("INSERT INTO usergroups (gid, uid, status, joined, chave) VALUES(".$teamid.", ".$userid.", 'membrogrupo', ".$joined." , '1')");
						$delretira = "DELETE FROM `grupoaceita` WHERE `idteam` = $teamid AND simounao = 'yes' AND  `iduser` = $userid  ";
						$delretirav = mysql_query($delretira);
									show_error_msg("Ops.", "Update sucesso!!!",1);   
                           }   
}

?>