<?php
############################################################
#######                                             ########
#######                                             ########
#######           malucos-share.org 2.0             ########
#######                                             ########
#######                                             ########
############################################################
require_once("backend/functions.php");
require_once("backend/bbcode.php");
dbconn(false);
loggedinonly();




if($CURUSER["view_users"]=="no")
	show_error_msg(T_("ERROR"), T_("NO_USER_VIEW"), 1);

stdhead("User CP");

$id = (int)$_GET["id"];

if (!is_valid_id($id))
  show_error_msg(T_("NO_SHOW_DETAILS"), "Bad ID.",1);

$r = @mysql_query("SELECT * FROM users WHERE id=$id");
$user = mysql_fetch_array($r) or  show_error_msg(T_("NO_SHOW_DETAILS"), T_("NO_USER_WITH_ID")." $id.",1);

//add invites check here

if (( ($user["status"] == "pending")) && $CURUSER["edit_users"] == "no")
	show_error_msg(T_("ERROR"), T_("NO_ACCESS_ACCOUNT_DISABLED"), 1);

//get all vars first

//$country
$res = mysql_query("SELECT name FROM countries WHERE id=$user[country] LIMIT 1");
if (mysql_num_rows($res) == 1){
	$arr = mysql_fetch_assoc($res);
	$country = "$arr[name]";
}

if (!$country) $country = "<b>Indefinido</b>";

//$country

$res = mysql_query("SELECT name,flagpic FROM estados WHERE id=$user[estado]");
if (mysql_num_rows($res) == 1){
	$arr = mysql_fetch_assoc($res);
	$estados = "$arr[name]";
}

if (!$estados) $estados = "<b>Indefinido</b>";

//$ratio
if ($user["downloaded"] > 0) {
    $ratio = $user["uploaded"] / $user["downloaded"];
}else{
	$ratio = "---";
}

   $loginstatus = "" . (get_elapsed_time(sql_timestamp_to_unix_timestamp($user["last_access"]))) . " atrás";

   

$numtorrents = get_row_count("torrents", "WHERE owner = $id");
$numcomments = get_row_count("comments", "WHERE user = $id");
$numforumposts = get_row_count("forum_posts", "WHERE userid = $id");

$torrenteslan = SQL_Query_exec("SELECT COUNT(*) FROM torrentlancado WHERE uid=$id");
$rowlan = mysql_fetch_row($torrenteslan);
$lantorrent = $rowlan[0];

$avatar = htmlspecialchars($user["avatar"]);
	if (!$avatar) {
		$avatar = $site_config["SITEURL"]."/images/default_avatar.gif";
	}
$connectable = get_row_count("peers", "WHERE connectable='yes' AND userid=$id");
$unconnectable = get_row_count("peers", "WHERE connectable='no' AND userid=$id");

if ($unconnectable){
$conectado = "<font color='#ff0000'>Não</font>";}
elseif ($connectable){
$conectado = "<font color='#258016'>Sim</font>";}	
else{
$conectado="N/A";
}
if ($user["downloaded"] > 0) {
	       $ratio = $user["uploaded"] / $user["downloaded"];
        $color = get_ratio_color($ratio);
        $ratio = number_format($ratio, 2);
        if ($color){
          $ratio = "<font  >$ratio</font>";
      }
      else{
        $ratio = "Inf.";
		}
		}   
      else{
        $ratio = "Inf.";
		}
function peerstable($res){
	$ret = "<table align='center' cellpadding=\"3\" cellspacing=\"0\" class=\"table_table\" width=\"100%\" border=\"1\"><tr><th class='table_head'>".T_("NAME")."</th><th class='table_head'>".T_("SIZE")."</th><th class='table_head'>" .T_("UPLOADED"). "</th>\n<th class='table_head'>" .T_("DOWNLOADED"). "</th><th class='table_head'>" .T_("RATIO"). "</th></tr>\n";

	while ($arr = mysql_fetch_assoc($res)){
		$res2 = mysql_query("SELECT name,size FROM torrents WHERE id=$arr[torrent] ORDER BY name");
		$arr2 = mysql_fetch_assoc($res2);
		
	
		
		
		
		

		$ret .= "<tr><td class='tab1_col3'><a href='torrents-details.php?id=$arr[torrent]&amp;hit=1'><b>" . htmlspecialchars($arr2["name"]) . "</b></a></td><td align='center' class='tab1_col3'>" . mksize($arr2["size"]) . "</td><td align='center' class='tab1_col3'>" . mksize($arr["uploaded"]) . "</td><td align='center' class='tab1_col3'>" . mksize($arr["downloaded"]) . "</td><td align='center' class='tab1_col3'>$ratio</td></tr>\n";
  }
  $ret .= "</table>\n";
  return $ret;
}



//Layout
stdhead(sprintf(T_("USER_DETAILS_FOR"), $user["username"]));

begin_framec(sprintf(T_("USER_DETAILS_FOR"), $user["username"]));

?>
<?php
        if ($user["signature"])
 $usersignature = stripslashes(format_comment($user["signature"]));
        ?>
<table width="100%">
<tr>
<td align="center" width="9%" >
	<a href="account-details.php?id=<?php echo $user["id"] ;?>"><img border="0" title="<?php echo T_("ACCOUNT_PERFIL"); ?>" src="images/icones/contadetalhes/perfil.png"><br><b>Perfil</b></a></td>
	
	<td align="center" width="9%" ><img border="0"  src="images/icones/contadetalhes/separado.png"></td>
	
	<td align="center" width="9%" >
	
	<a href="account-details.php?id=<?php echo $user["id"]; ?>&action=inform"><img border="0" title="<?php echo T_("ACCOUNT_INFOR_ADICIONA"); ?>" src="images/icones/contadetalhes/informa.png"><br><b>Informações</b></a></td>
	<td align="center" width="9%" >
   <img border="0"  src="images/icones/contadetalhes/separado.png"></td>
	
	<td align="center" width="9%" >
	<a href="account-details.php?id=<?php echo $user["id"]; ?>&action=statis"><img border="0" title="<?php echo T_("ACCOUNT_ESTA_USER"); ?>" src="images/icones/contadetalhes/estatistica.png"><br><b>Estatísticas</b></a></td>
	
	<td align="center" width="9%" >
	<img border="0"src="images/icones/contadetalhes/separado.png"></td>
	
	<td align="center" width="9%" >
	<a href="account-details.php?id=<?php echo $user["id"]; ?>&action=torrentes"><img border="0" title="<?php echo T_("ACCOUNT_ESTA_TORRENT"); ?>" src="images/icones/contadetalhes/statistics.png"><br><b>Torrentes</b></a></td>
	
	<td align="center" width="9%" >
    <img border="0" src="images/icones/contadetalhes/separado.png"></td>
	
	<td align="center" width="9%" >
	<a href="account-details.php?id=<?php echo $user["id"]; ?>&action=afilhados"><img border="0" title="<?php echo T_("ACCOUNT_AFILHADOS"); ?>" src="images/icones/contadetalhes/afilhados.png"><br><b>Afilhados</b></a></td>	
	
	<td align="center" width="9%" >
    <img border="0" src="images/icones/contadetalhes/separado.png"></td>
		<td align="center"><a href='forumhistory.php?id=<?php echo $user["id"]; ?>'><img src="images/icones/contadetalhes/historico.png" border=0 title='<?php echo ("Histórico Fórum"); ?>'><br><b>Hist.Fórum</b></a></br></td>
	
