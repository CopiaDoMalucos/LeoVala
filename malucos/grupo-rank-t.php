<?php
############################################################
#######                                             ########
#######                                             ########
#######           Malucos-share.org 2.0             ########
#######                                             ########
#######                                             ########
############################################################

 require_once("backend/functions.php"); 
 dbconn(); 
 loggedinonly();
 
 $id = (int) $_GET['id'];
 
 $res = mysql_query("SELECT teams.id, teams.image, teams.image1, teams.image2, teams.info, teams.owner,  users.username, (SELECT GROUP_CONCAT(id, ' ', username) FROM users WHERE FIND_IN_SET(users.team, teams.id) AND users.enabled = 'yes' AND users.status = 'confirmed') AS members FROM teams LEFT JOIN users ON teams.owner = users.id WHERE users.enabled = 'yes' AND users.status = 'confirmed' AND teams.id = '$id'");  
 $row = mysql_fetch_assoc($res);
 
  $resultsub1 = SQL_Query_exec("SELECT * FROM usergroups WHERE uid = ".$CURUSER['id']." AND gid = ".$CURUSER['team']."") ;
$row21 = mysql_fetch_array($resultsub1);	
if ($row21["gid"] !=  $id ){
     show_error_msg("Error", "Você não tem acesso a este grupo", 1);
}
  

       
 if ($row21["status"] == 'moderadores' || $row21["status"] == 'submoderadores' )
{ 
      
 $uploader = array();
 foreach ( explode(',', $row['members']) as $member ): $member = explode(' ', $member);
  $uploader[] = $member[0];
 endforeach;
 
 stdhead("Rank do grupo");
 
 begin_framec("Rank do grupo");
   echo"<a href=painel_grupos.php?id=$id><font align='center'><b>Voltar painel de grupo</b></font></a>";
 ?>

 <table width="100%">
 <tr>
     <td valign="top" align="right">
     <b>Filtra po categorias:</b>
	      </td>
 </tr>
 <tr>
     <td valign="top" align="right">
     <form id='sort' action=''>

     <select name="cat" onchange="window.location='grupo-rank-t.php?id=<?php echo $id; ?>&duration=<?php echo ($_GET['duration']); ?>&amp;cat='+this.options[this.selectedIndex].value">
     <option value="">Todas as categorias</option>
     <?php foreach ( genrelist() as $category ): ?>
        <option value="<?php echo $category["id"]; ?>" <?php echo ($_GET['cat'] == $category["id"] ? " selected='selected'" : ""); ?>><?php echo $category["parent_cat"] . ' > ' . $category["name"]; ?></option>
     <?php endforeach; ?>
     </select>   
     </form> 
     </td>
 </tr>
 </table>
 <br>
  <?php 
  
  $data['id'] = $id;
  
  if ( is_valid_id($_GET['cat']) )
  {
       $data['cat'] = $_GET['cat'];
  }
  
  $link = http_build_query($data);
  $sabado = 6; //sabado = 6º dia = fim da semana.
$dia_atual=date('w'); //pego o dia atual
$dias_que_faltam_para_o_sabado = $sabado - $dia_atual;

$inicio = strtotime("-$dia_atual days");
$fim = strtotime("+$dias_que_faltam_para_o_sabado days");
    $data2 = date('Y-m-d H:i:s');  
$data21 = date("d/m/Y", utc_to_tz_time($data2));

$datasem = date('Y-m-d H:i:s');  
$datas = date("m/Y", utc_to_tz_time($datasem));  
$whereag=array();
if ($_GET["duration"] == 2) {
     $whereag[] = "AND date_format(added,'%Y-%m-%d')>='$data2' AND date_format(added,'%Y-%m-%d')<='$data21'";
	 $chave = "SEMANAL";
	 $trueinicio = date("m/d", $inicio); 
	 $trueifim = date("m/d", $fim); 
	 
	 $mensalinicio = "<font color='blue'>Início: </font><font color='red'>$trueinicio</font> <br><font color='blue'> Término: </font><font color='red'>$trueifim</font>";
}

if ($_GET["duration"] == 1) {
     $whereag[] = "AND date_format(added,'%m/%Y')='$datas'";
	 $chave = "MENSAL"; 
	 $mensalfim = date("m", utc_to_tz_time($datasem));  
	 $mensalinicio = "<font color='blue'>Início: </font><font color='red'>01/$mensalfim</font> <br><font color='blue'> Término: </font><font color='red'>".date("t")."/$mensalfim</font>";
 }
  $whereg = implode("AND", $whereag);

  echo"$mensalinicio"; 
 ?>
  <center>
 <a href="grupo-rank-t.php?id=<?php echo $id; ?>">Top 10 Total</a> | <a href="grupo-rank-t.php?id=<?php echo $id; ?>&amp;duration=<?php echo 1; ?>">Top 10 Mensal</a> | <a href="grupo-rank-t.php?id=<?php echo $id; ?>&amp;duration=<?php echo 2; ?>">Top 10 Semanal</a>
 </center>
 
 <?php $res = mysql_query("SELECT id, name, seeders, leechers, times_completed FROM torrents WHERE owner IN (".join(",", $uploader).") ".( is_valid_id($_GET['cat']) ? 'AND category = \''.$_GET['cat'].'\' ' : null )." ".( is_valid_id($_GET['cat']) ? 'AND category = \''.$_GET['cat'].'\' ' : null )." ". $whereg ." ORDER BY seeders DESC LIMIT 10"); ?>

 <table border='1' cellpadding='5' cellspacing='3' align='center' width='100%' class='ttable_headinner'>
   <tr>
     <th class="tab1_cab1" colspan="5" >Torrents mais seedes</th>
 </tr>
 <tr>
     <th class="ttable_head">Posição</th>
     <th class="ttable_head">Nome</th>
     <th class="ttable_head">Complet.</th>
     <th class="ttable_head">S</th>
     <th class="ttable_head">L</th> 
 </tr>
 <?php $i = 1; while ($row = mysql_fetch_assoc($res)): ?>
 <tr>
     <td class="ttable_col2"><center><?php echo $i; ?></center></td>
     <td class="ttable_col2"><a href="torrents-details.php?id=<?php echo $row['id']; ?>"><?php echo $row["name"]; ?></a></td>
     <td class="ttable_col2" align="center" ><?php echo $row["times_completed"]; ?></td>
     <td class="ttable_col2" align="center" ><?php echo $row["seeders"]; ?></td>
     <td class="ttable_col2" align="center" ><?php echo $row["leechers"]; ?></td>  
 </tr>
 <?php $i++; endwhile; ?>
 <?php if ( mysql_num_rows($res) == 0 ): ?>
 <tr>
     <td class="ttable_col2" colspan="5" align="center"><b>Total de registros encontrados com este critério: 0</b></td>
 </tr>
 <?php endif; ?>
 </table>
 
 <br /><br />
 <?php  $res = mysql_query("SELECT id, name, seeders, leechers, times_completed FROM torrents WHERE owner IN (".join(",", $uploader).") ".( is_valid_id($_GET['cat']) ? 'AND category = \''.$_GET['cat'].'\' ' : null )." ". $whereg ." ORDER BY leechers DESC LIMIT 10"); ?>

 <table border="0" cellpadding="3" cellspacing="0" class="table_table" width="100%">
  <tr>
     <th class="tab1_cab1" colspan="5" >Torrents com mais Leeche</th>
 </tr>
 <tr>
     <th class="ttable_head">Posição</th>
     <th class="ttable_head">Nome</th>
     <th class="ttable_head">Complet.</th>
     <th class="ttable_head">L</th>
     <th class="ttable_head">S</th> 
 </tr>
 <?php $i = 1; while ($row = mysql_fetch_assoc($res)): ?>
 <tr>
     <td class="ttable_col2"><center><?php echo $i; ?></center></td>
     <td class="ttable_col2"><a href="torrents-details.php?id=<?php echo $row['id']; ?>"><?php echo $row["name"]; ?></a></td>
     <td class="ttable_col2" align="center" ><?php echo $row["times_completed"]; ?></td>  
     <td class="ttable_col2" align="center" ><?php echo $row["leechers"]; ?></td> 
     <td class="ttable_col2" align="center" ><?php echo $row["seeders"]; ?></td> 
 </tr>
 <?php $i++; endwhile; ?>
 <?php if ( mysql_num_rows($res) == 0 ): ?>
 <tr>
     <td class="ttable_col2" colspan="5" align="center"><b>Total de registros encontrados com este critério: 0</b></td>
 </tr>
 <?php endif; ?>
 </table>
 
 <?php  
 end_framec();
 	}else{
      show_error_msg("Error", "Você não tem permissão para isso.", 1);
}
 stdfoot();
 
?>