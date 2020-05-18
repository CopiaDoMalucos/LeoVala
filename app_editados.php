<?php 
############################################################
#######                                             ########
#######                                             ########
#######           www.brshares.com 2.0              ########
#######                                             ########
#######                                             ########
############################################################
require "backend/functions.php";

dbconn();
loggedinonly();

if($CURUSER["level"]=="Administrador" || $CURUSER["level"]=="Moderador"  || $CURUSER["level"]=="S.Moderador" || $CURUSER["level"]=="Liberador"){
////somente torrentes com seed
stdhead("Approval List");
begin_framec("Torrents aguardando Aprovação <B>[Já editados]</B>");
$liberaesta = SQL_Query_exec("SELECT COUNT(*) FROM apppprovar WHERE uid=".$CURUSER["id"]."");
$rowesta = mysql_fetch_row($liberaesta);


$deletadostor = SQL_Query_exec("SELECT COUNT(*) FROM apppdel WHERE uid=".$CURUSER["id"]."");
$deletadostorr = mysql_fetch_row($deletadostor);




$liberados = $rowesta[0];
$deletadosto = $deletadostorr[0];


?>
<center ><h1 size="10" >Minhas estatísticas do mês</h1><br>Torrents liberados: <B><?php echo  $liberados ;?></B> <br>Torrents deletados: <B><?php echo $deletadosto ;?></B><br></center>
<div align="right">[ <a href="/app.php">Todos</a> | <a href="/app_meus.php">Meus pendentes</a> | <a href="/app_semmod.php">Sem mod responsavel</a> ]</div>
<?php 
$wherea=array();
$wherea[] = "seeders > '0'";
$where = implode(" AND ", $wherea);
///fim


$semmod = mysql_query("SELECT * FROM moderation WHERE uid = " . $CURUSER["id"] ." AND verifica = 'yes' ") or sqlerr();	



	
while($ressemmod = mysql_fetch_array($semmod)){



$opfor = $ressemmod["infohash"];


$res2 = mysql_query("SELECT COUNT(*) FROM torrents WHERE safe='no' AND visible='yes'  AND ". $where ." AND id =  $opfor ORDER BY added ASC");
        $row = mysql_fetch_array($res2);


	
		
        $count = $row[0];
$perpage = 19;
    list($pagertop, $pagerbottom, $limit) = pager($perpage, $count, $_SERVER["PHP_SELF"] ."?" );
$res = mysql_query("SELECT * FROM torrents WHERE safe='no'  AND visible='yes'   AND ". $where ."  AND id =  $opfor  ORDER BY added ASC $limit") or sqlerr();







	$num = mysql_num_rows($res);
	  

for ($i = 0; $i < $num; ++$i)
{

  $arr = mysql_fetch_assoc($res);




  $tm_sql="SELECT * from moderation WHERE infohash=".$arr['id']."";
$tm_r=mysql_query($tm_sql);
  
   $res_user_torr = SQL_Query_exec("SELECT users.id, users.username, torrents.name, torrents.owner FROM torrents LEFT JOIN users ON torrents.owner = users.id WHERE torrents.id =". $arr["id"] ." ") or sqlerr();
$row_user_torr = mysql_fetch_array($res_user_torr);


{

if(mysql_num_rows($tm_r)==0){
$remoderadotempo = '---';
$moderadortonovo = '---';  
$username = 'Ninguem';
$mod_motivo = 'O torrent ainda não foi moderado';
$tm_com = 'O torrent ainda não foi moderado';
$mederandotempo = '--:--';
$moderadorto = '----';
	$tentativas = '0' ;  
} else {






$tm_a = mysql_fetch_assoc($tm_r);
    $tm_u=mysql_query("SELECT * from moderation WHERE infohash=".$arr['id']."") or die(mysql_error());
/// moderador
	  date_default_timezone_set('Etc/GMT+2');
$data1 = utc_to_tz($tm_a['addmod']);
$data2 = date('Y-m-d H:i:s');  
$unix_data1 = strtotime($data1);
$unix_data2 = strtotime($data2);
$nHoras   = ($unix_data2 - $unix_data1) / 3600;
$nMinutos = (($unix_data2 - $unix_data1) % 3600) / 60;

  $tm_t=mysql_result($tm_u,0,"uid");
  $tm_pmd=mysql_result($tm_r,0,"mod");
   $tm_com=mysql_result($tm_u,0,"com");
$res1234 = mysql_query("SELECT username FROM users WHERE id = ".$tm_t."") or die (mysql_error());
$arr1234 = mysql_fetch_array($res1234);
$username = "<a href='account-details.php?id=" . $arr1234["owner"] . "'>" . $arr1234["username"] . "</a>";
$mod_motivo = $arr1234["com"];

$mederandotempo = sprintf('%02d:%02d', $nHoras , $nMinutos);
$moderadorto = date("d/m \à\s H:i", utc_to_tz_time($tm_a["addmod"]));


/// moderador
	  date_default_timezone_set('Etc/GMT+2');
$data12 = utc_to_tz($tm_a['dataremodera']);
$data21 = date('Y-m-d H:i:s');  
$unix_data12 = strtotime($data12);
$unix_data21 = strtotime($data21);
$nHoras1   = ($unix_data21 - $unix_data12) / 3600;
$nMinutos1 = (($unix_data21 - $unix_data12) % 3600) / 60;
 if ($tm_a["aceito"] == "yes"){
 
$remoderadotempo = sprintf('%02d:%02d', $nHoras1, $nMinutos1);
$moderadortonovo = date("d/m \à\s H:i", utc_to_tz_time($tm_a["dataremodera"]));	 



 if ($tm_a["aceito"] == "yes"){
 
$tentativas = $tm_a["tentativas"] ; 
}else{
$tentativas = '0' ; 
	  }	
}


	  


}
 if ($tm_a["verifica"] == "Yes"){
$verificar ="Sim";
}else
{
$verificar ="Não";
}
$tentativas = $tm_a["tentativas"] ;



//    echo $pmd; 
 if ($tm_a["aceito"] == "yes"){
 
$remoderadotempo = sprintf('%02d:%02d', $nHoras1, $nMinutos1);
$moderadortonovo = date("d/m \à\s H:i", utc_to_tz_time($tm_a["dataremodera"]));	 
}else{
$remoderadotempo = '---';
$moderadortonovo = '---';  
	  }
  $torrent_name = $arr["name"];
    $cat_t=mysql_query("SELECT * from categories WHERE id=".$arr['category']."") or die(mysql_error());
   $cat_paret=mysql_result($cat_t,0,"parent_cat");
      $cat_paren1=mysql_result($cat_t,0,"name");
	  
	  
	
if(mysql_num_rows($tm_r)==0){
$remoderadotempo = '---';
$moderadortonovo = '---';  
$username = 'Ninguem';
$mod_motivo = 'O torrent ainda não foi moderado';
$tm_com = 'O torrent ainda não foi moderado';
$mederandotempo = '--:--';
$moderadorto = '----';
	$tentativas = '0' ;  
}  

$gerenciar = "<a href='modtorrengeren.php?id=" . $arr["id"] . "'>Gerenciar</a>";	
 
  
?>

<BR>
<table width="100%" cellspacing="0" cellpadding="0" align="center" id="tabela1">
<tbody>
<tr><td align="center" colspan="4" class="tab1_cab8"><a href="torrents-details.php?id=<?php echo $arr["id"] ;?>"><?php echo $torrent_name ;?> </a><br><?php echo  $cat_paret ;?> > <?php echo  $cat_paren1 ;?> - Lançado por <a href="account-details.php?id=<?php echo $row_user_torr["id"] ;?>"><?php echo $row_user_torr["username"] ;?> </a></td></tr>

<tr>


<td width="100" align="center" class="ttable_col2"><b>Lançado em</b><br><?php echo date("d/m \à\s H:i", utc_to_tz_time($arr["added"])) ;?></td>
<td width="100" align="center" class="ttable_col2"><b>Moderado</b><br><?=$moderadorto?><br><b>Tempo </b><?php echo $mederandotempo ;?></td>

<td align="center" class="ttable_col2">Moderador Responsável:<br> <?php echo  $username ;?></td>
<td width="100" align="center" class="ttable_col2"><a href="#">[ <font size="2" ><?php echo $gerenciar ;?></font> ]</a></td>

</tr>


<tr><td width="100" align="center" class="ttable_col2"><b>Editado</b><br><b><font color="#0033CC"><?php echo $moderadortonovo ;?><br><b>Tempo </b><?php echo $remoderadotempo ;?></font></b></td>
<td width="100" align="center" class="ttable_col2"><b>Tentativas:</b><br><b><font color="#0033CC"><?php echo $tentativas ;?> </font></b></td>
<td align="center" colspan="3" class="ttable_col2"><b><font color="#FF0000"><?php echo  $tm_com ;?></font></b></td></tr>
</tbody></table>
<BR>

<?php 


}
}

}

	
	$nummod = mysql_num_rows($res);
	  


}else
{
show_error_msg("Erro", "<font color=red> Acesso Negado</font>");

}

end_framec();
if($nummod=='0'){
	show_error_msg("Erro", "<center>Não temos torrent já editados</center>");
}
stdfoot();
?>
 