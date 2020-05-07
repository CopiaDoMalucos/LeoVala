<?php
############################################################
#######                                             ########
#######                                             ########
#######           Malucos-share.org 2.0             ########
#######                                             ########
#######                                             ########
############################################################
 require_once("backend/functions.php"); 
 dbconn(); 
loggedinonly();
     
	 $res = SQL_Query_exec("SELECT * FROM messages LEFT JOIN users ON messages.receiver = users.id WHERE `receiver` = ".$CURUSER['id']." AND location IN ('in','both') ORDER BY messages.id DESC ");


	 
	 
 stdhead("Mensagens privadas - caixa de entrada"); 
 begin_framec("Mensagens privadas - caixa de entrada");
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
	while ($arr = mysql_fetch_assoc($res)) {
	 $enviado = SQL_Query_exec("SELECT id, username FROM users WHERE `id` = ".$arr['sender']."  ");
	 $rowenv = mysql_fetch_array($enviado);	
if ($arr["unread"] == "yes"){
$unread = "<B>(Nova!)</B>";
}else{
$unread = "";

}
	?>
<center>
	<table align="center" cellspacing="0" cellpadding="0" class="tab1">
			<tr>
				
				<td class=ttable_head align=center width="60%">&nbsp;</td>
				<td class=ttable_head align=center>Responder</td>
				<td class=ttable_head align=center>Apagar</td>
			</tr>



		<?php
print"<tr><td width='60%' class='tab1_col3'>Enviado por <a href=account-details.php?id=".$rowenv["id"].">".$rowenv["username"]."</a> em ".date("d/m/y", utc_to_tz_time($arr['added']))." às ". date("H:i:s", utc_to_tz_time($arr['added'])) . " $unread</td><td width='20%' align='center' class='tab1_col3'><a href=enviarmp.php?receiver=".$arr["username"]."&amp;replyto=".$rowenv["id"]." >Responder</a></td><td align='center' class='tab1_col3'><input type='checkbox' id='checkAll' onclick='checkAll(this)'></td></tr>
<tr><td class='ttable_col2' colspan='3'>" . format_comment($arr["msg"]) . "<br><br></td></tr>
</table><br><br>

";
}
end_framec();
 stdfoot();
?>