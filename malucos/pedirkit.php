<?php
############################################################
#######                                             ########
#######                                             ########
#######           malucos-share.org 2.0             ########
#######                                             ########
#######                                             ########
############################################################
require "backend/functions.php";

dbconn();

loggedinonly();

stdhead("Pedir kit");



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
function grupomes($ts = 0)
{    

$month = floor($ts / 2629743); 	

return sprintf( '%d mese(s)',  $month);
}


  
if ($CURUSER["downloaded"] > 0) {
    $ratio = number_format($CURUSER["uploaded"] / $CURUSER["downloaded"], 2);

}else{
	$ratio = '0.00';
}

   $seedbonus = $CURUSER["seedbonus"];
   
     $verpedido = mysql_query("SELECT * FROM kitpedido WHERE  userid=" . $CURUSER["id"] ."");    
   
$exepedi=mysql_fetch_assoc($verpedido) ;


$iniciovip =  $exepedi['added'];

	
$datas2vip = date("Y-m-d H:i:s", utc_to_tz_time($iniciovip)); 

	
$datasemvip = date('Y-m-d H:i:s');    
$datasvip = date("Y-m-d H:i:s", utc_to_tz_time($datasemvip)); 


		
$data_inicialvip = $datas2vip;

$data_finalvip = $datasemvip;

$time_inicialvip = strtotime($data_inicialvip);

$time_finalvip = strtotime($data_finalvip);

$diferencavip = $time_finalvip - $time_inicialvip; // 19522800 segundos

$diasvip = (int)floor( $diferencavip / (60 * 60 * 24)); // 225 dias
if (mysql_num_rows($verpedido) == 0){
$validarvip = "verdade";
$diferencavip = 0; 
} 
if ($CURUSER["donator"] == "y" ){
 if(grupomes($diferencavip)  >= 3){
  $added12vip = 'Meta atingida';
 $added123vip = "<font color='Green'>$added12vip</font>";
   
  }else{
    $added12vip = 'Meta não atingida';
 $added123vip = "<font color='red'>$added12vip</font>";

  }	
$contavip = "3 meses";
}else{
 if(grupomes($diferencavip)  >= 6){
  $added12vip = 'Meta atingida';
 $added123vip = "<font color='Green'>$added12vip</font>";
   
  }else{
    $added12vip = 'Meta não atingida';
 $added123vip = "<font color='red'>$added12vip</font>";

  }	
$contavip = "6 meses";
}
 if(grupomes($diferenca)  >= 3){
  $added12 = 'Meta atingida';
 $added123 = "<font color='Green'>$added12</font>";
   
  }else{
    $added12 = 'Meta não atingida';
 $added123 = "<font color='red'>$added12</font>";

  }	
if  ($validarvip == "verdade"){
$added123vip = "<font color='Green'>Meta atingida</font>";
}

     $rei = mysql_query("SELECT *  FROM users WHERE id=" . $CURUSER["id"] ."") or sqlerr();
    while ($arr2 = mysql_fetch_assoc($rei)) {
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
           if ($arr2["warned"] == "no")
                $warned22 = "Não";
            else
                $warned22 = "Sim";
   

 }

  if(number_format("$ratio",2)>= '1.00'){
  $ratio12 = 'Meta atingida';
 $ratio1 = "<font color='Green'>$ratio12</font>";
   
  }else{
    $ratio12 = 'Meta não atingida';
 $ratio1 = "<font color='red'>$ratio12</font>";

  }	

 if(grupomes($diferenca)  >= 3){
  $added12 = 'Meta atingida';
 $added123 = "<font color='Green'>$added12</font>";
   
  }else{
    $added12 = 'Meta não atingida';
 $added123 = "<font color='red'>$added12</font>";

  }	
 
 if($seedbonus >= '1000'){
  $seedbonusr = 'Meta atingida';
 $seedbonusr1 = "<font color='Green'>$seedbonusr</font>";
   
  }else{
    $seedbonusr = 'Meta não atingida';
 $seedbonusr1 = "<font color='red'>$seedbonusr</font>";

  }	
 //$numtorrents
 		$res4 = SQL_Query_exec("SELECT COUNT(*) FROM forum_posts WHERE userid=" . $CURUSER["id"] ."") or forumsqlerr();
		$arr33 = mysql_fetch_row($res4);
		$forumposts = $arr33[0];
  if($forumposts >= '30'){
  $forumpostsr = 'Meta atingida';
 $forumpostsr1 = "<font color='Green'>$forumpostsr</font>";
   
  }else{
    $forumpostsr = 'Meta não atingida';
 $forumpostsr1 = "<font color='red'>$forumpostsr</font>";

  }	
 

