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

if($CURUSER["level"]=="Administrador" || $CURUSER["level"]=="Sysop" || $CURUSER["level"]=="S.Moderador" || $CURUSER["level"]=="Moderador" || $CURUSER["level"]=="Liberador"){
////somente torrentes com seed
stdhead("Torrents bloqueados");
begin_framec("Torrents bloqueados");

$wherea=array();
if ($_GET["lech"] == '1') {
$wherea[] = "leechers = '0'";

}
if ($_GET["modid"] == $CURUSER["id"]) {
	$wherea[] = "uid = '1'";

}
	$wherea[] = "uid != '0'";
$where = implode(" AND ", $wherea);

$res2 = mysql_query("SELECT COUNT(*) FROM apppbloq LEFT JOIN torrents ON apppbloq.infohash = torrents.id WHERE banned='yes' AND $where ");
	$row1 = mysql_fetch_array($res2);
	$count = $row1[0];

	$perpage = 50;

	list($pagertop, $pagerbottom, $limit) = pager($perpage, $count, "torr_bloq.php?action&amp;");

 

?>
<div id="body_outer">

<div align="right">Filtrar: <a href="/torr_bloq.php">Ver todos</a> | <a href="/torr_bloq.php?modid=1">Bloqueados por mim</a> | <a href="/torr_bloq.php?lech=1">Sem leechers</a></div><br><br><div class="componentheading">Torrents bloqueados</div><div align="justify" class="framecentro"><p align="center" id="t4menu"><b><?php echo $pagertop; ?></b></p><br>
	<?php
	$rqq = "SELECT apppbloq.idblo, apppbloq.uid, apppbloq.bloqueado, apppbloq.addedb, apppbloq.infohash, apppbloq.motivo, torrents.id AS torrents_id, torrents.leechers AS torrents_leechers, torrents.name AS torrents_name, users.username AS users_name  FROM apppbloq LEFT JOIN torrents ON apppbloq.infohash = torrents.id LEFT JOIN users ON apppbloq.uid = users.id WHERE banned='yes' AND $where  ORDER BY name";
	$resqq = mysql_query($rqq);
		while ($row = mysql_fetch_array($resqq)){
		$bloqueado = date("d/m/y \à\s H:i:s", utc_to_tz_time($row["addedb"]));	 
	?>
<table cellspacing="1" cellpadding="0" align="center" id="tabela1">
<tbody>
<tr>
<td align="center" class="tab1_cab1"><a href="torrents-details.php?id=<?php echo $row["infohash"] ;?>"><?php echo $row["torrents_name"] ;?> </a></td>
</tr>
<tr>
<td align="center" class="ttable_col2">
<font size="2"><b>Motivo:</b></font> <br><br><i><?php echo $row["motivo"];?></i><br><br>
			<b>Torrent bloqueado por <a href="<?php echo $site_config["SITEURL"] ;?>/account-details.php?id=<?php echo $row["uid"]?>"><?php echo $row["users_name"] ;?></a> <?php echo $bloqueado ;?></b><br><br>
			<font size="2"><b>Leechers: <?php echo number_format($row["torrents_leechers"]) ;?></b></font></td></tr></tbody></table>
	

			<br>
					<?php } ?>
			<p align="center" id="t4menu"><b><?php echo $pagertop; ?></b></p></div>
				<div class="clr"></div>

			</div>
	
<?php
end_framec();
}
stdfoot();
?>
 