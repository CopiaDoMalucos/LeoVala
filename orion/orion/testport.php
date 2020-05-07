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
stdhead(T_("TESTE_SUA_PORTA"));
	begin_framec(T_("TESTE_SUA_PORTA",center));
error_reporting(0);
$query = mysql_query('SELECT `port` FROM `peers` WHERE `userid` = ' . $CURUSER['id'] . ' LIMIT 1');
  $ports = mysql_fetch_array($query);
  
  if ($ports) {
      $port = $ports['port'];
  } else {
      $port = 'Waiting For Connection...';
  }
  

$host = $_SERVER['REMOTE_ADDR'];
$i = $_GET['port'];
$fp = fsockopen("$host",$i,$errno,$errstr,10);
if($fp){
    echo "TCP port " . $i . " open on " . $host . "<br><br>Yay! :-)";
    fclose($fp);
}
else{
    echo "".T_("TESTE_PORTA")." " . $i . " ".T_("TESTE_FECHADA_IP")." " . $host . "\n";
		echo "".T_("TESTE_MENSAGEM")."<font color=#FF0000><font size=3> " . $port . " </font></font>".T_("TESTE_MENSAGEM1")." \n";
}
	$ip = $CURUSER['ip'];

	if ($_SERVER["REQUEST_METHOD"] == "POST")
		$port = $_POST["port"];
	else
		$port = $_GET['port'];
	$port = intval($port);
	if ($port) {
		$fp = @fsockopen ($ip, $port, $errno, $errstr, 10);
		if (!$fp) {
			print ("<table width=40% class=main cellspacing=1 cellpadding=5><br><tr>".
			"<td class=colhead align=center><b>".T_("TESTE_PORTA_FECH")."</b></td></tr><tr><td class=tableb><font color=darkred><br><center><b>IP: $ip ".T_("TESTE_PORTA_FECHADAIP").": $port ".T_("TESTE_PORTA_NAO")."!</b></center><br></font></td></tr><tr><td class=tableb><center><form><INPUT TYPE=\"BUTTON\" VALUE=\"".T_("TESTE_PORTA_NOVA_PORTA")."\" ONCLICK=\"window.location.href='/testport.php'\"></form></center></td></tr></table");
		} else {
			print ("<table width=40% class=main cellspacing=1 cellpadding=5><br><tr>".
			"<td class=colhead align=center><b>".T_("TESTE_PORTA_TESTE1")."</b></td></tr><tr><td class=tableb><font color=darkgreen><br><center><b>IP: $ip ".T_("TESTE_PORTAIS_PORTA").": $port ".T_("TESTE_PORTA_GOOD")."!</b></center><br></font></td></tr><tr><td class=tableb><center><form><INPUT TYPE=\"BUTTON\" VALUE=\"".T_("TESTE_PORTA_NEW_PORTA")."\" ONCLICK=\"window.location.href='/testport.php'\"></form></center></td></tr></table>");
		}
	}

	else
	{
	print("<table width=40% class=main cellspacing=1 cellpadding=5><br><tr>".
	"<td class=colhead align=center colspan=2><b>".T_("TESTE_PORTA_TESTE12")."</b></td>".
	"</tr>");
	print ("<form method=post action=testport.php>");
	print ("<tr><td class=tableb><center>".T_("TESTE_PORTA_NUMERO").":<center></td><td class=tableb><center><input type=text name=port></center></td></tr>");
	print ("<tr><td class=tableb></td><td class=tableb><center><input type=submit class=btn value='".T_("TESTE_PORTA_ENVIAR")."'></center></td></tr>");
	print ("</form>");
	print ("</table>");
	}
			print("<a href=http://www.brshares.com/forums.php?action=viewtopic&topicid=107&page=last><font color=#FF0000><CENTER><b>".T_("TESTE_PORTA_ABRIR_UT")."</b></CENTER></font></a>");

end_framec();
stdfoot();
flush();