begin_framec("Pedir kit");
?>

<table align="center" width="100%" cellspacing="1" cellpadding="0" id="tabela1">
<tbody>
	
	<tr>
	<td align="center" class="tab1_cab1">Critério</td>
	<td align="center" class="tab1_cab1">Necessário</td>
	<td align="center" class="tab1_cab1">Você tem</td>
	<td align="center" class="tab1_cab1">Status</td>
	</tr>
	
	<tr>
	<td align="center" class="tab1_col3">Tempo de cadastro</td>
	<td align="center" class="tab1_col3">3 meses</td>
	<td align="center" class="tab1_col3"><?php echo ( $diferenca > 0 ) ? grupopedi($diferenca) : 'N/A'; ?></td>
	<td align="center" class="tab1_col3"><font color="green"><?php echo $added123 ;?></font></td>
	</tr>

	<tr>
	<td align="center" class="tab1_col3">Tempo Necessário para novo pedido</td>
	<td align="center" class="tab1_col3"><?php echo  $contavip ;?> meses</td>
	<td align="center" class="tab1_col3"><?php echo ( $diferencavip > 0 ) ? grupopedi($diferencavip) : 'N/A'; ?></td>
	<td align="center" class="tab1_col3"><font color="green"><?php echo $added123vip ;?></font></td>
	</tr>	
	
	<tr> 
	<td align="center" class="tab1_col3">Ratio</td>
	<td align="center" class="tab1_col3">1 ou maior</td>
	<td align="center" class="tab1_col3"><?php echo $ratio ;?></td>
	<td align="center" class="tab1_col3"><font color="red"><?php echo $ratio1 ;?></font></td>
	</tr>
	
	<tr>
	<td align="center" class="tab1_col3">MS Pontos</td>
	<td align="center" class="tab1_col3">1000 MS Pontos (valor do pedido)</td>
	<td align="center" class="tab1_col3"><?php echo $seedbonus ;?> MS Pontos</td>
	<td align="center" class="tab1_col3"><font color="red"><?php echo $seedbonusr1 ;?></font></td>
	</tr>
	
	<tr>
	<td align="center" class="tab1_col3">Advertências</td>
	<td align="center" class="tab1_col3">não estar advertido</td>
	<td align="center" class="tab1_col3"><?php echo $warned22 ;?> </td>
	<td align="center" class="tab1_col3"><font color="green">Meta atingida</font></td>
	</tr>
	
	<tr>
	<td align="center" class="tab1_col3">Posts relevantes no fórum</td>
	<td align="center" class="tab1_col3">Pelo menos 30</td>
	<td align="center" class="tab1_col3"><?php echo $forumposts ;?></td>
	<td align="center" class="tab1_col3"><font color="red"><?php echo $forumpostsr1 ;?></font></td>
	</tr>
	
	</tbody>
	</table>
<?php 

print("<br>\n");

