<?php
############################################################
#######                                             ########
#######                                             ########
#######           www.brshares.com 2.0              ########
#######                                             ########
#######                                             ########
############################################################
require_once("backend/functions.php");
dbconn(false);
loggedinonly();


  $id = (int) $_GET["id"];
if (!is_valid_id($id)) die;

$res = mysql_query("SELECT `thanks` FROM torrents WHERE id=$id");
$row = mysql_fetch_assoc($res);
mysql_free_result($res);
if (!$row) show_error_msg("Error", "Lamentamos mas não há, nenhum torrent que foi encontrado com essa identificação, provavelmente ele foi excluído.",1);

$thanksl = explode(",", $row['thanks']);

if (in_array($CURUSER['username'], $thanksl)) show_error_msg("Error", "Você já deixou um agradecimento para este torrent obrigado...",1);

mysql_query(sprintf("UPDATE `torrents` SET thanks = CONCAT_WS(',', `thanks`, %s) WHERE `id`=%d", sqlesc($CURUSER['username']), $id)) or die(mysql_error());

header("Refresh: 3;url=torrents-details.php?id=$id");
show_error_msg("Sucesso  ", "Obrigado pelo incentivo ao uploader.<BR>Redirecionando...", 1);
?>