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

if($CURUSER["level"]=="Administrador" || $CURUSER["level"]=="Sysop" || $CURUSER["level"]=="Moderador" || $CURUSER["level"]=="S.Moderador" || $CURUSER["level"]=="Liberador"){
////somente torrentes com seed
stdhead("Painel de Controle");
begin_framec("Painel de Controle");
$data2 = date('Y-m-d H:i:s');  
$data21 = date("m/Y", utc_to_tz_time($data2));  

$liberaesta = SQL_Query_exec("SELECT COUNT(*) FROM apppprovar WHERE date_format(added,'%m/%Y')='$data21' AND uid=".$CURUSER["id"]."");
$rowesta = mysql_fetch_row($liberaesta);


$deletadostor = SQL_Query_exec("SELECT COUNT(*) FROM apppdel WHERE date_format(added,'%m/%Y')='$data21' AND uid=".$CURUSER["id"]."");
$deletadostorr = mysql_fetch_row($deletadostor);




$liberados = $rowesta[0];
$deletadosto = $deletadostorr[0];



?>
<div id="body_outer">

<center><b>Minhas estatísticas do mês</b><br><br>Torrents liberados: <b><?php echo $liberados ;?></b><br>Torrents deletados: <b><?php echo $deletadosto ;?></b><br></center><br><br>

<table cellspacing="1" cellpadding="0" align="center" id="tabela1">
	<tbody><tr>
	<td align="center" colspan="4" class="tab1_cab1">Painel da Moderação de Torrents</td>
	</tr>
	<tr>
	<td width="25%" align="center" class="ttable_col2">
	<a href="/torr_bloq.php"><img border="0" alt="" src="/images/torrent/bktorrent.png"><br>Torrents Bloqueados</a>
	</td>
	<td width="25%" align="center" class="ttable_col2">
	<a href="#"><img border="0" alt="" src="/images/torrent/arquivos.png"><br>Gerenciar Correções</a>
	</td>
	<td width="25%" align="center" class="ttable_col2">
	<a href="#"><img border="0" alt="" src="/images/torrent/modlog.png"><br>Log Completo</a>
	</td>
	<td width="25%" align="center" class="ttable_col2">
	<a href="#"><img border="0" alt="" src="/images/torrent/modok.png"><br>Torrents Liberados</a>
	</td>
	</tr>
	</tbody></table>
	
				<div class="clr"></div>

			</div>
<?php			

end_framec();
}
stdfoot();
?>
 