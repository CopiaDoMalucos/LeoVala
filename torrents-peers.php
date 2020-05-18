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
                     
  if ($site_config["MEMBERSONLY"]) {
      loggedinonly();
      
      if ($CURUSER["view_torrents"] == "no")
          show_error_msg(T_("ERROR"), T_("NO_TORRENT_VIEW"), 1);
  }
                  
  $id = (int) $_GET["id"];
    $res = mysql_query("SELECT torrents.anon, torrents.seeders, torrents.banned, torrents.leechers, torrents.info_hash, torrents.filename, torrents.nfo, torrents.last_action, torrents.numratings, torrents.name, torrents.owner, torrents.save_as, torrents.descr, torrents.visible, torrents.size, torrents.added, torrents.views, torrents.hits, torrents.times_completed, torrents.id, torrents.type, torrents.external, torrents.image1, torrents.image2, torrents.announce, torrents.numfiles, torrents.freeleech, IF(torrents.numratings < 2, NULL, ROUND(torrents.ratingsum / torrents.numratings, 1)) AS rating, torrents.numratings, categories.name AS cat_name, torrentlang.name AS lang_name, torrentlang.image AS lang_image, categories.parent_cat as cat_parent, users.username, users.privacy FROM torrents LEFT JOIN categories ON torrents.category = categories.id LEFT JOIN torrentlang ON torrents.torrentlang = torrentlang.id LEFT JOIN users ON torrents.owner = users.id WHERE torrents.id = $id");
$row = mysql_fetch_assoc($res);
	stdhead("Peers");
	$char1 = 50; //cut length
$shortname = CutName(htmlspecialchars($row["name"]), $char1);
begin_framec(("Peers conectados ao torrent"). " \"" . $shortname . "\"");

  ?>

  
 <br> <p align="center"><a href="torrents-details.php?id=<?php echo $id; ?>"><?php echo 'Voltar para a página do Torrent' ?></a></p>
  
  <?php



		
	    $res2 = mysql_query("SELECT COUNT(*) FROM peers WHERE torrent = $id ORDER BY seeder DESC");
    $row2 = mysql_fetch_row($res2);
    $postcount = $row2[0];	
		
 $page = $_GET["page"];

$postsperpage = 20;
    //------ Make page menu
    $pagemenu = "<br /><span class='small'>\n";
    $perpage = $postsperpage;
    $pages = floor($postcount / $perpage);
    if ($pages * $perpage != $postcount)
		++$pages;
    if ($page == "last")
		$page = $pages;
    else {
		if($page < 1)
			$page = 1;
		elseif ($page > $pages)
			$page = $pages;
    }
    $offset = $page * $perpage - $perpage;
	//
    if ($page == 1)
      $pagemenu .= "<b>&lt;&lt; Anterior</b>";
    else
      $pagemenu .= "<a href='torrents-peers.php?id=$id&amp;page=" . ($page - 1) . "'><b>&lt;&lt; Anterior</b></a>";
	//
	$pagemenu .= "&nbsp;&nbsp;";
	    for ($i = 1; $i <= $pages; ++$i) {
      if ($i == $page)
        $pagemenu .= "<b>$i</b>\n";
      else
        $pagemenu .= "<a href='torrents-peers.php?id=$id&amp;page=$i'><b>$i</b></a>\n";
    }
	//
    $pagemenu .= "&nbsp;&nbsp;";
    if ($page == $pages)
      $pagemenu .= "<b>Próximo &gt;&gt;</b><br /><br />\n";
    else
      $pagemenu .= "<a href='torrents-peers.php?id=$id&amp;page=" . ($page + 1) . "'><b>Próximo &gt;&gt;</b></a><br /><br />\n";
    $pagemenu .= "</span>";


	
$query = mysql_query("SELECT * FROM peers WHERE torrent = $id  LIMIT $offset,$perpage");
$numpeer = get_row_count("peers", "WHERE torrent = $id");
	$result = mysql_num_rows($query);
		if($result == 0) {
			echo T_("NO_ACTIVE_PEERS")."\n";
		}else{
				print("<center> ".$pagemenu."</center> ");
			?>

            <table class='tab1' cellpadding='0' cellspacing='1' align='center' width="100%" border="0" >
			
			<tr>

			    <th   align="right"  class="tab1_cab1"><center>Andamento</center></th>
			    <th   align="right"  class="tab1_cab1"><center>Conectável</center></th>
			    <th   align="right"  class="tab1_cab1"><center>Cliente</center></th>

			</tr>

			<?php
			while($row1 = mysql_fetch_array($query))	{
				
				if ($row1["downloaded"] > 0){
					$ratio = $row1["uploaded"] / $row1["downloaded"];
					$ratio = number_format($ratio, 3);
				}else{
					$ratio = "---";
				}

				$percentcomp = sprintf("%.2f", 100 * (1 - ($row1["to_go"] / $row["size"])));    

				if ($site_config["MEMBERSONLY"]) {
					$res = SQL_Query_exec("SELECT id, username, privacy FROM users WHERE id=".$row1["userid"]."");
					$arr = mysql_fetch_array($res);
                    
                    $arr["username"] = "<a href='account-details.php?id=$arr[id]'>$arr[username]</a>";
				}
                
				
				  if ($row1["connectable"] == 'yes' ){
				  $connectable = 'Sim';
				  }else
				  {
				   $connectable = 'Não';
				  }
				  
                # With $site_config["MEMBERSONLY"] off this will be shown.
                if ( !$arr["username"] ) $arr["username"] = "Unknown User";
        
			
					print("<tr><td class='tab1_col3'><center>".$percentcomp."%</center></td><td class='tab1_col3'><center> ".$connectable."</center></td><td class='tab1_col3'><center>".htmlspecialchars($row1["client"])."</center></td></tr>");
			
}
			print("<tr><td class='tab1_col3' colspan='3'><center>Total de peer<br>".$numpeer."</center></td></tr>");
			echo "</table>";
			
			print("<center> ".$pagemenu."</center> ");
	}




  ?>
<BR>
    <p align="center"><a href="torrents-details.php?id=<?php echo $id; ?>"><?php echo 'Voltar para a página do Torrent' ?></a></p>

  
  <?php

  end_framec();
  stdfoot();
  
?>