</tr>
</table>
	<?php

end_framec();


if ($_GET['action'] == "afilhados") {   
$rel = SQL_Query_exec("SELECT COUNT(*) FROM users WHERE status = 'confirmed' AND  invited_by = $user[id]") or sqlerr();
$arro = mysql_fetch_row($rel);
$number = $arro[0];
 $num1 = get_row_count("users", "WHERE  status = 'confirmed' AND invited_by = $user[id]");

list($pagertop, $pagerbottom, $limit) = pager(20, $num1, "account-details.php?id=".$user["id"]."&action=afilhados&amp;");


$ret = SQL_Query_exec("SELECT id, username, email, uploaded, downloaded, status, warned, enabled, donated, email FROM users WHERE status = 'confirmed' AND invited_by = ".$user["id"]."  $limit") or sqlerr();
$num = mysql_num_rows($ret); 

begin_framec(" Afiliados de " . $user["username"] . " ");

	?>
			<table width="100%" class='tab1' cellpadding='0' cellspacing='1' align='center'>
			<tbody>
			<tr>
			<td align="center" colspan="0" class="ttable_head" >Nick</td>
			<td align="center" colspan="0" class="ttable_head" >Semeado</td>
			<td align="center" colspan="0" class="ttable_head" >Baixado</td>
			<td align="center" colspan="0" class="ttable_head" >Ratio</td>
			</tr>
					<?php	for ($i = 0; $i < $num; ++$i)

{     
					$arr = mysql_fetch_assoc($ret);
					if ($arr["downloaded"] > 0) {
$ratio = number_format($arr["uploaded"] / $arr["downloaded"], 3);
$ratio = "<font color=" . get_ratio_color($ratio) . ">$ratio</font>";
} else {
if ($arr["uploaded"] > 0) {
$ratio = "Indefinido.";
}
else {
$ratio = "---";
}
}
  ?>
			<tr>

			<td align="center" class="tab1_col3"><a href="account-details.php?id=<?php echo $arr["id"]; ?>"><?php echo $arr["username"]; ?></a></td>
			<td align="center" class="tab1_col3"><?php echo  mksize($arr[uploaded]); ?></td>
			<td align="center" class="tab1_col3"><?php echo mksize($arr[downloaded]); ?></td>
		    <td align="center" class="tab1_col3"><?php echo $ratio ;?></td>
			</tr>
					<?php	}      ?>
			</tbody></table>

<?php
echo $pagertop;
end_framec();

}

if ($_GET['action'] == "torrentes") {    
begin_framec("" . $user["username"] . " Estatísticas de Torrentes");


			?>
			<table width="100%" class='tab1' cellpadding='0' cellspacing='1' align='center'>
			<tbody>
			<tr>
			<td align="center" colspan="2" class="ttable_head" >Torrents</td>
			</tr>
			<tr><td align="center" class="tab1_col3"><a href="lancados.php?id=<?php echo $user["id"] ;?>">Torrents lançados</a></td>
			<td align="center" class="tab1_col3"><a href="baixados.php?id=<?php echo $user["id"]; ?>">Torrents baixados</a></td></tr>
			<tr>
			<td align="center" class="tab1_col3"><a href="semeando.php?id=<?php echo $user["id"]; ?>">Semeando no momento</a></td>
			<td align="center" class="tab1_col3"><a href="baixando.php?id=<?php echo $user["id"]; ?>">Baixando no momento</a></td>
			</tr></tbody></table>
<?php
end_framec();
}




if ($_GET['action'] == "staff") {    
begin_framec(" " . $user["username"] . " (".T_("ACCOUNT_ESTA_TISTICA").")");
	?>
	<table width="40%" cellspacing="2" cellpadding="1" border="1" class="table_table">


<tr>
<td align="center"><a href='forumhistory.php?id=<?php echo $user["id"]; ?>'><img src="images/icones/contadetalhes/historico.png" border=0 title='<?php echo T_("ACCOUNT_HIST_FORUM"); ?>'></td>
 
</table>

<?php

end_framec();
}

