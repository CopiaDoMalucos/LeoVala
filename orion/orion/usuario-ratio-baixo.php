<?
//
//  TorrentTrader v2.x
//  This file was last updated: 18/Oct/2008 by Foz
//	
//	http://www.basslinebeats.co.uk
//
//
require "backend/functions.php";
dbconn(false);

stdhead("Bad Ratio Users");

begin_frame("Usuarios com Ratio Baixo");
if ($site_config["MEMBERSONLY"] && !$CURUSER) {
	echo "<BR><BR><b><CENTER>Você não está logado<br>Somente usuários podem ver Affiliates Por favor Entre ou registe-se.</CENTER><BR><BR>";
} else{


/* CONFIG */
$maxratio = 0.8; // Máxima relação à lista. = Padrão de 0,4.
$mindownload = 10737418240; // "Downloaded more than". Standard = 10 GB.
$deleteclass = ADMINISTRATOR; // Minimum and equal class that can delete users.
$drsource = false; // Defina como true se você usar o DR-source. False se você usar fonte oficial. Definição é para PM de.
$pmmsg = "Você foi avisado, devido à baixa proporção de Ratio. Voce esta com Ratio 0.8 e o limite minimo é 0.7, Você tem duas semanas para melhorá-lo. Se você não naum Aumentar, você será banido. Sempre fique Compartilhando seus Torrent para Evitar baixa de Ratio, Seja um Semeador. Naum delete torrents do Seu Cliente do BR2.in,! , Ajude o Site Faça uma Doação e Baixe Sempre Free sem se preocupar com RATIO, Este é um sistema de mensagem Gerada Automaticamente. Exclusão da conta também é controlada automaticamento pelo sistema,! ;)";
/* END CONFIG */

if (!$CURUSER || $CURUSER["control_panel"]!="yes"){
 show_error_msg("Error","Desculpe, mas você não tem os direitos para acessar esta página!",1);
}

if ($_GET['action'] == ""){

if ($_GET['godcomplex'] == "yes")
{
foreach ( $_POST as $key => $ertek )
{
if ( (strpos($key,'cb_') != 0) or ($ertek == -1) ) continue;
else
{
$username=substr($key,3);
}

if ($_POST['warn']){
$req="UPDATE users SET warned = 'yes', warneduntil = DATE_ADD(NOW(), INTERVAL ".(2 * $warnfor)." DAY) WHERE id = '$ertek'";
$res=mysql_query($req);
}

else if ($_POST['pm']){
$r = mysql_query("SELECT id AS userid FROM users WHERE id = '$ertek'") or sqlerr();
while($dat=mysql_fetch_assoc($r)){
if($drsource == "true"){
new_msg(0, $dat[userid], $pmmsg); /* DR Source */
}else{
mysql_query("INSERT INTO messages (sender, receiver, added, msg) VALUES (0,$dat[userid] , '" . get_date_time() . "', " . sqlesc($pmmsg) .")") or sqlerr(__FILE__,__LINE__); /* Official Source */
}
}
$res = "PM Sent!";
}

else if ($_POST['disable']){
$req="UPDATE users SET warned = 'yes', enabled = 'no' WHERE id = '$ertek'";
$res=mysql_query($req);
// write_log("User $username was diabled by $CURUSER[username]");
}

else if ($_POST['delete'] && get_user_class() > $deleteclass){
$req="DELETE FROM users WHERE id = '$ertek'";
$res=mysql_query($req);
// write_log("User $username was deleted by $CURUSER[username]");
}
if ($res == ''){print("<script language=\"javascript\">alert('No users Warned, Disabled or Deleted!');</script>");}
}
}


function usertable($res, $frame_caption)
{
global $CURUSER;
begin_frame($frame_caption, true);
begin_table();
?>
<tr><br><br><center>
<td class="colhead" align="left">Usuario</td>
<td class="colhead"><font color="green">Uploaded</font></td>
<td class="colhead"><font color="red">Downloaded</font></td>
<td class="colhead" align="right"><font color="orange">Ratio</font></td>
<td class="colhead" align="left"> Cadastrado</td>
<td class="colhead" align="center">X</td>
</tr>
<?

$cba='';
if ( isset($_GET["select"]) )
{
$select=$_GET["select"];
if ( $select == 'all' ) $cba='checked';
elseif ( $select =='none' ) $cba='';
}

$num = 0;
print("<form method=\"post\" action=\"usuario-ratio-baixo?godcomplex=yes\">");
while ($a = mysql_fetch_assoc($res))
{
foreach ($a as $key => $ertek )

++$num;
$highlight = $CURUSER["id"] == $a["userid"] ? " bgcolor=#BBAF9B" : "";
if ($a["downloaded"])
{
$ratio = $a["uploaded"] / $a["downloaded"];
$color = get_ratio_color($ratio);
$ratio = number_format($ratio, 2);
if ($color)
$ratio = "<font color=$color>$ratio</font>";
}
else
$ratio = "Inf.";
print("<tr class=row1 $highlight><td align=left$highlight><a href=account-details.php?id=" .
$a["userid"] . "><strong>" . $a["username"] . "</strong></a>");

if($a["warned"] == "yes"){print("<img src=\"" . $GLOBALS['pic_base_url'] . "/images/warned.gif\" />");}

print("</td><td class=row1 align=right $highlight>" . mksize($a["uploaded"]) .
"</td><td class=row1 align=right $highlight>" . mksize($a["downloaded"]) .
"</td><td class=row1 align=right $highlight>" . $ratio .
"</td><td class=row1 align=left>" . gmdate("Y-m-d",strtotime($a["added"])) . " (" .
get_elapsed_time(sql_timestamp_to_unix_timestamp($a["added"])) . " ago)</td>
<td><input type=checkbox name=\"cb_" . $a["username"] . "\" value=\"" . $a["userid"] . "\""); if($_GET["select"] == "unwarned" && $a["warned"] == "no"){print("checked");} print(" " . $cba . " /></td>
</tr>");
}
end_table();
end_frame();
}

stdhead("Ratio Under $maxratio/$mindownload byte Downloaded");
$mainquery = "SELECT id as userid, username, added, uploaded, downloaded, warned, uploaded / (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(added)) AS upspeed, downloaded / (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(added)) AS downspeed FROM users WHERE enabled = 'yes'";

$limit = 250;
$order = "added ASC";
$extrawhere = " AND uploaded / downloaded < $maxratio AND downloaded > $mindownload";
$r = mysql_query($mainquery . $extrawhere . " ORDER BY $order " . " LIMIT $limit") or sqlerr();
print("<a href=\"?select=unwarned\">Selecione desprevenido</a> | <a href=\"?select=all\">Selecionar Todos</a> | <a href=\"?select=none\">Selecionar Nenhum</a>");
$mindownloadprint = mksize ($mindownload);
?>
<div style="width:480px;">
<?
usertable($r, "Ratio Under $maxratio / $mindownloadprint Downloaded");
print("<a href=\"?action=sendpm\">Enviar Mass PM a todos os usuários baixa relação</a>");
print("<p><input type=\"submit\" name=\"pm\" value=\"PM selected\" onclick=\"return confirm('PM all selected users?');\" /></p>");
print("<input type=\"submit\" name=\"disable\" value=\"Disable selected\" onclick=\"return confirm('Disable all selected users?');\" />");
if( get_user_class() > $deleteclass ){
print("<input type=\"submit\" name=\"delete\" value=\"Delete selected\" onclick=\"return confirm('Are you bloody sure you want to delete all these users!?');\" />");
}
print("</form>");
?>
</div>
<?

if ($_GET['taking'] == "takepm"){
if ($HTTP_SERVER_VARS["REQUEST_METHOD"] == "POST"){
$dt = sqlesc(get_date_time());
$msg = $_POST['msg'];
if (!$msg)
stderr("Error","Please Type In Some Text");

$query = "SELECT id as userid, username, added, uploaded, downloaded, warned, uploaded / (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(added)) AS upspeed, downloaded / (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(added)) AS downspeed FROM users WHERE enabled = 'yes'";
$limit = 250;
$order = "added ASC";
//$order = "uploaded / downloaded ASC, downloaded DESC";
$extrawhere = " AND uploaded / downloaded < $maxratio AND downloaded > $mindownload";
$r = mysql_query($mainquery . $extrawhere . " ORDER BY $order " . " LIMIT $limit") or sqlerr();

while($dat=mysql_fetch_assoc($r)){

if($drsource == "true"){
new_msg(0, $dat[userid], $msg); /* DR Source */
}else{
mysql_query("INSERT INTO messages (sender, receiver, added, msg) VALUES (0,$dat[userid] , '" . get_date_time() . "', " . sqlesc($msg) .")") or sqlerr(__FILE__,__LINE__); /* Official Source */
}
}
mysql_query("INSERT INTO leecherspmlog ( user , date ) VALUES ( $CURUSER[id], $dt)") or sqlerr(__FILE__,__LINE__);
header("Refresh: 0; url=usuario-ratio-baixo.php");


}
}

if ($_GET['action'] == "sendpm") {
stdhead("Users that are bad");
?>
<table class="main" width="750" border="0" cellspacing="0" cellpadding="0"><tr><td class="embedded">
<div align="center">
<h1>Mass mensagem a todos os usuários</a></h1>
<form method="post" action="usuario-ratio-baixo.php?taking=takepm">
<?

if ($_GET["returnto"] || $_SERVER["HTTP_REFERER"])
{
?>
<input type=hidden name=returnto value=<?=$_GET["returnto"] ? $_GET["returnto"] : $_SERVER["HTTP_REFERER"]?>>
<?
}
?>
<table cellspacing=0 cellpadding=5>
<tr>
<td>Enviar Messege missa a todos os usuários<br>
<table style="border: 0" width="100%" cellpadding="0" cellspacing="0">
<tr>
<td style="border: 0">&nbsp;</td>
<td style="border: 0">&nbsp;</td>
</tr>
</table>
</td>
</tr>
<tr><td><textarea name=msg cols=120 rows=15><?=$pmmsg?></textarea></td></tr>
<tr>
<tr><td colspan=2 align=center><input type="submit" value="Send" class="btn"></td></tr>
</table>
<input type="hidden" name="receiver" value=<?=$receiver?>>
</form>

</div></td></tr></table>
<br>
NOTE: No HTML Code Allowed. (NO HTML)
<?
}
end_frame();

?>
<?
stdhead("Coded");

begin_frame("Codigo");
if ($site_config["MEMBERSONLY"] && !$CURUSER) {
	echo "<BR><BR><b><CENTER>You Are Not Logged In<br>Only Members Can View Affiliates Please Login or Signup.</CENTER><BR><BR>";
} else{
?></center>
<center>---<a href="http://br2.in" target="_blank" title="By BR2.in">By Evandro BR2.in</a> ---</center>
<?
}
}
end_frame();
stdfoot();
}
?>