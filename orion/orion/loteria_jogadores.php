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

$res = mysql_query("SELECT * FROM lottery_config") or sqlerr(__FILE__, __LINE__);
while ($arr = mysql_fetch_assoc($res))
$arr_config[$arr['name']] = $arr['value'];
$endday = $arr_config['end_date'];
if (!$arr_config["enable"])show_error_msg("Erro", "<font color=red>A loteria está fechada! Aguarde as próximas rodadas...</font>");
stdhead();
begin_framec("Usuários que compraram ingressos");
print("<center><font color=red>Termina loteria: </font><b>" . $endday . "</b></center><br /><br />");
?>
<table align=center border="1" width="600" cellpadding="5">
<tr>
<td class="table_col1">#</td>
<td class="table_col2">Usuário</td>
<td class="table_col1">Número de bilhetes</td>
<td class="table_col2">Semeado</td>
<td class="table_col1">Baixado</td>
<td class="table_col2">Ratio</td>
</tr>
<?php
$sql = mysql_query("SELECT user FROM tickets") or die (mysql_error());
while ($myrow = mysql_fetch_assoc($sql))$user[] = $myrow["user"];
$user = array_values(array_unique($user));
for ($i = 0; $i < sizeof($user); $i++){
$tickets[] = mysql_num_rows(mysql_query("SELECT * FROM tickets WHERE user=$user[$i]"));
$sqy = "SELECT id, username, uploaded, downloaded, uploaded/downloaded as user_ratio FROM users WHERE id=" . $user[$i];
 $vay = mysql_query($sqy) or die (mysql_error());
 $sq = mysql_fetch_assoc($vay);
echo "<tr><td class=table_col1>" . ($i + 1) . "</td>
<td class=table_col2><a href=account-details.php?id=" . $sq['id'] . ">" . $sq['username'] . "</a></td>
<td class=table_col1>$tickets[$i]</td>
<td class=table_col2>" . mksize($sq['uploaded']) . "</td>
<td class=table_col1>" . mksize($sq['downloaded']) . "</td>
<td class=table_col2>" . number_format($sq['user_ratio'], 3) . "</td></tr>";
}
?>
</table>
<?php
print("<center><h3><a href=loteria_ganhadores.php>Voltar</a></h3></center>");
end_framec();
stdfoot();
die;
?>