if ($_GET['action'] == "statis") {
begin_framec(" " . $user["username"] . " (".T_("ACCOUNT_ESTA_TISTICA").")");
	?>
	<table width="100%" class='tab1' cellpadding='0' cellspacing='1' align='center'>


<tr>
<td width="10%" class="tab1_col3" align="right"><font  size="2">Semeado: </font></td>
<td width="10%" class="tab1_col3"><font  size="2"> <?php echo mksize($user["uploaded"]); ?></font></td>
</tr>
<tr>
<td width="10%" class="tab1_col3" align="right"><font  size="2">Baixado: </font></td>
<td width="10%" class="tab1_col3"><font  size="2"> <?php echo mksize($user["downloaded"]); ?></font></td>
</tr>
<tr>
<td width="10%" class="tab1_col3" align="right"><font  size="2"><?php echo T_("RATIO"); ?>: </font></td>
<td width="10%" class="tab1_col3"><font  size="2"> <?php echo $ratio ;?></font></td>
</tr>
<tr>
<td width="10%" class="tab1_col3" align="right"><font  size="2"><?php echo T_("AVG_DAILY_UL"); ?>: </font></td>
<td width="10%" class="tab1_col3"><font  size="2"> <?php echo mksize($user["uploaded"] / (DateDiff($user["added"], time()) / 86400)); ?></font></td>
</tr>
<tr>
<td width="10%" class="tab1_col3" align="right"><font  size="2"><?php echo T_("AVG_DAILY_DL"); ?>: </font></td>
<td width="10%" class="tab1_col3"><font  size="2"><?php echo mksize($user["downloaded"] / (DateDiff($user["added"], time()) / 86400)); ?></font></td>
</tr>
<tr>
<td width="10%" class="tab1_col3" align="right"><font  size="2">Conectável: </font></td>
<td width="10%" class="tab1_col3"><?php echo $conectado; ?></td>
</tr>
<tr>
<td width="10%" class="tab1_col3" align="right"><font  size="2">Torrents lançados: </font></td>
<td width="10%" class="tab1_col3"><font  size="2"> <?php echo $lantorrent; ?></font></td>
</tr>
<tr>
<td width="10%" class="tab1_col3" align="right"><font  size="2">Torrents ativos: </font></td>
<td width="10%" class="tab1_col3"><font  size="2"> <?php echo $numtorrents; ?></font></td>
</tr>
<tr>
<td width="10%" class="tab1_col3" align="right"><font  size="2"><?php echo T_("COMMENTS_POSTED"); ?>: </font></td>
<td width="10%" class="tab1_col3"><font  size="2"> <?php echo $numcomments; ?></font></td>
</tr>
<tr>
<td width="10%" class="tab1_col3" align="right"><font  size="2"><?php echo T_("COMMENTS_FORUM_POST"); ?>: </font></td>
<td width="10%" class="tab1_col3"><font  size="2"> <?php echo number_format($numforumposts); ?></font></td>
</tr>
<tr>
<td width="10%" class="tab1_col3" align="right"><font  size="2">Tempo de seed:</font></td>
<td width="10%" class="tab1_col3"><font  size="2"> <?php echo ( $user['seedtime'] > 0 ) ? seedtimenovo($user['seedtime']) : 'N/A'; ?></font></td>
</tr>
<tr>
<td width="10%" class="tab1_col3" align="right"><font  size="2">MS Pontos:</font></td>
<td width="10%" class="tab1_col3"><font  size="2"> <?php echo htmlspecialchars($user["seedbonus"]); ?></font></td>
</tr>
</table>

<?php

end_framec();
}
if ($_GET['action'] == "inform") {
begin_framec(" " . $user["username"] . " (".T_("ACCOUNT_INFOR_MACAO").")");
if ($user["freeleechuser"] == "yes")
{     
        if ($user["freeleechexpire"] == "0000-00-00 00:00:00")
		
            echo("Freeleech Expire: Unlimited Freeleech<br>");
			
        else
            echo("Plano Vip expirar : " . htmlspecialchars(utc_to_tz($user["freeleechexpire"])) . "<br>");
}
if ($user["warned"] == "yes")
{
$userwarned= "<font color='red'>Sim</font>";
}
else
{
$userwarned= "Não";
}

if ($user["freeleechuser"] == "yes")
{
$freeuser= "Sim";
}
else
{
$freeuser= "Não";
}
if ($user["gender"] =='Male'){
$sexo = "Masculino";}
elseif ($user["gender"] =='Female'){
$sexo = "Feminino";}	
else{
$sexo="Indefinido";
}
	?>
	
	<table width="100%" class='tab1' cellpadding='0' cellspacing='1' align='center'>
<tr><td width=50%  align=right  class=tab1_col3><b>Cadastrado em:</b></td><td width=50%  align=left class=tab1_col3  ><?php echo  date(utc_to_tz($user["added"])); ?></tr>
<tr><td width=50%  align=right  class=tab1_col3><b>Último acesso:</b></td><td width=50%  align=left class=tab1_col3  ><?php echo  date(utc_to_tz($user["last_access"])); ?></tr>
<tr><td width=50%  align=right  class=tab1_col3><b>Quando:</b></td><td width=50%  align=left class=tab1_col3  ><?php echo $loginstatus; ?></tr>
	<?php if ($user["invited_by"]) {
					$res = mysql_query("SELECT username FROM users WHERE id=$user[invited_by]");
					$row = mysql_fetch_array($res);
					
				  ?>
				  <tr><td width=50%  align=right  class=tab1_col3><b>Padrinho:</b></td><td width=50%  align=left class=tab1_col3  ><?php echo "<b></b><a href=\"account-details.php?id=$user[invited_by]\">$row[username]</a><br />";?></tr>
        <?php }
        else { ?>
		<tr><td width=50%  align=right  class=tab1_col3><b>Padrinho:</b></td><td width=50%  align=left class=tab1_col3  >Auto registrado</tr>
        <?php } ?>
		<tr><td width=50%  align=right  class=tab1_col3><b>Sexo:</b></td><td width=50%  align=left class=tab1_col3  ><?php echo $sexo ;?></tr>
		<tr><td width=50%  align=right  class=tab1_col3><b>Pais:</b></td><td width=50%  align=left class=tab1_col3  ><?php echo $country ;?></tr>
		<tr><td width=50%  align=right  class=tab1_col3><b>Estado:</b></td><td width=50%  align=left class=tab1_col3  ><?php echo $estados ;?></tr>
</table>





<?php

end_framec();
}
if ($_GET['action'] == "afiliados") {


$res = SQL_Query_exec("SELECT invites FROM users WHERE id = $id") or sqlerr();
$inv = mysql_fetch_assoc($res);

$rel = SQL_Query_exec("SELECT COUNT(*) FROM users WHERE status = 'confirmed' AND  invited_by = $id") or sqlerr();
$arro = mysql_fetch_row($rel);
$number = $arro[0];

$num1 = get_row_count("users", "WHERE  status = 'confirmed' AND invited_by = $id");

list($pagertop, $pagerbottom, $limit) = pager(20, $num1, "afilhados.php?user=$id&amp;");


$ret = SQL_Query_exec("SELECT id, username, email, uploaded, downloaded, status, warned, enabled, donated, email FROM users WHERE status = 'confirmed' AND invited_by = $id  $limit") or sqlerr();
$num = mysql_num_rows($ret); 

echo "<center><table width=80% border=0 align=center><tr><td class=teste12><h1><center></center></h1></center>";


    

print("<form method=post action=takeconfirm.php?id=$id><table border=1 width=750 cellspacing=0 cellpadding=5>".
"<tr class=tabletitle><td colspan=7><b>Total de Afilhados </b> ($number)</td></tr>");

if(!$num){
print("<tr class=tableb><td colspan=7>Sem convidados ainda.</tr>");
} else {


print("<tr class=teste1><td class=teste1><b>Usuário</b></td><td class=teste1><b>Aviso</b></td><td class=teste1><b>Enviou</b></td><td class=teste1><b>Baixou</b></td><td class=teste1><b>Ratio</b></td><td class=teste1><b>Status</b></td>");



print("</tr>");
for ($i = 0; $i < $num; ++$i)
{        
  //=======change colors
                if($count2 == 0)
{
$count2 = $count2+1;
$class = "teste1";
}
else
{
$count2 = 0;
$class = "teste1";
}
                //=======end
$arr = mysql_fetch_assoc($ret);
if ($arr[status] == 'pending')
$user = "<td align=left class=$class><a class=altlink href=checkuser.php?id=$arr[id]>$arr[username]</a></td>";
else
$user = "<td align=left class=$class><a class=altlink href=account-details.php?id=$arr[id]>$arr[username]</a>" .($arr["warned"] == "yes" ? "&nbsp;<img src=pic/warned.gif border=0 alt='Warned'>" : "")."&nbsp;" .($arr["enabled"] == "no" ? "&nbsp;<img src=pic/disabled.gif border=0 alt='Disabled'>" : "")."&nbsp;" .($arr["donated"] == "yes" ? "<img src=pic/star.gif border=0 alt='Donated'>" : "")."</td>";

if ($arr["downloaded"] > 0) {
$ratio = number_format($arr["uploaded"] / $arr["downloaded"], 3);
$ratio = "<font color=" . get_ratio_color($ratio) . ">$ratio</font>";
} else {
if ($arr["uploaded"] > 0) {
$ratio = "Indefinido.";
}
else {
$ratio = "---";
}
    if ($arr["warned"] == "no")
        $warned2 = ' (Advertido: <font color=Green>Não</font>)';
    else
        $warned2 = " (Advertido: <font color=red>Sim</font>)";
}
if ($arr["status"] == 'confirmed')
$status = "<a class=altlink href=account-details.php?id=$arr[id]><font color=#1f7309>Confirmados</font></a>";
else
$status = "<a class=altlink href=account-details.php?id=$arr[id]><font color=#ca0226>Pendentes</font></a>";

print("<tr  class=$class>$user<td class=$class>$warned2</td><td class=$class>" . mksize($arr[uploaded]) . "</td><td class=$class>" . mksize($arr[downloaded]) . "</td><td class=$class>$ratio</td><td class=$class>$status</td>");


print("</tr>");
}
}

print("</table><br>");


echo $pagertop;



end_framec();

die;
}
if (!$_GET['action']){

		

if ($user["gender"] =='Male'){
$sexo = "Masculino";}
elseif ($user["gender"] =='Female'){
$sexo = "Feminino";}	
else{
$sexo="Indefinido";
}


	if ($user["warned"] == "yes"){
		echo"<center><b><img src=http://www.malucos-share.org/images/warned.gif>Usuário advertido!<img src=http://www.malucos-share.org/images/warned.gif></b></center>";
}	

 
	if($user["enabled"] == "no"){
		echo"<center><b><img src=http://www.malucos-share.org/images/warned.gif>Usuário banido do site!<img src=http://www.malucos-share.org/images/warned.gif></b></center>";

		}
		$placa = $user;

if ($user["class"] == 100){
$placa="&nbsp;<img src=images/sysop.png alt=Sysops	 title=Sysops  border=0>";}

elseif ($user["class"] == 95){
$placa="&nbsp;<img src=images/adm.png alt=Administrador	 title=Administrador border=0>";}

elseif ($user["class"] == 86){
$placa="&nbsp;<img src=images/S.Moderador.png alt=Moderador title=Moderador border=0>";}

elseif ($user["class"] == 85){
$placa="&nbsp;<img src=images/MODERADOR.png alt=Moderador title=Moderador border=0>";}

elseif ($user["class"] == 75){
$placa="&nbsp;<img src=images/LIBERADOR-DE-TORRENTS.png alt=Liberador de Torrents title=Liberador de Torrents border=0>";}

elseif ($user["class"] == 80){
$placa="&nbsp;<img src=images/COLABORADOR.png alt=Colaborador title=Colaborador border=0>";}

elseif ($user["class"] == 70){
$placa="&nbsp;<img src=images/DESIGNER.png alt=Designer title=Designer border=0>";}

elseif ($user["class"] == 71){
$placa="&nbsp;<img src=images/Coord.Designer.png alt=Coord de designer title=Coord de designer border=0>";}

elseif ($user["class"] == 69){ 
$placa="&nbsp;<img src=images/DJs.png alt=DJ's de Torrents title=DJ's border=0>";}

elseif ($user["class"] == 50){
$placa="&nbsp;<img src=images/UPLOADER.png alt=Uploader title=Uploader border=0>";}
elseif ($user["class"] == 1){
$placa="&nbsp;<b>Usuário</b>";

}
else{
$placa="";
}
		$resgrupos = mysql_query("SELECT name,id,image FROM teams WHERE id = ".$user["team"]." LIMIT 1");
	    $arrgrupos = mysql_fetch_assoc($resgrupos);
	    $res1grupos = mysql_query("SELECT status FROM usergroups WHERE gid=".$user["team"]." AND uid = ".$user["id"]."  ");
	    $arr1grupos = mysql_fetch_assoc($res1grupos);

		
if ( $arr1grupos["status"] == 'membrogrupo'){ 
$grupos_exe ="<font size=2 color=#336633><B>Membro da Equipe</B> (<a href='grupos_lancamentos.php?id=".$arrgrupos["id"]."'>".$arrgrupos["name"]."</a>)</font>";
}							
elseif ( $arr1grupos["status"] == 'submoderadores'){ 
$grupos_exe ="<font size=2 color=#336633><B>Sub-moderador da Equipe</B> (<a href='grupos_lancamentos.php?id=".$arrgrupos["id"]."'>".$arrgrupos["name"]."</a>)</font>";
}	
elseif ( $arr1grupos["status"] == 'moderadores'){ 
$grupos_exe ="<font size=2 color=#336633><B>Moderador da Equipe</B> (<a href='grupos_lancamentos.php?id=".$arrgrupos["id"]."'>".$arrgrupos["name"]."</a>)</font>";
} 
else{
$grupos_exe="";
}
	?>
     <div id="body_outer">

<div align="justify" class="framecentro"><table width="100%" cellspacing="0" cellpadding="0" border="0" align="center"></table><br></div><table width="100%" cellspacing="0" cellpadding="0" border="0" align="center"><tbody><tr><td width="150" valign="top" align="center"><table cellspacing="1" cellpadding="6" align="center" id="tabela1"><tbody><tr><td align="center" class="tab1_col3"><b>Ações</b></td></tr><tr><td align="center" class="tab1_col3"><a href="mailbox.php?Escrever&id=<?=$user["id"]?>">Enviar MP</a></td></tr><tr><td align="center" class="tab1_col3"><a href="friends.php?user=<?=$user["id"]?>">+ amigo</a></td></tr><tr><td align="center" class="tab1_col3"><a href="report.php?user=<?=$user["id"]?>"><font color="#FF0000">Denunciar</font></a></td></tr></tbody></table></td><td width="155" align="center"><br><img width="150" hspace="5" height="250"src=<?=$avatar?>></td><td valign="top"><font size="4"><b><?= $user["username"] ?></b></font><br><hr width="50%" color="#000000" align="left"><?=   $grupos_exe   ?><br><?=$placa?><br></td></tr></tbody></table><br><br><br><div class="componentheading"><?=begin_framec(" Assinatura de " . $user["username"] . " ");?></div><div align="justify" class="framecentro"><center><center><?=$usersignature?><br>
</center></center></div>
				<div class="clr"></div>
			</div>
<?php


}
 
