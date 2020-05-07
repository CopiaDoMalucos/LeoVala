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
  require ("backend/conexao.php");
 $pdo = conectar();
 
  $id = (int) $_GET['id'];
  

 $res = mysql_query("SELECT * FROM teams WHERE  id = '$id'");  
 $row = mysql_fetch_assoc($res);
 
 $resultsub1 = SQL_Query_exec("SELECT * FROM usergroups WHERE uid = ".$CURUSER['id']." AND gid = ".$CURUSER['team']."") ;
$row21 = mysql_fetch_array($resultsub1);	
if ($row21["gid"] !=  $id ){
     show_error_msg("Error", "Você não tem acesso a este grupo", 1);
}
  

       
 if ($row21["status"] == 'moderadores' || $row21["status"] == 'submoderadores' )
{ 
  
  if ($_SERVER["REQUEST_METHOD"] == "POST"){
  	$doapontos  = (int)$_POST["points"]; 
    stdhead("Distribuir prêmios");
    begin_framec("Distribuir prêmios");  

   if ($row["bonusteam"] >= $doapontos )
{

   

            $select_row = $pdo->prepare("SELECT usergroups.gid,  usergroups.uid, usergroups.status, users.id, users.username,  usergroups.status AS grupostatus, users.id AS userid FROM usergroups LEFT JOIN users ON usergroups.uid = users.id WHERE usergroups.gid = :id AND users.username = :username"); 
            $select_row->bindParam(':id', $id);
            $select_row->bindParam(':username', $_POST['username']);
            $select_row->execute(); 
            $row_select = $select_row->fetch(PDO::FETCH_ASSOC); 
				
				if ($row_select["grupostatus"] == 'moderadores' || $row_select["grupostatus"] == 'submoderadores' )
{ 
		  print("<b><center>Os pontos não são validos para moderadores e sub-moderadores!!!<a href='grupo-rank-p.php?id=$id'><br>Voltar</a></center></b>");
		  
		  end_framec();
	      stdfoot();
		        die();
}


		if (!$row_select){
		  print("<b><center>O membro solicitado não é valido!!!<a href='grupo-rank-p.php?id=$id'><br>Voltar</a></center></b>");
		  end_framec();
	      stdfoot();
		 }else{
        if ($doapontos == '100' || $doapontos == '200' || $doapontos == '300' || $doapontos == '400' || $doapontos == '500'){
          $select_bonus=$pdo->prepare("UPDATE usergroups SET seedbonus= seedbonus + :seedbonus where uid= :id");
		  $select_bonus->bindParam(':seedbonus', $doapontos);
          $select_bonus->bindParam(':id', $row_select['userid']);
          $select_bonus->execute();
		  $timenow = get_date_time();
          $grupo_bonus=$pdo->prepare("INSERT INTO grupobonus (gid, uid, joined, bonusrank) VALUES (:gid, :uid, :timenow, :seedbonus)");
		  $grupo_bonus->bindParam(':gid', $row_select['gid']);
          $grupo_bonus->bindParam(':uid', $row_select['userid']);
		  $grupo_bonus->bindParam(':timenow', $timenow);
		  $grupo_bonus->bindParam(':seedbonus', $doapontos);
          $grupo_bonus->execute();
		  
		  $msg = "Prezado membro,\n\nO grupo ".$row["name"]." fez uma doação de ".$doapontos." MS Pontos para você devido seu destaque no grupo!\n\nOs pontos já foram adicionados em sua conta.\n\n Atenciosamente,\n\n Grupo ".$row["name"]."  ";
          $added = get_date_time();
          $subject  = "Grupo ".$row["name"]." ";
          $mpbonus=$pdo->prepare("INSERT INTO messages (poster, sender, receiver, msg, added,subject) VALUES ('0','0', " . $row_select['id']. ", " .sqlesc($msg) . ", '" . get_date_time() . "','Grupo ".$row["name"]."!')");			  
          $mpbonus->execute();  
		  
		  $select_bonus1=$pdo->prepare("UPDATE teams SET bonusteam= bonusteam - :seedbonus where id= :id");
          $select_bonus1->bindParam(':seedbonus', $doapontos);
	      $select_bonus1->bindParam(':id', $id);
          $select_bonus1->execute(); 
					  
		  print("<b><center>Update foi realizado com sucesso!!!<a href='grupo-rank-p.php?id=$id'><br>Voltar</a></center></b>");
		  end_framec();
	      stdfoot();
	      die();
		  }else{
		  print("<b><center>Pontos selecionas invalidos!!!<a href='grupo-rank-p.php?id=$id'><br>Voltar</a></center></b>");
		  end_framec();
	      stdfoot();
	      die();
		  }
		  
		 }
		 }else{
		  print("<b><center>Pontos insuficiente!!!<a href='grupo-rank-p.php?id=$id'><br>Voltar</a></center></b>");
		  end_framec();
	      stdfoot();
	      die();

		 }
		 
 }
  function data() {
$semana = date(N); 
$dia = date(d);
$mes = date(m);
$ano = date(Y); 

switch($mes) {
case 01: $mes = "Janeiro"; break;
case 02: $mes = "Fevereiro"; break;
case 03: $mes = "Março"; break;
case 04: $mes = "Abril"; break;
case 05: $mes = "Maio"; break;
case 06: $mes = "Junho"; break;
case 07: $mes = "Julho"; break;
case 08: $mes = "Agosto"; break;
case 09: $mes = "Setembro"; break;
case 10: $mes = "Outubro"; break;
case 11: $mes = "Novembro"; break;
case 12: $mes = "Dezembro"; break;
}
 

switch($semana) {
case 1: $semana = "Segunda-feira"; break;
case 2: $semana = "Terça-feira"; break;
case 3: $semana = "Quarta-feira"; break;
case 4: $semana = "Quinta-feira"; break;
case 5: $semana = "Sexta-feira"; break;
case 6: $semana = "Sabado-feira"; break;
case 7: $semana = "Domingo-feira"; break;
 
}
 
//mostrar o resultado

return $mes;
}

$datasem = date('Y-m-d H:i:s'); 
	 $mensalfim = date("m", utc_to_tz_time($datasem));  

	 $mensalinicio = "".date("t")."/$mensalfim às 23:59";
 stdhead("Rank prêmios");
 
 begin_framec("Rank prêmios");
echo"<a href=painel_grupos.php?id=$id><font align='center'><b>Voltar painel de grupo</b></font></a>";
echo"<BR><BR><font size='2'>Cada grupo receberá <B>2000 MS Pontos</B> por mês. Tais pontos serão cumulativos, ou seja, caso os pontos não sejam repassados, não poderão ser usados no mês posterior.<br> 
Os pontos deverão ser distribuídos entre <B>no máximo</B> 8 membros.<br>
O <B>prêmio máximo </B>será de <B>500 MS pontos para um único membro.</B><br>
Só poderão ganhar pontos os membros que lançaram <B>pelo menos 1 torrente no mês</B> ou a <B>soma dos torrents lançados seja no mínimo 1 GB.</B><br>
Os pontos estão disponiveis sempre no <span >mês </span>posterior. Ou seja, os pontos do mês de março (por exemplo) estão disponiveis no dia 01/04 e perderão a validade no dia 30/04.</font><br>";
 ?>
 
<br>
<br>

<table width="100%" class='tab1' cellpadding='0' cellspacing='1' align='center'>
    <tr>
     <th class="tab1_cab1" colspan="2" >Dados do mês de premiação</th>
 </tr>
<tr><td width=50%  align=right  class=tab1_col3><b>Mês:</b></td><td width=50%  align=left class=tab1_col3  ><?php echo data() ;?></tr>
<tr><td width=50%  align=right  class=tab1_col3><b>Pontos disponíveis:</b></td><td width=50%  align=left class=tab1_col3  ><?php echo $row["bonusteam"]; ?></tr>
<tr><td width=50%  align=right  class=tab1_col3><b>Período avaliado:</b></td><td width=50%  align=left class=tab1_col3  >Sempre no mês anterior</tr>
<tr><td width=50%  align=right  class=tab1_col3><b>Prazo para premiação:</b></td><td width=50%  align=left class=tab1_col3  ><?php echo $mensalinicio ; ?></tr>
</table>
 <BR>
 <BR>
 <?php

 $res = SQL_Query_exec("SELECT grupobonus.uid, grupobonus.gid, grupobonus.bonusrank, grupobonus.joined, users.status,  users.id AS iduser, users.username, users.class, teams.id, users.id AS userid, teams.id AS teamid FROM grupobonus LEFT JOIN users ON grupobonus.uid = users.id LEFT JOIN teams ON grupobonus.gid = teams.id WHERE grupobonus.gid = '$id' LIMIT 10");
 ?>
<table align="center" cellpadding="0" cellspacing="0" class="ttable_headinner" width="50%">
    <tr>
     <th class="tab1_cab1" colspan="4" >Prêmios distribuídos no mês de  <?php echo data() ;?></th>
 </tr>
 <tr>
     <th class="ttable_head" width="1%"  align="center">Posição</th>
     <th class="ttable_head"  align="center" >Membro</th>
	  <th class="ttable_head"  align="center" >Data</th>
     <th class="ttable_head" width="10%"  align="center" >Pontos ganhos</th>
 </tr>
 <?php $i = 1; while ($row = mysql_fetch_assoc($res)): ?>
 <tr>
     <td class="ttable_col2"  align="center" ><?php echo $i; ?></td>
     <td class="ttable_col2"  align="center" ><a href="account-details.php?id=<?php echo $row['iduser']; ?>"><?php echo $row["username"]; ?></a></td>
	 <td class="ttable_col2"  align="center" ><?php echo date("d/m/y", utc_to_tz_time($row['joined']))." às ". date("H:i:s", utc_to_tz_time($row['joined'])) ?></td>
     <td class="ttable_col2"  align="center" ><?php echo $row["bonusrank"]; ?> </td>
 </tr>
 <?php $i++; endwhile; ?>
 <?php if ( mysql_num_rows($res) == 0 ): ?>
 <tr>
     <td class="ttable_col2" colspan="4" align="center"><b>Total de registros encontrados com este critério: 0</b></td>
 </tr>
 <?php endif; ?>
 </table>
 
 <?php  
 end_framec();
 echo"<BR><BR>";
 begin_framec("Distribuir prêmios");
 
     echo("<table id='tabela1' cellpadding='0' cellspacing='1'  width='100%' align='center'>");
   echo("<form method='post' action='grupo-rank-p.php?id=$id'>");
          echo("<input type='hidden' name='option' value='".$CURUSER["id"]."'>");
        echo("<input type='hidden' name='art' value='friend'>");

  echo("<tr>");

  echo("<td class='tab1_col3' align='center'>Doar <select name='points' id='points'> <option value='100'>100</option>
  <option value='200' >200</option>
  <option  value='300'>300</option>
  <option value='400'>400</option>
    <option value='500'>500</option>

</select> MS Pontos para o usuário <input type='text' name='username' size='30'/></td>");
echo("</tr>");
echo("<tr>");
  echo("<td class='tab1_col3' align='center'><input type='submit' value='      Doar!      '></td>");
  echo("</tr>");
  echo("</table>");
  echo("</form>");
 
 end_framec();
   	}else{
      show_error_msg("Error", "Você não tem permissão para isso.", 1);
}
 stdfoot();
 
?>