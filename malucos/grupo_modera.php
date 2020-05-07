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
 
 $id = (int) $_GET["id"];
 $res = SQL_Query_exec("SELECT * FROM teams WHERE id = '$id'");
 $row = mysql_fetch_assoc($res);
                                       
 if (mysql_num_rows($res) == 0)
     show_error_msg("Error", "Esse grupo não existe..", 1);
 
   $resultsub1 = SQL_Query_exec("SELECT * FROM usergroups WHERE uid = ".$CURUSER['id']." AND gid = ".$CURUSER['team']."") ;
$row21 = mysql_fetch_array($resultsub1);	
if ($row21["gid"] !=  $id ){
     show_error_msg("Error", "Você não tem acesso a este grupo", 1);
}
  

       
 if ($row21["status"] == 'moderadores' || $row21["status"] == 'submoderadores' )
{ 
  stdhead("Gestão de grupo");
begin_framec("Gestão de grupo");   
 if ($_GET['do'] == "del") { 
		if (!@count($_POST["del"])) {
				print("<b><center>Nada selecionado!!!<a href='grupo_modera.php?id=$id'><br>Voltar</a></center></b>");
					end_framec();
	                stdfoot();
	                die();
					}
		$ids = array_map("intval", $_POST["del"]);
			$ids = implode(", ", $ids);				
                 if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            if (count($_POST['id']))
            {
                foreach ($_POST['id'] as $key)
                {
                    if (is_valid_id($key))
                    {    
		 $status = $_POST["status$key"];
				 
		 $sql = mysql_query("SELECT * FROM `teams` WHERE `id` = " . $id . "");
                $row = mysql_fetch_array($sql);
			 $res1234 = mysql_query("SELECT usergroups.uid, usergroups.gid, users.status, users.id, users.username, users.class, teams.id,  usergroups.status AS grupostatus, users.id AS userid, teams.id AS teamid FROM usergroups LEFT JOIN users ON usergroups.uid = users.id LEFT JOIN teams ON usergroups.gid = teams.id WHERE usergroups.gid = $id ");    
				$user = mysql_fetch_array($res1234);

					

			 
if (mysql_num_rows($res1234) == 1){
	 if ($status == 'retirar'){
		print("<b><center>Desculpe, mais você não pode retirar todos os membros do grupo!!!<a href='grupo_modera.php?id=$id'><br>Voltar</a></center></b>");
					end_framec();
	                stdfoot();
	                die();
}
}
        if ($status == 'retirar'){

			 
		       $sql = mysql_query("SELECT * FROM `teams` WHERE `id` = " . $id . "");
             $row = mysql_fetch_array($sql);
			 $res1234 = mysql_query("SELECT usergroups.uid, usergroups.gid, users.status, users.id, users.username, users.class, teams.id,  usergroups.status AS grupostatus, users.id AS userid, teams.id AS teamid FROM usergroups LEFT JOIN users ON usergroups.uid = users.id LEFT JOIN teams ON usergroups.gid = teams.id WHERE usergroups.gid = $id AND usergroups.uid = $key AND users.id IN ($ids) LIMIT 1");    
         	 $user = mysql_fetch_array($res1234);
			 $userid = $user["userid"] ;
			 $useidteam = $user["teamid"] ;
		     $dt = sqlesc(get_date_time()); 
			 			 $verificar = mysql_query("SELECT * FROM `usergroups` WHERE `uid` = " . $CURUSER["id"] . "");
             $rowverificar = mysql_fetch_array($verificar);
			 if ($rowverificar["status"] == 'submoderadores'){
				   $gruclassub = 1 ;
		       	 }	 	
         	 if ($user["grupostatus"] == 'moderadores'){
				   $gruclasmod = 2 ;
		       	 }

              if ($gruclassub < $gruclasmod){
			  	   print("<b><center>Desculpe, mais você não pode rebaixa o moderador do grupo!!!<a href='grupo_modera.php?id=$id'><br>Voltar</a></center></b>");
					end_framec();
	                stdfoot();
	                die();
			  }	
			 
			 if ($userid == $CURUSER["id"]){
			 		print("<b><center>Desculpe, mais você não pode muda seu status dentro do grupo!!!<a href='grupo_modera.php?id=$id'><br>Voltar</a></center></b>");
					end_framec();
	                stdfoot();
	                die();
			 }

		     mysql_query("INSERT INTO grupodel (gruid, gruuserid, grudata) VALUES($useidteam, $userid, $dt)");


							    if ($user["class"] == 55)
                              {

						 	  if (mysql_num_rows($res1234) > 0){
							$delretira = "DELETE FROM `usergroups` WHERE `gid` = $useidteam AND `uid` = $userid ";
							$delretirav = mysql_query($delretira);
							
					
							mysql_query("UPDATE users SET  team='0' WHERE id=$userid ");
							
							    $message = 'Você acaba de ser removido do grupo - ' . htmlspecialchars($row['name']);
                    			$dt = sqlesc(get_date_time()); 
                                $sub = sqlesc($row['name'] . ' Grupo');
                                $msg = sqlesc($message);
  			                    mysql_query("INSERT INTO messages (sender, receiver, added, msg, poster, subject) VALUES(0, $userid, $dt, $msg, 0, $sub)");
			                                }
                              }else{

						 	  if (mysql_num_rows($res1234) > 0){
							$delretira2 = "DELETE FROM `usergroups` WHERE `gid` = $useidteam AND `uid` = $userid  ";                            
							$delretirav2 = mysql_query($delretira2);{
							mysql_query("UPDATE users SET team='0' WHERE id=$userid ");
							                }
							    $message = 'Você acaba de ser removido do grupo - ' . htmlspecialchars($row['name']);
                    			$dt = sqlesc(get_date_time()); 
                                $sub = sqlesc($row['name'] . ' Grupo');
                                $msg = sqlesc($message);
  			                    mysql_query("INSERT INTO messages (sender, receiver, added, msg, poster, subject) VALUES(0, $userid, $dt, $msg, 0, $sub)");
							          }
			                    }

					   }
		if ($status == 'membrogrupo'){
		       $sql = mysql_query("SELECT * FROM `teams` WHERE `id` = " . $id . "");
             $row = mysql_fetch_array($sql);
			 $res1234 = mysql_query("SELECT usergroups.uid, usergroups.gid, users.status, users.id, users.username, users.class, teams.id,  usergroups.status AS grupostatus, users.id AS userid, teams.id AS teamid FROM usergroups LEFT JOIN users ON usergroups.uid = users.id LEFT JOIN teams ON usergroups.gid = teams.id WHERE usergroups.gid = $id AND usergroups.uid = $key AND users.id IN ($ids) LIMIT 1");    


						$user = mysql_fetch_array($res1234);
						$userid = $user["userid"] ;
						$useidteam = $user["teamid"] ;
									 $verificar = mysql_query("SELECT * FROM `usergroups` WHERE `uid` = " . $CURUSER["id"] . "");
             $rowverificar = mysql_fetch_array($verificar);
			 if ($rowverificar["status"] == 'submoderadores'){
				   $gruclassub = 1 ;
		       	 }	 	
         	 if ($user["grupostatus"] == 'moderadores'){
				   $gruclasmod = 2 ;
		       	 }

              if ($gruclassub < $gruclasmod){
			  	   print("<b><center>Desculpe, mais você não pode rebaixa o moderador do grupo!!!<a href='grupo_modera.php?id=$id'><br>Voltar</a></center></b>");
					end_framec();
	                stdfoot();
	                die();
			  }	
			 if ($userid == $CURUSER["id"]){
			 		print("<b><center>Desculpe, mais você não pode muda seu status dentro do grupo!!!<a href='grupo_modera.php?id=$id'><br>Voltar</a></center></b>");
					end_framec();
	                stdfoot();
	                die();
			 }						
						
								if ($user["class"] == 55 || $user["class"] == 60|| $user["class"] == 65){
							    if (mysql_num_rows($res1234) > 0){
   
                                mysql_query("UPDATE users SET class='55' WHERE id=$userid ");
								mysql_query("UPDATE `usergroups` SET `status` = 'membrogrupo',`chave` = '1' WHERE `gid` = $useidteam AND `uid` = $userid");
                                $message = 'Você acaba de ser promovido a membro do grupo - ' . htmlspecialchars($row['name']);
                    			$dt = sqlesc(get_date_time()); 
                                $sub = sqlesc($row['name'] . ' Grupo');
                                $msg = sqlesc($message);
  			                    mysql_query("INSERT INTO messages (sender, receiver, added, msg, poster, subject) VALUES(0, $userid, $dt, $msg, 0, $sub)");  
                                   }
								   }
									else
								    {
								if (mysql_num_rows($res1234) > 0){
			                    mysql_query("UPDATE `usergroups` SET `status` = 'membrogrupo',`chave` = '1' WHERE `gid` = $useidteam AND `uid` = $userid");  
                                $message = 'Você acaba de ser promovido a membro do grupo - ' . htmlspecialchars($row['name']);
                    			$dt = sqlesc(get_date_time()); 
                                $sub = sqlesc($row['name'] . ' Grupo');
                                $msg = sqlesc($message);
  			                    mysql_query("INSERT INTO messages (sender, receiver, added, msg, poster, subject) VALUES(0, $userid, $dt, $msg, 0, $sub)");  									

							mysql_query("UPDATE `usergroups` SET `status` = 'membrogrupo', `chave` = '1' WHERE `gid` = $id AND uid = $key AND `status` = 'submoderadores'");
							}
							}
						
			}
	    if ($status == 'submoderadores'){
		       $sql = mysql_query("SELECT * FROM `teams` WHERE `id` = " . $id . "");
             $row = mysql_fetch_array($sql);
			 $res1234 = mysql_query("SELECT usergroups.uid, usergroups.gid, users.status, users.id, users.username, users.class, teams.id,  usergroups.status AS grupostatus, users.id AS userid, teams.id AS teamid FROM usergroups LEFT JOIN users ON usergroups.uid = users.id LEFT JOIN teams ON usergroups.gid = teams.id WHERE usergroups.gid = $id AND usergroups.uid = $key AND users.id IN ($ids) LIMIT 1");    


						$user = mysql_fetch_array($res1234);
						$userid = $user["userid"] ;
						$useidteam = $user["teamid"] ;
			$verificar = mysql_query("SELECT * FROM `usergroups` WHERE `uid` = " . $CURUSER["id"] . "");
             $rowverificar = mysql_fetch_array($verificar);
			 if ($rowverificar["status"] == 'submoderadores'){
				   $gruclassub = 1 ;
		       	 }	 	
         	 if ($user["grupostatus"] == 'moderadores'){
				   $gruclasmod = 2 ;
		       	 }

              if ($gruclassub < $gruclasmod){
			  	   print("<b><center>Desculpe, mais você não pode rebaixa o moderador do grupo!!!<a href='grupo_modera.php?id=$id'><br>Voltar</a></center></b>");
					end_framec();
	                stdfoot();
	                die();
			  }	
			 if ($userid == $CURUSER["id"]){
			 		print("<b><center>Desculpe, mais você não pode muda seu status dentro do grupo!!!<a href='grupo_modera.php?id=$id'><br>Voltar</a></center></b>");
					end_framec();
	                stdfoot();
	                die();
			 }						
						
								if ($user["class"] == 55 || $user["class"] == 60|| $user["class"] == 65){
							    if (mysql_num_rows($res1234) > 0){
                                    
                                mysql_query("UPDATE users SET class='60' WHERE id=$userid ");
								mysql_query("UPDATE `usergroups` SET `status` = 'submoderadores',`chave` = '2' WHERE `gid` = $useidteam AND `uid` = $userid");
                                $message = 'Você acaba de ser promovido a submoderado do  - ' . htmlspecialchars($row['name']);
                    			$dt = sqlesc(get_date_time()); 
                                $sub = sqlesc($row['name'] . ' Grupo');
                                $msg = sqlesc($message);
  			                    mysql_query("INSERT INTO messages (sender, receiver, added, msg, poster, subject) VALUES(0, $userid, $dt, $msg, 0, $sub)");  
                                   }
								   }
									else
								    {
								if (mysql_num_rows($res1234) > 0){
			                    mysql_query("UPDATE `usergroups` SET `status` = 'submoderadores',`chave` = '2' WHERE `gid` = $useidteam AND `uid` = $userid");  
                                $message = 'Você acaba de ser promovido a submoderado do - ' . htmlspecialchars($row['name']);
                    			$dt = sqlesc(get_date_time()); 
                                $sub = sqlesc($row['name'] . ' Grupo');
                                $msg = sqlesc($message);
  			                    mysql_query("INSERT INTO messages (sender, receiver, added, msg, poster, subject) VALUES(0, $userid, $dt, $msg, 0, $sub)");  									
							}
							}
   
					   }
        if ($status == 'moderadores'){

		
		

  $sql = mysql_query("SELECT * FROM `teams` WHERE `id` = " . $id . "");
             $row = mysql_fetch_array($sql);
			 $res1234 = mysql_query("SELECT usergroups.uid, usergroups.gid, users.status, users.id, users.username, users.class, teams.id,  usergroups.status AS grupostatus, users.id AS userid, teams.id AS teamid FROM usergroups LEFT JOIN users ON usergroups.uid = users.id LEFT JOIN teams ON usergroups.gid = teams.id WHERE usergroups.gid = $id AND usergroups.uid = $key AND users.id IN ($ids) LIMIT 1");    


						$user = mysql_fetch_array($res1234);
						$userid = $user["userid"] ;
						$useidteam = $user["teamid"] ;
			$verificar = mysql_query("SELECT * FROM `usergroups` WHERE `uid` = " . $CURUSER["id"] . "");
             $rowverificar = mysql_fetch_array($verificar);
			 if ($rowverificar["status"] == 'submoderadores'){
				   $gruclassub = 1 ;
		       	 }	 	
         	 if ($user["grupostatus"] == 'moderadores'){
				   $gruclasmod = 2 ;
		       	 }

              if ($gruclassub < $gruclasmod){
			  	   print("<b><center>Desculpe, mais você não pode rebaixa o moderador do grupo!!!<a href='grupo_modera.php?id=$id'><br>Voltar</a></center></b>");
					end_framec();
	                stdfoot();
	                die();
			  }				
			 if ($userid == $CURUSER["id"]){
			 		print("<b><center>Desculpe, mais você não pode muda seu status dentro do grupo!!!<a href='grupo_modera.php?id=$id'><br>Voltar</a></center></b>");
					end_framec();
	                stdfoot();
	                die();
			 }						
								if ($user["class"] == 55 || $user["class"] == 60|| $user["class"] == 65){
							    if (mysql_num_rows($res1234) > 0){
                                    
                                mysql_query("UPDATE users SET class='65' WHERE id=$userid ");
								mysql_query("UPDATE `usergroups` SET `status` = 'moderadores',`chave` = '3' WHERE `gid` = $useidteam AND `uid` = $userid");
                                $message = 'Você acaba de ser promovido a moderador do  - ' . htmlspecialchars($row['name']);
                    			$dt = sqlesc(get_date_time()); 
                                $sub = sqlesc($row['name'] . ' Grupo');
                                $msg = sqlesc($message);
  			                    mysql_query("INSERT INTO messages (sender, receiver, added, msg, poster, subject) VALUES(0, $userid, $dt, $msg, 0, $sub)");  
                                   }
								   }
									else
								    {
								if (mysql_num_rows($res1234) > 0){
			                    mysql_query("UPDATE `usergroups` SET `status` = 'moderadores',`chave` = '3' WHERE `gid` = $useidteam AND `uid` = $userid");  
                                $message = 'Você acaba de ser promovido a moderador do - ' . htmlspecialchars($row['name']);
                    			$dt = sqlesc(get_date_time()); 
                                $sub = sqlesc($row['name'] . ' Grupo');
                                $msg = sqlesc($message);
  			                    mysql_query("INSERT INTO messages (sender, receiver, added, msg, poster, subject) VALUES(0, $userid, $dt, $msg, 0, $sub)");  									
							}
							}
   
			
					   
					   }			   
					   }
					   }
					 print("<b><center>Update sucesso!!!<a href='grupo_modera.php?id=$id'><br>Voltar</a></center></b>");
					end_framec();
	                stdfoot();
	                die();
					   }
					   }

}


	 $resultu = mysql_query("SELECT usergroups.uid, usergroups.gid, usergroups.joined,  users.status, users.enabled, users.id, users.username, users.last_access,  users.uploaded,  users.downloaded, usergroups.status AS grupostatus FROM usergroups LEFT JOIN users ON usergroups.uid = users.id WHERE  usergroups.gid = '$id' AND users.enabled = 'yes' AND users.status = 'confirmed' ");    

