<?php
############################################################
#######                                             ########
#######                                             ########
#######           malucos-share.org 2.0             ########
#######                                             ########
#######                                             ########
############################################################
  require "backend/functions.php";
  dbconn(false);
  loggedinonly();
  stdhead("TOP 10");
begin_framec("TOP 10");

?>

<div id="body_outer">
<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center"><tbody><tr>
<td width="20%" align="center" style="padding-right: 3px; padding-left: 3px; border-right: #003060 1px solid;"><a href="top10.php?acao=1"><img border="0" alt="" width="83" height="80" src="images/top/download.png"><br>Torrents</a></td>
<td width="20%" align="center" style="padding-right: 3px; padding-left: 3px; border-right: #003060 1px solid;"><a href="top10.php?acao=2"><img border="0" alt="" width="83" height="80" src="images/top/user.png"><br>Usuários</a></td>
<td width="20%" align="center" style="padding-right: 3px; padding-left: 3px; border-right: #003060 1px solid;"><a href="top10.php?acao=3"><img border="0" alt="" width="83" height="80" src="images/top/mundo.png"><br>Estados / Países</a></td>
<td width="20%" align="center" style="padding-right: 3px; padding-right: 3px;"><a href="top10.php?acao=4"><img border="0" alt=""  width="83" height="80" src="images/top/star.png"><br>Doadores</a></td>
</tr></tbody></table>
  <?php 
  
  $cat= $_GET['cat'];
$sabado = 6; //sabado = 6º dia = fim da semana.
$dia_atual=date('w'); //pego o dia atual
$dias_que_faltam_para_o_sabado = $sabado - $dia_atual;

$inicio = strtotime("-$dia_atual days");
$fim = strtotime("+$dias_que_faltam_para_o_sabado days");

$datasem = date('Y-m-d H:i:s');  
$datas = date("m/Y", utc_to_tz_time($datasem));  

$data2 = date("Y-m-d", $inicio);  
$data21 = date("Y-m-d", $fim);  
$whereag=array();

if ($_GET["duration"] == 1) {
     $whereag[] = "AND date_format(added,'%Y-%m-%d')>='$data2' AND date_format(added,'%Y-%m-%d')<='$data21'";
	 $chave = "SEMANAL";
	 $trueinicio = date("m/d", $inicio); 
	 $trueifim = date("m/d", $fim); 
	 
	 $mensalinicio = "<font color='blue'>Início: </font><font color='red'>$trueinicio</font> <br><font color='blue'> Término: </font><font color='red'>$trueifim</font>";
}

