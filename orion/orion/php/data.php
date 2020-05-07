<?php
############################################################
#######                                             ########
#######                                             ########
#######           malucos-share.org 2.0             ########
#######                                             ########
#######                                             ########
############################################################
require_once("backend/functions.php");
dbconn(false);
loggedinonly();
$data2 = date('Y-m-d H:i:s');  
$data = date("d/m/Y", utc_to_tz_time($data2));  
echo $data

?>