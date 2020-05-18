<?php
############################################################
#######                                             ########
#######                                             ########
#######           www.brshares.com 2.0              ########
#######                                             ########
#######                                             ########
############################################################

require_once("backend/functions.php");

dbconn();

stdhead("Vote");

begin_framec("" . VOTES . "");

$requestid = (int)$_GET["id"];
$userid = (int)$CURUSER["id"];
$res = SQL_Query_exec("SELECT * FROM kitbarra WHERE requestid=$requestid and userid = $userid") or sqlerr();
$arr = mysql_fetch_assoc($res);



SQL_Query_exec("UPDATE kitpedido SET hits = hits + 10 WHERE id=$requestid") or sqlerr();
@SQL_Query_exec("INSERT INTO kitbarra VALUES(0, $requestid, $userid)") or sqlerr();

print("<br><p>Voltar para  $requestid</p><p>os<a href=pkitviewrequests.php><b>pedidos</b></a></p><br><br>");


end_framec();

stdfoot();
?>