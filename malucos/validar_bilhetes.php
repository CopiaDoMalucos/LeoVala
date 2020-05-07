<?php
############################################################
#######                                             ########
#######                                             ########
#######           malucos-share.org 2.0             ########
#######                                             ########
#######                                             ########
############################################################
require "backend/functions.php";
dbconn(false);
loggedinonly();

////////////////////////////GET ALL DATAS FROM LOTTERY CONFIG//////////////////////
$res = mysql_query("SELECT * FROM lottery_config") or sqlerr(__FILE__, __LINE__);
while ($arr = mysql_fetch_assoc($res))
$arr_config[$arr['name']] = $arr['value'];
$endday = $arr_config['end_date'];
$ticket_amount_display = $arr_config['ticket_amount'];
///////////////////////////////MAKE SOME CONVERSIONS///////////////////////////////
if ($arr_config["ticket_amount_type"] == GB)
$arr_config['ticket_amount'] = 1024 * 1024 * 1024 * $arr_config['ticket_amount'];
else if ($arr_config["ticket_amount_type"] == MB)
$arr_config['ticket_amount'] = 1024 * 1024 * $arr_config['ticket_amount'];
$size = $arr_config['ticket_amount'];
$minupload = $size; //Minimum Upload Required to Buy Ticket!
if (!$arr_config["enable"])show_error_msg("Error", "Ops loteria fechada..");
stdhead("Tickets Page");
//if (get_user_class() < 0){
//print("<h1>Sorry</h2><p>You must be Registered to request, see the <a href=faq.php><b>FAQ</b></a> for information on different user classes</p>");
//die();
//}
//if (get_date_time() > $arr_config['end_date']){
//print ("Sorry I cannot sell you any tickets!");
//die();
//}

////////////////////////INSERT VALUES OF THE USER IN CASINO TABLE IF IT'S THE FIRST TIME THAT HE PLAY////////////////////////
$query ="select * from casino where userid = '".$CURUSER["id"]."'";
$result = mysql_query($query) or die (mysql_error());
if(mysql_affected_rows()!=1)
{
 mysql_query("INSERT INTO casino (userid, win, lost, trys, date) VALUES(" . $CURUSER["id"] . ",0,0,0, '" . get_date_time() . "')") or sqlerr(__FILE__, __LINE__);
 //stderr("Hi ".$CURUSER["username"], "This is the first time you try to play at the Casino please refresh the site");
 $result = mysql_query($query); ///query another time to get the new user, if the stderr is uncomment
}
////////////////////////////////////////////////////////////END CASINO INSERT/////////////////////////////////////////////////
/////////////////Verif si peux jouer au casino//////////////////////
//$row = mysql_fetch_array($result);
//$user_enableplay = $row["enableplay"];
//if($user_enableplay=="no")
 //stderr("Sorry ".$CURUSER["username"],"you're banned from casino.");
