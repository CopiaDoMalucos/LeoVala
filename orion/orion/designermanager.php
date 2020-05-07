<?php
############################################################
#######                                             ########
#######                                             ########
#######           www.brshares.com 2.0              ########
#######                                             ########
#######                                             ########
############################################################
require "backend/functions.php";
dbconn();
loggedinonly();

if($CURUSER["level"]=="Administrador" || $CURUSER["level"]=="Sysop" || $CURUSER["level"]=="Designer" || $CURUSER["level"]=="Coord.Designer" || $CURUSER["level"]=="Liberador" || $CURUSER["level"]=="Colaborador"|| $CURUSER["level"]=="Moderador" ||  $CURUSER["level"]=="S.Moderador"){
if($CURUSER["level"]=="Administrador" || $CURUSER["level"]=="Sysop" ||  $CURUSER["level"]=="S.Moderador"){
if ($_GET['do'] == "del") {
		if ($_POST["delall"])
			SQL_Query_exec("DELETE FROM `loguser`");
		else {
			if (!@count($_POST["del"])) 
				show_error_msg(T_("ERROR"), T_("LOG_USER_ERRO"), 1);		
			$ids = array_map("intval", $_POST["del"]);
			$ids = implode(", ", $ids);
			SQL_Query_exec("DELETE FROM `logkit` WHERE `id` IN ($ids)");
		}

		stdhead();
	print("<center>O log foi apagado com sucesso!!!!<br>[<a href='designermanager.php?action=log'>Voltar</a>]</center>");
		stdfoot();
		die;
	}
}
$action = $_REQUEST["action"] ;

$do = $_REQUEST["do"] ;
stdhead("Painel de Controle");

if ($action=="aceitar" ){
begin_framec("Painel Designer BR");

if($CURUSER["level"]=="Administrador" || $CURUSER["level"]=="Sysop" || $CURUSER["level"]=="Designer" || $CURUSER["level"]=="Coord.Designer" || $CURUSER["level"]=="Liberador" || $CURUSER["level"]=="Colaborador"|| $CURUSER["level"]=="Moderador" ||  $CURUSER["level"]=="S.Moderador"){
function write_logukit($type,$couleur,$text){

	$text = sqlesc($text);
	$type = sqlesc($type);
	$couleur = sqlesc($couleur);
	$added = sqlesc(get_date_time());
	SQL_Query_exec("INSERT INTO  logkit (added, type, couleur, txt) VALUES($added, $type, $couleur, $text)");
}
$id = 0 + $_GET['idaceitar'];
$verpedido = mysql_query("SELECT * FROM kitpedido WHERE  status	='feito' AND id	= $id");    
$exepedi=mysql_fetch_assoc($verpedido) ;
if (!$exepedi["id"]){
show_error_msg("Erro", "Kit selecionad não encontrado!!!!<br>[<a href='designermanager.php'>Voltar</a>]", 1);
}
mysql_query("UPDATE kitpedido SET status='aceito', desid= ".$CURUSER["id"].", desname= '".$CURUSER["username"]."', aceito= '" . get_date_time() . "' WHERE  status ='feito' AND id = $id");
write_logukit("kit_aceito","#FF0000","O kit pedido por [url=http://www.brshares.com/account-details.php?id=".$exepedi["userid"]."]".$exepedi["kusername"]."[/url] foi aceito por [url=http://www.brshares.com/account-details.php?id=".$CURUSER["id"]."]".$CURUSER["username"]."[/url] pedido em ".date("d/m/y", utc_to_tz_time($exepedi['added']))." às ". date("H:i:s", utc_to_tz_time($exepedi['added']))."\n");

print("<center>O pedido foi aceito com sucesso!!!!<br>[<a href='designermanager.php'>Voltar</a>]</center>");
}else{
print("<center>Acesso negado!!!!<br>[<a href='designermanager.php'>Voltar</a>]</center>");
}

end_framec();
}






if ($action=="kitpt" ){
begin_framec("Painel Designer BR");

if($CURUSER["level"]=="Administrador" || $CURUSER["level"]=="Sysop" || $CURUSER["level"]=="Coord.Designer" || $CURUSER["level"]=="Designer" || $CURUSER["level"]=="Liberador" || $CURUSER["level"]=="Colaborador"|| $CURUSER["level"]=="Moderador" ||  $CURUSER["level"]=="S.Moderador"){
function write_logukit($type,$couleur,$text){

	$text = sqlesc($text);
	$type = sqlesc($type);
	$couleur = sqlesc($couleur);
	$added = sqlesc(get_date_time());
	SQL_Query_exec("INSERT INTO  logkit (added, type, couleur, txt) VALUES($added, $type, $couleur, $text)");
}
$id = 0 + $_GET['do'];
$verpedido = mysql_query("SELECT * FROM kitpedido WHERE  status	='aceito' AND id	= $id");    
$exepedi=mysql_fetch_assoc($verpedido) ;
if (!$exepedi["id"]){
show_error_msg("Erro", "Kit selecionad não encontrado!!!!<br>[<a href='designermanager.php'>Voltar</a>]", 1);
}
$link_avata = $_POST["link_avata"];
$link_sing = $_POST["link_sing"];

$link_avata = sqlesc($link_avata);
$link_sing = sqlesc($link_sing);

	mysql_query("UPDATE users SET seedbonus=seedbonus+'1000' WHERE id=".$CURUSER["id"]."");
SQL_Query_exec("UPDATE kitpedido SET status='aprovado', desid= ".$CURUSER["id"].", desname= '".$CURUSER["username"]."', link_avata= $link_avata , link_sing= $link_sing, termina= '" . get_date_time() . "' WHERE  status ='aceito' AND id = $id");
write_logukit("kit_concluido","#FF0000","O Kit pedido por [url=http://www.brshares.com/account-details.php?id=".$exepedi["userid"]."]".$exepedi["kusername"]."[/url] foi entreque por [url=http://www.brshares.com/account-details.php?id=".$CURUSER["id"]."]".$CURUSER["username"]."[/url] pedido em ".date("d/m/y", utc_to_tz_time($exepedi['added']))." às ". date("H:i:s", utc_to_tz_time($exepedi['added']))."\n");

$msg = "Olá ".$exepedi['kusername'].",\n\nEm atendimento a sua solicitação de kit feita no dia ".date("d/m/y", utc_to_tz_time($exepedi['added']))." às ". date("H:i:s", utc_to_tz_time($exepedi['added']))."\n\n atendida pelo nosso designer [url=http://www.brshares.com/account-details.php?id=".$CURUSER["id"]."]".$CURUSER["username"]."[/url] em ".date("d/m/y", utc_to_tz_time($exepedi['aceito']))." às ". date("H:i:s", utc_to_tz_time($exepedi['aceito'])).",\n
informamos que seu pedido está pronto, caso prefira também poderá usar os links abaixo .\n\n Avatar $link_avata \n\n Sign $link_sing.\n\n Os pontos referente ao pedido de kit já forão deduzidos da sua conta.
\n Não esqueça de agradecer afinal esse é o nosso pagamento :xD \n\n Equipe Designer BRShares  \n
Qualidade e Amizade sempre.";
$sql = "INSERT INTO messages (sender, receiver, msg, added, subject) VALUES (0, ".$exepedi["userid"].", \"". stripslashes ($msg)."\", '".get_date_time()."', 'Kit concluido')";

mysql_query($sql);

print("<center>Seu pedido foi finalizado com sucesso!!!!<br>[<a href='designermanager.php'>Voltar</a>]</center>");
}else{
print("<center>Acesso negado!!!!<br>[<a href='designermanager.php'>Voltar</a>]</center>");
}


end_framec();
}




if ($action=="concluirkit" ){
begin_framec("Painel Designer BR");

if($CURUSER["level"]=="Administrador" || $CURUSER["level"]=="Sysop" || $CURUSER["level"]=="Coord.Designer" || $CURUSER["level"]=="Designer" || $CURUSER["level"]=="Liberador" || $CURUSER["level"]=="Colaborador"|| $CURUSER["level"]=="Moderador" ||  $CURUSER["level"]=="S.Moderador"){
function write_logukit($type,$couleur,$text){

	$text = sqlesc($text);
	$type = sqlesc($type);
	$couleur = sqlesc($couleur);
	$added = sqlesc(get_date_time());
	SQL_Query_exec("INSERT INTO  logkit (added, type, couleur, txt) VALUES($added, $type, $couleur, $text)");
}
$id = 0 + $_GET['concluir'];
$verpedido = mysql_query("SELECT * FROM kitpedido WHERE  status	='aceito' AND id	= $id");    
$exepedi=mysql_fetch_assoc($verpedido) ;
if (!$exepedi["id"]){
show_error_msg("Erro", "Kit selecionado não encontrado!!!!<br>[<a href='designermanager.php'>Voltar</a>]", 1);
}



print("<table class='tab1' cellpadding='0' cellspacing='1' align='center' >");
print("<form method=post action=designermanager.php?action=kitpt&do=".$id."><a name=add id=add></a>\n");
print("<tr><td align='center' colspan='2' class='tab1_cab1'>concluindo Kits</td></tr>");
print("<tr><td width='40%'  align='right'  class='tab1_col3' ><b>link avatar:</b></td><td  width='60%'  align='left'  class='tab1_col3'><input type=text size=40 name=link_avata><BR>Favor colocar o link direto que tenha as extensão ( *.gif, *.jpg ou *.png )\n</td></tr>");
print("<tr><td width='40%'  align='right'  class='tab1_col3' ><b>link sing:</b></td><td  width='60%'  align='left'  class='tab1_col3'><input type=text size=40 name=link_sing><BR>Favor colocar o link direto que tenha as extensão ( *.gif, *.jpg, *.swf  ou *.png )\n</td></tr>");
print("<tr><td align=center colspan=2 class=tab1_col3><input type=submit value='Enviar' style='height: 22px'><input type='button' value='Voltar' onClick='history.go(-1)' style='height: 22px'> \n");
print("</form>\n");
print("</table>");
}else{
print("<center>Acesso negado!!!!<br>[<a href='designermanager.php'>Voltar</a>]</center>");
}

end_framec();
}











if ($action=="apagar" ){
begin_framec("Painel Designer BR");
if($CURUSER["level"]=="Administrador" || $CURUSER["level"]=="Sysop" || $CURUSER["level"]=="Coord.Designer" ||  $CURUSER["level"]=="S.Moderador"){
function write_logukit($type,$couleur,$text){

	$text = sqlesc($text);
	$type = sqlesc($type);
	$couleur = sqlesc($couleur);
	$added = sqlesc(get_date_time());
	SQL_Query_exec("INSERT INTO  logkit (added, type, couleur, txt) VALUES($added, $type, $couleur, $text)");
}
$id = 0 + $_GET['idapagar'];
$verpedido = mysql_query("SELECT * FROM kitpedido WHERE  status	='feito' AND id	= $id");    
$exepedi=mysql_fetch_assoc($verpedido) ;

if (!$exepedi["id"]){
show_error_msg("Erro", "Kit selecionada não encontrado!!!!<br>[<a href='designermanager.php'>".$exepedi["id"]."Voltar</a>]", 1);
}
$bonus_kit = '200';
write_logukit("kit_apagado","#FF0000","O kit pedido por [url=http://www.brshares.com/account-details.php?id=".$exepedi["userid"]."]".$exepedi["kusername"]."[/url] foi apagado por [url=http://www.brshares.com/account-details.php?id=".$CURUSER["id"]."]".$CURUSER["username"]."[/url] pedido em ".date("d/m/y", utc_to_tz_time($exepedi['added']))." às ". date("H:i:s", utc_to_tz_time($exepedi['added']))."\n");
SQL_Query_exec("UPDATE `users` SET `seedbonus` = `seedbonus`+ '$bonus_kit' WHERE `id` = '" . $exepedi["userid"] . "'") or exit(mysql_error());
SQL_Query_exec("DELETE FROM kitpedido WHERE id = $id");

print("<center>O pedido foi apagado com sucesso!!!!<br>[<a href='designermanager.php'>Voltar</a>]</center>");
}else{
print("<center>Acesso negado!!!!<br>[<a href='designermanager.php'>Voltar</a>]</center>");
}
end_framec();
}

if ($action=="apagar_at" ){
begin_framec("Painel Designer BR");
if($CURUSER["level"]=="Administrador" || $CURUSER["level"]=="Sysop" || $CURUSER["level"]=="Coord.Designer" ||  $CURUSER["level"]=="S.Moderador"){
function write_logukit($type,$couleur,$text){

	$text = sqlesc($text);
	$type = sqlesc($type);
	$couleur = sqlesc($couleur);
	$added = sqlesc(get_date_time());
	SQL_Query_exec("INSERT INTO  logkit (added, type, couleur, txt) VALUES($added, $type, $couleur, $text)");
}
$id = 0 + $_GET['idapagar'];

$verpedido = mysql_query("SELECT * FROM kitpedido WHERE  status	='aceito' AND id = $id");    
$exepedi=mysql_fetch_assoc($verpedido) ;

if (!$exepedi["id"]){
show_error_msg("Erro", "Kit selecionada não encontrado!!!!<br>[<a href='designermanager.php'>".$exepedi["id"]."Voltar</a>]", 1);
}
$bonus_kit = '200';
write_logukit("kit_apagado","#FF0000","O kit pedido por [url=http://www.brshares.com/account-details.php?id=".$exepedi["userid"]."]".$exepedi["kusername"]."[/url] foi apagado por [url=http://www.brshares.com/account-details.php?id=".$CURUSER["id"]."]".$CURUSER["username"]."[/url] pedido em ".date("d/m/y", utc_to_tz_time($exepedi['added']))." às ". date("H:i:s", utc_to_tz_time($exepedi['added']))."\n");
SQL_Query_exec("UPDATE `users` SET `seedbonus` = `seedbonus`+ '$bonus_kit' WHERE `id` = '" . $exepedi["userid"] . "'") or exit(mysql_error());
SQL_Query_exec("DELETE FROM kitpedido WHERE id = $id");
print("<center>O pedido foi apagado com sucesso!!!!<br>[<a href='designermanager.php'>Voltar</a>]</center>");
}else{
print("<center>Acesso negado!!!!<br>[<a href='designermanager.php'>Voltar</a>]</center>");
}
end_framec();
}
function navmenu(){
?>



<table cellspacing="1" cellpadding="0" width="100%" align="center" id="tabela1">
	<tbody><tr>
	<td align="center" colspan="5" class="tab1_cab1">Painel Moderação Kits</td>
	</tr>
	<tr>
	<td width="20%" align="center" class="tab1_col3">
		<a href="designermanager.php?action=aguardar&do=ok"><img border="0" alt="" src="/images/dadosuser.png"><br>Aguardando Designer</a>
	</td>
	<td width="20%" align="center" class="tab1_col3">
	<a href="designermanager.php?action=aceito&do=1"><img border="0" alt="" src="/images/Icon_perfil.png"><br>Pedidos Atendidos</a>
	<td width="20%" align="center" class="tab1_col3">
	<a href="designermanager.php?action=rank"><img border="0" alt="" src="/images/stats.rank.d.png"><br>Rank</a>
	</td>
	<td width="20%" align="center" class="tab1_col3">
	<a href="designermanager.php?action=log&do=ok"><img border="0" alt="" src="/images/torrent/modlog.png"><br>Log Completo</a>
	</td>
	</tr>
	</tbody></table>
	
	

		
<?php
}




if ($action=="rank" ){



begin_framec("Painel Designer BR");
navmenu();
  
  $data['id'] = $id;
  
  if ( is_valid_id($_GET['cat']) )
  {
       $data['cat'] = $_GET['cat'];
  }
  
  $link = http_build_query($data);
  $sabado = 6; //sabado = 6º dia = fim da semana.
$dia_atual=date('w'); //pego o dia atual
$dias_que_faltam_para_o_sabado = $sabado - $dia_atual;

$inicio = strtotime("-$dia_atual days");
$fim = strtotime("+$dias_que_faltam_para_o_sabado days");
    $data2 = date('Y-m-d H:i:s');  
$data21 = date("d/m/Y", utc_to_tz_time($data2));

$datasem = date('Y-m-d H:i:s');  
$datas = date("m/Y", utc_to_tz_time($datasem));  
$whereag=array();
if ($_GET["duration"] == 2) {
     $whereag[] = "AND date_format(aceito,'%Y-%m-%d')>='$data2' AND date_format(aceito,'%Y-%m-%d')<='$data21'";
	 $chave = "SEMANAL";
	 $trueinicio = date("m/d", $inicio); 
	 $trueifim = date("m/d", $fim); 
	 
	 $mensalinicio = "<font color='blue'>Início: </font><font color='red'>$trueinicio</font> <br><font color='blue'> Término: </font><font color='red'>$trueifim</font>";
}

if ($_GET["duration"] == 1) {
     $whereag[] = "AND date_format(t.aceito,'%m/%Y')='$datas'";
	 $chave = "MENSAL"; 
	 $mensalfim = date("m", utc_to_tz_time($datasem));  
	 $mensalinicio = "<font color='blue'>Início: </font><font color='red'>01/$mensalfim</font> <br><font color='blue'> Término: </font><font color='red'>".date("t")."/$mensalfim</font>";
 }
  $whereg = implode("AND", $whereag);
  echo"<BR>";
  echo"$mensalinicio"; 
 
 ?>
  <center>
 <a href="designermanager.php?action=rank&id=">Top 10 Total</a> | <a href="designermanager.php?action=rank&amp;duration=<?php echo 1; ?>">Top 10 Mensal</a> | <a href="designermanager.php?action=rank&amp;duration=<?php echo 2; ?>">Top 10 Semanal</a>
 </center>
 <?php
 
 $res = SQL_Query_exec("SELECT u.id, u.username, t.desid, COUNT(t.desid) as num FROM kitpedido t LEFT JOIN users u ON u.id = t.desid  WHERE u.enabled = 'yes' AND t.status = 'aprovado' ". $whereg ." GROUP BY desid ORDER BY num DESC LIMIT 10"); 
 ?>
<table align="center" cellpadding="0" cellspacing="0" class="ttable_headinner" width="100%">
    <tr>
     <th class="tab1_cab1" colspan="3" >Quem mais concluiu kits</th>
 </tr>
 <tr>
     <th class="ttable_head" width="1%"  align="center">Posição</th>
     <th class="ttable_head"  align="center" >Membro</th>
     <th class="ttable_head" width="10%"  align="center" >Concluido</th>
 </tr>
 <?php $i = 1; while ($row = mysql_fetch_assoc($res)): ?>
 <tr>
     <td class="ttable_col2"  align="center" ><?php echo $i; ?></td>
     <td class="ttable_col2"  align="center" ><a href="account-details.php?id=<?php echo $row['id']; ?>"><?php echo $row["username"]; ?></a></td>
     <td class="ttable_col2"  align="center" ><?php echo $row["num"]; ?></td>
 </tr>
 <?php $i++; endwhile; ?>
 <?php if ( mysql_num_rows($res) == 0 ): ?>
 <tr>
     <td class="ttable_col2" colspan="3" align="center"><b>Total de registros encontrados com este critério: 0</b></td>
 </tr>
 <?php endif; ?>
 </table>
 
 <?php  
 end_framec();


}


if ($action=="aguardar" || !$_GET['action']){
begin_framec("Painel Designer BR");
navmenu();

$verpedido = mysql_query("SELECT * FROM kitpedido WHERE  status	='feito'");    
  
while ( $exepedi=mysql_fetch_assoc($verpedido) )
{

 $screens1 = "<a href='" . $exepedi["screens1"] . "' target='_blank'>screens1</a>";
  $screens2 = "<a href='" . $exepedi["screens2"] . "' target='_blank'>screens2</a>";
   $screens3 = "<a href='" . $exepedi["screens3"] . "' target='_blank'>screens3</a>";
   if ($exepedi["cat"] == 1){
   $tipo = 'Animada';
   }
   else{
   $tipo = 'Estatíca';
   }
?>
<br>
<table align="center" width="100%" cellspacing="1" cellpadding="0" id="tabela1">
<tbody><tr><td width="40%" align="right" class="tab1_cab1">Usuário: </td><td class="tab1_cab1"><p class="adenisair" a href="account-details.php?id=<?php echo $exepedi['userid'] ; ?>"><?php echo $exepedi['kusername'] ; ?></p></a></td></tr>
<tr><td align="right" class="tab1_col3">Data do pedido: </td><td class="tab1_col3"><?php echo date("d/m/y", utc_to_tz_time($exepedi['added']))." às ". date("H:i:s", utc_to_tz_time($exepedi['added'])) ;?></td></tr>
<tr><td align="right" class="tab1_col3">Tipo: </td><td class="tab1_col3"><?php echo $tipo ; ?></td></tr>

<?php
if ($exepedi["estilo"]){
?>
<tr><td align="right" class="tab1_col3">Estilo de Borda: </td><td class="tab1_col3"><?php echo $exepedi['estilo']  ; ?></td></tr>
<?php
}
?>
<tr><td align="right" class="tab1_col3">screens1: </td><td class="tab1_col3"><?php echo ( $exepedi['screens1'] > null ) ? $screens1 : 'N/A'  ; ?></td></tr>
<tr><td align="right" class="tab1_col3">screens2: </td><td class="tab1_col3"><?php echo ( $exepedi['screens2'] > null ) ? $screens2 : 'N/A'; ?></td></tr>
<tr><td align="right" class="tab1_col3">screens3: </td><td class="tab1_col3"><?php echo ( $exepedi['screens3'] > null ) ? $screens3 : 'N/A'; ?></td></tr>
<tr><td align="center" class="ttable_col1" colspan="2">Descrição</td></tr>
<tr><td align="center" class="tab1_col3" colspan="2"><?php echo $exepedi['descr'] ;?> </td></tr>
<tr><td align="center" class="ttable_col1" colspan="2">
<script language="JavaScript"> 
function aceitar(id){ 
if (window.confirm('Deseja aceitar este kit?')) {
 window.location.href = 'designermanager.php?action=aceitar&idaceitar='+id
}
else { window.alert('Ok, nenhuma ação foi feita!') }

} 
</script>
<script language="JavaScript"> 
function apagar(id){ 
if (window.confirm('Deseja apagar este kit?')) {
 window.location.href = 'designermanager.php?action=apagar&idapagar='+id
}
else { window.alert('Ok, nenhuma ação foi feita!') }

} 
</script>
<input type=submit value='Aceitar' onclick="aceitar(<?php echo $exepedi['id'] ;?>)" style='height: 22px'>
<input type=submit value='Apagar' onclick="apagar(<?php echo $exepedi['id'] ;?>)" style='height: 22px'>
</td></tr>
</tbody></table>

<br>

<?php




}


end_framec();
}




if ($action=="aceito" ){
begin_framec("Painel Designer BR");
navmenu();

if ($do=="2"){
	$wherea[] = "desid = '".$CURUSER["id"]."'";
}else{
	$wherea[] = "desid != '0'";
}
$where = implode(" AND ", $wherea);
?>
<div align="right">Filtrar: <a href="designermanager.php?action=aceito&do=1">Ver todos</a> | <a href="designermanager.php?action=aceito&do=2">Atendidos por mim</a> </div><br>
<?php

$verpedido = mysql_query("SELECT * FROM kitpedido WHERE  status	='aceito' AND $where");    
  
while ( $exepedi=mysql_fetch_assoc($verpedido) )
{

 $screens1 = "<a href='" . $exepedi["screens1"] . "' target='_blank'>screens1</a>";
  $screens2 = "<a href='" . $exepedi["screens2"] . "' target='_blank'>screens2</a>";
   $screens3 = "<a href='" . $exepedi["screens3"] . "' target='_blank'>screens3</a>";
   if ($exepedi["cat"] == 1){
   $tipo = 'Animada';
   }
   else{
   $tipo = 'Estatíca';
   }
?>
<br>
<table align="center" cellspacing="1" cellpadding="0" id="tabela1">
<tbody><tr><td width="40%" align="right" class="tab1_cab1">Designer: </td><td class="tab1_cab1"><p class="adenisair" a href="account-details.php?id=<?php echo $exepedi['desid'] ; ?>"><?php echo $exepedi['desname'] ; ?></p></a></td></tr>
<tr><td align="right" class="tab1_col3">Usuário: </td><td class="tab1_col3"><a href="account-details.php?id=<?php echo $exepedi['userid'] ; ?>"><?php echo $exepedi['kusername'] ; ?></a></td></tr>
<tr><td align="right" class="tab1_col3">Data do pedido: </td><td class="tab1_col3"><?php echo date("d/m/y", utc_to_tz_time($exepedi['added']))." às ". date("H:i:s", utc_to_tz_time($exepedi['added'])) ;?></td></tr>
<tr><td align="right" class="tab1_col3">Tipo: </td><td class="tab1_col3"><?php echo $tipo ; ?></td></tr>
<?php
if ($exepedi["estilo"]){
?>
<tr><td align="right" class="tab1_col3">Estilo de Borda: </td><td class="tab1_col3"><?php echo $exepedi['estilo']  ; ?></td></tr>
<?php
}
?>
<tr><td align="right" class="tab1_col3">screens1: </td><td class="tab1_col3"><?php echo ( $exepedi['screens1'] > null ) ? $screens1 : 'N/A'  ; ?></td></tr>
<tr><td align="right" class="tab1_col3">screens2: </td><td class="tab1_col3"><?php echo ( $exepedi['screens2'] > null ) ? $screens2 : 'N/A'; ?></td></tr>
<tr><td align="right" class="tab1_col3">screens3: </td><td class="tab1_col3"><?php echo ( $exepedi['screens3'] > null ) ? $screens3 : 'N/A'; ?></td></tr>
<tr><td align="center" class="ttable_col1" colspan="2">Descrição</td></tr>
<tr><td align="center" class="tab1_col3" colspan="2"><?php echo $exepedi['descr'] ;?> </td></tr>
<tr><td align="center" class="ttable_col1" colspan="2">
<script language="JavaScript"> 
function apagar(id){ 
if (window.confirm('Deseja apagar este kit?')) {
 window.location.href = 'designermanager.php?action=apagar_at&idapagar='+id
}
else { window.alert('Ok, nenhuma ação foi feita!') }

} 
</script>
<script language="JavaScript"> 
function concluirkit(id){ 
if (window.confirm('Deseja concluir este kit?')) {
 window.location.href = 'designermanager.php?action=concluirkit&concluir='+id
}
else { window.alert('Ok, nenhuma ação foi feita!') }

} 
</script>
<input type=submit value='concluir kit' onclick="concluirkit(<?php echo $exepedi['id'] ;?>)" style='height: 22px'>
<input type=submit value='Apagar' onclick="apagar(<?php echo $exepedi['id'] ;?>)" style='height: 22px'>
</td></tr>
</tbody></table>

<br>

<?php




}


end_framec();
}


if ($action=="log" ){
begin_framec("Painel Designer BR");
navmenu();

$param ="";
	$search = trim($_GET["search"]);
	$type = $_GET["type"];
    $wherea = array();
	
    	if ($search != '' ){
			$wherea[] = " txt LIKE " . sqlesc("%$search%") . "";
			$param .= "search=$search&amp;";
							}
	
   		if($type != '') {
			$wherea[] = " type ='$type'";
			$param .= "type=$type&amp;";
						}
								
												
    $where = implode(" AND ", $wherea);
	
	if ($where != "")
	$where = "WHERE $where";
	
	$res2 = SQL_Query_exec("SELECT COUNT(*) FROM logkit $where");
	$row = mysql_fetch_array($res2);
	$count = $row[0];

	$perpage = 50;

	list($pagertop, $pagerbottom, $limit) = pager($perpage, $count, "designermanager.php?action=log&".$param);

print("<center><form method=get action=?>\n");
	print("<input type=hidden name=action value=log>\n");
	print(" <input type=text size=30 name=search value=\"".stripslashes(htmlspecialchars($search))."\">\n");
	$res3 = SQL_Query_exec("SELECT DISTINCT type,couleur FROM logkit WHERE type !='' ORDER by type");
	print("<select name=type>");
	print("<option value=>" .T_("LOG_USER_TODOS"). "</option>");
	while ($arr = mysql_fetch_array($res3))
	{
    print("<option  value=".htmlspecialchars($arr[type]).">".htmlspecialchars($arr[type])."</option>");
	}
	print("<input type=submit value='" .T_("LOG_USER_PESQUISA"). "'>\n");
	print("</form></center>\n");
	echo $pagertop;

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

	<center>
		<table class='tab1' cellpadding='0' cellspacing='1' align='center' width="100%" border="0" >
			<tr>
				<td class="tab1_cab1" width="1%" align=left><input type='checkbox' id='checkAll' onclick='checkAll(this)'></td>
				<td class="tab1_cab1" width="10%" align=center>Data / Hora</td>
				<td class="tab1_cab1" width="84%" align=center><?php echo T_("LOG_USER_EVENTO"); ?></td>
			</tr>
	<?php
	
	
	$rqq = "SELECT * FROM logkit $where ORDER BY id DESC $limit";
	$res = SQL_Query_exec($rqq);

	echo "<form action='designermanager.php?action=sitelog&do=del' method='POST'>";
	 while ($arr = MYSQL_FETCH_ARRAY($res)){
		$arr['added'] = date("d/m \à\s H:i",utc_to_tz_time(($arr['added'])));
		$date = substr($arr['added'], 0, strpos($arr['added'], " "));
		$time = substr($arr['added'], strpos($arr['added'], " ") + 1);
		print("
			<tr>
			<td class=tab1_col3 ><input type='checkbox' name='del[]' value='$arr[id]'></td>
				<td class=tab1_col3 ><center>".$date." ".$time."</center></td>
				<td class=tab1_col3 >".format_comment($arr['txt'])."</td>
			</tr>\n");
	 }
	echo "</table></center>\n";
if($CURUSER["level"]=="Administrador" || $CURUSER["level"]=="Sysop" ){



	echo "<input type='submit' value='Apagar seleccionado'> <input type='submit' value='Apagar todos' name='delall'></form>";
}
	print($pagerbottom);

end_framec();
}

		
}else
{

show_error_msg("Erro", "<font color=red>Acesso negado</font>");
}

stdfoot();


?> 