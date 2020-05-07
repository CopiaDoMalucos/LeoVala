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

stdhead(T_("FAQ"));

begin_framec("Torrents Aprovação");
$semmod = mysql_query("SELECT * FROM torrents WHERE adota = '0' LIMIT 10 ") or sqlerr();	
	
while($ressemmod = mysql_fetch_array($semmod)){



echo "".$ressemmod['id']." ".$ressemmod['name']."";

	///mysql_query("UPDATE torrents SET adota='0'") or die(mysql_error());

	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}

   end_framec();
  
 




stdfoot();
?>
