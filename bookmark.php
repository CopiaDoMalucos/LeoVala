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

if (!isset($_GET[user]))
      $user = $CURUSER[id];
else
      $user = $_GET[user];

$res = mysql_query("SELECT username FROM users WHERE id = $user") or sqlerr();
$arr = mysql_fetch_array($res);

stdhead("Bookmarks for " . $arr[username]);

//print ("<h1>Bookmarks for <a href=userdetails.php?id=$user><b>$arr[username]<b></a></h2>");

$res = mysql_query("SELECT COUNT(id) FROM bookmarks WHERE userid = $user");
$row = mysql_fetch_array($res);
$count = $row[0];

if($count == 0){
    show_error_msg("ERROR","O usuário não tem torrent nos favoritos.!",1);
}
list($pagertop, $pagerbottom, $limit) = pager(25, $count, "bookmarks.php?");

$res = mysql_query("SELECT bookmarks.id as bookmarkid, users.username,users.id as owner, torrents.id, torrents.name, torrents.type, torrents.comments, torrents.leechers, torrents.seeders, ROUND(torrents.ratingsum / torrents.numratings) AS rating, categories.name AS cat_name, categories.image AS cat_pic, torrents.save_as, torrents.numfiles, torrents.added, torrents.filename, torrents.size, torrents.views, torrents.visible, torrents.hits, torrents.times_completed, torrents.category FROM bookmarks LEFT JOIN torrents ON bookmarks.torrentid = torrents.id LEFT JOIN users on torrents.owner = users.id LEFT JOIN categories ON torrents.category = categories.id WHERE bookmarks.userid = $user ORDER BY torrents.id DESC $limit")  or sqlerr();

begin_framec("Meus Favoritos");

print($pagertop);
torrenttable($res, "bookmarks", TRUE);
print($pagerbottom);

end_framec();
stdfoot();

?>