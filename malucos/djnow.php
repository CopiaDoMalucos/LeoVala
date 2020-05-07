<?php
############################################################
#######                                             ########
#######                                             ########
#######           malucos-share.org 2.0             ########
#######                                             ########
#######                                             ########
############################################################
?>
<div  align="center"  >
<a href="JavaScript:location.reload(true);"> 
<img src='/images/refresh.png'>
</a> 
	</div>
<?

require_once("backend/functions.php");
dbconn();




$ano = date("y", utc_to_tz_time($row['date']));
$day = date("M", utc_to_tz_time($row['date']));


 date_default_timezone_set('Etc/GMT+3');
$hora = date('H:i:s');

$query = "SELECT  * FROM dj WHERE day = '$day' AND startime < '$hora' AND endtime > '$hora' ORDER BY startime ASC LIMIT 1";

$sql = mysql_query($query);
$num = mysql_num_rows($sql);

if($num == '0') {

echo ("<b><center><img src='/style/images/down.gif'><br><br><font color='white'></b>");

}else{

while ($row = mysql_fetch_array($sql)) {
$avatar = $row['image'];
$username = $row['username'];
$today = date('l');
$genre = $row['genre'];
$startime = $row['startime'];
$endtime = $row['endtime'];
echo("<center><img src='$avatar'><br><br><font size='1' font color='white'><b>$username</b><br><br><b>$startime</b> AS <b>$endtime</b><br></center>");
 echo "hora: $ano";

}
}
echo "hora: $ano";
echo "<br><br><br></br></br></br>";




?>