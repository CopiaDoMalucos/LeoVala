<?php
############################################################
#######                                             ########
#######                                             ########
#######           www.brshares.com 2.0              ########
#######                                             ########
#######                                             ########
#############################################################
  
  require_once("backend/functions.php");
  dbconn();
  
  stdhead( T_("SITE_RULES") );
  
  $res = SQL_Query_exec("SELECT * FROM `rules` ORDER BY `id`");
  while ($row = mysql_fetch_assoc($res))
  {
      if ($row["public"] == "yes")
      {
          begin_framec($row["title"]);
          echo format_comment($row["text"]); 
          end_framec();
      }
      else if ($row["public"] == "no" && $row["class"] <= $CURUSER["class"])
      {
          begin_framec($row["title"]);
          echo format_comment($row["text"]);
          end_framec();
      }
  }
  
  stdfoot();

?>