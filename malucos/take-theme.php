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
 loggedinonly();
  if ( is_valid_id($_POST['msgid']) )
 {
                  SQL_Query_exec("UPDATE `messages` SET `unread` = 'no' WHERE `receiver` = " . $CURUSER["id"] . " AND `id` = " . $_POST["msgid"]);
                  die;
 }
 $updateset = array();

 $stylesheet = $_POST['stylesheet'];
 $language = $_POST['language'];

 if (is_valid_id($stylesheet))
         $updateset[] = "stylesheet = '$stylesheet'";
 if (is_valid_id($language))
         $updateset[] = "language = '$language'";

 if (count($updateset))
         SQL_Query_exec("UPDATE `users` SET " . implode(', ', $updateset) . " WHERE `id` = " . $CURUSER["id"]);

 if (isset($_SERVER["HTTP_REFERER"]))
 {
         header("Location: {$_SERVER["HTTP_REFERER"]}"); 
         return;
 }       

 header("Location: index.php"); 

?>