//////////////////////////////////////SNATCHED/////////////////////////////////
$r = mysql_query("SELECT snatched_t.tid, snatched_t.uload, snatched_t.dload, snatched_t.stime, snatched_t.utime, snatched_t.ltime, torrents.seeders, torrents.leechers  FROM snatched_t JOIN torrents ON torrents.id = snatched_t.tid WHERE snatched_t.completed='1' AND uid=$id ");
$completed = "<table width=100%  class=main border=1 cellspacing=0 cellpadding=3>\n" ."<tr>\n" ."<td class=ttable_head align=center>Cat</td>\n" ."<td class=ttable_head align=center>Torrent</td>\n" ."<td class=ttable_head align=center>Up</td>\n" ."<td class=ttable_head align=center>Down.</td>\n" ."<td class=ttable_head align=center>Inicio</td>\n" ."<td class=ttable_head align=center>Última acção</td>\n" ."<td class=ttable_head align=center>Tempo de seed</td>\n" ."<td class=ttable_head align=center>seed</td>\n" ."<td class=ttable_head align=center>Leech</td>\n" ."</tr>\n";
while ($a = mysql_fetch_assoc($r)) {
        $r1 = mysql_query("SELECT * FROM torrents WHERE id = ". $a[tid] ." ") or sqlerr(__FILE__, __LINE__);
        $a1 = mysql_fetch_assoc($r1);
        $r2 = mysql_query("SELECT name, image FROM categories WHERE id = ". $a1[category] ."") or sqlerr(__FILE__, __LINE__);
        $a2 = mysql_fetch_assoc($r2);
        if ($a["dload"] > 0) {
                $ratio = number_format($a["uload"] / $a["dload"], 3);
                $ratio = "<font color=" . get_ratio_color($ratio) . ">$ratio</font>";
                }elseif ($a["uload"] > 0)
                $ratio = "Inf.";
                else
                $ratio = "---";
                $uploaded =mksize($a["uload"]);
                $downloaded = mksize($a["dload"]);
                $cat = "<img src=\"/images/categories/$a2[image]\" alt=\"$a2[name]\">";
                $starttime = date( 'd-m-Y \a\s H:i:s', $a[ stime ] );       
                $last_action = date( 'd-m-Y \a\s H:i:s', $a[ utime ] );
                 $leechtime = ( $a[ ltime ] ) ? seedtime( $a[ ltime ] ) : '-';

                $td = mysql_query("SELECT * FROM torrents WHERE id = ". $a[tid] ."");
                while ($tdl = mysql_fetch_assoc($td)) {
                        $timesdownloaded = $a["dload"] / $tdl["size"];
                }
                $smallname3 =substr(htmlspecialchars($a1["name"]) , 0, 20);
                if ($smallname3 != htmlspecialchars($a1["name"])) {
                        $smallname3 .= '...';
                        }
                        $completed .= "<tr>\n" ."<td class=tab1_col3 align=center><a href=torrents.php?cat=". $a1[category] .">$cat</a></td>\n" ."<td class=tab1_col3><a href=torrents-details.php?id=". $a[tid] ."><b>$smallname3</b></a></td>\n" ."<td class=tab1_col3 align=center>$uploaded</td>\n" ."<td class=tab1_col3 align=center>$downloaded</td>\n" ."<td class=tab1_col3 align=center>$starttime</td>\n" ."<td class=tab1_col3 align=center>$last_action</td>\n" ."<td class=tab1_col3 align=center>$leechtime</td>\n" ."<td class=tab1_col3 align=center>". $a[seeders] ."</td>\n" ."<td class=tab1_col3 align=center>". $a[leechers] ."</td>\n" ."</tr>\n";
                        }
                        $completed .= "</table>";
                        
                        $res_tor_c = mysql_query("SELECT sid FROM snatched_t WHERE uid = ". $user[id] ." AND completed='1'");
