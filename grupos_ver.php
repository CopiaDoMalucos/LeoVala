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
 }
 
 # Possibly Add Caching, Pagination...
 $res = SQL_Query_exec("SELECT teams.id, teams.name, teams.image, teams.image1, teams.image2, teams.info, teams.owner, users.username FROM teams LEFT JOIN users ON teams.owner = users.id WHERE users.enabled = 'yes' AND users.status = 'confirmed'");
     	$online = "";
   	     $datetime = get_date_time(gmtime() - 180);  
                                    
 if (mysql_num_rows($res) == 0)
     show_error_msg("Error", "No teams available, to create a group please contact <a href='staff.php'>staff</a>.", 1);
     
 stdhead("Grupos");
 begin_framec("Grupos");
 
 ///////////echo '<center>Caso queira criar um novo grupo, por favor <a href="staff.php">contate</a> um membro da nossa equipe.</center><br />';
 


 while ($row = mysql_fetch_assoc($res)):
 

	$resultsub = mysql_query("SELECT * FROM users  LEFT JOIN usergroups ON users.id = usergroups.uid WHERE usergroups.gid = $row[id] AND usergroups.status = 'moderadores' AND users.enabled = 'yes' AND users.status = 'confirmed'");  
	 
	 

    $resultsub2 = mysql_query("SELECT * FROM users  LEFT JOIN usergroups ON users.id = usergroups.uid WHERE usergroups.gid = $row[id] AND usergroups.status = 'submoderadores' AND users.enabled = 'yes' AND users.status = 'confirmed'");  
	  


   $resultsub1 = SQL_Query_exec("SELECT * FROM usergroups WHERE uid = ".$CURUSER['id']." AND gid = $row[id]") ;
$row21 = mysql_fetch_array($resultsub1);	

$data2 = date('Y-m-d H:i:s');  
$data21 = date("d/m/Y", utc_to_tz_time($data2));

$datasem = date('Y-m-d H:i:s');  
$datas = date("m/Y", utc_to_tz_time($datasem));  
 ?>

 
 
<table class='tab1' cellpadding='0' cellspacing='1' align='center'>
	<tbody><tr><td align="center" colspan="2" class="tab1_cab1"><?php echo $row["name"]; ?> <?php   if ($row21["status"] == 'moderadores' || $row21["status"] == 'submoderadores' )
{ 
?>
<a href=painel_grupos.php?id=<?php echo"".$row['id']." ;"?>><b><font color=red >[Moderar Grupo]</font></b></a>
<?php
}?>
</td></tr>
	<tr><td align="center" colspan="2" class="tab1_col3"><img src="<?php echo htmlspecialchars($row['image1']) ?>" alt="" title="" /></td></tr>
	<tr><td align="right" width="30%" class="tab1_col3">Membros</td><td width="70%" class="tab1_col3"><?php echo get_row_count("usergroups", "WHERE gid = ".$row['id']."");?></td></tr>
	<tr><td align="right" class="tab1_col3">Torrents lançados</td><td class="tab1_col3"><?php echo get_row_count("torrents", "WHERE owner IN (SELECT id FROM users WHERE team = ".$row['id'].") AND safe = 'yes'"); ?></td></tr>
	<tr><td align="right" class="tab1_col3">Torrents lançados no dia</td><td class="tab1_col3"><?php echo get_row_count("torrents", "WHERE owner IN (SELECT id FROM users WHERE team = ".$row['id'].") AND date_format(torrents.added,'%d/%m/%Y')='$data21' AND safe = 'yes'"); ?></td></tr>
	<tr><td align="right" class="tab1_col3">Torrents lançados no mês</td><td class="tab1_col3"><?php echo get_row_count("torrents", "WHERE owner IN (SELECT id FROM users WHERE team = ".$row['id'].") AND date_format(added,'%m/%Y')='$datas' AND safe = 'yes'"); ?></td></tr>
	<tr><td align="center" colspan="2" class="tab1_col3"><a href="grupos_lancamentos.php?id=<?php  echo $row['id'] ?>">Ver todos os lançamentos do grupo</a></td></tr>
	<tr><td align="center" colspan="2" class="tab1_col3"><a href="grupo_membros.php?id=<?php echo $row['id']  ?>">Ver membros do grupo</a></td></tr>
	
	
	
	<tr><td align="right" class="tab1_col3">Moderador(es) do grupo</td>
	
	<td class="tab1_col3">
	<table cellspacing="0" cellpadding="0" style="width: 200px;" class="tab1"><tbody><tr>
	
	<?php  while ($row1 = mysql_fetch_array($resultsub))
            {
			 $online1 = "<img src='images/button_o".($row1["last_access"] > $datetime ? "n":"ff")."line.png' alt='' >";	
			
			
			?>
	<td width="12" class="tab1_col3" ><?php echo $online1 ;?></td>
		<td width="100" class="tab1_col3" ><?php echo ( $row['username'] ) ? '<a href="account-details.php?id='.$row1['uid'].'">' . $row1['username'] . '</a>' : 'Unknown User'; ?></a></td>
		<td width="18"  class="tab1_col3" ><a href="enviarmp.php?receiver=<?php echo $row1['username'] ;?>"><img border="0" title="Enviar Mensagem Privada" alt="Enviar Mensagem Privada" src="images/button_pm.gif"></a></td></tr>
			<?php  
            }?>
		
		
	
	</tbody></table></td></tr>
	<tr><td align="right" class="tab1_col3">Submoderadores do grupo</td><td class="tab1_col3">
	<table cellspacing="0" cellpadding="0" style="width: 200px;" class="tab1"><tbody>
	
	<tr>
	
			<?php  while ($row2 = mysql_fetch_array($resultsub2))
            {
				$online2 = "<img src='images/button_o".($row2["last_access"] > $datetime ? "n":"ff")."line.png' alt='' >";
			
			?>
	<td width="12" class="tab1_col3" ><?php echo $online2 ;?></td>
		<td width="100" class="tab1_col3" ><?php echo ( $row['username'] ) ? '<a href="account-details.php?id='.$row2['uid'].'">' . $row2['username'] . '</a>' : 'Unknown User'; ?></a></td>
		<td width="18" class="tab1_col3" ><a href="enviarmp.php?receiver=<?php echo $row2['username'] ;?>"><img border="0" title="Enviar Mensagem Privada" alt="Enviar Mensagem Privada" src="images/button_pm.gif"></a></td></tr><tr>
		
			<?php 
            }?>
		
		
		</tbody></table></td></tr></tbody></table>
 
 
 
 <?php

  endwhile;
 end_framec();
 stdfoot();
 
?>