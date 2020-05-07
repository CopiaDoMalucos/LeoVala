<?php
 ############################################################
#######                                             ########
#######                                             ########
#######           malucos-share.org 2.0             ########
#######                                             ########
#######                                             ########
############################################################

 require_once("backend/functions.php"); 
dbconn(true);




loggedinonly();  
stdhead("Faturas online");

 




begin_framec("Faturas online");

 print("<br /><center><a href='http://www.malucos-share.org/forums.php?action=viewtopic&topicid=1223'>ABRIL/2013</a></center><br />");
 print("<br /><center><a href=' http://www.malucos-share.org/forums.php?action=viewtopic&topicid=1314'>MAIO/2013</a></center><br />");
 print("<br /><center><a href=' http://www.malucos-share.org/forums.php?action=viewtopic&topicid=1422&page=last'>junho/2013</a></center><br />");

end_framec();
stdfoot();
?>