if ($_GET["duration"] == 2) {
     $whereag[] = "AND date_format(added,'%m/%Y')='$datas'";
	 $chave = "MENSAL"; 
	 $mensalfim = date("m", utc_to_tz_time($datasem));  
	 $mensalinicio = "<font color='blue'>Início: </font><font color='red'>01/$mensalfim</font> <br><font color='blue'> Término: </font><font color='red'>".date("t")."/$mensalfim</font>";
 }



 $whereg = implode("AND", $whereag);
  ?>
  <br><br>
  <?php	
    if ($_GET['acao'] == 1 || !$_GET['acao'] ) {   
	begin_framec("TOP 10 $chave");
echo"$mensalinicio";
  ?>
<div align="justify" class="framecentro"><form id='sort' action=''><div align="right">Filtrar por categorias:<br><select name="cat" onchange="window.location='top10.php?acao=<?php echo $_GET["acao"] ?>&duration=<?php echo $_GET["duration"] ?>&cat='+this.options[this.selectedIndex].value">
     <option value="">Todas as Categorias</option>
     <?php foreach ( genrelist() as $category ): ?>
        <option value="<?php echo $category["id"]; ?>" <?php echo ($_GET['cat'] == $category["id"] ? " selected='selected'" : ""); ?>><?php echo $category["parent_cat"] . ' > ' . $category["name"]; ?></option>
     <?php endforeach; ?> 
     </select>   </div></form>
	 <center>[ <a href="top10.php?acao=1&amp;cat=<?php echo $cat ?>"> Top 10 Total </a> | <a href="top10.php?acao=<?php echo $_GET["acao"] ?>&amp;duration=<?php echo 2; ?>&cat=<?php echo $cat ?>">Top 10 Mensal</a> | <a href="top10.php?acao=<?php echo $_GET["acao"] ?>&amp;duration=<?php echo 1; ?>&cat=<?php echo $cat ?>">Top 10 Semanal</a> ]</center><table cellspacing="1" cellpadding="0" align="center" id="tabela1"><tbody><tr><td align="center" colspan="5" class="tab1_cab1">Torrents mais baixados</td></tr><tr><td width="10%" align="center" class="ttable_head"><b>Posição</b></td><td align="left" class="ttable_head"><b>Nome</b></td><td align="center" class="ttable_head"><b>Complet.</b></td><td align="center" class="ttable_head"><b>S</b></td><td align="center" class="ttable_head"><b>L</b></td></tr>
    <?php
 $queryc = SQL_Query_exec("SELECT id, added, category, leechers, seeders, name, times_completed FROM torrents  WHERE banned = 'no' AND safe = 'yes' " . ( is_valid_id($_GET['cat']) ? 'AND  category = \''.$_GET['cat'].'\' ' : null ) . "  ". $whereg ." ORDER BY times_completed DESC LIMIT 10");

	?>
 <tr>
  <?php $comp = 1; while ($rowc = mysql_fetch_assoc($queryc)): ?>
<td align="center" class="ttable_col6"><b><?php echo ($comp); ?></b></td>
<td align="left" class="ttable_col6"><a title="<?php echo $rowc["name"] ?>" href="torrents-details.php?id=<?php echo $rowc["id"] ?>&amp;"><b><?php echo $rowc["name"] ?></b></a></td>
<td align="center" class="ttable_col6"><b><?php echo number_format($rowc["times_completed"]) ?></b></td>
<td align="center" class="ttable_col6"><b><font color="green"><?php echo $rowc["seeders"] ?></font></b></td>
<td align="center" class="ttable_col6"><b><font color="red"><?php echo $rowc["leechers"] ?></font></b></td>
 </tr>
  <?php $comp++; endwhile; ?>
 <?php if ( mysql_num_rows($queryc) == 0 ): ?>
 <tr>
     <td class="ttable_col6" colspan="5" align="center"><b>Total de registros encontrados com este critério: 0</b></td>
 </tr>
 <?php endif; ?>
</tbody></table>

<br><table cellspacing="1" cellpadding="0" align="center" id="tabela1"><tbody><tr><td align="center" colspan="5" class="tab1_cab1">Torrents com mais seeders</td></tr><tr><td width="10%" align="center" class="ttable_head"><b>Posição</b></td><td align="left" class="ttable_head"><b>Nome</b></td><td align="center" class="ttable_head"><b>Complet.</b></td><td align="center" class="ttable_head"><b>S</b></td><td align="center" class="ttable_head"><b>L</b></td></tr>
     <?php
 $querys = SQL_Query_exec("SELECT id, added, comments, category, leechers, seeders, name, times_completed FROM torrents  WHERE banned = 'no' AND safe = 'yes' " . ( is_valid_id($_GET['cat']) ? 'AND  category = \''.$_GET['cat'].'\' ' : null ) . " ". $whereg ." ORDER BY seeders DESC LIMIT 10");

	?>
 <tr>
   <?php $cos = 1; while ($rows = mysql_fetch_assoc($querys)): ?>
<td align="center" class="ttable_col6"><b><?php echo ($cos); ?></b></td>
<td align="left" class="ttable_col6"><a title="<?php echo $rows["name"] ?>" href="torrents-details.php?id=<?php echo $rows["id"] ?>&amp;"><b><?php echo $rows["name"] ?></b></a></td>
<td align="center" class="ttable_col6"><b><?php echo number_format($rows["times_completed"]) ?></b></td>
<td align="center" class="ttable_col6"><b><font color="green"><?php echo $rows["seeders"] ?></font></b></td>
<td align="center" class="ttable_col6"><b><font color="red"><?php echo $rows["leechers"] ?></font></b></td>
 </tr>
  <?php $cos++; endwhile; ?>
 <?php if ( mysql_num_rows($querys) == 0 ): ?>
 <tr>
     <td class="ttable_col6" colspan="5" align="center"><b>Total de registros encontrados com este critério: 0</b></td>
 </tr>
 <?php endif; ?>
</tbody></table>





<br><table cellspacing="1" cellpadding="0" align="center" id="tabela1"><tbody><tr><td align="center" colspan="5" class="tab1_cab1">Torrents melhores avaliados</td></tr><tr><td width="10%" align="center" class="ttable_head"><b>Posição</b></td><td align="left" class="ttable_head"><b>Nome</b></td><td align="center" class="ttable_head"><b>Avaliação</b></td><td align="center" class="ttable_head"><b>S</b></td><td align="center" class="ttable_head"><b>L</b></td></tr>
     <?php
 $querya = SQL_Query_exec("SELECT id, added, comments, category, leechers, seeders, name, times_completed, numratings,  IF(numratings < 0, NULL, ROUND(ratingsum / numratings, 0)) AS rating, numratings FROM torrents  WHERE banned = 'no'  AND safe = 'yes' " . ( is_valid_id($_GET['cat']) ? 'AND  category = \''.$_GET['cat'].'\' ' : null ) . "  ". $whereg ." ORDER BY numratings DESC LIMIT 10");

	?>
 <tr>
   <?php $coa = 1; while ($rowa = mysql_fetch_assoc($querya)): ?>
<td align="center" class="ttable_col6"><b><?php echo ($coa); ?></b></td>
<td align="left" class="ttable_col6"><a title="<?php echo $rowa["name"] ?>" href="torrents-details.php?id=<?php echo $rowa["id"] ?>&amp;"><b><?php echo $rowa["name"] ?></b></a></td>
<td align="center" class="ttable_col6"><b><?php echo ratingpic($rowa["rating"]) ?><br>Votos <?php echo  $rowa["numratings"] ?></b></td>
<td align="center" class="ttable_col6"><b><font color="green"><?php echo $rowa["seeders"] ?></font></b></td>
<td align="center" class="ttable_col6"><b><font color="red"><?php echo $rowa["leechers"] ?></font></b></td>
 </tr>
  <?php $coa++; endwhile; ?>
 <?php if ( mysql_num_rows($querya) == 0 ): ?>
 <tr>
     <td class="ttable_col6" colspan="5" align="center"><b>Total de registros encontrados com este critério: 0</b></td>
 </tr>
 <?php endif; ?>

</tbody></table>








<br><table cellspacing="1" cellpadding="0" align="center" id="tabela1"><tbody><tr><td align="center" colspan="5" class="tab1_cab1">Torrents mais comentados</td></tr><tr><td width="10%" align="center" class="ttable_head"><b>Posição</b></td><td align="left" class="ttable_head"><b>Nome</b></td><td align="center" class="ttable_head"><b>Comentários</b></td><td align="center" class="ttable_head"><b>S</b></td><td align="center" class="ttable_head"><b>L</b></td></tr>
     <?php
 $querye = SQL_Query_exec("SELECT id, added, comments, category, leechers, seeders, name, times_completed FROM torrents  WHERE banned = 'no' AND safe = 'yes' " . ( is_valid_id($_GET['cat']) ? 'AND  category = \''.$_GET['cat'].'\' ' : null ) . "  ". $whereg ." ORDER BY comments DESC LIMIT 10");

	?>
 <tr>
   <?php $come = 1; while ($rowe = mysql_fetch_assoc($querye)): ?>
<td align="center" class="ttable_col6"><b><?php echo ($come); ?></b></td>
<td align="left" class="ttable_col6"><a title="<?php echo $rowe["name"] ?>" href="torrents-details.php?id=<?php echo $rowe["id"] ?>&amp;"><b><?php echo $rowe["name"] ?></b></a></td>
<td align="center" class="ttable_col6"><b><?php echo $rowe["comments"] ?></b></td>
<td align="center" class="ttable_col6"><b><font color="green"><?php echo $rowe["seeders"] ?></font></b></td>
<td align="center" class="ttable_col6"><b><font color="red"><?php echo $rowe["leechers"] ?></font></b></td>
 </tr>
  <?php $come++; endwhile; ?>
 <?php if ( mysql_num_rows($querye) == 0 ): ?>
 <tr>
     <td class="ttable_col6" colspan="5" align="center"><b>Total de registros encontrados com este critério: 0</b></td>
 </tr>
 <?php endif; ?>

</tbody></table>



<br><table cellspacing="1" cellpadding="0" align="center" id="tabela1"><tbody><tr><td align="center" colspan="5" class="tab1_cab1">Torrents com mais leechers</td></tr><tr><td width="10%" align="center" class="ttable_head"><b>Posição</b></td><td align="left" class="ttable_head"><b>Nome</b></td><td align="center" class="ttable_head"><b>Complet.</b></td><td align="center" class="ttable_head"><b>S</b></td><td align="center" class="ttable_head"><b>L</b></td></tr>
     <?php
 $queryl = SQL_Query_exec("SELECT id, added, comments, category, leechers, seeders, name, times_completed FROM torrents  WHERE banned = 'no'  AND safe = 'yes' " . ( is_valid_id($_GET['cat']) ? 'AND  category = \''.$_GET['cat'].'\' ' : null ) . "  ". $whereg ." ORDER BY leechers DESC LIMIT 10");

	?>
 <tr>
   <?php $col = 1; while ($rowl = mysql_fetch_assoc($queryl)): ?>
<td align="center" class="ttable_col6"><b><?php echo ($col); ?></b></td>
<td align="left" class="ttable_col6"><a title="<?php echo $rowl["name"] ?>" href="torrents-details.php?id=<?php echo $rowl["id"] ?>&amp;"><b><?php echo $rowl["name"] ?></b></a></td>
<td align="center" class="ttable_col6"><b><?php echo number_format($rowl["times_completed"]) ?></b></td>
<td align="center" class="ttable_col6"><b><font color="green"><?php echo $rowl["seeders"] ?></font></b></td>
<td align="center" class="ttable_col6"><b><font color="red"><?php echo $rowl["leechers"] ?></font></b></td>
 </tr>
  <?php $col++; endwhile; ?>
 <?php if ( mysql_num_rows($queryl) == 0 ): ?>
 <tr>
     <td class="ttable_col6" colspan="5" align="center"><b>Total de registros encontrados com este critério: 0</b></td>
 </tr>
 <?php endif; ?>
</tbody></table>



<br><table cellspacing="1" cellpadding="0" align="center" id="tabela1"><tbody><tr><td align="center" colspan="5" class="tab1_cab1">Torrents menos compartilhados</td></tr><tr><td width="10%" align="center" class="ttable_head"><b>Posição</b></td><td align="left" class="ttable_head"><b>Nome</b></td><td align="center" class="ttable_head"><b>Complet.</b></td><td align="center" class="ttable_head"><b>S</b></td><td align="center" class="ttable_head"><b>L</b></td></tr>
     <?php
 $queryp = SQL_Query_exec("SELECT id, added, comments, category, leechers, seeders, name, times_completed FROM torrents  WHERE banned = 'no' AND safe = 'yes' " . ( is_valid_id($_GET['cat']) ? 'AND  category = \''.$_GET['cat'].'\' ' : null ) . "  ". $whereg ." ORDER BY seeders / leechers ASC, leechers  DESC LIMIT 10");

	?>
 <tr>
   <?php $cop = 1; while ($rowp = mysql_fetch_assoc($queryp)): ?>
<td align="center" class="ttable_col6"><b><?php echo ($cop); ?></b></td>
<td align="left" class="ttable_col6"><a title="<?php echo $rowp["name"] ?>" href="torrents-details.php?id=<?php echo $rowp["id"] ?>&amp;"><b><?php echo $rowp["name"] ?></b></a></td>
<td align="center" class="ttable_col6"><b><?php echo number_format($rowp["times_completed"]) ?></b></td>
<td align="center" class="ttable_col6"><b><font color="green"><?php echo $rowp["seeders"] ?></font></b></td>
<td align="center" class="ttable_col6"><b><font color="red"><?php echo $rowp["leechers"] ?></font></b></td>
 </tr>
  <?php $cop++; endwhile; ?>
 <?php if ( mysql_num_rows($queryp) == 0 ): ?>
 <tr>
     <td class="ttable_col6" colspan="5" align="center"><b>Total de registros encontrados com este critério: 0</b></td>
 </tr>
 <?php endif; ?>
</tbody></table>

<br></div>
<div class="clr"></div>
</div>
<?php	
  end_framec();
  }
  if ($_GET['acao'] == 2) {    
  begin_framec("TOP 10");
  $whereus=array();
if ($_GET["p"] == 2) {
     $whereus[] = "AND date_format(torrents.added,'%Y-%m-%d')>='$data2' AND date_format(torrents.added,'%Y-%m-%d')<='$data21'";
	 $chaveu = "SEMANAL";
	 $trueiniciou = date("m/d", $inicio); 
	 $trueifimu = date("m/d", $fim); 
	 
	 $mensaliniciou = "<font color='blue'>Início: </font><font color='red'>$trueiniciou</font> <br><font color='blue'> Término: </font><font color='red'>$trueifimu</font>";
}

if ($_GET["p"] == 1) {
     $whereus[] = "AND date_format(torrents.added,'%m/%Y')='$datas'";
	 $chaveu = "MENSAL"; 
	 $mensalfimu = date("m", utc_to_tz_time($datasem));  
	 $mensaliniciou = "<font color='blue'>Início: </font><font color='red'>01/$mensalfimu</font> <br><font color='blue'> Término: </font><font color='red'>".date("t")."/$mensalfimu</font>";
 }
  $whereg = implode("AND", $whereus);
  echo"$mensaliniciou";
  ?>
  <div align="justify" class="blockContent"><center>[ <a href="top10.php?acao=2">Top 10 Total</a> | <a href="top10.php?acao=2&amp;p=1">Top 10 Mensal</a> | <a href="top10.php?acao=2&amp;p=2">Top 10 Semanal</a> ]</center><table cellspacing="1" cellpadding="0" align="center" id="tabela1"><tbody><tr><td align="center" colspan="3" class="tab1_cab1">Usuários que mais fazem lançamentos</td></tr>
  
  
  <?php  if ($_GET['p'] == 1 || $_GET['p'] == 2  || !$_GET['p'] ) {   ?> 
  <tr>
<td width="10%" align="center" class="ttable_head"><b>Posição</b></td>
<td align="center" class="ttable_head"><b>Usuário</b></td>
<td align="center" class="ttable_head"><b>Lançamentos</b></td>
</tr>
     <?php

  $toptorup = mysql_query("SELECT users.id, users.username, torrents.added, torrents.banned, torrents.safe, COUNT(torrents.owner) as num FROM torrents LEFT JOIN users ON users.id = torrents.owner WHERE torrents.banned = 'no' AND torrents.safe = 'yes'  ". $whereg ."GROUP BY owner ORDER BY num DESC LIMIT 10");
	?>
<tr>
   <?php $topcont = 1; while ($rowtup = mysql_fetch_assoc($toptorup)): ?>
   
<td width="10%" align="center" class="ttable_col6"><?php echo ($topcont); ?></td>
<td align="center" class="ttable_col6"><a href="account-details.php?id=<?php echo $rowtup["id"] ?>"><?php echo $rowtup["username"] ?></a></td>
<td width="20%" align="center" class="ttable_col6"><?php echo $rowtup["num"]?></td>
  </tr>
 <?php $topcont++; endwhile; ?>
 <?php if ( mysql_num_rows($toptorup) == 0 ): ?>
 <tr>
     <td class="ttable_col6" colspan="5" align="center"><b>Total de registros encontrados com este critério: 0</b></td>
 </tr>
 <?php endif; ?>

  <?php }  ?> 
</tbody></table>



<br>
<?php  if ($_GET['p'] == 1 || $_GET['p'] == 2  ) {   } else{?> 
















<table cellspacing="1" cellpadding="0" align="center" id="tabela1"><tbody><tr><td align="center" colspan="5" class="tab1_cab1">Usuários mais semeadores</td></tr>
  
  
<tr>
<td width="10%" align="center" class="ttable_head"><b>Posição</b></td>
<td align="center" class="ttable_head"><b>Usuário</b></td>
<td align="center" class="ttable_head"><b>Semeado</b></td>
<td align="center" class="ttable_head"><b>Baixado</b></td>
<td align="center" class="ttable_head"><b>Ratio</b></td>
</tr>
     <?php

  $topuseup = mysql_query("SELECT id, username, uploaded, downloaded FROM users WHERE enabled = 'yes' ORDER BY uploaded DESC LIMIT 10") or sqlerr();
	?>
<tr>
   <?php $topupuser = 1; while ($rowuseup = mysql_fetch_assoc($topuseup)):

					if ($rowuseup["downloaded"] > 0) {
$ratio = number_format($rowuseup["uploaded"] / $rowuseup["downloaded"], 3);
$ratio = "<font color=" . get_ratio_color($ratio) . ">$ratio</font>";
} else {
if ($rowuseup["uploaded"] > 0) {
$ratio = "Indefinido.";
}
else {
$ratio = "---";
}
}
   ?>
<td align="center" class="ttable_col6"><b><?php echo ($topupuser); ?></b></td>
<td align="center" class="ttable_col6"><a href="account-details.php?id=<?php echo $rowuseup["id"]?>"><?php echo $rowuseup["username"]?></a></td>
<td align="center" class="ttable_col6"><b><font color="green"><?php echo mksize($rowuseup["uploaded"])?></font></b></td>
<td align="center" class="ttable_col6"><b><font color="red"><?php echo mksize($rowuseup["downloaded"])?></font></b></td>
<td align="center" class="ttable_col6"><b><font color="#000000"><?php echo $ratio ;?></font></b></td>
</tr>
 <?php $topupuser++; endwhile; ?>
 <?php if ( mysql_num_rows($toptorup) == 0 ): ?>
 <tr>
     <td class="ttable_col6" colspan="5" align="center"><b>Total de registros encontrados com este critério: 0</b></td>
 </tr>
 <?php endif; ?>
<?php }?>




















<table cellspacing="1" cellpadding="0" align="center" id="tabela1"><tbody><tr><td align="center" colspan="5" class="tab1_cab1">Usuários que mais baixam</td></tr><tr>
<td width="10%" align="center" class="ttable_head"><b>Posição</b></td>
<td align="center" class="ttable_head"><b>Usuário</b></td>
<td align="center" class="ttable_head"><b>Semeado</b></td>
<td align="center" class="ttable_head"><b>Baixado</b></td>
<td align="center" class="ttable_head"><b>Ratio</b></td>
</tr>
     <?php

  $topusedow = mysql_query("SELECT id, username, uploaded, downloaded FROM users WHERE enabled = 'yes' ORDER BY downloaded DESC LIMIT 10") or sqlerr();
	?>
<tr>
   <?php $topupudow = 1; while ($rowusedow = mysql_fetch_assoc($topusedow)):

					if ($rowusedow["downloaded"] > 0) {
$ratiod = number_format($rowusedow["uploaded"] / $rowusedow["downloaded"], 3);
$ratiod = "<font color=" . get_ratio_color($ratiod) . ">$ratio</font>";
} else {
if ($rowusedow["uploaded"] > 0) {
$ratiod = "Indefinido.";
}
else {
$ratiod = "---";
}
}
   ?>
<td align="center" class="ttable_col6"><b><?php echo ($topupudow); ?></b></td>
<td align="center" class="ttable_col6"><a href="account-details.php?id=<?php echo $rowusedow["id"]?>"><?php echo $rowusedow["username"]?></a></td>
<td align="center" class="ttable_col6"><b><font color="green"><?php echo mksize($rowusedow["uploaded"])?></font></b></td>
<td align="center" class="ttable_col6"><b><font color="red"><?php echo mksize($rowusedow["downloaded"])?></font></b></td>
<td align="center" class="ttable_col6"><b><font color="#000000"><?= $ratiod ?></font></b></td>
</tr>
 <?php $topupudow++; endwhile; ?>
 <?php if ( mysql_num_rows($topusedow) == 0 ): ?>
 <tr>
     <td class="ttable_col6" colspan="5" align="center"><b>Total de registros encontrados com este critério: 0</b></td>
 </tr>
 <?php endif; ?>
</tbody></table><br><table cellspacing="1" cellpadding="0" align="center" id="tabela1"><tbody><tr><td align="center" colspan="3" class="tab1_cab1">Usuários que mais atenderam a pedidos de torrents</td></tr><tr>
<td width="10%" align="center" class="ttable_head"><b>Posição</b></td>
<td align="center" class="ttable_head"><b>Usuário</b></td>
<td align="center" class="ttable_head"><b>Posts</b></td>
</tr>
     <?php

  $topaprovar = mysql_query("SELECT id, username, quantpe FROM users WHERE enabled = 'yes' ORDER BY quantpe DESC LIMIT 10") or sqlerr();
	?>
<tr>
   <?php $topcontap = 1; while ($rowaprova = mysql_fetch_assoc($topaprovar)):?>
<td width="10%" align="center" class="ttable_col6"><?php echo ($topcontap); ?></td>
<td align="center" class="ttable_col6"><a href="usuario.php?id=<?php echo $rowaprova["id"]?>"><?php echo $rowaprova["username"]?></a></td>
<td width="20%" align="center" class="ttable_col6"><?php echo $rowaprova["quantpe"]?></td>
</tr>
 <?php $topcontap++; endwhile; ?>
 <?php if ( mysql_num_rows($topaprovar) == 0 ): ?>
 <tr>
     <td class="ttable_col6" colspan="5" align="center"><b>Total de registros encontrados com este critério: 0</b></td>
 </tr>
 <?php endif; ?>
 
</tbody></table>
<br>
<table cellspacing="1" cellpadding="0" align="center" id="tabela1"><tbody><tr><td align="center" colspan="3" class="tab1_cab1">Usuários com maior tempo de seed</td></tr><tr>
<td width="10%" align="center" class="ttable_head"><b>Posição</b></td>
<td align="center" class="ttable_head"><b>Usuário</b></td>
<td align="center" class="ttable_head"><b>Tempo de seed</b></td>
</tr>

     <?php

  $toptempo = mysql_query("SELECT id, username, seedtime FROM users ORDER BY seedtime DESC LIMIT 10"); 
	?>
<tr>
   <?php $topconttem = 1; while ($rowtemps = mysql_fetch_assoc($toptempo)): 
     $leechtime = ( $rowtemps[ seedtime ] ) ? seedtimenovo( $rowtemps[ seedtime ] ) : '-';
   ?>
   

<td width="10%" align="center" class="ttable_col6"><?php echo ($topconttem); ?></td>
<td align="center" class="ttable_col6"><a href="usuario.php?id=<?php echo $rowtemps["id"]?>"><?php echo $rowtemps["username"]?></a></td>
<td align="center" class="ttable_col6"><?php echo $leechtime ?></td>
</tr>
 <?php $topconttem++; endwhile; ?>
 <?php if ( mysql_num_rows($toptempo) == 0 ): ?>
 <tr>
     <td class="ttable_col6" colspan="5" align="center"><b>Total de registros encontrados com este critério: 0</b></td>
 </tr>
 <?php endif; ?>
</tbody></table><br></div>
  <?php
    end_framec();
  }
    if ($_GET['acao'] == 3) {    
  begin_framec("TOP 10");
?>
<div align="justify" class="blockContent"><table cellspacing="1" cellpadding="0" align="center" id="tabela1"><tbody><tr><td align="center" colspan="3" class="tab1_cab1">Países com mais usuários</td></tr><tr>
<td width="10%" align="center" class="ttable_head"><b>Posição</b></td>
<td align="left" class="ttable_head"><b>Localização</b></td>
<td width="70" align="center" class="ttable_head"><b>Usuários</b></td>
</tr>
     <?php

  $toppais = mysql_query("SELECT name, flagpic, COUNT(users.country) as num FROM countries LEFT JOIN users ON users.country = countries.id WHERE users.enabled = 'yes' GROUP BY name ORDER BY num DESC LIMIT 10") or sqlerr();
	?>
<tr>
   <?php $contpais= 1; while ($rowpais= mysql_fetch_assoc($toppais)):?>
<td align="center" class="ttable_col6"><b><?php echo ($contpais); ?></b></td>
<td align="left" class="ttable_col6"><img align="middle" src="/images/flag/<?php echo $rowpais["flagpic"]; ?>"> &nbsp;<b><?php echo $rowpais["name"]; ?></b></td>
<td align="center" class="ttable_col6"><b><?php echo $rowpais["num"]; ?></b></td>

</tr>
 <?php $contpais++; endwhile; ?>
 <?php if ( mysql_num_rows($toppais) == 0 ): ?>
 <tr>
     <td class="ttable_col6" colspan="5" align="center"><b>Total de registros encontrados com este critério: 0</b></td>
 </tr>
 <?php endif; ?>
</tbody></table>


<br><br><table cellspacing="1" cellpadding="0" align="center" id="tabela1"><tbody><tr><td align="center" colspan="3" class="tab1_cab1">Países com mais lançamentos</td></tr><tr>
<td width="10%" align="center" class="ttable_head"><b>Posição</b></td>
<td align="left" class="ttable_head"><b>Localização</b></td>
<td width="70" align="center" class="ttable_head"><b>Lançamentos</b></td>
</tr>
     <?php

  $toppaisup = mysql_query("SELECT countries.name, countries.flagpic, torrents.owner, users.id, COUNT(torrents.owner) as num FROM countries LEFT JOIN users ON users.country = countries.id  LEFT JOIN torrents ON torrents.owner = users.id WHERE users.enabled = 'yes' GROUP BY name ORDER BY num DESC LIMIT 10");
	?>
<tr>
   <?php $contpaisup= 1; while ($rowpaisup= mysql_fetch_assoc($toppaisup)):?>
<td align="center" class="ttable_col6"><b><?php echo ($contpaisup); ?></b></td>
<td align="left" class="ttable_col6"><img align="middle" src="images/flag/<?php echo $rowpaisup["flagpic"]; ?>">&nbsp;<b><?php echo $rowpaisup["name"]; ?></b></td>
<td align="center" class="ttable_col6"><b><?php echo $rowpaisup["num"]; ?></b></td></tr>
 <?php $contpaisup++; endwhile; ?>
 <?php if ( mysql_num_rows($toppaisup) == 0 ): ?>
 <tr>
     <td class="ttable_col6" colspan="5" align="center"><b>Total de registros encontrados com este critério: 0</b></td>
 </tr>
 <?php endif; ?>

</tbody></table><br><br><table cellspacing="1" cellpadding="0" align="center" id="tabela1"><tbody><tr><td align="center" colspan="3" class="tab1_cab1">Países com maior upload</td></tr><tr>
<td width="10%" align="center" class="ttable_head"><b>Posição</b></td>
<td align="left" class="ttable_head"><b>Localização</b></td>
<td width="70" align="center" class="ttable_head"><b>Upload Total</b></td>
</tr>
     <?php

  $toptotalup = mysql_query("SELECT c.name, c.flagpic, sum(u.uploaded) AS ul FROM users AS u LEFT JOIN countries AS c ON u.country = c.id WHERE u.enabled = 'yes' GROUP BY c.name ORDER BY ul DESC LIMIT 10") or sqlerr();
	?>
<tr>
   <?php $conttoup= 1; while ($rowtoup= mysql_fetch_assoc($toptotalup)):?>
<td align="center" class="ttable_col6"><b><?php echo ($conttoup); ?></b></td>
<td align="left" class="ttable_col6"><img align="middle" src="images/flag/<?php echo $rowtoup["flagpic"]; ?>">&nbsp;<b><?php echo $rowtoup["name"]; ?></b></td>
<td align="center" class="ttable_col6"><b><?php echo mksize($rowtoup["ul"]); ?></b></td></tr>
 <?php $conttoup++; endwhile; ?>
 <?php if ( mysql_num_rows($toptotalup) == 0 ): ?>
 <tr>
     <td class="ttable_col6" colspan="5" align="center"><b>Total de registros encontrados com este critério: 0</b></td>
 </tr>
 <?php endif; ?>
</tbody></table><br><br><table cellspacing="1" cellpadding="0" align="center" id="tabela1"><tbody><tr><td align="center" colspan="3" class="tab1_cab1">Estados com mais usuários</td></tr><tr>
<td width="10%" align="center" class="ttable_head"><b>Posição</b></td>
<td align="left" class="ttable_head"><b>Localização</b></td>
<td width="70" align="center" class="ttable_head"><b>Usuários</b></td>
</tr>
     <?php

  $topesta= mysql_query("SELECT name, flagpic, COUNT(users.estado) as num FROM estados LEFT JOIN users ON users.estado = estados.id WHERE users.enabled = 'yes' GROUP BY name ORDER BY num DESC LIMIT 10") or sqlerr();
	?>
<tr>
   <?php $contestado= 1; while ($rowesta= mysql_fetch_assoc($topesta)):?>

<td align="center" class="ttable_col6"><b><?php echo ($contestado); ?></b></td>
<td align="left" class="ttable_col6"><img align="middle" src="images/estado/<?php echo $rowesta["flagpic"]; ?>">&nbsp;<b><?php echo $rowesta["name"]; ?></b></td>
<td align="center" class="ttable_col6"><b><?php echo $rowesta["num"]; ?></b></td></tr>
 <?php $contestado++; endwhile; ?>
 <?php if ( mysql_num_rows($topesta) == 0 ): ?>
 <tr>
     <td class="ttable_col6" colspan="5" align="center"><b>Total de registros encontrados com este critério: 0</b></td>
 </tr>
 <?php endif; ?>
</tbody></table><br><br><table cellspacing="1" cellpadding="0" align="center" id="tabela1"><tbody><tr><td align="center" colspan="3" class="tab1_cab1">Estados com mais lançamentos</td></tr><tr>
<td width="10%" align="center" class="ttable_head"><b>Posição</b></td>
<td align="left" class="ttable_head"><b>Localização</b></td>
<td width="70" align="center" class="ttable_head"><b>Lançamentos</b></td>
</tr>
     <?php

  $estadoup = mysql_query("SELECT estados.name, estados.flagpic, torrents.owner, users.id, COUNT(torrents.owner) as num FROM estados LEFT JOIN users ON users.estado = estados.id  LEFT JOIN torrents ON torrents.owner = users.id WHERE users.enabled = 'yes' GROUP BY name ORDER BY num DESC LIMIT 10");
	?>
<tr>
   <?php $contestadoup= 1; while ($rowestadoup= mysql_fetch_assoc($estadoup)):?>
<td align="center" class="ttable_col6"><b><?php echo ($contestadoup); ?></b></td>
<td align="left" class="ttable_col6"><img align="middle" src="images/estado/<?php echo $rowestadoup["flagpic"]; ?>">&nbsp;<b><?php echo $rowestadoup["name"]; ?></b></td>
<td align="center" class="ttable_col6"><b><?php echo $rowestadoup["num"]; ?></b></td></tr>
 <?php $contestadoup++; endwhile; ?>
 <?php if ( mysql_num_rows($estadoup) == 0 ): ?>
 <tr>
     <td class="ttable_col6" colspan="5" align="center"><b>Total de registros encontrados com este critério: 0</b></td>
 </tr>
 <?php endif; ?>

</tbody></table><br><br><table cellspacing="1" cellpadding="0" align="center" id="tabela1"><tbody><tr><td align="center" colspan="3" class="tab1_cab1">Estados com mais upload</td></tr><tr>
<td width="10%" align="center" class="ttable_head"><b>Posição</b></td>
<td align="left" class="ttable_head"><b>Localização</b></td>
<td width="70" align="center" class="ttable_head"><b>Upload Total</b></td>
</tr>
     <?php

  $topupestado = mysql_query("SELECT c.name, c.flagpic, sum(u.uploaded) AS ul FROM users AS u LEFT JOIN estados AS c ON u.estado = c.id WHERE u.enabled = 'yes' GROUP BY c.name ORDER BY ul DESC LIMIT 10") or sqlerr();
	?>
<tr>
   <?php $contupestado= 1; while ($rowupestado= mysql_fetch_assoc($topupestado)):?>
<td align="center" class="ttable_col6"><b><?php echo ($contupestado); ?></b></td>
<td align="left" class="ttable_col6"><img align="middle" src="images/estado/<?php echo $rowupestado["flagpic"]; ?>">&nbsp;<b><?php echo $rowupestado["name"]; ?></b></td>
<td align="center" class="ttable_col6"><b><?php echo mksize($rowupestado["ul"]); ?></b></td></tr>
 <?php $contupestado++; endwhile; ?>
 <?php if ( mysql_num_rows($topupestado) == 0 ): ?>
 <tr>
     <td class="ttable_col6" colspan="5" align="center"><b>Total de registros encontrados com este critério: 0</b></td>
 </tr>
 <?php endif; ?>
</tbody></table><br><br></div>
    <?php
    end_framec();
  }
    if ($_GET['acao'] == 4) {    
  begin_framec("TOP 10");
  echo"4";
    end_framec();
  }
  end_framec();
  stdfoot();
?>