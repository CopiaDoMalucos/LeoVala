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

function bark($msg) {
stdhead();
show_error_msg("Invitation failed!", $msg);
stdfoot();
exit;
}

$id = 0 + $_GET["id"];

if ($id == 0){
$id = $CURUSER["id"];
}
if (get_user_class() <= 6)
$id = $CURUSER["id"];


$re = mysql_query("SELECT invites FROM users WHERE id = $id") or sqlerr();
$tes = mysql_fetch_assoc($re);
if ($tes[invites] <= 0)
show_error_msg("You have no invites left!");





$ret = mysql_query("SELECT username FROM users WHERE id = $id") or sqlerr();
$arr = mysql_fetch_assoc($ret);


$hash = md5(mt_rand(1,1000000));

mysql_query("INSERT INTO invites (inviter, invite, time_invited) VALUES ('$id', '$hash', '" . get_date_time() . "')");
mysql_query("UPDATE users SET invites = invites - 1 WHERE id = $id") or sqlerr(__FILE__, __LINE__);

header("Refresh: 0; url=invite.php?id=$id");


?>
