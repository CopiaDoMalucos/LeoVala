<?php
############################################################
#######                                             ########
#######                                             ########
#######           www.brshares.com 2.0              ########
#######                                             ########
#######                                             ########
############################################################
require_once("backend/functions.php");
require_once("backend/bbcode.php");
dbconn();
$count = get_row_count("comments");
    
    list($pagertop, $pagerbottom, $limit) = pager(15, $count, "admincp.php?action=lastcomm&amp;");
                 
	stdhead("Histórico de comentários");
   begin_framec("Histórico de comentários: " . $CURUSER["username"]);
	$res = SQL_Query_exec("SELECT c.id, c.text, c.user, c.torrent, c.news, t.name, n.title, u.username, c.added FROM comments c LEFT JOIN torrents t ON c.torrent = t.id LEFT JOIN news n ON c.news = n.newid LEFT JOIN users u ON c.user = u.id WHERE c.user = '".$CURUSER["id"]."' ORDER BY c.added DESC $limit");
    
	while ($arr = mysql_fetch_assoc($res)) {
		$userid = $arr["user"];
		$username = $arr["unome"];
		$data = $arr["added"];
		$tid = $arr["torrent"];
        $nid = $arr["news"];
		$title = ( $arr['title'] ) ? $arr['title'] : $arr['name'];
		$comentario = stripslashes(format_comment($arr["text"]));
		$cid = $arr["id"];    
        
        $type = '<a href="torrents-details.php?id='.$tid.'">'.$title.'</a>';
        
    
    
             $type1 = '<a href="comments.php?type=torrent&id='.$tid.'">'.$tid.'</a>';
     
                       
		
        $rows[] = $arr;

   ?>
 <table cellpadding="5" cellspacing="3" class="table_table" width="100%" align="center">  
<div align="justify" class="framecentro"><br><tbody><tr><td class="ttable_col1"><b>Torrent: </b><?php echo  $type; ?><br><b>Comentário: </b><?php echo  $type1; ?><br><b>Postado em: </b><?php echo date("d-m-Y H:i:s", utc_to_tz_time($arr["added"]));?></td></tr>
<tr valign="top"><td class="ttable_col2"><?php echo $comentario; ?></td></tr></tbody>
</div>
   </table>
   <?php


		}
    

    
	end_framec();
	stdfoot();
?>