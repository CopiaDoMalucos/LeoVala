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

$gottorrent = (int)$_GET["torrent"];

if (!isset($gottorrent))
      show_error_msg("Error"," ... Nenhum torrent selecionado",1);

if ((get_row_count("bookmarks", "WHERE userid=$CURUSER[id] AND torrentid = $gottorrent")) > 0)
      show_error_msg("Error","Já marcou torrent",1);

if ((get_row_count("torrents", "WHERE id = $gottorrent")) > 0) {
mysql_query("INSERT INTO bookmarks (userid, torrentid) VALUES ($CURUSER[id], $gottorrent)") or die(mysql_error());

stdhead("Bookmarks");
begin_framec ("Com sucesso");
echo "torrent adicionado com Sucesso";
echo "<br /><a href=torrents-details.php?id=$gottorrent>Voltar ao Torrent</a>";
end_framec();
stdfoot();
}
else  show_error_msg("Error","ID não foi encontrado",1);

?>