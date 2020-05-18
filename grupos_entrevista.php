<?php
############################################################
#######                                             ########
#######                                             ########
#######           www.brshares.com 2.0              ########
#######                                             ########
#######                                             ########
############################################################
  
  require_once("backend/functions.php");
  dbconn(false);
  loggedinonly();
require ("backend/conexao.php");
///////////////
function grupopedi($ts = 0)
{    

$month = floor($ts / 2629743); 	
 $ts = $ts - ($month*2629743); 
$weeks = floor($ts / 604800); 
 $ts = $ts - ($weeks*604800);                                                                                                                                                                                       
$days = floor($ts / 86400);
$ts = $ts - ($days*86400);
$hours = floor($ts / 3600 ); 
$ts = $ts - ($hours*3600);     
$mins = floor($ts / 60) % 60;

return sprintf( '%d mês(es), %d semana(s), %d dia(s), %d hora(s), %d minuto(s)',  $month, $weeks, $days, $hours, $mins);
}
////////////
function grupomes($ts = 0)
{    

$month = floor($ts / 2629743); 	

return sprintf( '%d mese(s)',  $month);
}
$pdo = conectar();



 	stdhead("Grupos");
?>

<?php 
begin_framec("Grupos");
	$res = SQL_Query_exec("SELECT username FROM users WHERE id=" . $CURUSER["id"] ."");
			$row = mysql_fetch_array($res);

	

	  if ($CURUSER["downloaded"] > 0) {
    $ratio = number_format($CURUSER["uploaded"] / $CURUSER["downloaded"], 2);
  }else{
    $ratio = "---";
  }	
   $seedbonus = $CURUSER["seedbonus"];
   
  	$grupofalta = SQL_Query_exec("SELECT * FROM grupodel WHERE gruuserid=" . $CURUSER["id"] ."");
			$rowfalta = mysql_fetch_array($grupofalta);
			
     	$grupopende = SQL_Query_exec("SELECT * FROM  grupoaceita WHERE iduser=" . $CURUSER["id"] ."");
			$rowpende = mysql_fetch_array($grupopende);
   
$faltagrupo =  $rowfalta['grudata'];
$datas22 = date("Y-m-d H:i:s", utc_to_tz_time($faltagrupo)); 				
$datasem3 = date('Y-m-d H:i:s');    
$datas1 = date("Y-m-d H:i:s", utc_to_tz_time($datasem3)); 
$data_inicial2 = $datas22;
$data_final2 = $datas1;
$time_inicial2 = strtotime($data_inicial2);
$time_final2 = strtotime($data_final2);
$diferenca2 = $time_final2 - $time_inicial2; // 19522800 segundos
$dias23 = (int)floor( $diferenca2 / (60 * 60 * 24)); // 225 dias

   
   

   
   
   
   
   
     $rei = mysql_query("SELECT DISTINCT username, id, invited_by, added, enabled, warned  FROM users WHERE id=" . $CURUSER["id"] ."") or sqlerr();
    while ($arr2 = mysql_fetch_assoc($rei)) {

          $numtorrents = get_row_count("torrents", "WHERE owner = " . $CURUSER["id"] ."");
           if ($arr2["warned"] == "no"){
				$aviso = 'Meta atingida';
                $aviso1 = "<font color='Green'>$aviso</font>";
				$warned22 = "Não";
				$waraviso = "nao" ;
				 }
            else{
			    $aviso = 'Meta não atingida';
                $aviso1 = "<font color='red'>$aviso</font>";
                $warned22 = "Sim";
			    $waraviso = "sim" ;
	             }
$inicio =  $arr2['added'];

	
$datas2 = date("Y-m-d H:i:s", utc_to_tz_time($inicio)); 

				
$datasem = date('Y-m-d H:i:s');    
$datas = date("Y-m-d H:i:s", utc_to_tz_time($datasem)); 



$data_inicial = $datas2;

$data_final = $datas;

$time_inicial = strtotime($data_inicial);

$time_final = strtotime($data_final);

$diferenca = $time_final - $time_inicial; // 19522800 segundos

$dias = (int)floor( $diferenca / (60 * 60 * 24)); // 225 dias

 }
