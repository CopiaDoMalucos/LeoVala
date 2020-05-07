<?php
require("backend/functions.php");
dbconn();
loggedinonly();
stdhead("Userstats Image");
$SITEURL = $site_config["SITEURL"];
$SITENAME = $site_config["SITENAME"];


echo "<table align=center>";
echo "<div align=center><a href=$SITEURL/userstats/$CURUSER[id].png><img src=$SITEURL/userstats/$CURUSER[id].png></a></div><hr>";
echo "<tr>
<td><div align='center'><br>
Copy BB Code:<br>
<textarea cols='90' rows='2'>[url=$SITEURL][img]$SITEURL/userstats/$CURUSER[id].png[/img][/url]</textarea>
</div></td></tr><tr>
<td><div align='center'><br>
Copy HTML:<br>
<textarea cols='90' rows='2'><a href='$SITEURL'><img src='$SITEURL/userstats/$CURUSER[id].png' title='$SITENAME' border=0></a></textarea>
</div></td>
</tr></table>";
stdfoot();
?>