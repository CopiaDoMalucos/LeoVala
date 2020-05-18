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
loggedinonly();
$dataatual =  get_date_time()  ;
$res = mysql_query("SELECT * FROM lottery_config") or sqlerr(__FILE__, __LINE__);
while ($arr = mysql_fetch_assoc($res)) $arr_config[$arr['name']] = $arr['value'];

$user_class = get_user_class();
$class_allowed = array_map('trim', @explode('|', $arr_config["class_allowed"]));
if (!in_array($user_class, $class_allowed)){

}

$ticket_amount_display = $arr_config['ticket_amount'];
if ($arr_config["ticket_amount_type"] == GB)
$arr_config['ticket_amount'] = 1024 * 1024 * 1024 * $arr_config['ticket_amount'];
else if ($arr_config["ticket_amount_type"] == MB)
$arr_config['ticket_amount'] = 1024 * 1024 * $arr_config['ticket_amount'];
$size = $arr_config['ticket_amount'];
if ($arr_config["ticket_amount_type"] == GB)
$arr_config['prize_fund'] = 1024 * 1024 * 1024 * $arr_config['prize_fund'];
else if ($arr_config["ticket_amount_type"] == MB)
$arr_config['prize_fund'] = 1024 * 1024 * $arr_config['prize_fund'];
$prize_fund = $arr_config['prize_fund'];
$ratioerr = "<font color=\"red\"><b>You must have uploaded atleast $arr_config[ticket_amount] $arr_config[ticket_amount_type] in order to buy a ticket!</b></font>";
stdhead("Tickets Page");
$total = mysql_num_rows(mysql_query("SELECT * FROM tickets"));
if ($arr_config["use_prize_fund"])
$pot = $prize_fund;
else
$pot = $total * $size;
$me = mysql_num_rows(mysql_query("SELECT * FROM tickets WHERE user=" . $CURUSER["id"]));
$me2 = mysql_query("SELECT * FROM tickets WHERE user=". $CURUSER['id'] ." ORDER BY id ASC");
while ($myrow = mysql_fetch_assoc($me2))
$ticketnumbers .= "$myrow[id] ";
$purchaseable = $arr_config['user_tickets'] - $me;
///////////////Test Max Tickets////////////////////
$maxpurchaseable = $arr_config['usermax_tickets'];
//////////////////////////////////////////////////
if ($me >= $arr_config["user_tickets"])
$purchaseable = 0;
if ( date("d-m-Y H:i:s", utc_to_tz_time($dataatual))  > $arr_config["end_date"])
$purchaseable = 0;

begin_framec("Loteria");
?>
<table border="0" class="table_table" cellpadding="3" cellspacing="3" width="100%">
<tr><td>
<table border="0" class="table_table" cellpadding="3" cellspacing="3" width="100%">
<tr>
<td align=center  class="table_col1"><ul><li>Os bilhetes não são reembolsáveis</li>
<li>Cada bilhete custa <?php echo  $ticket_amount_display . ' ' . $arr_config['ticket_amount_type']; ?> que é descontado a partir do seu valor de upload</li>
<li>Você só pode comprar até sua quantidade permitida.</li>
<li>A competição vai acabar: <font color=red><b><?= $arr_config["end_date"]; ?></font></b></li>
<li>Haverá <font color=red><b><?php echo  $arr_config['total_winners']; ?></font></b> vencedores, que serão escolhidos de forma aleatória</li>
<li>Cada vencedor irá receber <font color=red><b><?php echo  mksize($pot/$arr_config['total_winners']); ?></font></b> acrescentado ao seu montante de upload</li>
<li>Os vencedores serão anunciados <a href=loteria_ganhadores.php>Aqui</a> uma vez que a loteria foi fechado.</li>
<?
if (!$arr_config["use_prize_fund"]){
?>
<li>Os mais bilhetes que são vendidos a maior pote será!</li>
<?
}
?>
<li><b>Você já possui os números de bilhete:</b> <font color=green><b><?php echo  $ticketnumbers; ?></font></b></li>
</ul>Boa Sorte!<hr>
<table align=center width=40% class=frame border=1 cellspacing=0 cellpadding=10 bgcolor="#FFFFFF">
<tr>
<td align=center>
<table width=100% class=tableb class=main border=1 cellspacing=0 cellpadding=5>
<tr>
<td class=table_col1>Cada bilhete custa</td>
<td class=table_col1 align=right><?php echo  $ticket_amount_display;?> <?php echo $arr_config['ticket_amount_type']; ?></td>
</tr>
<tr>
<td class=table_col2>Valor total do prêmio</td>
<td class=table_col2 align=right><?php echo  mksize($pot); ?></td>
</tr>
<tr>
<td class=table_col1>Total de bilhetes vendidos</td>
<td class=table_col1 align=right><?php echo  $total; ?> Bilhetes</td>
</tr>
<tr>
<td class=table_col2>Meus bilhetes</td>
<td class=table_col2 align=right><?php echo  $me; ?> Bilhetes</td>
</tr>
<tr>
<td class=table_col1>Total de bilhetes disponíveis</td>
<td class=table_col1 align=right><?php echo  $purchaseable; ?> Bilhetes</td>
</tr>
<!---------------Test Max Tickets------------------>
<tr>
<td class=table_col2>Maximo de bilhetes que você pode comprar</td>
<td class=table_col2 align=right><?php echo  $maxpurchaseable; ?> Bilhetes</td>
</tr>
<!-------------Fin Test Max Tickets---------------->
</table>
</table>
<hr>

<?php 

if ($purchaseable > 0){
?>
<center><form method="post" action="validar_bilhetes.php">Quantidade <input type="text" name="number"> Bilhetes <input type="submit" value="Comprar"></form></center>
<?php 
}

else if ( date("d-m-Y H:i:s", utc_to_tz_time($dataatual))  > $arr_config["end_date"]){
?>
<center><h1><font color = "red">Loteria fechada!</font></h1></center>
<center><a href="javascript:history.go(-1)">Voltar</a></center>
<?php 
}
?>
</td>
</tr>
</table>
</td></tr>

</table>
<?php 
print("<br>\n");


end_framec();

stdfoot();
die;
?>