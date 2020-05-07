<?php
############################################################
#######                                             ########
#######                                             ########
#######           malucos-share.org 2.0             ########
#######                                             ########
#######                                             ########
############################################################
require_once ("backend/functions.php");
require_once ("backend/bbcode.php");
dbconn(false);
loggedinonly();



if (!$CURUSER || $CURUSER["control_panel"]!="yes"){
	 show_error_msg(T_("ERROR"), T_("SORRY_NO_RIGHTS_TO_ACCESS"), 1);
}


$sure = $_GET['sure'];
$del = $_GET['del'];
$team = htmlspecialchars($_GET['team']);
$edited = (int)$_GET['edited'];
$id = (int)$_GET['id'];
$team_name = $_GET['team_name'];
$team_title = $_GET['info'];
$team_image = $_GET['team_image'];
$team_description = $_GET['team_description'];
$teamownername = $_GET['team_owner'];
$editid = $_GET['editid'];
$editmembers = $_GET['editmembers'];
$name = $_GET['name'];
$image = $_GET['image1'];
$owner = $_GET['owner'];
$info = $_GET['info'];
$add = $_GET['add'];
$ditarmembro = $_GET['ditarmembro'];
$grupocriado = $_GET['grupocriado'];
$added = sqlesc(get_date_time());


stdhead("Grupos de gestão");
begin_framec("Grupos de gestão");

if($grupocriado == 'v1') {
print("<b><center>Grupo criado com sucesso!!!</center></b>");
}
//Delete Team
if($sure == "yes") {


   
 $query1 = mysql_query("SELECT * FROM usergroups LEFT JOIN users ON usergroups.uid = users.id WHERE usergroups.gid = " .sqlesc($del) . " AND users.enabled = 'yes' AND users.status = 'confirmed' AND  usergroups.gid IN (" .sqlesc($del) . ") ");    
   
   while($dat1=mysql_fetch_assoc($query1)){
	SQL_Query_exec("INSERT INTO messages (sender, receiver, added, msg, subject) VALUES('system', $dat1[uid], '".get_date_time()."', ".sqlesc($_POST['body']).", 'Encerramento do Grupo - ($team)')");
	}
	
	$query = "UPDATE users SET team=0 WHERE team=" .sqlesc($del) . "";
	$sql = mysql_query($query);

	$query = "DELETE FROM teams WHERE id=" .sqlesc($del) . " LIMIT 1";
	$sql = mysql_query($query);
	
	$query = "DELETE FROM usergroups WHERE gid=" .sqlesc($del) . "";
	$sql = mysql_query($query);
	
	echo("<center>Grupo excluido com sucesso!!!<br>[<a href='teams-create.php'>Voltar</a>]</center>");
	end_framec();
	stdfoot();
	die();
}

if($del > 0) {
    print("<center><b>Informe a causa, motivo, razão ou circunstância sobre o fechamento do grupo? <br>(<b> $team </b>) </b><br /></center>");
    print("<center><form method='post' action='teams-create.php?del=$del&amp;team=$team&amp;body=$body&amp;sure=yes'><input type='hidden' name='torrent' value='$torrent' /><textarea COLS=80 ROWS=9 name='body'></textarea><br><input type='submit' value='Confirmar' /><input type='button' value='Voltar' onClick='history.go(-1)'> </form></center>");
	

	end_framec();
	stdfoot();
	die();
}

