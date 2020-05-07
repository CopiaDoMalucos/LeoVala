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
 
 $id = (int) $_GET["id"];
 

 
 # Possibly Add Caching, Pagination...
   $res = SQL_Query_exec("SELECT users.id, users.username, teams.name, teams.id AS teamid FROM users LEFT JOIN teams ON users.team = teams.id WHERE teams.id = '$id' ");
	$online = "";
   	     $datetime = get_date_time(gmtime() - 180);   
	 
 if (mysql_num_rows($res) == 0)
     show_error_msg("Error", "No teams available, to create a group please contact <a href='staff.php'>staff</a>.", 1);
     
	       $row = mysql_fetch_assoc($res);
 stdhead("Membros do grupo");
 begin_framec("Membros do grupo");





		 
 $resultsub = mysql_query("SELECT * FROM usergroups LEFT JOIN users ON usergroups.uid = users.id WHERE usergroups.gid = $row[teamid] AND usergroups.status = 'moderadores' AND users.enabled = 'yes' AND users.status = 'confirmed'");    

	$resultsub2 = mysql_query("SELECT * FROM usergroups LEFT JOIN users ON usergroups.uid = users.id WHERE usergroups.gid = $row[teamid] AND usergroups.status = 'submoderadores' AND users.enabled = 'yes' AND users.status = 'confirmed'");    


	  $resultsubm = mysql_query("SELECT * FROM usergroups LEFT JOIN users ON usergroups.uid = users.id WHERE usergroups.gid = $row[teamid] AND usergroups.status = 'membrogrupo' AND users.enabled = 'yes' AND users.status = 'confirmed'");    

	     
		

		     
	  

 ?>

 
 
 
 <table align="center" width="100%" cellspacing="1" cellpadding="0" class="tab1"><tbody><tr><td align="center" colspan="3" class="tab1_cab1">Membros do grupo - <?php echo $row["name"]; ?></td></tr><tr><td align="center" colspan="3" class="ttable_head">Moderadores</td></tr>
 <?php  while ($row1 = mysql_fetch_array($resultsub))
 
            {
			
			 $online1 = "<img src='images/button_o".($row1["last_access"] > $datetime ? "n":"ff")."line.png' alt='' >";	
			?>
 <tr>
 <td width="12" class="tab1_col3"><?php echo $online1 ;?></td>
	<td class="tab1_col3"><?php echo ( $row['username'] ) ? '<a href="account-details.php?id='.$row1['uid'].'">' . $row1['username'] . '</a>' : 'Unknown User'; ?></td>
	<td width="18" class="tab1_col3"><a href="mailbox.php?Escrever&amp;id=<?php echo $row1['uid'] ;?>"><img border="0" title="Enviar Mensagem Privada" alt="Enviar Mensagem Privada" src="images/button_pm.gif"></a></td></tr>
	<?php  
            }?>
		
		
	<tr><td align="center" colspan="3" class="ttable_head">Submoderadores</td></tr>
		<?php  while ($row2 = mysql_fetch_array($resultsub2))
	
            {
					$online2 = "<img src='images/button_o".($row2["last_access"] > $datetime ? "n":"ff")."line.png' alt='' >";
			
			
			
			?>
	<tr><td width="12" class="tab1_col3"><?php echo $online2 ;?></td>
	<td class="tab1_col3"><?php echo ( $row['username'] ) ? '<a href="account-details.php?id='.$row2['uid'].'">' . $row2['username'] . '</a>' : 'Unknown User'; ?></td>
	<td width="18" class="tab1_col3"><a href="mailbox.php?Escrever&amp;id=<?php echo $row2['uid'] ;?>"><img border="0" title="Enviar Mensagem Privada" alt="Enviar Mensagem Privada" src="images/button_pm.gif"></a></td></tr>
	
		<?php  
            }?>
	
	
	
	
	<tr>

	</tr><tr><td align="center" colspan="3" class="ttable_head">Membros</td></tr><tr>
		<?php  while ($row3 = mysql_fetch_array($resultsubm))
            {
			$online3 = "<img src='images/button_o".($row3["last_access"] > $datetime ? "n":"ff")."line.png' alt='' >";
			?>
	<td width="12" class="tab1_col3"><?php echo $online3 ;?></td>
	<td class="tab1_col3"><?php echo ( $row['username'] ) ? '<a href="account-details.php?id='.$row3['uid'].'">' . $row3['username'] . '</a>' : 'Unknown User'; ?></td>
	<td width="18" class="tab1_col3"><a href="mailbox.php?Escrever&amp;id=<?php echo $row3['uid'] ;?>"><img border="0" title="Enviar Mensagem Privada" alt="Enviar Mensagem Privada" src="images/button_pm.gif"></a></td></tr>
	
		<?php 
            }?>
	
</tr></tbody></table>
 
 
 <?php


 end_framec();
 stdfoot();
 
?>