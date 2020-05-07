<?php
############################################################
#######                                             ########
#######                                             ########
#######           www.brshares.com 2.0              ########
#######                                             ########
#######                                             ########
############################################################
require "backend/functions.php";
dbconn(false);

stdhead("Loteria ganhadores");
begin_framec("Loteria ganhadores");
?>
<table width="100%" cellspacing="0" cellpadding="0" border="1" >
<tr>
<td>
<style type="text/css">
<!--
.style1 {
color: #FF0000;
font-weight: bold;
}
-->
</style>

<br/>
<?php
$res = mysql_query("SELECT * FROM lottery_config") or sqlerr(__FILE__, __LINE__);
while ($arr = mysql_fetch_assoc($res))
$arr_config[$arr['name']] = $arr['value'];
$who_won = explode("|", $arr_config['lottery_winners']);
$who_won = array_unique($who_won);
$lottery_winners = '';
for ($x = 0; $x < count($who_won); $x++){
$username = '';
$res2 = mysql_query("SELECT id, username FROM users") or sqlerr(__FILE__, __LINE__);
while ($arr2 = mysql_fetch_assoc($res2))
{
if ($arr2['id'] == $who_won[$x])
{
$username = '<a href="account-details.php?id='. $arr2['id'] .'">'. $arr2['username'] .'</a>';
$lottery_winners .= (!$lottery_winners) ? $username : ', '. $username;
break;
}
}}
if (count($who_won) > 1)
$winners = 'encedores';
else
$winners = 'Vencedor';
if (count($who_won) > 1)
$each = ' (Each)';
else
$each = '';
?><table  align="center" width="80%" border="1">
<tr>
<td class="tab1_col3"><h4><center>Vencedores da loteria</center></h4></td>
</tr>
</table>
<table bgcolor=#FFFFFF align=center width=80% border=1>
<tr>
<td class="tab1_col3">Vencedor da última loteria</td>
<td class="tab1_col3" align="left"><?php echo count($who_won) ;?> <?php echo $winners ;?></td>
</tr>
<tr>
<td class="tab1_col3">Última loteria <?php echo $winners ;?></td>
<td class="tab1_col3" align="left"><?php echo $lottery_winners ;?></td>
</tr>
<tr>
<td class="tab1_col3">Montante ganho<?php echo $each ;?></td>
<td class="tab1_col3" align="left"><?php echo mksize($arr_config['lottery_winners_amount']) ;?></td>
</tr>
<tr>
<td class="tab1_col3">Data que terminou Loteria </td>
<td class="tab1_col3" align="left"><?php echo $arr_config['lottery_winners_time'] ;?></td>
</tr>
<tr>
<td class="tab1_col3">Total de jogadores da loteria</td>
<td class="tab1_col3" align="left"><a href=loteria_jogadores.php>Ver</a></td>
</tr>
</tr>
</table>
<br>
</td>
</tr>
</table>
<?php
end_framec();
print("<br/>");
stdfoot();
?>