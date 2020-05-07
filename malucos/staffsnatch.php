<?php
require "backend/functions.php";
dbconn();
loggedinonly();

if($CURUSER['class'] < 5) die(); //Class 5 = moderator

stdhead("Hit N Runs");
begin_framec("Search HnR");
 ?>
 <p>This hit 'n' run list currently displays all suspected hit n runs for the past 24 hours only, if you want to see a list of hit 'n' runs for a longer period of time please enter the number of days to display in the form below.</p>
 <p>
  <form method="get" action="staffsnatch.php">
   Search for: <input type="text" size="3" name="days" /> days &nbsp;&nbsp; <input type="submit" value="Search" />
  </form>
 </p>
 <p>Welcome:<br /><b><font color=red>Please remeber these are SUSPECTED hit n runs, the system is not 100% foolproof and it is up to you to decide if the user deserves to be banned or warned. On my own site i do not warn VIP's easily and users with a generally good seed back ratio i leave alone, One thing i always say is Hit N Runs are GOOD in moderation and they keep the site alive. Use a few brain cells before giving out warnings and you shouldnt get any complaints!</font></b>
 <?php
end_framec();
begin_framec("Suspected Hit N Runs");
echo "<p>This table displays all users who have not returned to complete seeding to a good ratio for 3 hours. Interpret the data as you want</p>";
$lastact = time()-10800;
$search = time()-(isset($_GET['days']) && (0+$_GET['days']) > 0 ? (0+$_GET['days'])*86400 : 86400);

$res = mysql_query("SELECT snatched.*, torrents.name, users.username FROM snatched LEFT JOIN torrents ON snatched.torrentid = torrents.id LEFT JOIN users ON snatched.userid = users.id WHERE finished='yes' AND snatched.uploaded < snatched.downloaded/2 AND snatched.last_action < FROM_UNIXTIME($lastact) AND snatched.last_action > FROM_UNIXTIME($search)") or sqlerr(__FILE__,__LINE__);
if(mysql_num_rows($res) == 0)
 echo "<p>No Hit N Runs Were Found</p>";
else {
 echo "<p>The search returned ".mysql_num_rows($res)." possible Hit N Runs";
 echo "<table cellpadding=2 cellspacing=0 class=ttable_headinner>";
 echo "<tr><td class=ttable_head>Username</td><td class=ttable_head>Torrent</td><td class=ttable_head>Downloaded</td><td class=ttable_head>Uploaded</td><td class=ttable_head>Ratio</td><td class=ttable_head>Last Seed</td></tr>";
 $x=1;
 while($arr = mysql_fetch_assoc($res)) 
  {
  $last_action = strtotime($arr['last_action']);
  $timediff = time()-$last_action;
  $last_act = secondsToWords($timediff);
   echo "<tr><td class=ttable_col$x><a href='account-details.php?id=".$arr['userid']."'>".htmlspecialchars($arr['username'])."</a></td>".
                "<td class=ttable_col$x><a href='torrents-details.php?id=".$arr['torrentid']."'>".htmlspecialchars($arr['name'])."</a></td>".
                "<td class=ttable_col$x>".mksize($arr['downloaded'])."</td><td class=ttable_col$x>".mksize($arr['uploaded'])."</td>".
                "<td class=ttable_col$x>".($arr['downloaded'] > 0 ? round(($arr['uploaded']/$arr['downloaded']),2) : "---")."</td>".
                "<td class=ttable_col$x>$last_act</td></tr>";
  $x=($x=1 ? 2 : 1);
  }
 echo "</table>";
}

end_framec();
stdfoot();
?>