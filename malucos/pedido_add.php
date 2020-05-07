<?php
############################################################
#######                                             ########
#######                                             ########
#######           malucos-share.org 2.0             ########
#######                                             ########
#######                                             ########
############################################################
require "backend/functions.php";
require ("backend/conexao.php");
dbconn();
loggedinonly();

stdhead("Fazer pedido de torrent");


$pdo = conectar();

if ($site_config["REQUESTSON"]) {



?>
<script type="text/javascript"  src="jquery.validate.min.js"></script>
<script type="text/javascript" type="text/javascript">
$(document).ready(function(){
 
 $('#add').validate(
 {
  rules: {

	    category: {
      required: true,
    },
		requesttitle: {
	  minlength: 10,
      required: true,
    },	
			descr: {
	  minlength: 10,
      required: true,
    },	
  },
  highlight: function(element) {
    $(element).closest('.control-group').removeClass('success').addClass('error');
  },
  success: function(element) {
    element
    .text('OK!').addClass('valid')
    .closest('.control-group').removeClass('error').addClass('success');
  }
 });
}); // end document.ready
</script>
<?php 
print("<center>[ <a href='/pedidos_torrents.php'> Pedidos </a> | <a href='/pedido_add.php'> Fazer pedido </a> | <a href='/pesquisa_pedidos.php'> Pesquisar pedidos </a> | <a href='/pedido_liberacao.php'> Aguardando verificação </a> ]</center>");

print("<form name=add id=add method=post action=confirmarpedido.php><a name=add id=add></a>\n");
print("<CENTER><table class='tab1' cellpadding='0' cellspacing='1' align='center' >");
print("<tr><td width=100% align=center colspan=2 class=tab1_cab1><B>Fazer pedido de torrent</B></td></tr>");
print("<tr><td align=center colspan=2 class=tab1_col3>Antes de fazer seu pedido, não esqueça de verificar se o torrent já foi lançado no site ou se ele já está na lista de pedidos.<br>");
print("Caso esteja fora das regras e/ou não seja claro, o pedido poderá ser deletado sem aviso prévio.");
print("<br>");
print("<br>");
print("<b>Você tem ".$CURUSER["seedbonus"]." Pontos.<b>");
print("<br>");
print("<font color=red>Cada pedido custará 100 MS Pontos.</font></td></tr>");
print("<br>");
?>

<tr><td width="40%"  align="right"  class="tab1_col3"><b>Categoria: *</b></td>
<td  width="60%"  align="left"  class="tab1_col3"><select name="category" id="category">
<option value="">Escolher</option>
<?php


$res2 = $pdo->prepare("SELECT id, name,parent_cat FROM categories  order by parent_cat"); 
$res2->execute(); 



$conttorrent = "SELECT count(*) id, name,parent_cat FROM  categories  order by parent_cat"; 
$conttorr = $pdo->prepare($conttorrent); 
$conttorr->execute(); 
$rowconttor = $conttorr->fetchColumn() ; 


$catdropdown2 = "";
for ($i = 0; $i < $rowconttor; ++$i)
   {
 $cats2 = $res2->fetch(PDO::FETCH_ASSOC);  
     $catdropdown2 .= "<option value=\"" . $cats2["id"] . "\"";
     $catdropdown2 .= ">" . htmlspecialchars($cats2["parent_cat"]) . ": " . htmlspecialchars($cats2["name"]) . "</option>\n";
   }

?>
<?php echo  $catdropdown2 ;?>
</select>

<?php print("</td></tr><br>\n");
print("<tr><td width=40%  align=right  class=tab1_col3><b>Nome do torrent desejado: *</b></td><td width=60%  align=left  class=tab1_col3><input type=text size=40  name=requesttitle id=requesttitle></td></tr>");
print("<tr><td width=40%  align=right  class=tab1_col3>Descrição do pedido: *</td><td width=60%  align=left  class=tab1_col3> <textarea name='descr' id='descr' cols='30' rows='4' onkeydown='if(this.value.length &gt;= 300){this.value = this.value.substring(0, 300)}' onkeyup='if(this.value.length &gt;= 300){this.value = this.value.substring(0, 300)}'></textarea></td></tr>\n");
print("<tr><td width=100% align=center colspan=2 class=tab1_col3 ><input type=submit value='Enviar Pedido' style='height: 22px'>\n");
print("</form>\n");
print("</table></CENTER>\n");
} else {
echo "<b><font color=red>Desculpe, os pedidos são atualmente desativado.<br><Br>";
}


stdfoot();
?>