//Edit Team
if($edited == 1) {
    
    if (!$team_name || !$teamownername|| !$team_description || !$teamownername ) {
         print 'Um ou mais campos foram deixados em branco.';
         end_framec();
         stdfoot();
         die;
    }
    
    $team_name = sqlesc($team_name);
    $team_image = sqlesc($team_image);
    $teamownername = sqlesc($teamownername);
    $team_description = sqlesc($team_description);
    
	$aa = mysql_query("SELECT class, id FROM users WHERE username=$teamownername");
	$ar = mysql_fetch_assoc($aa);
	$team_owner = $ar["id"];
	$query = "UPDATE teams SET	name = $team_name, info = $team_description, owner = $team_owner, image1 = $team_image WHERE id=".sqlesc($id);
	$sql = mysql_query($query);

	mysql_query("UPDATE users SET team = '$id' WHERE id= '$team_owner'");

	if($sql) {
		echo("<table cellspacing='0' cellpadding='5' width='50%'>");
		echo("<center><tr><td><div align='left'><b>Editado com sucesso</b><br><br>[<a href='teams-create.php'>Voltar</a>]</div></tr></center>");
		echo("</table>");

		end_framec();
		stdfoot();
		die();
	}
}

if($editid > 0) {
$queryt = "SELECT * FROM teams WHERE id=$editid";
$sqlt = mysql_query($queryt);
$rowt = mysql_fetch_array($sqlt);

	echo("<form name='smolf3d' method='get' action='teams-create.php'>");
    echo("<input type='hidden' name='id' value='$editid' />");
    echo("<input type='hidden' name='edited' value='1' />");   
	print("<CENTER><table class='tab1' cellpadding='0' cellspacing='1' align='center' >");
	print("<tr><td width=40%  align=right  class=tab1_col3><b>Nome do grupo: *</b></td><td width=20%  align=left  class=tab1_col3><input type=text size=40 maxlength=20 name='team_name' value='".$rowt['name']."'></td></tr>");
	print("<tr><td width=40%  align=right  class=tab1_col3><b>Logo do grupo: *</b></td><td width=60%  align=left  class=tab1_col3><input type=text size=40  name='team_image' value='".$rowt['image1']."' ></td></tr>");
	print("<tr><td width=40%  align=right  class=tab1_col3><b>Fundador do grupo: *</b></td><td width=60%  align=left  class=tab1_col3><input type=text size=40  name='team_owner'  value='$owner'></td></tr>");
	print("<tr><td width=40%  align=right  class=tab1_col3><b>Capa do grupo: *</b></td><td width=60%  align=left  class=tab1_col3><textarea cols='50' rows='10' name='team_description'>".$rowt['info']."</textarea></td></tr>");
	print("<tr><td width=100% align=center colspan=2 class=tab1_col3 ><input type=submit value='Salvar' style='height: 22px'>\n");
	echo("</table></form>");
	end_framec();
	stdfoot();
	die();
}

