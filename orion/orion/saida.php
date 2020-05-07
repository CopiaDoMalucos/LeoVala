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
		SQL_Query_exec("DELETE FROM `messages` WHERE `sender` = $CURUSER[id] AND `location` IN ('out','both')  ");
		else {
			if (!@count($_POST["del"])) 
			 show_error_msg("ERRO", "<br></br>As mensagens não foram selecionadas!<br></br><br></br><a href='ssaida.php'>Continuar</a>", 1);
			$ids = array_map("intval", $_POST["del"]);
			$ids = implode(", ", $ids);
			SQL_Query_exec("DELETE FROM `messages` WHERE `sender` = $CURUSER[id] AND `location` IN ('in','both')  AND `id` IN ($ids) ");
		}
		stdhead();
			 show_error_msg("SUCESSO", "<br></br>Mensagens deletadas com sucesso!<br></br><br></br><a href='saida.php'>Continuar</a>", 1);
		stdfoot();
		die;
	}



	 	$res2 = SQL_Query_exec("SELECT COUNT(*) FROM messages WHERE `sender` = $CURUSER[id] AND `location` IN ('in','both')  ");
	$row = mysql_fetch_array($res2);
	$count = $row[0];


	

	$perpage = 5;

	list($pagertop, $pagerbottom, $limit) = pager($perpage, $count, "saida.php?action=mp&".$param);
	 
	 $res = SQL_Query_exec("SELECT * FROM messages  WHERE `sender` = $CURUSER[id] AND `location` IN ('in','both') ORDER BY messages.id DESC $limit ");

 stdhead("MENSAGENS PRIVADAS - CAIXA DE SAÍDA");
echo '<div align="right">[ <a href="entrada.php">Mensagens Recebidas</a> | <a href="enviarmp.php">Enviar MP</a> ]</div><BR>';
	  
 begin_framec("MENSAGENS PRIVADAS - CAIXA DE SAÍDA");
		echo "<br>";
		echo $pagertop;
?>

<form action="saida.php?action=mp&do=del"  method="post">
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
	 $enviado = SQL_Query_exec("SELECT id, username FROM users WHERE `id` = ".$arr['receiver']."  ");
	 $rowenv = mysql_fetch_array($enviado);	



print"<table align='center' cellspacing='1' cellpadding='0'class='tab1'><tr><td width='20' class='ttable_col1'><img border='0' src='images/button_pm.gif'></td><td width='98%'  class='ttable_col1'>Enviado para <b><a href=account-details.php?id=".$rowenv["id"].">".$rowenv["username"]."</a></b> em ".date("d/m/y", utc_to_tz_time($arr['added']))." às ". date("H:i:s", utc_to_tz_time($arr['added'])) . "</td><td align='center' class='ttable_col1' width='5'><input type='checkbox' name='del[]' value='$arr[id]'></td></tr>
<tr><td class='ttable_col2'  colspan='5'>" . format_comment($arr["msg"]) . "<br><br></td></tr>
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