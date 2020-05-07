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

     <select name="cat" onchange="window.location='grupo-rank-u.php?id=<?php echo $id; ?>&duration=<?php echo ($_GET['duration']); ?>&amp;cat='+this.options[this.selectedIndex].value">
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
     $whereag[] = "AND date_format(t.added,'%Y-%m-%d')>='$data2' AND date_format(t.added,'%Y-%m-%d')<='$data21'";
	 $chave = "SEMANAL";
	 $trueinicio = date("m/d", $inicio); 
	 $trueifim = date("m/d", $fim); 
	 
	 $mensalinicio = "<font color='blue'>Início: </font><font color='red'>$trueinicio</font> <br><font color='blue'> Término: </font><font color='red'>$trueifim</font>";
}

if ($_GET["duration"] == 1) {
     $whereag[] = "AND date_format(t.added,'%m/%Y')='$datas'";
	 $chave = "MENSAL"; 
	 $mensalfim = date("m", utc_to_tz_time($datasem));  
	 $mensalinicio = "<font color='blue'>Início: </font><font color='red'>01/$mensalfim</font> <br><font color='blue'> Término: </font><font color='red'>".date("t")."/$mensalfim</font>";
 }
  $whereg = implode("AND", $whereag);

  echo"$mensalinicio"; 
 ?>
  <center>
 <a href="grupo-rank-u.php?id=<?php echo $id; ?>">Top 10 Total</a> | <a href="grupo-rank-u.php?id=<?php echo $id; ?>&amp;duration=<?php echo 1; ?>">Top 10 Mensal</a> | <a href="grupo-rank-u.php?id=<?php echo $id; ?>&amp;duration=<?php echo 2; ?>">Top 10 Semanal</a>
 </center>
 <?php
 $res = SQL_Query_exec("SELECT u.id, u.username, u.team, teams.name, t.owner, COUNT(t.owner) as num FROM torrents t LEFT JOIN users u ON u.id = t.owner LEFT JOIN teams ON u.team = teams.id WHERE u.enabled = 'yes' " . ( is_valid_id($_GET['cat']) ? 'AND t.category = \''.$_GET['cat'].'\' ' : null ) . " AND u.team = '$id' ". $whereg ." GROUP BY owner ORDER BY num DESC LIMIT 10");
 ?>
<table align="center" cellpadding="0" cellspacing="0" class="ttable_headinner" width="100%">
    <tr>
     <th class="tab1_cab1" colspan="3" >Quem mais lançou</th>
 </tr>
 <tr>
     <th class="ttable_head" width="1%"  align="center">Posição</th>
     <th class="ttable_head"  align="center" >Membro</th>
     <th class="ttable_head" width="10%"  align="center" >Lançamentos</th>
 </tr>
 <?php $i = 1; while ($row = mysql_fetch_assoc($res)): ?>
 <tr>
     <td class="ttable_col2"  align="center" ><?php echo $i; ?></td>
     <td class="ttable_col2"  align="center" ><a href="account-details.php?id=<?php echo $row['id']; ?>"><?php echo $row["username"]; ?></a></td>
     <td class="ttable_col2"  align="center" ><?php echo $row["num"]; ?></td>
 </tr>
 <?php $i++; endwhile; ?>
 <?php if ( mysql_num_rows($res) == 0 ): ?>
 <tr>
     <td class="ttable_col2" colspan="3" align="center"><b>Total de registros encontrados com este critério: 0</b></td>
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