?>	
	<script language="JavaScript" type="text/Javascript">
		function checkAll(box) {
			var x = document.getElementsByTagName('input');
			for(var i=0;i<x.length;i++) {
				if(x[i].type=='checkbox') {
					if (box.checked)
						x[i].checked = false;
					else
						x[i].checked = true;
				}
			}
			if (box.checked)
				box.checked = false;
			else
				box.checked = true;
		}
	</script>


	<?php
	  if (mysql_num_rows($resultu) > 0)
        {	   

            echo("<table border='1' cellpadding='5' cellspacing='3' align='center' width='100%' class='ttable_headinner'>");
            echo("<tr>");
			echo("<td class='ttable_head' width='1%' align='center'><input type='checkbox' id='checkAll' onclick='checkAll(this)'></td>");
            echo("<td class='ttable_head' width='10%' align='center'><strong>Membros</strong></td>");
            echo("<td class='ttable_head' width='10%' align='center'><strong>Pediu para se juntar</strong></td>");
			echo("<td class='ttable_head' width='10%'  align='center' ><strong>Ultimo acesso/Quando</strong></td>");
			echo("<td class='ttable_head' width='10%' align='center'><strong>Ação</strong></td>");
			echo("<td class='ttable_head' width='10%' align='center'><strong>Torrents ativos:</strong></td>");
			echo("<td class='ttable_head' width='10%' align='center'><strong>Download</strong></td>");
            echo("<td class='ttable_head' width='10%' align='center'><strong>Upload</strong></td>");
            echo("</tr>");
           
          while ($row = mysql_fetch_array($resultu))
            {

$numtorrents = get_row_count("torrents", "WHERE owner = " . $row['uid'] . "");
            
		$uploaded = mksize($row['uploaded']);
		$downloaded = mksize($row['downloaded']);

			
   $loginstatus = "" . (get_elapsed_time(sql_timestamp_to_unix_timestamp($row["last_access"]))) . " atrás";


 
	        echo("<form method='post' action='grupo_modera.php?id=$id&do=del'>");

                echo("<input type='hidden' name='id[]' value='" . $row["uid"] . "'>");  
                
                echo("<tr>");
				echo("<td class='ttable_col2' width='1%' align='center'><input type='checkbox' name='del[]' value='$row[uid]'></td>");
                echo("<td class='ttable_col2' width='10%' align='center'><a href='account-details.php?id=" . $row['uid'] . "'>" . htmlspecialchars($row["username"]) . "</a></td>");  
                echo("<td class='ttable_col2' width='10%' align='center'>" . utc_to_tz($row['joined']) . "</td>");
				echo("<td class='ttable_col2' width='10%' align='center'><center> ".date(utc_to_tz($row["last_access"]))."<br>$loginstatus</center></td>");
				
				
				
				
$query3 = mysql_query("SHOW COLUMNS FROM usergroups WHERE Field = 'status'");
$row3 = mysql_fetch_array($query3);
$enum = str_replace("enum(", "", $row3['Type']);
$enum = str_replace("'", "", $enum);
$enum = substr($enum, 0, strlen($enum) - 1);
$enum = explode(",", $enum);
echo "<td class=ttable_col2 width=10% align=center><select  name='status" . $row['uid'] . "'>";
foreach ($enum as $chave => $campo) {
if($campo == $row['grupostatus'] ){
  echo '<option selected="selected" value="'.$campo.'">'.$campo.'</option>';
 }else{
  echo '<option value="'.$campo.'">'.$campo.'</option>';
 }
}

echo '</select></td>';
echo("<td class='ttable_col2' width='10%' align='center'>$numtorrents </td>");
echo("<td class='ttable_col2' width='10%' align='center'>$downloaded </td>");
echo("<td class='ttable_col2' width='10%' align='center'>$uploaded<input type='hidden' name='ditarmembro' value='true' /></td>");
	
 echo("</tr>");  
							
				
 
         }
            
            echo("<tr>");
			echo("<tr><td width=100% align=center colspan=8 class=ttable_col2 ><input type='submit' value='Salvar alterações'/><input type='reset' value='Redefinir'/></td>\n");

            echo("</tr>");
            echo("</table>");
            echo("</form>");
	    		echo "<BR><BR>";
 print("<a href=painel_grupos.php?id=$id><font color=#FF0000><CENTER><b>[Voltar painel de grupo]</b></CENTER></font></a>");
	    }
		   else {
           echo('<center>Nao temos membros no grupo</center>'); 
        }


	end_framec();

	}else{
      show_error_msg("Error", "Você não tem permissão para isso.", 1);
}
	stdfoot();

 
?>