//View Members
if($editmembers > 0) {
if($ditarmembro == 'true') {
if ($_GET['do'] == "del") { 
		if (!@count($_POST["del"])) {
				print("<b><center>Nada selecionado!!!<a href='teams-create.php?editmembers=$editmembers'><br>Voltar</a></center></b>");
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
						   			 $sql = mysql_query("SELECT * FROM `teams` WHERE `id` = " . $editmembers . "");
             $row = mysql_fetch_array($sql);
			 $res1234 = mysql_query("SELECT usergroups.uid, usergroups.gid, users.status, users.id, users.username, users.class, teams.id,  usergroups.status AS grupostatus, users.id AS userid, teams.id AS teamid FROM usergroups LEFT JOIN users ON usergroups.uid = users.id LEFT JOIN teams ON usergroups.gid = teams.id WHERE usergroups.gid = $editmembers ");    
				$user = mysql_fetch_array($res1234);

						

if (mysql_num_rows($res1234) == 1){
	 if ($status == 'retirar'){
		print("<b><center>Desculpe, mais você não pode retirar todos os membros do grupo!!!<a href='teams-create.php?editmembers=$editmembers'><br>Voltar</a></center></b>");
					end_framec();
	                stdfoot();
	                die();
}
}
        if ($status == 'retirar'){

			 
		       $sql = mysql_query("SELECT * FROM `teams` WHERE `id` = " . $editmembers . "");
             $row = mysql_fetch_array($sql);
			 $res1234 = mysql_query("SELECT usergroups.uid, usergroups.gid, users.status, users.id, users.username, users.class, teams.id,  usergroups.status AS grupostatus, users.id AS userid, teams.id AS teamid FROM usergroups LEFT JOIN users ON usergroups.uid = users.id LEFT JOIN teams ON usergroups.gid = teams.id WHERE usergroups.gid = $editmembers AND usergroups.uid = $key AND users.id IN ($ids) LIMIT 1");    
         	 $user = mysql_fetch_array($res1234);
			 $userid = $user["userid"] ;
			 $useidteam = $user["teamid"] ;
		     $dt = sqlesc(get_date_time()); 
		     mysql_query("INSERT INTO grupodel (gruid, gruuserid, grudata) VALUES($useidteam, $userid, $dt)");


							    if ($user["class"] == 55)
                              {

						 	  if (mysql_num_rows($res1234) > 0){
							$delretira = "DELETE FROM `usergroups` WHERE `gid` = $useidteam AND `uid` = $userid ";
							$delretirav = mysql_query($delretira);
							
					
							mysql_query("UPDATE users SET class='1', team='0' WHERE id=$userid ");
							
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
		       $sql = mysql_query("SELECT * FROM `teams` WHERE `id` = " . $editmembers . "");
             $row = mysql_fetch_array($sql);
			 $res1234 = mysql_query("SELECT usergroups.uid, usergroups.gid, users.status, users.id, users.username, users.class, teams.id,  usergroups.status AS grupostatus, users.id AS userid, teams.id AS teamid FROM usergroups LEFT JOIN users ON usergroups.uid = users.id LEFT JOIN teams ON usergroups.gid = teams.id WHERE usergroups.gid = $editmembers AND usergroups.uid = $key AND users.id IN ($ids) LIMIT 1");    


						$user = mysql_fetch_array($res1234);
						$userid = $user["userid"] ;
						$useidteam = $user["teamid"] ;
						
						
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
		       $sql = mysql_query("SELECT * FROM `teams` WHERE `id` = " . $editmembers . "");
             $row = mysql_fetch_array($sql);
			 $res1234 = mysql_query("SELECT usergroups.uid, usergroups.gid, users.status, users.id, users.username, users.class, teams.id,  usergroups.status AS grupostatus, users.id AS userid, teams.id AS teamid FROM usergroups LEFT JOIN users ON usergroups.uid = users.id LEFT JOIN teams ON usergroups.gid = teams.id WHERE usergroups.gid = $editmembers AND usergroups.uid = $key AND users.id IN ($ids) LIMIT 1");    


						$user = mysql_fetch_array($res1234);
						$userid = $user["userid"] ;
						$useidteam = $user["teamid"] ;
						
						
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

		
		

  $sql = mysql_query("SELECT * FROM `teams` WHERE `id` = " . $editmembers . "");
             $row = mysql_fetch_array($sql);
			 $res1234 = mysql_query("SELECT usergroups.uid, usergroups.gid, users.status, users.id, users.username, users.class, teams.id,  usergroups.status AS grupostatus, users.id AS userid, teams.id AS teamid FROM usergroups LEFT JOIN users ON usergroups.uid = users.id LEFT JOIN teams ON usergroups.gid = teams.id WHERE usergroups.gid = $editmembers AND usergroups.uid = $key AND users.id IN ($ids) LIMIT 1");    


						$user = mysql_fetch_array($res1234);
						$userid = $user["userid"] ;
						$useidteam = $user["teamid"] ;
						
						
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
					 print("<b><center>Update sucesso!!!<a href='teams-create.php?editmembers=$editmembers'><br>Voltar</a></center></b>");
					end_framec();
	                stdfoot();
	                die();
					   }
					   }

}
}
	$query = "SELECT id,username,uploaded,downloaded FROM users WHERE team=$editmembers";
	$sql = mysql_query($query);
	while ($row1 = mysql_fetch_array($sql)) {
		$username = htmlspecialchars($row1['username']);
		$uploaded = mksize($row1['uploaded']);
		$downloaded = mksize($row1['downloaded']);
		

	}
	 $resultu = mysql_query("SELECT usergroups.uid, usergroups.gid, usergroups.joined,  users.status, users.enabled, users.id, users.username, users.last_access,  users.uploaded,  users.downloaded, usergroups.status AS grupostatus FROM usergroups LEFT JOIN users ON usergroups.uid = users.id WHERE usergroups.gid = $editmembers  AND users.enabled = 'yes' AND users.status = 'confirmed'");    

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


 
	        echo("<form method='post' action='teams-create.php?editmembers=$editmembers&ditarmembro=true&do=del'>");

                echo("<input type='hidden' name='id[]' value='" . $row["uid"] . "'>");  
                
                echo("<tr>");
				echo("<td class='tab1_col3' width='1%' align='center'><input type='checkbox' name='del[]' value='$row[uid]'></td>");
                echo("<td class='tab1_col3' width='10%' align='center'><a href='account-details.php?id=" . $row['uid'] . "'>" . htmlspecialchars($row["username"]) . "</a></td>");  
                echo("<td class='tab1_col3' width='10%' align='center'>" . utc_to_tz($row['joined']) . "</td>");
				echo("<td class='tab1_col3' width='10%' align='center'><center> ".date(utc_to_tz($row["last_access"]))."<br>$loginstatus</center></td>");
				
				
				
				
$query3 = mysql_query("SHOW COLUMNS FROM usergroups WHERE Field = 'status'");
$row3 = mysql_fetch_array($query3);
$enum = str_replace("enum(", "", $row3['Type']);
$enum = str_replace("'", "", $enum);
$enum = substr($enum, 0, strlen($enum) - 1);
$enum = explode(",", $enum);
echo "<td class=tab1_col3 width=10% align=center><select  name='status" . $row['uid'] . "'>";
foreach ($enum as $chave => $campo) {
if($campo == $row['grupostatus'] ){
  echo '<option selected="selected" value="'.$campo.'">'.$campo.'</option>';
 }else{
  echo '<option value="'.$campo.'">'.$campo.'</option>';
 }
}

echo '</select></td>';
echo("<td class='tab1_col3' width='10%' align='center'>$numtorrents </td>");
echo("<td class='tab1_col3' width='10%' align='center'>$downloaded </td>");
echo("<td class='tab1_col3' width='10%' align='center'>$uploaded<input type='hidden' name='ditarmembro' value='true' /></td>");
	
 echo("</tr>");  
							
				
 
         }
            
            echo("<tr>");
			echo("<tr><td width=100% align=center colspan=8 class=tab1_col3 ><input type='submit' value='Salvar alterações'/><input type='reset' value='Redefinir'/><input type='button' value='Voltar'  onclick='window.location='destino.html''></td>\n");

            echo("</tr>");
            echo("</table>");
            echo("</form>");
	    }
		   else {
           echo('<center>Nao temos membros no grupo</center>'); 
        }
	echo "</table></center>";
	end_framec();
	stdfoot();
	die();
}


