<?php
require_once("backend/functions.php");
dbconn(true);
loggedinonly();

$delid = (int)$_GET['bookmarkid'];

$res2 = mysql_query ("SELECT id, userid FROM bookmarks WHERE torrentid = $delid AND userid = $CURUSER[id]") or die();

$arr = mysql_fetch_assoc($res2);
if (!$arr)
show_error_msg("Error!","ID not found in your bookmarks list...",1);

mysql_query ("DELETE FROM bookmarks WHERE torrentid = $delid AND userid = $CURUSER[id]") or die();
header("Refresh: 0;url=".$_SERVER['HTTP_REFERER']);
?>