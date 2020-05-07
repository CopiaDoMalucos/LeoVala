<?php
############################################################
#######                                             ########
#######                                             ########
#######           www.brshares.com 2.0              ########
#######                                             ########
#######                                             ########
############################################################

 require_once("backend/functions.php"); 
 require_once ("backend/bbcode.php");
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
 
 if ($_POST["do"] == "pm")
 {   
     if (!@count($_POST['to']) || !$_POST['body']) show_error_msg('Error', 'Falta de dados do formulário.', 1);
     $sender_id = ($_POST['sender'] == 'system' ? 0 : $CURUSER['id']);
	$dt = sqlesc(get_date_time());
		$body = $_POST['body'];
        $subject = $_POST["subject"];
		
                     $ids = array_map("intval", $_POST["to"]);
					 
                $ids = implode(", ", $ids);
				

 $query1 = mysql_query("SELECT * FROM usergroups LEFT JOIN users ON usergroups.uid = users.id WHERE usergroups.gid = $id AND users.enabled = 'yes' AND users.status = 'confirmed' AND  usergroups.chave IN ($ids) ");    

	
		
     	while($dat1=mysql_fetch_assoc($query1)){
       SQL_Query_exec("INSERT INTO messages (sender, receiver, added, msg, subject) VALUES($sender_id, $dat1[uid], '".get_date_time()."', ".sqlesc($_POST['body']).", ".sqlesc($_POST['subject']).")");
     	}  
	
   		
          

	 
	
     autolink("painel_grupos.php?id=" . $id . "", "Mp enviada com sucesso....");
 }
 
 stdhead("Team Panel");


 

 
 begin_framec("Mp grupos");
 $dossier = $CURUSER['bbcode'];
 ?>
  
  
 <form method="post" action="grupo_mp.php?id=<?php echo $id; ?>">
 <div  align="center"  >
 <input type="hidden" name="do" value="pm" />
<table border='1' cellpadding='5' cellspacing='3' align='center' width='100%' class='ttable_headinner'>
 <tr>
     <th class="ttable_head" colspan="2">Mp para grupo</th>
 </tr>
 <tr>
     <td class="tab1_col2"><b>Para:</b></td>
     <td class="tab1_col2">
     <input type="checkbox" name="to[]" value="1" /> Membros
     <input type="checkbox" name="to[]" value="2" /> Sub-moderadores
	 <input type="checkbox" name="to[]" value="3" /> Moderadores
     </td>
 </tr>
 <tr>
     <td class="tab1_col2"><b>Assunto:</b></td>
     <td class="tab1_col2"><input type="text" name="subject" size="70" /></td>
 </tr>
 <tr>
     <td class="tab1_col2"><b>Informações:</b></td>
	 
     <td class="tab1_col2"><?php print textbbcode("masspm", "body","$dossier"); ?></textarea></td>
 </tr>
 <tr>
	<td colspan="2" align="right"  class="tab1_col2" ><b>Remetente: </b>
	<?php echo $CURUSER['username']?> <input name="sender" type="radio" value="self" checked="checked" />
	System <input name="sender" type="radio" value="system" /></td>
	</tr>
 <tr>
     <td colspan="2" align="right"  class="tab1_col2">
     <input type="reset" value="Redefinir" />
     <input type="submit" value="Enviar" />
     </td>
 </tr>
 </table>
 	</div>

 <?php
echo" </form><br>";
 print("<a href=painel_grupos.php?id=$id><font color=#FF0000><CENTER><b>[ Voltar painel de grupo]</b></CENTER></font></a>");
 end_framec();
 	}else{
      show_error_msg("Error", "Você não tem permissão para isso.", 1);
}
 
 stdfoot();

?>