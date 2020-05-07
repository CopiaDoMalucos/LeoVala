<?php
   

   require_once("backend/functions.php");
   dbconn();
   loggedinonly();
   
if($CURUSER["class"]< 84)
       show_error_msg("Error", "Desculpe esta página é para o pessoal da staff.", 1);

   $id = (int) $_GET["id"];
   
   $res = mysql_query("SELECT `username` FROM `users` WHERE `id` = '$id' AND `enabled` = 'yes' AND `status` = 'confirmed'");
   $row = mysql_fetch_assoc($res);
   
   if (!$row)
        show_error_msg("Error", "Invalid UserId.", 1);
   
   $count = get_row_count("forum_posts", "WHERE `userid` = '$id'");

   list($pagertop, $pagerbottom, $limit) = pager(25, $count, "forumhistory.php?id=$id&");
   
   $res = mysql_query("SELECT * FROM `forum_posts` WHERE `userid` = '$id' ORDER BY `added` DESC $limit");
   if (mysql_num_rows($res) == 0)
       show_error_msg("Ops", "Este membro não possui nenhum post em fóruns.", 1);

   stdhead("ForumHistory");
   begin_framec("Histórico de forúm  " . $row["username"]);
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
<?php
   
   ?>
   
   <table cellpadding="5" cellspacing="3" class="table_table" width="100%" align="center">
   <tr>
       <th class="table_head">Tópico</th>
       <th class="table_head">Add</th>
       <th class="table_head">Mensagem</th>
       <th class="table_head">Última Edição</th>
       <th class="table_head">Por</th>
   </tr>
   <?php while ($row = mysql_fetch_assoc($res)): ?>
   
   <?php 
         
         $thetopic = mysql_fetch_assoc(mysql_query("SELECT `id`, `subject` FROM `forum_topics` WHERE `id` = " . $row["topicid"]));  
         $thetopic = ($thetopic) ? "<a href='forums.php?action=viewtopic&topicid=".$thetopic["id"]."'>".$thetopic["subject"]."</a>" : "Unknown Topic";
       
         $editedat = ($row["editedat"] == "0000-00-00 00:00:00") ? "-" : utc_to_tz($row["editedat"]); 
        
         $editedby = mysql_fetch_assoc(mysql_query("SELECT `id`, `username` FROM `users` WHERE `id` = " . $row["editedby"]));
         $editedby = ($editedby) ? "<a href='account-details.php?id=".$editedby["id"]."'>".$editedby["username"]."</a>" : "-";
   ?>
   <tr>
       <td class="table_col1"><?php echo $thetopic; ?></td>
       <td class="table_col2"><?php echo utc_to_tz($row["added"]); ?></td>
       <td class="table_col1"><?php echo format_comment($row["body"]); ?></td>
       <td class="table_col2"><?php echo $editedat; ?></td>
       <td class="table_col1"><?php echo $editedby; ?></td>
   </tr>
   <?php endwhile; ?>
   </table>
   
   <?php
   
   echo($pagerbottom); 
    
   end_framec();
   stdfoot();
   
?>