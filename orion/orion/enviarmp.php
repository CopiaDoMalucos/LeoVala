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




$replyto = (int)$_GET["replyto"];
$receiver = $_GET["receiver"];

 if ($_POST["do"] == "pm")
 {   
     if (!$_POST['msg']) show_error_msg('Error', 'Falta de dados do formulário.', 1);
     $sender_id =  $CURUSER['id'];
	 $subject = $_POST["subject"];

	$dt = sqlesc(get_date_time());
	$resrepli = mysql_query("SELECT `id`, `username`, `acceptpms` FROM `users` WHERE `username` = '$subject' ");
    $rowrepli = mysql_fetch_array($resrepli);	
if(@mysql_num_rows($resrepli) > 0){

       mysql_query("INSERT INTO messages (sender, receiver, added, msg) VALUES($sender_id, $rowrepli[id], '".get_date_time()."', ".sqlesc($_POST['msg']).")");

}else{
show_error_msg('Error', 'O usuário especificado não existe.<br></br><a href="javascript: history.go(-1)">Voltar</a>', 1);
} 		
          
 $rowrepid = $rowrepli["id"] ;
 $rowrepuser = $rowrepli["username"] ;
	 show_error_msg("SUCESSO", "<br></br>Mensagem enviada com sucesso!<br></br><br></br>[ <a href='account-details.php?id=$rowrepid'>Voltar para o perfil de $rowrepuser </a> ]", 1);

 }
     $res = SQL_Query_exec("SELECT * FROM `messages` WHERE `id` = $replyto AND '$CURUSER[id]' IN (`sender`,`receiver`) LIMIT 1");
 $rowpli = mysql_fetch_array($res);	
 stdhead("ENVIAR MP");
echo '<div align="right">[ <a href="entrada.php">Mensagens Recebidas</a> | <a href="saida.php">Mensagens Enviadas</a> ]</div><BR>';
	  
 begin_framec("ENVIAR MP");
$dossier = $CURUSER['bbcode'];

if ($receiver > null ){
$readonly = 'readonly';
}else{
$readonly = '';
}

     if ($rowpli["msg"] > null){
	  $userid = $rowpli['sender'];
	    $resrepli = SQL_Query_exec("SELECT `username`, `acceptpms` FROM `users` WHERE `id` = $userid");
   $rowrepli = mysql_fetch_assoc($resrepli);
        $to = $rowrepli["username"];
   
	 $msg = "\n\n======================= $to escreveu: =======================\n".$rowpli["msg"]."";
}

	 ?>
  
  
 <form method="post" action="enviarmp.php">
 <div  align="center"  >
 <input type="hidden" name="do" value="pm" />
<table border='1' cellpadding='5' cellspacing='3' align='center' width='100%' class='ttable_headinner'>
 <tr>
     <th class="tab1_cab1" colspan="2">Nova Mensagem Privada</th>
 </tr>
 <tr>
     <td class="tab1_col2" align="right" ><b>Destinatário:</b></td>
     <td class="tab1_col2" align="center"><input type="text" name="subject" value="<?php echo $receiver ;?>"<?php echo $readonly ;?>  /></td>
 </tr>
 <tr>
     <td class="tab1_col2" align="right"><b>Mensagem:</b></td>
	 
     <td class="tab1_col2"><?php print ("".textbbcode("compose","msg",$dossier,$msg).""); ?></textarea></td>
 </tr>
 <tr>
     <td class="tab1_cab1"  align="center" colspan="2">
     <input type="submit" value="Enviar" />
     </td>
 </tr>
 </table>
 	</div>

 <?php
echo" </form><br>";


end_framec();
 stdfoot();
?>