if (!$rowpende){
  $grupopende = 'Meta atingida';
 $grupopende1 = "<font color='Green'>$grupopende</font>";
   $gruoenmeta = 'nao';

}else{
    $grupopende = 'Meta não atingida';
 $grupopende1 = "<font color='red'>$grupopende</font>";
 $gruoenmeta = 'sim';
}



  if(number_format("$ratio",2)>= '1.00'){
  $ratio12 = 'Meta atingida';
 $ratio1 = "<font color='Green'>$ratio12</font>";
 $ratiometa = 'sim';
   
  }else{
    $ratio12 = 'Meta não atingida';
 $ratio1 = "<font color='red'>$ratio12</font>";
  $ratiometa = 'nao';

  }	
 
 if($CURUSER["team"] != 0){
  $vergrupo1 = 'Meta não atingida';
 $vergru = "<font color='red'>$vergrupo1</font>";
 $vergru1 = 'Não';
 $vergru12 = "nao";
  }
  else
  {
  $vergrupo = 'Meta atingida ';
 $vergru = "<font color='Green'>$vergrupo</font>";
 $vergru1 = 'Sim';
  $vergru12 = "sim";

  }
  
  
  
  
 
 if(grupomes($diferenca2)  >= 2){
  $faltamdif = 'Meta atingida';
 $faltadiv = "<font color='Green'>$faltamdif</font>";
 $faltatemp = 'sim';
  }
  else
  {
    $faltamdif = 'Meta não atingida';
 $faltadiv = "<font color='red'>$faltamdif</font>";
 $faltatemp = 'nao';

  }
  
 if (!$rowfalta ){
   $faltamdif = 'Meta atingida';
 $faltadiv = "<font color='Green'>$faltamdif</font>";
 $faltatemp = 'sim';
 }
 
 
 if(grupomes($diferenca)  >= 1){
  $added12 = 'Meta atingida';
 $added123 = "<font color='Green'>$added12</font>";
  $ultimogru = "sim";
   
  }else{
    $added12 = 'Meta não atingida';
 $added123 = "<font color='red'>$added12</font>";
   $ultimogru = "nao";

  }	
 
 if($numtorrents >= '10'){
  $numetorr = 'Meta atingida';
 $torrentesati = "<font color='Green'>$numetorr</font>";
  $torrenquanti = "sim";
   
  }else{
    $torrenao = 'Meta não atingida';
 $torrentesati = "<font color='red'>$torrenao</font>";
  $torrenquanti = "nao";
  }	
  
 if ($_GET["do"] == "add"){
  	 $status = $_POST["status"];
	 $tema = $_POST["tema"];

	  	 $sql12 = mysql_query("SELECT * FROM `grupoaceita` WHERE `iduser` = " . $CURUSER['id'] . " ");
     
				
		if (mysql_num_rows($sql12) >= 1){
				print("<b><center>Você já fez solicitação!!!<a href='grupos_entrevista.php'><br>Voltar</a></center></b>");
					end_framec();
	                stdfoot();
	                die();
					}
					
	 
 	 $sql1 = mysql_query("SELECT * FROM `teams` WHERE `id` = '" . $status . "'");
     $row1 = mysql_fetch_array($sql1);
				
		if (mysql_num_rows($sql1) == 0){
				print("<b><center>Nada selecionado!!!<a href='grupos_entrevista.php'><br>Voltar</a></center></b>");
					end_framec();
	                stdfoot();
	                die();
					}
			if (!$tema){
		print("<b><center>Desculpe, favor colocar o motivo para ser aceito no grupo!!!<a href='grupos_entrevista.php'><br>Voltar</a></center></b>");
					end_framec();
	                stdfoot();
	                die();		
                     }

 
 if ( $waraviso == "nao"  && $gruoenmeta == "nao" && $ultimogru == "sim"  && $ratiometa == "sim" && $vergru12 == "sim"){
  $dt = sqlesc(get_date_time()); 
 
 		$delretira = "DELETE FROM `grupodel` WHERE `gruuserid` = " . $CURUSER["id"] . " ";
		$delretirav = mysql_query($delretira);
		
		$status = sqlesc($status);
	    $iduser = sqlesc($CURUSER['id']);
        $tema = sqlesc($tema);
       mysql_query("INSERT INTO grupoaceita (idteam, iduser, motivo, datepedi) VALUES($status, $iduser, $tema, $dt)");
		
		
			 		print("<b><center>Update sucesso!!!<a href='grupos_entrevista.php'><br>Voltar</a></center></b>");
					end_framec();
	                stdfoot();
	                die();
 
 }
 else{
 			 		print("<b><center>Desculpe, mais você não pode participar de nenhum grupo no momento!!!<a href='grupos_entrevista.php'><br>Voltar</a></center></b>");
					end_framec();
	                stdfoot();
	                die();
 
 }
 }
  
  ?>

