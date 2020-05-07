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

if($CURUSER["level"]=="Administrador" || $CURUSER["level"]=="S.Moderador" || $CURUSER["level"]=="Moderador" || $CURUSER["level"]=="Liberador"){
stdhead("Approval List");
begin_framec("Torrents aguardando Aprovação");
?>
<div id="body_outer">

<center><b>Minhas estatísticas do mês</b><br><br>Torrents liberados: <b>14</b><br>Torrents deletados: <b>3</b><br></center><br><br><table cellspacing="1" cellpadding="0" align="center" id="tabela1">
	<tbody><tr>
	<td align="center" colspan="4" class="ttable_col3">Painel da Moderação de Torrents</td>
	</tr>
	<tr>
	<td width="25%" align="center" class="ttable_col2">
	<a href="smt_bloq.php"><img border="0" alt="" src="images/icones/nlss.jpg"><br>Torrents Bloqueados</a>
	</td>
	<td width="25%" align="center" class="ttable_col2">
	<a href="mod_correcoes.php"><img border="0" alt="" src="images/icones/carga.jpg"><br>Gerenciar Correções</a>
	</td>
	<td width="25%" align="center" class="ttable_col2">
	<a href="modlog.php"><img border="0" alt="" src="images/icones/modlog.jpg"><br>Log Completo</a>
	</td>
	<td width="25%" align="center" class="ttable_col2">
	<a href="browse.php?mlib=1"><img border="0" alt="" src="images/icones/modkeys.jpg"><br>Torrents Liberados</a>
	</td>
	</tr>
	</tbody></table>
	
				<div class="clr"></div>

			</div>
<?php
}else{

show_error_msg("::...Erro", "Acesso restrito aos membros da Staff");
end_framec();
}
stdfoot();
?>