$tor_c = mysql_num_rows($res_tor_c);
if($CURUSER["edit_users"]=="yes"){
if ($completed)
begin_framec("Staff");
echo "<B>Torrentes completados:</B>&nbsp;<img src='images/plus.gif' id='pic1' onclick='klappe_torrent(1)'>&nbsp;&nbsp;&nbsp;&nbsp;<b>$tor_c Torrentes completado(s)</b><div id='k1' style='display: none;'>$completed</div>";
end_framec();
}
//////////////////////////////////////////////////////////////////////////////
if($CURUSER["edit_users"]=="yes"){



	begin_framec(T_("STAFF_ONLY_INFO"));

	$avatar = htmlspecialchars($user["avatar"]);
	$signature = htmlspecialchars($user["signature"]);


	$enabled = $user["enabled"] == 'yes';
	$warned = $user["warned"] == 'yes';
	$forumbanned = $user["forumbanned"] == 'yes';
	$dj = $user["dj"] == 'yes';
	$djstaff = $user["djstaff"] == 'yes';
	$modcomment = htmlspecialchars($user["modcomment"]);

	print("<form method='post' action='admin-modtasks.php'>\n");
	print("<input type='hidden' name='action' value='edituser' />\n");
	print("<input type='hidden' name='userid' value='$id' />\n");
	
	print("<table class='tab1' cellpadding='0' cellspacing='1' align='center'>\n");
	print("<tr><td width=50%  align=right  class=tab1_col3>".T_("TITLE").": </td><td width=50%  align=left class=tab1_col3><input type='text' size='67' name='title' value=\"$user[title]\" /></td></tr>\n");
		if($CURUSER["level"]=="Administrador" || $CURUSER["level"]=="Sysop" || $CURUSER["level"]=="S.Moderador"){
	print("<tr><td width=50%  align=right  class=tab1_col3>".T_("EMAIL")."</td><td width=50%  align=left class=tab1_col3><input type='text' size='67' name='email'  value=\"$user[email]\" /></td></tr>\n");
}
	print("<tr><td width=50%  align=right  class=tab1_col3>".T_("SIGNATURE").": </td><td width=50%  align=left  class=tab1_col3><textarea cols='50' rows='10' name='signature'>".htmlspecialchars($user["signature"])."</textarea><br></td></tr>\n");


	print("<tr width=50%  align=right  class=tab1_col3><td>Avatar url</td><td width=50%  align=left  class=tab1_col3><input type='text' size='67' name='avatar' value=\"$avatar\" /></td></tr>\n");
	print("<tr><td  width=50%  align=right  class=tab1_col3>".T_("IP_ADDRESS").": </td><td  width=50%  align=left  class=tab1_col3><input type='text' size='20' name='ip' value=\"$user[ip]\" /></td></tr>\n");
		if($CURUSER["level"]=="Administrador" || $CURUSER["level"]=="Sysop" || $CURUSER["level"]=="Moderador"|| $CURUSER["level"]=="S.Moderador"){
	print("<tr><td  width=50%  align=right  class=tab1_col3>".T_("INVITES").": ".$user["invites"]." +</td><td  width=50%  align=left class=tab1_col3><input type='text' size='4' name='invites' value='' /></td></tr>\n");
}
	if($CURUSER["level"]=="Administrador" || $CURUSER["level"]=="Sysop" || $CURUSER["level"]=="Moderador"|| $CURUSER["level"]=="S.Moderador"){
	$exe_classes = mysql_query("SELECT * FROM groups ORDER BY group_id");
	print("<tr><td  width=50%  align=right  class=tab1_col3>".T_("CLASS").": </td><td  width=50%  align=left  class=tab1_col3><select name=class>\n");
	while($arr_classes=mysql_fetch_array($exe_classes)) {
		print("<option value=".$arr_classes["group_id"]."" . ($user["class"] == $arr_classes["group_id"] ? " selected" : "") . ">$prefix" . get_user_class_name($arr_classes["group_id"]) . "\n");
	}
	print("</select></td></tr>\n");
	}

	


	
	
	
	
	
	
		if($CURUSER["level"]=="Administrador" || $CURUSER["level"]=="Sysop" || $CURUSER["level"]=="S.Moderador"){
	print("<tr><td width=50%  align=right  class=tab1_col3>".T_("DONATED_US").": </td><td width=50%  align=left  class=tab1_col3><input type='text' size='4' name='donated' value='$user[donated]' /></td></tr>\n");
}
		if($CURUSER["level"]=="Administrador" || $CURUSER["level"]=="Sysop" || $CURUSER["level"]=="Moderador"|| $CURUSER["level"]=="S.Moderador"){
	print("<tr><td width=50%  align=right  class=tab1_col3>Pontos ".$user["seedbonus"]." +</td><td width=50%  align=left  class=tab1_col3><input type=text size=4 name=seedbonus value=></tr>\n");  
}
	print("<tr><td width=50%  align=right  class=tab1_col3>".T_("PASSWORD").": </td><td width=50%  align=left  class=tab1_col3><input type='password' size='67' name='password' value=\"$user[password]\" /></td></tr>\n");
	print("<tr><td width=50%  align=right  class=tab1_col3>".T_("CHANGE_PASS").": </td><td width=50%  align=left  class=tab1_col3><input type='checkbox' name='chgpasswd' value='yes'/></td></tr>");
	print("<tr><td width=50%  align=right  class=tab1_col3>Comentário moderação: </td><td width=50%  align=left  class=tab1_col3><textarea cols='50' rows='10' name='modcomment'>$modcomment</textarea></td></tr>\n");

	if ($user["enabled"] == 'yes'){
	$userenabled = "<font color='#258016'>Habilitado</font>";
	}
	if ($user["enabled"] == 'no'){
	$userenabled = "<font color='#ff0000'>Desativado</font>";
	}
	print("<tr><td width=50%  align=right  class=tab1_col3>Status da conta: </td><td width=50%  align=left  class=tab1_col3><input name='enabled' value='yes' type='radio'  />Habilitado <input name='enabled' value='no' type='radio'  />Desativado<br>Status da conta: $userenabled</td></tr>\n");
	if ($user["warned"] == 'yes'){
	$userwarned = "<font color='#ff0000'>Sim</font>";
	}
	if ($user["warned"] == 'no'){
	$userwarned = "<font color='#258016'>Não</font>";
	}
	print("<tr><td width=50%  align=right  class=tab1_col3>Advertido: </td><td width=50%  align=left  class=tab1_col3><input name='warned' value='yes' type='radio'/>Sim <input name='warned' value='no' type='radio' />Não<br>Usuário Advertido: $userwarned </td></tr>\n");
		if ($user["forumbanned"] == 'yes'){
	$userforumbanned = "<font color='#ff0000'>Sim</font>";
	}
	if ($user["forumbanned"] == 'no'){
	$userforumbanned = "<font color='#258016'>Não</font>";
	}
	print("<tr><td width=50%  align=right  class=tab1_col3>Fórum banido: </td><td width=50%  align=left  class=tab1_col3><input name='forumbanned' value='yes' type='radio' />Sim <input name='forumbanned' value='no' type='radio' />Não <br>Ao clicar em sim o usuário terá que aguardar 30 minutos para voltar a acessar o forúm<br>Usuário banido $userforumbanned</td></tr>\n");
			if ($user["hideshoutbox"] == 'yes'){
	$hideshoutbox = "<font color='#ff0000'>Sim</font>";
	}
	if ($user["hideshoutbox"] == 'no'){
	$hideshoutbox = "<font color='#258016'>Não</font>";
	}	
    print("<tr><td width=50%  align=right  class=tab1_col3>Shoutbox banido: </td><td width=50%  align=left  class=tab1_col3><input name='hideshoutbox' value='yes' type='radio' />Sim <input name='hideshoutbox' value='no' type='radio' />Não <br>Ao clicar em sim o usuário terá que aguardar 30 minutos para voltar a acessar o shoutbox<br>Usuário banido $hideshoutbox</td></tr>\n");	
	
	print("<tr><td width=50%  align=right  class=tab1_col3>DJ: </td><td width=50%  align=left  class=tab1_col3><input name=dj value=yes type=radio" . ($dj ? " checked" : "") . ">Habilitado <input name=dj value=no type=radio" . (!$dj ? " checked" : "") . ">Desativado</td></tr>\n");
	print("<tr><td width=50%  align=right  class=tab1_col3>Dj Coodernadora da Equipe: </td><td width=50%  align=left  class=tab1_col3><input name=djstaff value=yes type=radio" . ($djstaff ? " checked" : "") . ">Habilitado <input name=djstaff value=no type=radio" . (!$djstaff ? " checked" : "") . ">Desativado</td></tr>\n");

	
	

	print("<tr><td colspan='2' align='center'><input type='submit' value='Salvar alterações' /></td></tr>\n");
	print("</table>\n");
	print("</form>\n");

	end_framec();
}
//Alterar Nick + fácil
	if($CURUSER["level"]=="Administrador" || $CURUSER["level"]=="S.Moderador" || $CURUSER["level"]=="Sysop" ){

		//Verifico se tem o novo nick recebido
		$nick = $_POST["nick"];
		if(isset($nick) && strlen($nick)>3){

			//Consulto antes
			$num_nicks = mysql_num_rows(mysql_query("SELECT * FROM users WHERE username='".$nick."'"));
			if($num_nicks > 0){
				show_error_msg("Erro", "Este nick já está em uso", 1);
				exit;
			}
			//Inicia o processo de alteração de NICK
			mysql_query("UPDATE users SET username='".$nick."' WHERE id='".$id."' LIMIT 1");
			write_log($CURUSER["username"]." alterou o nick de ".$user["username"]." para ".$nick);

			$subject = "Alteração de Nome de Usuário";
			$body = "Seu nome de usuário no site malucos-share foi alterado pelo ".$CURUSER["level"]." ".$CURUSER["username"]."\n\n";
			$body .= "Seu antigo usuário era: ".$user["username"]."\nSeu Novo usário é: ".$nick."\n";

			//Envia para o usuário por e-mail
			$headers = "From: $site_config[SITEEMAIL]".PHP_EOL;
			$headers .= "Return-Path: $site_config[SITEEMAIL]".PHP_EOL;
			$headers .= "Content-type: text/plain; charset=uft-8; format=flowed".PHP_EOL;
			$headers .= "X-Mailer: PHP".phpversion().PHP_EOL;

			$subject = $site_config["SITENAME"]." - ".(get_magic_quotes_gpc()?stripslashes($subject):$subject);
			$body = (get_magic_quotes_gpc()?stripslashes($body):$body);
			//$body .= "\n\n\n\nAtenção: Este E-Mail é Automático e foi enviado para todos os membros do site.";

			mail("Multiple recipients <$site_config[SITEEMAIL]>", $subject, $body, $headers."Bcc: $user[email]".PHP_EOL);
			$ok=1;
		}

		begin_framec("Alterar Nick (Admin, S.Moderador e Sysop)");
		if($ok==1){
			print("Nick name foi alterado com sucesso! Atualize");
			header("Location: account-details.php?id=".$id);
		}
		?>
        	<form name="alterarnick" method="post" action="?id=<?php echo $id;?>">
            <strong>Novo Nick:</strong><br />
            <input type="text" name="nick" value="<?php echo $user["username"];?>" size="25" /><br /><br />
            <input type="submit" value="Alterar Nick" />
            </form>
        <?php 
		end_framec();
	}
	//Fim do Alterar nick
	//Fim do Alterar nick
		if($CURUSER["level"]=="Administrador" || $CURUSER["level"]=="Moderador" || $CURUSER["level"]=="S.Moderador" ){
begin_framec("Adicionar Upload");
	if (isset($_REQUEST['adicionarUp'])){
		$qtde = $_POST["qtde"];
		$multiplicador = $_POST["multiplicador"];

		if($qtde>0){
			for($i=0;$i<$multiplicador-1;$i++){
				$qtde = $qtde * 1024;
			}
		}
		echo 'Adicionado '.$qtde.' Bytes de UP';
		mysql_query("UPDATE users SET uploaded=uploaded+".$qtde." WHERE id='".$id."'");
		write_logstaff("Usuário","#FF0000","O usuário [url=http://www.malucos-share.org/account-details.php?id=".$CURUSER['id']."]".$CURUSER['username']."[/url] adicionou ".$qtde." bytes de upload para o usuário [url=http://www.malucos-share.org/account-details.php?id=".$user["id"]."]".$user["username"]."[/url]\n");

		$msg = "O usuário " . $CURUSER['username'] . " adicionou ".$qtde." bytes de upload para você " ;
             SQL_Query_exec("INSERT INTO messages (poster, sender, receiver, msg, added,subject) VALUES('0','0', " . $id . ", " .sqlesc($msg) . ", '" . get_date_time() . "','Upload!')") or die (mysql_error());
		
		
    autolink("account-details.php?id=" . $id . "", "Atualização de usuários ok.");
		
		exit;
	}
	?>
    <form name="addUp" action="?id=<?php echo $id;?>&adicionarUp" method="post">
		<input type="text" name="qtde" value="1" />&nbsp;
        <select name="multiplicador">
        	<option value="0" selected="selected">--- Escolha um Tipo ---</option>
            <option value="1">Bytes</option>
            <option value="2">Kilo Bytes</option>
            <option value="3">Mega Bytes</option>
            <option value="4">Giga Bytes</option>
            <option value="5">Tera Bytes</option>
            <option value="6">Peta Bytes</option>
        </select>&nbsp;<input type="submit" value="Adicionar Upload" />
    </form>
    <?php
	end_framec();

	begin_framec("Apenas para uso da STAFF");

	$avatar = htmlspecialchars($user["avatar"]);
	$signature = htmlspecialchars($user["signature"]);
	print ("<strong>Calculadora de Bytes:</strong>"); 	//inicio addon calculadora by brayanp
?>	<align= right><form name="bandwidth">

  <p><input type="text" name="original" size="20" onfocus="if (this.value == 'Digite o Valor') this.value='';" onblur="if (this.value == '') this.value='Digite o Valor' value="Digite o valor"> <select size="1" name="units">
    <option value="Bytes">Bytes</option>
    <option value="Kb">Kb</option>
    <option value="Mb">Mb</option>
    <option value="Gb">Gb</option>
  </select> <input type="button" value="Calcular" name="B1" onClick="calculate()"></p>
</form>

<p>

<script>


var bytevalue=0
function calculate(){
var invalue=document.bandwidth.original.value
var selectunit=document.bandwidth.units.options[document.bandwidth.units.selectedIndex].value
if (selectunit=="Bytes")
bytevalue=invalue
else if (selectunit=="Kb")
bytevalue=invalue*1024
else if (selectunit=="Mb")
bytevalue=invalue*1024*1024
else if (selectunit=="Gb")
bytevalue=invalue*1024*1024*1024

alert (invalue+" "+selectunit+" é igual a:\n\n- "+bytevalue+" Bytes\n- "+Math.round(bytevalue/1024)+" Kb\n- "+Math.round(bytevalue/1024/1024)+" Mb\n- "+Math.round(bytevalue/1024/1024/1024)+" Gb\n")
}

</script>

<?php	}//FIM ADDON CALCULADORA;
if($CURUSER["edit_users"]=="yes"){
	begin_framec(T_("BANS_WARNINGS"));

    print '<a name="warnings"></a>';
    
	$rqq = "SELECT * FROM warnings WHERE userid=$id ORDER BY id DESC";
	$res = mysql_query($rqq);

	if (mysql_num_rows($res) > 0){

		?>
		<b>Warnings:</b><br />
			<table class='tab1' cellpadding='0' cellspacing='1' align='center' width="100%" border="0" >
		<tr>
            <th class="tab1_cab1">Added</th>
		    <th class="tab1_cab1"><?php echo T_("EXPIRE"); ?></th>
		    <th class="tab1_cab1"><?php echo T_("REASON"); ?></th>
		    <th class="tab1_cab1"><?php echo T_("WARNED_BY"); ?></th>
		    <th class="tab1_cab1"><?php echo T_("TYPE"); ?></th>      
		</tr>
		<?php

		while ($arr = mysql_fetch_assoc($res)){
			if ($arr["warnedby"] == 0) {
				$wusername = "System";
			} else {
				$res2 = mysql_query("SELECT id,username FROM users WHERE id = ".$arr['warnedby']."");
				$arr2 = mysql_fetch_assoc($res2);

				$wusername = htmlspecialchars($arr2["username"]);
			}
			$arr['added'] = utc_to_tz($arr['added']);
			$arr['expiry'] = utc_to_tz($arr['expiry']);

			$addeddate = substr($arr['added'], 0, strpos($arr['added'], " "));
			$expirydate = substr($arr['expiry'], 0, strpos($arr['expiry'], " "));
			print("<tr><td class='tab1_col3' align='center'>$addeddate</td><td class='tab1_col3' align='center'>$expirydate</td><td class='tab1_col3'>".format_comment($arr['reason'])."</td><td class='tab1_col3' align='center'><a href='account-details.php?id=".$arr2['id']."'>".$wusername."</a></td><td class='tab1_col3' align='center'>".$arr['type']."</td></tr>\n");
		 }

		echo "</table>\n";
	}else{
		echo T_("NO_WARNINGS");
	}


	print("<form method='post' action='admin-modtasks.php'>\n");
	print("<input type='hidden' name='action' value='addwarning' />\n");
	print("<input type='hidden' name='userid' value='$id' />\n");
	echo "<br /><br /><center><table border='0'><tr><td align='right'><b>".T_("REASON").":</b> </td><td align='left'><textarea cols='40' rows='5' name='reason'></textarea></td></tr>";
	echo "<tr><td align='right'><b>".T_("EXPIRE").":</b> </td><td align='left'><input type='text' size='4' name='expiry' />(days)</td></tr>";
	echo "<tr><td align='right'><b>".T_("TYPE").":</b> </td><td align='left'><input type='text' size='10' name='type' /></td></tr>";
	echo "<tr><td colspan='2' align='center'><input type='submit' value='".T_("ADD_WARNING")."' /></td></tr></table></center></form>";
if ($CURUSER["id"] =="1"){ 
	if($CURUSER["delete_users"] == "yes"){
		print("<hr /><center><form method='post' action='admin-modtasks.php'>\n");
		print("<input type='hidden' name='action' value='deleteaccount' />\n");
		print("<input type='hidden' name='userid' value='$id' />\n");
		print("<input type='hidden' name='username' value='".$user["username"]."' />\n");
		echo "<b>".T_("REASON").":</b><input type='text' size='30' name='delreason' />";
		echo "&nbsp;<input type='submit' value='".T_("DELETE_ACCOUNT")."' /></form></center>";
	}
}
	
	
	
	
	
	end_framec();
}