///////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////
$res = mysql_query("SELECT downloaded, uploaded FROM users WHERE id = $CURUSER[id]") or sqlerr(__FILE__, __LINE__);
$result = mysql_fetch_assoc($res);
$res2 = mysql_query("SELECT COUNT(id) AS tickets FROM tickets WHERE user = $CURUSER[id]") or sqlerr(__FILE__, __LINE__);
$result2 = mysql_fetch_assoc($res2);
$purchaseable = $arr_config['user_tickets'];
//////////////////////////////RATIO VERIFICATION////////////////////////////////////////////
$miniratio = $arr_config['ratio_mini'];
   $ratio = number_format($result["uploaded"] / $result["downloaded"], 2);
  if($ratio < $miniratio){
  begin_framec("Error");
print("
<table class=main width=100% cellspacing=0 cellpadding=5>
<tr>
<td align=center><font color=red><b>Desculpe $CURUSER[username], mínimo ratio permitido $miniratio</b></font></td>
</tr>
<tr>
<td align=center><a href=loteria_bilhetes.php>Voltar</a></td>
</tr>
</table>");
end_framec();
stdfoot();
die;
}
/////////////////////////////////END RATIO VERIFICATION/////////////////////////////////
/////////////////////ERROR IF USER TRY TO BUY ZERO TICKETS//////////////////////////////
if ($_REQUEST['number'] == 0 || $_REQUEST['number'] == ''){
begin_framec("Error");
print("
<table class=main width=100% cellspacing=0 cellpadding=5>
<tr>
<td align=center><font color=red><b>Não me faça perder meu tempo! Tem certeza de que deseja por 0 bilhetes?</b></font></td>
</tr>
<tr>
<td align=center><a href=loteria_bilhetes.php>Voltar</a></td>
</tr>
</table>");
end_framec();
stdfoot();
die;
}
//////////////////////////END ERROR/////////////////////////////////////////////////////
////////////////ERROR IF IF USER TRY TO BOUGH MUCH TICKETS THAT ALLOWED/////////////////
if ($_REQUEST['number'] > $purchaseable || $_REQUEST['number'] < 1 ){
begin_framec("Error");
print("<table class=main width=100% cellspacing=0 cellpadding=5>
<tr>
<td align=center><font color=red><b>Bilhetes máximo de bilhetes atingidos é $purchaseable</b></font></td>
</tr>
<tr>
<td align=center><a href=loteria_bilhetes.php>Voltar</a></td>
</tr>
</table>");
end_framec();
stdfoot();
die;
}
////////////////////////END ERROR////////////////////////////////////////////////
///////////ERROR IF USER DON'T HAVE ENOUGH EUPLOAD TO BUY TICKETS////////////////
if (($minupload * $_REQUEST['number']) > $result["uploaded"] ){
begin_framec("Error");
print("<table class=main width=100% cellspacing=0 cellpadding=5>
<tr>
<td align=center><font color=red><b>Você não tem o suficiente de upload para comprar os bilhetes solicitados!</b></font></td>
</tr>
<tr>
<td align=center><a href=loteria_bilhetes.php>Voltar</a></td>
</tr>
</table>");
end_Frame();
stdfoot();
die;
}
/////////////////////////////END ERROR//////////////////////////////////////////////
/////////////////////////Test Max Tickets/////////////////////////////////
$maxpurchaseable = $arr_config['usermax_tickets'];
if ($_REQUEST['number'] > $maxpurchaseable || (($_REQUEST['number'] + $result2['tickets']) > $maxpurchaseable)){
begin_framec("Error");
print("
<table class=main width=100% cellspacing=0 cellpadding=5>
<tr>
<td align=center><font color=red><b>Você comprou: ".$result2['tickets']." Bilhetes. apenas $maxpurchaseable bilhete é permitido do mesmo jogador</b></font></td>
</tr>
<tr>
<td align=center><a href=loteria_bilhetes.php>Voltar</a></td>
</tr>
</table>");
end_framec();
stdfoot();
die;
}
///////////////////////Fin test Max Tickets///////////////////////////////
$last_upload = $result['uploaded'];
$cost = $_REQUEST['number'] * $size;
// code original $upload = $result['uploaded'] - ($minupload * $_REQUEST['number']);
$upload =($minupload * $_REQUEST['number']);
$newup = $result['uploaded'] - ($minupload * $_REQUEST['number']);
$userid = $CURUSER["id"];
//code original mysql_query("UPDATE users SET uploaded='$upload' WHERE id='$userid'") or die(mysql_error());
mysql_query("UPDATE users SET uploaded= uploaded - '$upload'  WHERE id=". $CURUSER["id"]) or sqlerr(__FILE__, __LINE__);
$tickets = $_REQUEST['number'];
for ($i = 0; $i < $tickets; $i++)
mysql_QUERY("INSERT INTO tickets(user) VALUES($CURUSER[id])");
$me = mysql_num_rows(mysql_query("SELECT * FROM tickets WHERE user=" . $CURUSER["id"]));
/////////////////////////Intégration dans casino///////////////////////
mysql_query("UPDATE casino SET date = '".get_date_time()."', trys = trys + 1 WHERE userid=".$CURUSER["id"]) or sqlerr(__FILE__, __LINE__);
///////////////////////////Fin Integration//////////////////////////////
print("<br>\n");
begin_framec("Os bilhetes comprados");
?>
<table align=center border=1 width=600 cellspacing=0 cellpadding=5>
<tr>
<td class=tableb align=left>Você comprou <?php echo $_REQUEST["number"]; ?> bilhete(s)
<?php 
if ($_REQUEST["number"] > 1) echo "s"; ?>!<br>Este custo a você <?php echo mksize($cost); ?><br>Seu novo total é <?php echo $me; ?>!<br>O seu envio foi <?php echo mksize($last_upload); ?><br>Seu total de upload novo é <?php echo mksize($newup); ?>!<br><br><a href=loteria_bilhetes.php>Votar</a>
</td>
</tr>
</table>
<?php
print("<br>\n");
end_framec();
stdfoot();
die;
?>
