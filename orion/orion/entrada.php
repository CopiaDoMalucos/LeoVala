<?php
############################################################
#######                                             ########
#######                                             ########
#######           www.brshares.com 2.0              ########
#######                                             ########
#######                                             ########
############################################################
 require_once("backend/functions.php"); 
 dbconn(); 
loggedinonly();

if ($_GET['do'] == "del") {
		if ($_POST["delall"])
		SQL_Query_exec("DELETE FROM `messages` WHERE `receiver` = ".$CURUSER['id']." AND location IN ('in','both')  ");
		else {
			if (!@count($_POST["del"])) 
			 show_error_msg("ERRO", "<br></br>As mensagens não foram selecionadas!<br></br><br></br><a href='entrada.php'>Continuar</a>", 1);
			$ids = array_map("intval", $_POST["del"]);
			$ids = implode(", ", $ids);
			SQL_Query_exec("DELETE FROM `messages` WHERE `receiver` = ".$CURUSER['id']." AND location IN ('in','both')  AND `id` IN ($ids) ");
		}
		stdhead();
			 show_error_msg("SUCESSO", "<br></br>Mensagens deletadas com sucesso!<br></br><br></br><a href='entrada.php'>Continuar</a>", 1);
		stdfoot();
		die;
	}



	 	$res2 = SQL_Query_exec("SELECT COUNT(*) FROM messages WHERE `receiver` = ".$CURUSER['id']." AND location IN ('in','both')  ");
	$row = mysql_fetch_array($res2);
	$count = $row[0];


	

	$perpage = 5;

	list($pagertop, $pagerbottom, $limit) = pager($perpage, $count, "entrada.php?action=mp&".$param);
	 
	 $res = SQL_Query_exec("SELECT * FROM messages  WHERE `receiver` = ".$CURUSER['id']." AND location IN ('in','both') ORDER BY messages.id DESC $limit ");

 stdhead("Mensagens privadas - caixa de entrada");
echo '<div align="right">[ <a href="saida.php">Mensagens Enviadas</a> | <a href="enviarmp.php">Enviar MP</a> ]</div><BR>';
	  
 begin_framec("Mensagens privadas - caixa de entrada");
		echo "<br>";
		echo $pagertop;
?>

<form action="entrada.php?action=mp&do=del"  method="post">
		<?php
if ($count){

?>	
<div align="right">
<input type="checkbox" onclick="checkUncheckAll(this);" name="checkall">Marcar todos
<input type="submit" value="Deletar selecionados"><input type='submit' value='Deletar todos' name='delall'>
</div>
		<?php
}else{
print"<br></br><center><b>Você não possui mensagens privadas<b></center><br></br>";
}
?>	
	<?php

	while ($arr = mysql_fetch_assoc($res)) {
	 $enviado = SQL_Query_exec("SELECT id, username FROM users WHERE `id` = ".$arr['sender']."  ");
	 $rowenv = mysql_fetch_array($enviado);	
if ($arr["unread"] == "yes"){
$unread = "<B>(Nova!)</B>";
           SQL_Query_exec("UPDATE `messages` SET `unread` = 'no' WHERE `receiver` = " . $CURUSER["id"] . " AND `id` = " . $arr["id"]);
}else{
$unread = "";

}
if ($arr["sender"] == 0){
$sentto = "SYSTEM";
$repli = "";

}else{
$repli = "<a href=enviarmp.php?receiver=".$rowenv["username"]."&amp;replyto=".$arr["id"]." >Responder</a>";
  $sentto = "<a href=account-details.php?id=".$rowenv["id"].">".$rowenv["username"]."</a>";
}
	?>

<center>
	<table align="center" cellspacing="0" cellpadding="0" class="tab1">
			<tr>
				
				<td class=ttable_head align=center width="70%">&nbsp;</td>
				<td class=ttable_head align=center width="20%">Responder</td>
				<td class=ttable_head align=center>Apagar</td>
			</tr>



		<?php
print"<tr><td width='60%' class='ttable_col1'>Enviado por ".$sentto." em ".date("d/m/y", utc_to_tz_time($arr['added']))." às ". date("H:i:s", utc_to_tz_time($arr['added'])) . " $unread</td><td width='20%' align='center' class='ttable_col1'>".$repli."</td><td align='center' class='ttable_col1'><input type='checkbox' name='del[]' value='$arr[id]'></td></tr>
<tr><td class='ttable_col2' colspan='3'>" . format_comment($arr["msg"]) . "<br><br></td></tr>
</table><br>";

}

?>
		<?php
if ($count){
?>	
	<div align="right">	
<input type="checkbox" name="checkall" onclick="checkUncheckAll(this);">Marcar todos
<input type="submit"  value="Deletar selecionados"><input type='submit' value='Deletar todos' name='delall'>

</div>
		<?php
}
?>	
</form>

<script language="JavaScript">
<!-- Begin
function checkUncheckAll(theElement) {
	var theForm = theElement.form, z = 0;
	for(z=0; z<theForm.length;z++){
		if(theForm[z].type == 'checkbox' && theForm[z].name != 'checkall'){
			theForm[z].checked = theElement.checked;
		}
	}
}
//  End -->
</script>
		<?php
			print($pagerbottom);
			print'<br></br><p align="center"><a href="enviarmp.php">Enviar uma Mensagem Privada</a></p>';
end_framec();
 stdfoot();
?>