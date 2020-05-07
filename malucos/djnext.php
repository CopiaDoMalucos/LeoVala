<?php
############################################################
#######                                             ########
#######                                             ########
#######           malucos-share.org 2.0             ########
#######                                             ########
#######                                             ########
############################################################

require_once("backend/functions.php");
dbconn();

echo("<center><font <font size='1' color='red'><b>On Next</b><br><br>");

$day = date('N');
$query = "SELECT  * FROM users WHERE day = '$day' AND startime >= DATE_ADD(NOW(), INTERVAL 4 HOUR) AND endtime > DATE_ADD(NOW(), INTERVAL 4 HOUR) ORDER BY startime ASC LIMIT 1";

$sql = mysql_query($query);
$num = mysql_num_rows($sql);

if($num == '0') {

echo ("<b><meta http-equiv='refresh' content='5'><center><img src='/images/djdefault.jpg'><br><br><font color='white'>Not ON Air</b>"); 

}else{

while ($row = mysql_fetch_array($sql)) {
$avatar = htmlspecialchars($row['avatar']);
$username = htmlspecialchars($row['username']);
$today = date('l');
$genre = $row['genre'];
$startime = $row['startime'];
$endtime = $row['endtime'];
echo("<meta http-equiv='refresh' content='5'><center><img src='$avatar'><br><br><font size='1' font color='white'><b>$username</b><br><b>$genre</b><br><b>$startime</b> - <b>$endtime</b><br><b>$today</b></center>");

}
}


?>