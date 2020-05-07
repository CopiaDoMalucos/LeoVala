<?php
############################################################
#######                                             ########
#######                                             ########
#######           malucos-share.org 2.0             ########
#######                                             ########
#######                                             ########
############################################################
//MYSQL CONNECTION INFO, DONT PASS IT OUT!
header("Content-Type: text/html; charset=utf-8");
//Access Security check
if (preg_match('/mysql.php/i',$_SERVER['PHP_SELF'])) {
	die;
}

//Change the settings below to match your MYSQL server connection settings
$mysql_host = "localhost";  //leave this as localhost if you are unsure
$mysql_user = "fastshr_bd";  //Username to connect
$mysql_pass = "ariglr231282"; //Password to connect
$mysql_db = "fastshr_bd";  //Database name

?>