//Add Team
if($add == 'true') {
    
    if (!$team_name || !$teamownername|| !$team_description || !$team_image) {
         print '<center>Um ou mais campos foram deixados em branco.</center>';
         end_framec();
         stdfoot();
         die;
    }
    
    $team_name = sqlesc($team_name);
    $team_description = sqlesc($team_description);
    $team_image = sqlesc($team_image);
    $teamownername = sqlesc($teamownername);
    
	$aa = mysql_query("SELECT id, class FROM users WHERE username = $teamownername");
	$ar = mysql_fetch_assoc($aa);
	$team_owner = $ar["id"];
	$query = "INSERT INTO teams SET	name = $team_name, owner = $team_owner, info = $team_description, image1 = $team_image, added = $added";
	$sql = mysql_query($query);

	$tid = mysql_insert_id();
  $joined = sqlesc(get_date_time()); 
  

	
	mysql_query("UPDATE users SET team = '$tid' WHERE id= '$team_owner'");
	    if ($ar["class"] <= 45){
		
	  mysql_query("UPDATE users SET class='65' WHERE id='$team_owner'");
	  }
	  
mysql_query("INSERT INTO usergroups (uid, gid, joined) VALUES (".$team_owner.", ".$tid.", " . $joined . ")") or die (mysql_error());
 mysql_query("UPDATE usergroups SET status='moderadores' WHERE uid ='$team_owner'") or sqlerr();
	if($sql) {
		$success = TRUE;
	}else{
		$success = FALSE;
	}
}




