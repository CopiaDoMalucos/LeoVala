<?php
############################################################
#######                                             ########
#######                                             ########
#######           brshares.com 2.0                  ########
#######                                             ########
#######                                             ########
############################################################
require_once("backend/functions.php");
dbconn();

$delete = (int)$_GET["delete"];
$sql = SQL_Query_exec("SELECT * FROM radio ORDER BY id LIMIT 20");
$resultado = mysql_num_rows($sql);
stdhead(T_("RADIO_VER_PEDIDOS"));
begin_framec(T_("RADIO_VER_PEDIDOS_MS"));
?>

<div align="right">[ <a href="http://www.brshares.com/pedirmusica.php">Pedir música</a> | <a href="http://www.brshares.com/djevents.php">Programação da rádio</a> | <a href="http://www.brshares.com/index.php">Voltar</a> ]</div>
<?php
	if(!$resultado){
end_framec();
show_error_msg(T_("ERROR"), T_("RADIO_VER_PEDIDOS_ERR"),"", 0);
stdfoot();
die;
	}else{	
	print("<table border='0' cellpading='0' cellspacing='0' width='100%' align='center'>");



				while ($row = mysql_fetch_array($sql)){
				
				
			if($CURUSER['dj'] == 'yes'){
	$gerenciar = "<td width=5%  class=ttable_col2><a href='http://www.brshares.com/verpedidos.php?id=".$row["id"]."&delete=1'>[".T_("IMGHOST_IMAGE_APAGAR")."]</a></td>";	
 	$gerenciar1 = "	<td width=5% class=tab1_cab1>Apagar</td>";
			
			}
		
				
				
				?>

<BR> 
<table width="100%" cellspacing="0" cellpadding="0" align="center" id="tabela1">
<tbody>
<tr><td width="10%" class=tab1_cab1>Ouvinte</td><td width="15%"  class=tab1_cab1>Artista</td><td   class=tab1_cab1>Música</td><?php echo $gerenciar1 ;?></tr>

<tr>


<td  align="center" class="ttable_col2"><b><?php echo $row["ouvinte"] ;?></b><br></td>
<td  align="center" class="ttable_col2"><b><?php echo $row["artista"] ;?></b><br></td>

<td align="center" class="ttable_col2"><?php echo $row["musica"] ;?><br> </td>
<?php echo $gerenciar ;?>
</tr>
</tbody></table>
<BR>

<?php
				}
			
	print("</table>");
	}
	

	
	
	if ($delete=='1' && is_valid_id($_GET['id'])){
$id = $_GET['id'];
		if($CURUSER["dj"]=="no"){
		show_error_msg(T_("ERROR"), T_("RADIO_VER_PEDIDOS_PERMI"), 1);
		}else{
		SQL_Query_exec("DELETE FROM radio WHERE id = $id");
		end_framec();
		show_error_msg(T_("COMPLETED"), T_("RADIO_VER_PEDIDOS_APAGA"),"<br> <a href='http://www.brshares.com/verpedidos.php'>".T_("FRIENDS_APENAS_AUTOLINK_VOLT")."</a>", 0);
stdfoot();
die;
 exit;
		}
	}
	
	end_framec();

?>