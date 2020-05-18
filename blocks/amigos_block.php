<?php
############################################################
#######                                             ########
#######                                             ########
#######           brshares.com 2.0                  ########
#######                                             ########
#######                                             ########
############################################################ 
if($CURUSER){
	begin_block("Amigos Online");
	
	$sql = "SELECT id, friendid FROM friends WHERE userid = '$CURUSER[id]'";
	$mysql_result=mysql_query($sql) or die (mysql_error());
	$res = mysql_fetch_assoc($mysql_result);
	$id = $res['friendid'];
	$dt = get_date_time(gmtime() - 180);
	$exe_contagem = mysql_query("SELECT * FROM users WHERE id = '".$id."' AND last_access > '".$dt."'");
	$contagem = mysql_num_rows($exe_contagem);
	if ( $contagem > 0 ) {
		echo"<table widt=100%>";
		while ($row=mysql_fetch_array($mysql_result)){
			$idami = $row['friendid'];
			$sql2 = mysql_query("SELECT id, username, last_access, avatar FROM users WHERE id = '$idami'");
			$res2= mysql_fetch_array($sql2) or die (mysql_error());
			$ami = $res2['username'];
			$avatar = htmlspecialchars($res2["avatar"]);
			if (!$avatar) {
				$avatar = "".$site_config["SITEURL"]."/images/default_avatar.gif";
			}

			echo "<td><a href=account-details.php?id=$res2[id]><b><font size=2>".$ami."</font></b></a></td>";
			echo "<td><a href=mailbox.php?compose&id=$res2[id]><br><br>". "Enviar MP</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src=$avatar width=60 height=75 border=0></td><tr><br><br>";
		}
		echo"</tr>";
		echo "</table>";
		print ("<center><a href=amigos.php><br><br>Ver todos os Amigos</a></center>");
	}else{
		echo"<table widt=100%>";
		echo"<tr>";
		print ("<td><center>Nenhum amigo online</center></td>");
		echo"</tr>";
		echo "<tr><td>&nbsp;</td></tr>";
		echo"<tr>";
		print ("<td><center><a href=amigos.php>Ver todos os Amigos</a></center></td>");
		echo"</tr>";
		echo "</table>";
	}
	end_block();
}
?>