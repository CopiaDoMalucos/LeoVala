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
 
   $resultsub1 = SQL_Query_exec("SELECT * FROM usergroups WHERE uid = ".$CURUSER['id']." AND gid = ".$CURUSER['team']."") ;;
$row21 = mysql_fetch_array($resultsub1);	

 

if ($row21["gid"] !=  $id ){
     show_error_msg("Error", "Você não tem acesso a este grupo", 1);
}                
 if (mysql_num_rows($res) == 0)
     show_error_msg("Error", "This team does not exist.", 1);
     
 if ($row21["status"] == 'moderadores' || $row21["status"] == 'submoderadores' )
{
 
 if ($_POST["do"] == "edit")
 {
     $updateset = array();
     $updateset[] = "`image`  = " . sqlesc($_POST['image']);

     

     SQL_Query_exec("UPDATE `teams` SET " . implode(',', $updateset) . " WHERE `id` = '$id'");
     autolink("painel_grupos.php?id=$id", "Sua icone foi atualizado.");
 }
 

 
 stdhead("Adicionar icone do grupo");
 begin_framec("Adicionar icone do grupo");
 ?>
  
 <form method="post" action="grupo_icone.php?id=<?php echo $id; ?>">
 <input type="hidden" name="do" value="edit" />
<table border='1' cellpadding='5' cellspacing='3' align='center' width='100%' class='ttable_headinner'>
 <tr>
     <th class="ttable_head" colspan="2">icone do grupo</th>
 </tr>
 
 <tr>
     <td class="tab1_col2"><b>icone do grupo:</b></td>
     <td class="tab1_col2"><input type="text" name="image" value="<?php echo htmlspecialchars($row['image']); ?>" size="70" /><br> <b>Este icone ficara na home do site</b><br><b>Limites 25x25 pixels</b></td>
 </tr>

 <tr>
     <td colspan="2" class="tab1_col2" align="center">
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