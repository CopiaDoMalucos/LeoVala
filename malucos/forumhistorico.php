<?php
   

   require_once("backend/functions.php");
   dbconn();
   loggedinonly();
   


   
   $res = mysql_query("SELECT `username` FROM `users` WHERE `id` = '$CURUSER[id]' AND `enabled` = 'yes' AND `status` = 'confirmed'");
   $row = mysql_fetch_assoc($res);
   
   if (!$row)
        show_error_msg("Error", "Invalid UserId.", 1);
   
   $count = get_row_count("forum_posts", "WHERE `userid` = '$CURUSER[id]'");



   
   $res = mysql_query("SELECT * FROM `forum_posts` WHERE `userid` = '$CURUSER[id]' ORDER BY `added` DESC   LIMIT 15");
   if (mysql_num_rows($res) == 0)
       show_error_msg("Ops", "Este membro não possui nenhum post em fóruns.", 1);

   stdhead("ForumHistory");
   begin_framec("Histórico de forúm de " . $row["username"]);
   		?>
<script type="text/javascript" src="ncodeforum_imageresizer.js"></script>
<script type="text/javascript">
<!--
NcodeImageResizer.MODE = 'newwindow';
NcodeImageResizer.MAXWIDTH = 430;
NcodeImageResizer.MAXHEIGHT = 0;

NcodeImageResizer.Msg1 = 'Clique aqui para ampliar a imagem.';
NcodeImageResizer.Msg2 = 'Clique aqui para ampliar a imagem.';
NcodeImageResizer.Msg3 = 'Clique aqui para ampliar a imagem.';
NcodeImageResizer.Msg4 = 'Clique aqui para ampliar a imagem.';
//-->
</script>
   <?php while ($row = mysql_fetch_assoc($res)): ?>
   
   <?php 
         
         $thetopic = mysql_fetch_assoc(mysql_query("SELECT `id`, `subject`, `forumid` FROM `forum_topics` WHERE `id` = " . $row["topicid"]));  
         $thetopic = ($thetopic) ? "<a href='forums.php?action=viewtopic&topicid=".$thetopic["id"]."'>".$thetopic["subject"]."</a>" : "Unknown Topic";
       

	         $thetopic1 = mysql_query("SELECT id, subject, forumid    FROM forum_topics WHERE id=".$row["topicid"]."") ;
	       $thetopic12 = mysql_fetch_assoc($thetopic1);
	   
	   
	      $thetopic1 = mysql_query("SELECT id, subject, forumid    FROM forum_topics WHERE id=".$row["topicid"]."") ;
	       $thetopic12 = mysql_fetch_assoc($thetopic1);
	   
	      $res1 = mysql_query("SELECT name FROM forum_forums WHERE id=".$thetopic12["forumid"]."") ;
$forum = mysql_fetch_assoc($res1);

	   $forumname = "<a href=/forums.php?action=viewforum&forumid=".$thetopic12["forumid"]."><b>" . htmlspecialchars($forum["name"]) . "</b></a>";
	   

$forumpost = "<a href=/forums.php?action=viewtopic&topicid=".$thetopic12["id"]."&page=p".$row["id"]."#".$row["id"]."><b>" . htmlspecialchars($row["id"]) . "</b></a>";
         $editedat = ($row["editedat"] == "0000-00-00 00:00:00") ? "-" : utc_to_tz($row["editedat"]); 
        
         $editedby = mysql_fetch_assoc(mysql_query("SELECT `id`, `username` FROM `users` WHERE `id` = " . $row["editedby"]));
         $editedby = ($editedby) ? "<a href='account-details.php?id=".$editedby["id"]."'>".$editedby["username"]."</a>" : "-";
   ?>
 <table cellpadding="5" cellspacing="3" class="table_table" width="100%" align="center">  
<div align="justify" class="framecentro"><br><tbody><tr><td class="ttable_col1"><b>Fórum: </b><?php echo  $forumname; ?><br><b>Tópico: </b><?php echo $thetopic; ?><br><b>Post: </b><?php echo  $forumpost; ?><br>Postado <?php echo date("d-m-Y H:i:s", utc_to_tz_time($row["added"]));?></td></tr>
<tr valign="top"><td class="ttable_col2"><?php echo format_comment($row["body"]); ?></td></tr></tbody>
</div>
   <?php endwhile; ?>
   </table>
   <br>
   <br>
   <br>
   <?php   

    
   end_framec();
   stdfoot();
   
?>