print("<form name='smolf3d' method='get' action='teams-create.php'>\n");
print("<CENTER><table class='tab1' cellpadding='0' cellspacing='1' align='center' >");
print("<tr><td width=100% align=center colspan=2 class=tab1_cab1><B>Add novo grupo</B></td></tr>");
print("<tr><td align=center colspan=2 class=tab1_col3><b>Regras</b><br><br>");
print("1 - .................<br>");
print("<br>");

print("</td></tr><br>\n");

print("<tr><td width=40%  align=right  class=tab1_col3><b>Nome do grupo: *</b></td><td width=20%  align=left  class=tab1_col3><input type=text size=40 maxlength=20 name='team_name'></td></tr>");
print("<tr><td width=40%  align=right  class=tab1_col3><b>Fundador do grupo: *</b></td><td width=60%  align=left  class=tab1_col3><input type=text size=40  name='team_owner'></td></tr>");
print("<tr><td width=40%  align=right  class=tab1_col3><b>Logo do grupo: *</b></td><td width=60%  align=left  class=tab1_col3><input type=text size=40  name='team_image' ><input type='hidden' name='add' value='true' /></td></tr>");
print("<tr><td width=40%  align=right  class=tab1_col3><b>Capa do grupo: *</b></td><td width=60%  align=left  class=tab1_col3><textarea cols='50' rows='10' name='team_description'></textarea></td></tr>");


print("<tr><td width=100% align=center colspan=2 class=tab1_col3 ><input type=submit value='Criar grupo' style='height: 22px'>\n");

print("</table></CENTER>\n");





if($success == TRUE) {
	echo "<script>location.href='teams-create.php?grupocriado=v1';</script>";
}
echo("<br />");
echo("</form>");

echo("<table class='tab1' cellpadding='0' cellspacing='1' align='center' width='100%' border='0' >");



//ELSE Display ".T_("TEAMS")."
print("<b>Atual Grupos:</b>");
print("<br />");
print("<br />");


echo("			<tr>
				<td class='tab1_cab1' width='1%' align=left>Id</td>
				<td class='tab1_cab1' width='20%' align=center>Grupo</td>
				<td class='tab1_cab1' width='20%' align=center>Fundador</td>
					<td class='tab1_cab1' width='20%' align=center>Opções</td>
			</tr>");
$query = "SELECT * FROM teams";
$sql = mysql_query($query);
while ($row = mysql_fetch_array($sql)) {
	$id = (int)$row['id'];
	$name = htmlspecialchars($row['name']);
	$image1 = htmlspecialchars($row['image1']);
	$owner = (int)$row['owner'];
	$info = format_comment($row['info']);
	$OWNERNAME1 = mysql_query("SELECT username, class FROM users WHERE id=$owner");
	$OWNERNAME2 = mysql_fetch_array($OWNERNAME1);
	$OWNERNAME = $OWNERNAME2['username'];

	echo("<tr><td class='tab1_col3'><b>$id</b> </td> <td class='tab1_col3' align='center'>$name</td> <td class='tab1_col3' align='center'><a href='account-details.php?id=$owner' align='center'>$OWNERNAME</a></td><td class='tab1_col3' align='center'><a href='teams-create.php?editid=$id&amp;owner=$OWNERNAME'>[Editar]</a>&nbsp;<a href='teams-create.php?editmembers=$id'>[Membros]</a>&nbsp;<a href='teams-create.php?del=$id&amp;team=$name'>[Apagar]</a></td></tr>");
}
echo "</table></center>";

end_framec();
stdfoot();

?> 