#####################################
#####          #####
#####                      #####
#####################################
if ($site_config["ADVANCED_DONATER"])   {
if ($CURUSER && $CURUSER["class"] >= 94) {

begin_framec("Doação sistema");

echo "<br><p align=center>&nbsp;&nbsp;&nbsp;<button  onclick=window.open('viewdonatings.php?sort=id'); 
return false;>Lista de todas as doações</button>&nbsp;&nbsp;&nbsp;<button  onclick=window.open('site_budget.php?sort=id'); 
return false;>Acesso ao Orçamento do Site</button>&nbsp;&nbsp;&nbsp;<button  onclick=window.open('viewdonators.php?sort=id');
 return false;>Ver Doadores</button></p><br>";
    
$rqq = "SELECT * FROM donatings WHERE userid=$id ORDER BY id DESC";
$res = mysql_query($rqq);

if (mysql_num_rows($res) > 0)	{

?>

<CENTER>
<table class='tab1' cellpadding='0' cellspacing='1' align='center'>
        <tr>
        <td class="tab1_cab1" align="center"> ID</td>
        <td class="tab1_cab1" align="center">User ID</td>
	<td class="tab1_cab1" align="center">Nome de Usuário</td>
	<td class="tab1_cab1" align="center">País</td>
	<td class="tab1_cab1" align="center">Class</td>
	<td class="tab1_cab1" align="center">Nível</td>
        <td class="tab1_cab1" align="center">Add</td>
	<td class="tab1_cab1" align="center">Duração</td>
        <td class="tab1_cab1" align="center">expiração</td>
        <td class="tab1_cab1" align="center">Razão</td>
        <td class="tab1_cab1" align="center">Viper</td>
        <td class="tab1_cab1" align="center">Valor</td>
        <td class="tab1_cab1" align="center">Valor</td>
        <td class="tab1_cab1" align="center">Total doado</td>
	<td class="tab1_cab1" align="center">Excluir ?</td>
        </tr>
<?php

while ($arr = MYSQL_FETCH_ARRAY($res))		{
         
$res2 = mysql_query("SELECT id, username, donated, total_donated FROM users 
WHERE id = ".$arr['vipedby']."") or die(mysql_error());
$arr2 = mysql_fetch_array($res2);

$wusername = htmlspecialchars($arr2["username"]);

$arr['added'] = utc_to_tz_time($arr['added']);
$arr['expiry'] = utc_to_tz_time($arr['expiry']);
            
$addeddate = substr($arr['added'], 0, strpos($arr['added'], " "));
$expirydate = substr($arr['expiry'], 0, strpos($arr['expiry'], " "));

if ($arr[duration] == '1') { $duration = "LIFETIME"; }
else { $duration = $arr[duration]; }

print("<tr>
<td class=tab1_col3 align=center>$arr[id]</td>
<td class=tab1_col3 align=center>$id</td>
<td class=tab1_col3 align=center>$arr[username]</td>
<td class=tab1_col3 align=center>$country</td>
<td class=tab1_col3 align=center>$arr[class]</td>
<td class=tab1_col3 align=center>$arr[level]</td>
<td class=tab1_col3 align=center>$addeddate</td>
<td class=tab1_col3 align=center>$duration</td>
<td class=tab1_col3 align=center>$expirydate</td>
<td class=tab1_col3 align=center>".format_comment($arr['reason'])."</td>
<td class=tab1_col3 align=center>".$wusername."</td>
<td class=tab1_col3 align=center>".$arr['money']."</td>
<td class=tab1_col3 align=center>".$arr['donated']."</td>
<td class=tab1_col3 align=center>".$arr['total_donated']."</td>
<td class=tab1_col3 align=center>");

echo "<form id='1' action='takedeldon.php?userid=$id' method='POST'>";
echo "<input type=\"checkbox\" name=\"deldon[]\" value=\"" . $arr[id] . "\" /></td></tr>\n";

$userid=$id;
$moneyless=$arr['money'];
       									  }



print("<br><b>Resumo das Doações para este membro: $arr[username]</b><br><br>");
echo "</table><BR><input type='submit' value='Excluir Doação'></form></CENTER>\n";


    						}		else			{
        echo "<CENTER><B>Usuário não tem doação ativa</B></CENTER>\n";
    											}

print("<form method=post action=admin-modtasks.php>\n");
print("<input type=hidden name='action' value='adddonater'>\n");
print("<input type=hidden name='userid' value='$id'>\n");
echo "<BR><BR><CENTER><table border=0><tr><td align=right><B>Razão:</B> </td>
<td align=left><textarea cols=20 rows=1 name=reason></textarea></td></tr>";
echo "<tr><td align=right><B>Valor :</B> </td><td align=left>
<input type=text size=10 name=money></td></tr>";
echo "</tr>";

if ($CURUSER["class"] >= $user["class"])          		{
	print("<tr><td><B>Class :</B></td><td align=left><select name=class>\n");
	$maxclass = $CURUSER["class"];
	for ($i = 1; $i <= $maxclass; ++$i)
	print("<option value=$i" . ($user["class"] == $i ? " selected" : "") . ">$prefix" . get_user_class_name($i) . "\n");
	print("</select></td></tr>\n");
                                         						}
?>

<form name="chooseDuration" method="post" action="">
  <p>
    <select name="expiry" onChange="chang();">
      <option value="selection">Escolha o plano contratado</option>
      <option value=30>Maluco vip Mensal</option>
	  <option value=30>Maluco vip bronze Mensal</option>
	  <option value=30>Maluco vip prata Mensal</option>
	  <option value=30>Maluco vip ouro Mensal</option>
      <option value=60>Maluco vip Trimestral</option>
	  <option value=60>Maluco vip bronze Trimestral</option>
	  <option value=60>Maluco vip prata Trimestral</option>
	  <option value=60>Maluco vip ouro Trimestral</option>
	  <option value=90>Maluco vip Semestral</option>
	  <option value=90>Maluco vip bronze Semestral</option>
	  <option value=90>Maluco vip prata Semestral</option>
      <option value=90>Maluco vip ouro Semestral</option>
    </select>
 </p>
</form>
<?php
 
echo "</tr>";

echo "<tr><td colspan=2 align=center><input type=submit value='Adicionar doação'></td></tr></table></form></CENTER>";

end_framec();

}
}//////////////end mod
##############
stdfoot();

?>