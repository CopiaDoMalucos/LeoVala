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
  
  $dt = get_date_time(gmtime() - 180);
  
  $res = SQL_Query_exec("SELECT `users`.`id`, `users`.`username`, `users`.`class`, `users`.`last_access` FROM `users` INNER JOIN `groups` ON `users`.`class` = `groups`.`group_id`  WHERE `users`.`enabled` = 'yes' AND `users`.`status` = 'confirmed' AND `users`.`username` != 'masterinfo' AND `groups`.`staff_page` = 'yes' ORDER BY `username`");
  while ($row = mysql_fetch_assoc($res))
  {
      $table[$row["class"]] = $table[$row["class"]] .
        "<tr><td width=2% colspan=3 align=center  class=tab1_col3><img src='images/button_o".($row["last_access"] > $dt ? "n" : "ff")."line.png' alt='' title='Útimo acesso: ".date("d/m/y", utc_to_tz_time($row['last_access']))." às ". date("H:i:s", utc_to_tz_time($row['last_access'])) . "'/></td> ". 
        "<td colspan=3 align=center  class=tab1_col3><a href='account-details.php?id=".$row["id"]."'>".$row["username"]."</a></td> ".       
        "<td width=2% colspan=3 align=center  class=tab1_col3><a href='mailbox.php?Escrever&amp;id=".$row["id"]."'><img src='images/button_pm.gif' border='0' alt='Enviar mp' /></a></td></tr>";
        

  }

  $where = "";

  
  $res = SQL_Query_exec("SELECT `group_id`, `level`, `staff_public` FROM `groups` WHERE `staff_page` = 'yes' $where ORDER BY `staff_sort` DESC");

  if (mysql_num_rows($res) == 0)
      show_error_msg(T_("ERROR"), T_("NO_STAFF_HERE"), 1);
      
  stdhead("Equipe de Site");
  begin_framec("Equipe do Site");
  ?>
<center><font size="2" >Essa é a nossa Equipe Malucos-Share, estamos felizes por sua visita e à disposição para quaisquer dúvidas, basta mandar uma MP!!!</align><br></br></br></font>
<table cellspacing="1" cellpadding="0" align="center" class="tab1"><tbody><tr><td align="center" class="tab1_cab1">
<center>Equipe do Site</center></td></tr></table>
  <table  class='tab1' cellpadding='0' cellspacing='1' align='center' >
  
  <?php while ($row = mysql_fetch_assoc($res)): if ( !isset($table[$row["group_id"]]) ) continue; ?>

  <?php
  print("<tr><td width=100% align=center colspan=10 class=tab1_cab1><b><font  class=tab1_cab1 >".$row["level"] ."</font></tr>");
  ?>

  

  
      <?php echo $table[$row["group_id"]]; ?>
	  
  <?php endwhile; ?>
  
  </table>

 <table  class='tab1' cellpadding='0' cellspacing='1' align='center' ><td align="center" class="tab1_cab1"><center>Best of Seeder</center></td>
<tr><td width=2% colspan=3 align=center  class=tab1_col3><a href='account-details.php?id= <?php echo $CURUSER["id"] ; ?>'><?php echo $CURUSER["username"] ; ?></a><br>O que seria de nós sem você fiel semeador? <img src="images/smilies/grin.gif" alt="" border="0"></td></tr>

  </table>

  
  <?
  end_framec();
  stdfoot();

?>