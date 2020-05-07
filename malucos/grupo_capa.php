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
 
 $res = SQL_Query_exec("SELECT teams.id, teams.image, teams.image1, teams.image2, teams.info, teams.owner, users.username, (SELECT GROUP_CONCAT(id, ' ', username) FROM users WHERE FIND_IN_SET(users.team, teams.id) AND users.enabled = 'yes' AND users.status = 'confirmed' AND users.id != '$CURUSER[id]') AS members FROM teams LEFT JOIN users ON teams.owner = users.id WHERE users.enabled = 'yes' AND users.status = 'confirmed' AND teams.id = '$id'");
 $row = mysql_fetch_assoc($res);
  
 stdhead("Adicionar capa de lançamento");
 begin_framec("Adicionar capa de lançamento");                                      
 if (mysql_num_rows($res) == 0)
     show_error_msg("Error", "This team does not exist.", 1);
     
   $resultsub1 = SQL_Query_exec("SELECT * FROM usergroups WHERE uid = '$CURUSER[id]' AND gid = ".$CURUSER['team']."") ;
$row21 = mysql_fetch_array($resultsub1);	
if ($row21["gid"] !=  $id ){
     show_error_msg("Error", "Você não tem acesso a este grupo", 1);
}

 if ($row21["status"] == 'moderadores' || $row21["status"] == 'submoderadores' )
{
 
 if ($_POST["do"] == "edit")
 {
     $updateset = array();
     $updateset[] = "`info`  = " . sqlesc($_POST['info']);

     

     SQL_Query_exec("UPDATE `teams` SET " . implode(',', $updateset) . " WHERE `id` = '$id'");
					 print("<b><center>Update sucesso!!!<a href='grupo_capa.php?id=$id'><br>Voltar</a></center></b>");
					end_framec();
	                stdfoot();
	                die();
 }
 


 ?>
  
 <form method="post" action="grupo_capa.php?id=<?php echo $id; ?>">
 <input type="hidden" name="do" value="edit" />
<table border='1' cellpadding='5' cellspacing='3' align='center' width='100%' class='ttable_headinner'>
 <tr>
     <th class="ttable_head" colspan="2">Capa do grupo</th>
 </tr>
 <tr>
   
     <td class="tab1_col2" align="center"><textarea cols="100" rows="20" name="info"><?php echo htmlspecialchars($row['info']); ?></textarea></td>
 </tr>

 

 <tr>
     <td colspan="2" class="tab1_col2"  align="center">
     <input type="reset" value="Redefinir" />
     <input type="submit" value="Salva" />
     </td>
 </tr>
 </table>
 </form>
 <br />

 <?php
 								echo "<BR><BR>";
 print("<a href=painel_grupos.php?id=$id><font color=#FF0000><CENTER><b>[Voltar painel de grupo]</b></CENTER></font></a>");
 end_framec();
  }else{
      show_error_msg("Error", "Você não tem permissão para isso.", 1);
}


 
 
 stdfoot();
 
?>