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
                     
  if ($site_config["MEMBERSONLY"]) {
      loggedinonly();
      
      if ($CURUSER["view_torrents"] == "no")
          show_error_msg(T_("ERROR"), T_("NO_TORRENT_VIEW"), 1);
  }
                  
  $id = (int) $_GET["id"];
	stdhead("Arquivos do torrent");

begin_framec("Arquivos do torrent");
  ?>

  
  <p align="center"><a href="torrents-details.php?id=<?php echo $id; ?>"><?php echo 'Voltar para a página do Torrent' ?></a></p>
  
  <?php
 echo "<BR> <table border='1' cellpadding='5' cellspacing='2' align='center' width='100%' class='ttable_headinner'><TR><TD class=ttable_head align=left>&nbsp;".T_("FILE")."</TD><TD width=50 class=ttable_head>&nbsp;".T_("SIZE")."</td></tr>";
$fres = SQL_Query_exec("SELECT * FROM `files` WHERE `torrent` = $id");
if (mysql_num_rows($fres)) {
    while ($frow = mysql_fetch_array($fres)) {
        echo "<TR><td class=ttable_col2>".htmlspecialchars($frow['path'])."</td><TD class=ttable_col2>".mksize($frow['filesize'])."</td></tr>";
    }
}else{
    echo "<TR><td class=ttable_col2>".htmlspecialchars($row["name"])."</td><TD class=ttable_col2>".mksize($row["size"])."</td></tr>";
}
echo "</table></div>";




  ?>

  
  <p align="center"><a href="torrents-details.php?id=<?php echo $id; ?>"><?php echo 'Voltar para a página do Torrent' ?></a></p>
  
  <?php

  end_framec();
  stdfoot();
  
?>