if ($added12vip == "Meta atingida" || $validarvip == "verdade" ){
$validadovip = "verdade" ;

}
 

 if ($forumpostsr == "Meta atingida" && $seedbonusr == "Meta atingida" && $added12 == "Meta atingida"  && $validadovip == "verdade" && $ratio12 == "Meta atingida"){ 


print("<table class='tab1' width='100%' cellpadding='0' cellspacing='1' align='center' >");
print("<form method=post action=pedikitrequest.php><a name=add id=add></a>\n");
print("<tr><td width='40%'  align='right'  class='tab1_col3' ><b>Tema: *</b></td><td  width='60%'  align='left'  class='tab1_col3'><input type=text size=40 name=requesttitle></td></tr>");
?>
<script language="JavaScript" type="text/Javascript">
function mostraCampo( el ){
var txt_outro = document.getElementById('txt_outro');
 if (el.value == '2')
  txt_outro.style.display = 'block';
else
  txt_outro.style.display = 'none';
}
	</script>
<tr><td width="40%"  align="right"  class="tab1_col3"><b>Categoria: *</b></td>
<td  width="60%"  align="left"  class="tab1_col3"><select name="category" id="category" onchange="mostraCampo(this)">
<option value="">(Selecione um tipo)</option>

<?php 

$res2 = mysql_query("SELECT id, name, parent_cat FROM kittipo ORDER BY parent_cat ASC, sort_index ASC");
$num = mysql_num_rows($res2);
$catdropdown2 = "";
for ($i = 0; $i < $num; ++$i)
   {
 $cats2 = mysql_fetch_assoc($res2);  
     $catdropdown2 .= "<option value=\"" . $cats2["id"] . "\"";
     $catdropdown2 .= ">" . htmlspecialchars($cats2["parent_cat"]) . " </option>\n";
   }


   
?>
<?php echo  $catdropdown2 ;?>

</select>

	<select name='txt_outro' id='txt_outro' style='display:none;'>
	<option value="">Estilo de Borda</option>
	<option value="TECH">Tech</option>
	<option value="GFX">Gfx</option>
		<option value="GFX">Modelada</option>
			<option value="GFX">3d</option>
	</select>
	
<?php print("</td></tr><br>\n");
?>


<?php
print("<tr><td width='40%'  align='right'  class='tab1_col3' ><b>screens1:</b></td><td  width='60%'  align='left'  class='tab1_col3'><input type=text size=40 name=screens1></td></tr>");
print("<tr><td width='40%'  align='right'  class='tab1_col3' ><b>screens2:</b></td><td  width='60%'  align='left'  class='tab1_col3'><input type=text size=40 name=screens2></td></tr>");
print("<tr><td width='40%'  align='right'  class='tab1_col3' ><b>screens3:</b></td><td  width='60%'  align='left'  class='tab1_col3'> <input type=text size=40 name=screens3></td></tr>");
print("<tr><td align=center colspan=2 class=tab1_col3>Informação Adicional <b>(Opcional - mas tente fazer uma descrição!</b>)<br><textarea name=descr rows=7 
cols=60></textarea>\n");
print("<tr><td align=center colspan=2 class=tab1_col3><input type=submit value='Enviar' style='height: 22px'>\n");
print("</form>\n");
print("</table></CENTER>\n");

}

     $kitcon = mysql_query("SELECT *  FROM kitpedido WHERE status='aprovado'") or sqlerr();
    while ($kitapp = mysql_fetch_assoc($kitcon)) {
?>
	<table align="center" cellspacing="1" cellpadding="0" id="tabela1">
<tbody><tr><td width="40%" align="right" class="tab1_cab1">Designer: </td><td class="tab1_cab1"><a href="account-details.php?id=<?php echo $kitapp['desid'] ; ?>"><?php echo $kitapp['desname'] ; ?></a></td></tr>
<tr><td align="right" class="tab1_col3">Usuário: </td><td class="tab1_col3"><a href="account-details.php?id=<?php echo $kitapp['userid'] ; ?>"><?php echo $kitapp['kusername'] ; ?></a></td></tr>
<tr><td align="right" class="tab1_col3">Data do pedido: </td><td class="tab1_col3"><?php echo date("d/m/y", utc_to_tz_time($kitapp['added']))." às ". date("H:i:s", utc_to_tz_time($kitapp['added'])) ;?></td></tr>
<tr><td align="right" class="tab1_col3">Data de conclusão: </td><td class="tab1_col3"><?php echo date("d/m/y", utc_to_tz_time($kitapp['termina']))." às ". date("H:i:s", utc_to_tz_time($kitapp['termina'])) ;?></td></tr>
<tr><td align="center" colspan="2" class="tab1_col3">
<b>Avatar:</b><br><img border="0" alt="" src="<?php echo $kitapp['link_avata'] ; ?>"><br><br>
<b>Assinatura:</b><br><img border="0" alt="" src="<?php echo $kitapp['link_sing'] ; ?>">
</td>
</tr></tbody></table>
<BR>
<?php
	}
	
	
	
	
	
	
	
	
	
	
	
	
end_framec();

stdfoot();
?>
