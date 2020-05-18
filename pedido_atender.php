<?php
############################################################
#######                                             ########
#######                                             ########
#######           www.brshares.com 2.0              ########
#######                                             ########
#######                                             ########
############################################################
require "backend/functions.php";
require ("backend/conexao.php");
dbconn();
loggedinonly();



$pdo = conectar();




stdhead("Request Details");
$id = (int)$_GET["id"];
if (!is_valid_id($id))
	show_error_msg("Erro", "Requisição inválida", 1);
	

	$select_row=$pdo->prepare("SELECT id, name, size FROM torrents WHERE id= :id");
    $select_row->bindParam(':id', $id );
    $select_row->execute();
	$row_select = $select_row->fetch(PDO::FETCH_ASSOC);  		  

if (!$row_select)
	show_error_msg("ERROR", 'Torrent não encontrado.', 1);
	
if ($_POST["confenvio"] == "sim") {

 
	$pcategory = $_POST["category"];
	$precebeValor = $_POST["recebeValor"];

	
	if (!$pcategory || !$precebeValor ){		
	show_error_msg("Erro", 'Requisição inválida.', 1);
	}else
	{
	 $select_posts=$pdo->prepare("UPDATE requests SET  liberado = 'yes', torrid = :torrid, atenid = :atenid  where id= :id");
     $select_posts->bindParam(':id', $precebeValor);
	  $select_posts->bindParam(':torrid', $id);
	  	  $select_posts->bindParam(':atenid', $CURUSER["id"]);
     $select_posts->execute();
		show_error_msg("Sucesso", 'Pedido atendido com sucesso!<br>

A moderação verificará se o torrent corresponde com o que foi pedido.

<br>Obrigado!.

<br><a href=torrents-details.php?id='.$id.'><b>Continuar</b></a>', 1);
	}
	
}	

print("<table class='tab1' cellpadding='0' cellspacing='1' align='center' >\n");
print("<tr><td width=100% align=center colspan=2 class=tab1_cab1><B>Atender pedido de torrent</B></td></tr>");
print("<tr><td class=ttable_headp width=100% align=center colspan=2 >Detalhes do torrent</td></tr>\n");
print "<tr><td width=30%  align=right  class=tab1_col3><b>Título:</b></td><td align=left class=tab1_col3><a href='torrents-details.php?id=".$row_select['id']."'>".htmlspecialchars($row_select['name'])."</a>\n</tr>\n";
print "<tr><td width=30%  align=right  class=tab1_col3><b>Tamanho:</b></td><td align=left class=tab1_col3>". mksize($row_select["size"])."\n</tr>\n";
print("<tr><td class=ttable_headp width=100% align=center colspan=2 >Detalhes do torrent</td></tr>\n");
print "<tr><td width=30%  align=right  class=tab1_col3><b>Categoria:</b></td>";

?>
<script type="text/javascript">
var $$$$$$$$$$ = jQuery.noConflict();
   function getValor(valor){
     $$$$$$$$$$("#recebeValor").html("<option value='0'>Carregando...</option>");
     setTimeout(function(){
          $$$$$$$$$$("#recebeValor").load("ajax.php",{id:valor})
   }, 2000)
};
</script>
<script type="text/javascript">
var $$$$$$$$$ = jQuery.noConflict();
   function getValo(valor){
     $$$$$$$$$("#descped").html("<img src=images/loading.gif> Carregando...");
     setTimeout(function(){
          $$$$$$$$$(("#descped").load("ajax1.php",{id:valor})
   }, 2000)
};
</script>
<td align='left' class='tab1_col3'>
<form action="pedido_atender.php?id=<?php echo $id ;?>" name="enviar" id="enviar" method="post">
<input type="hidden" name="confenvio" value="sim" />
<select name="category" id="category" onchange="getValor(this.value, 0)">
<option value="0"><?php print("Escolher\n"); ?></option>

<?php


$cats = genrelist();
$catdropdown = "";
foreach ($cats as $cat) {
   $catdropdown .= "<option value=\"" . $cat["id"] . "\"";
   $catdropdown .= ">" . htmlspecialchars($cat["parent_cat"]) . ": " . htmlspecialchars($cat["name"]) . "</option>\n";
}


?>

<?php echo  $catdropdown ;?>





<?php

print "</td></tr>";



print "<tr><td width=30%  align=right  class=tab1_col3><b>Pedido:</b></td>";
?>
<td align='left' class='tab1_col3'>
</select>
  <select name="recebeValor" id="recebeValor" onchange="getValo(this.value, 0)">
        <option value="0">Selecione algo acima primeiro</option>
		
    </select>
<?php
print "</td></tr>";
print "<tr><td width=30%  align=right  class=tab1_col3><b>Descrição:</b></td><td align=left class=tab1_col3><div id='descped'>---</div></tr>\n";

print("<tr><td width=100% align=center colspan=2 class=tab1_col3><input type=submit  style='width:30%; height: 25px' value='Atender Pedido'></td></tr>");
print("</table></form>");





stdfoot();


?>