<table width="100%" cellspacing="2" cellpadding="1" border="1" class="table_table">
<tbody>
	
	<tr>
	<td align="center" class="tab1_cab1">Critério</td>
	<td align="center" class="tab1_cab1">Necessário</td>
	<td align="center" class="tab1_cab1">Você tem</td>
	<td align="center" class="tab1_cab1">Status</td>
	</tr>
	
	<tr>
	<td align="center" class="ttable_col2">Tempo de cadastro</td>
	<td align="center" class="ttable_col2">1 mês</td>
	<td align="center" class="ttable_col2"><?php echo ( $diferenca > 0 ) ? grupopedi($diferenca) : 'N/A'; ?></td>
	<td align="center" class="ttable_col2"><font color="green"><?php echo $added123 ;?></font></td>
	</tr>
	
	<tr>
	<td align="center" class="ttable_col2">Grupos</td>
	<td align="center" class="ttable_col2">Não participar de grupos</td>
	<td align="center" class="ttable_col2"><?php echo $vergru1 ; ?> </td>
	<td align="center" class="ttable_col2"><font color="green"><?php echo $vergru ;?></font></td>
	</tr>
	
	<tr>
	<td align="center" class="ttable_col2">Últimos grupo</td>
	<td align="center" class="ttable_col2">2 Meses sem participar de outro grupo</td>
	<td align="center" class="ttable_col2"><?php echo ( $diferenca2 > 0 ) ? grupopedi($diferenca2) : 'Sim'; ?> </td>
	<td align="center" class="ttable_col2"><font color="green"><?php echo $faltadiv ;?></font></td>
	</tr>
	
	<tr>
	<td align="center" class="ttable_col2">Ratio</td>
	<td align="center" class="ttable_col2">1 ou maior</td>
	<td align="center" class="ttable_col2"><?php echo $ratio ;?></td>
	<td align="center" class="ttable_col2"><font color="red"><?php echo $ratio1 ;?></font></td>
	</tr>
	
	<tr>
	<td align="center" class="ttable_col2">Pedidos em grupos</td>
	<td align="center" class="ttable_col2">Não</td>
	<td align="center" class="ttable_col2"><?php echo $gruoenmeta ;?></td>
	<td align="center" class="ttable_col2"><font color="red"><?php echo $grupopende1 ;?></font></td>
	</tr>
	
	<tr>
	<td align="center" class="ttable_col2">Torrents ativos</td>
	<td align="center" class="ttable_col2">10 Torrents ativos</td>
	<td align="center" class="ttable_col2">Você tem (<?php echo  $numtorrents ; ?>) Torrentes ativos</td>
	<td align="center" class="ttable_col2"><font color="red"><?php echo $torrentesati ;?></font></td>
	</tr>
	
	<tr>
	<td align="center" class="ttable_col2">Advertências</td>
	<td align="center" class="ttable_col2">Não estar advertido</td>
	<td align="center" class="ttable_col2"><?php echo $warned22 ;?> </td>
	<td align="center" class="ttable_col2"><font color="green"><?php echo $aviso1 ; ?></font></td>
	</tr>
	
	</tbody>
	</table>
<?php
print("<form method='post' name='addgrupo' id='addgrupo'  action='grupos_entrevista.php?do=add'>\n");
print("<CENTER><table class='tab1' cellpadding='0' cellspacing='1' align='center' >");
print("<tr><td width=100% align=center colspan=2 class=tab1_cab1><B>Pedidos de grupos</B></td></tr>");


$geragrupo = "SELECT id, name FROM  teams ";
	$sqlgrupo = mysql_query($geragrupo);
	echo"<tr><td width='40%0'  align='right'  class='tab1_col3'><b>Gupo: *</b></td>";
		echo "<td  width='60%'  align='left'  class='tab1_col3'><select   name='status'><option value=''>Escolher</option>";
	while ($rowgrupo = mysql_fetch_array($sqlgrupo)) {
	
  echo '<option value="'.$rowgrupo["id"].'">'.$rowgrupo["name"].'</option>';
	
	}
	echo '</select>';
?>
<?php
 print("<br></td></tr><br>\n");
?>

<?php print("</td></tr><br>\n");
print("<tr><td width=40%  align=right  class=tab1_col3><b>Motivo: *</b></td><td width=20%  align=left  class=tab1_col3><input type=text size=50 maxlength=50 name=tema id=tema></td></tr>");
print("<tr><td width=100% align=center colspan=2 class=tab1_col3 ><input type=submit value='Gera pedido' style='height: 22px'>\n");
print("</form>\n");
print("</table></CENTER>\n");
  
  end_framec();
  stdfoot();

  
  
  
  
?>