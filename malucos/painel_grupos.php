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
 
 $res = SQL_Query_exec("SELECT teams.id, teams.image, teams.image1, teams.image2, teams.info, teams.owner,  users.username, (SELECT GROUP_CONCAT(id, ' ', username) FROM users WHERE FIND_IN_SET(users.team, teams.id) AND users.enabled = 'yes' AND users.status = 'confirmed' AND users.id != '$CURUSER[id]') AS members FROM teams LEFT JOIN users ON teams.owner = users.id WHERE users.enabled = 'yes' AND users.status = 'confirmed' AND teams.id = '$id'");
 $row = mysql_fetch_assoc($res);
 
      if (mysql_num_rows($res) == 0)
     show_error_msg("Error", "This team does not exist.", 1);
	 
	 
	 
	 


   $resultsub1 = SQL_Query_exec("SELECT * FROM usergroups WHERE uid = '$CURUSER[id]' AND gid = '$id'") ;
$row21 = mysql_fetch_array($resultsub1);	

  

       
 if ($row21["status"] == 'moderadores' || $row21["status"] == 'submoderadores' )
{



  stdhead("Team Panel");
      begin_framec("Painel de grupos");

 $pending = get_row_count("grupoaceita", "LEFT JOIN users ON grupoaceita.iduser = users.id WHERE grupoaceita.simounao = 'no' AND grupoaceita.idteam = $id");
	echo "<center><b>".T_("USERS_AWAITING_VALIDATION").":</b> <a href='grupo_pendentes.php?id=$id'>($pending)</a></center><br />";

  ?>



<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td align="center"><a href="grupo_modera.php?id=<?= $id?>"><img src="images/admin/user_groups.png" border="0" width="32" height="32" alt="" /><br />Moderar grupo</a><br /></td>
<td align="center"><a href="grupo-rank-t.php?id=<?= $id?>"><img src="images/stats_rank.png" border="0" width="32" height="32" alt="" /><br />Rank torrents</a><br /></td>
<td align="center"><a href="grupo-rank-u.php?id=<?= $id?>"><img src="images/stats.rank.u.png" border="0" width="32" height="32" alt="" /><br />Rank membros</a><br /></td>
<td align="center"><a href="grupo-rank-p.php?id=<?= $id?>"><img src="images/gift_grupo.png" border="0" width="32" height="32" alt="" /><br />Prêmios</a><br /></td>
	</tr>
<tr>
    <td colspan="5">&nbsp;</td>
</tr>
<tr>
    <td align="center"><a href="grupo_mp.php?id=<?= $id?>"><img src="images/admin/mass_pm.png" border="0" width="32" height="32" alt="" /><br /><?php echo ("Enviar mp para o grupo"); ?></a><br /></td>
	<td align="center"><a href="grupo_capa.php?id=<?= $id?>"><img src="images/admin/mass_pm.png" border="0" width="32" height="32" alt="" /><br /><?php echo ("Capa do grupo"); ?></a><br /></td>
  	<td align="center"><a href="grupo_icone.php?id=<?= $id?>"><img src="images/admin/mass_pm.png" border="0" width="32" height="32" alt="" /><br /><?php echo ("Icone do grupo"); ?></a><br /></td>
  	<td align="center"><a href="grupo_banner.php?id=<?= $id?>"><img src="images/admin/mass_pm.png" border="0" width="32" height="32" alt="" /><br /><?php echo ("Banner do grupo"); ?></a><br /></td>
	</tr>
<tr>
    <td colspan="5">&nbsp;</td>
</tr>








</table>
 <?php
 

end_framec();
}
 else{
      show_error_msg("Error", "Você não tem permissão para isso.", 1);
 }



 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 stdfoot();
 
?>