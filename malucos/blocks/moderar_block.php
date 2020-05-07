<?php
if($CURUSER["level"]=="Administrador" || $CURUSER["level"]=="Sysop" ||  $CURUSER["level"]=="Moderador" ||  $CURUSER["level"]=="S.Moderador" || $CURUSER["level"]=="Liberador"  ){
begin_block("Staff");
$id = (int) $_GET["id"];
$scrape = (int)$_GET["scrape"];
if (!is_valid_id($id))
	show_error_msg("ERROR", T_("THATS_NOT_A_VALID_ID"), 1);

$res = SQL_Query_exec("SELECT torrents.anon, torrents.freeleechexpire, torrents.tube, torrents.temposeed,  torrents.seeders, torrents.markedby, torrents.filmeresolucalt, torrents.musicalinkloja, torrents.musicalbum, torrents.musicalautor, torrents.filmeresolucao, torrents.markdate, imdb, torrents.thanks, torrents.adota, torrents.adotadata, torrents.adota_yes_no, torrents.banned, torrents.leechers, torrents.info_hash, torrents.filename, torrents.points, torrents.nfo, torrents.last_action, torrents.numratings, torrents.name, torrents.screens1, torrents.screens2, torrents.screens3, torrents.screens4, torrents.screens5, torrents.owner, torrents.save_as, torrents.descr, torrents.filmesinopse,  torrents.visible, torrents.size, torrents.added, torrents.views, torrents.hits, torrents.times_completed, torrents.id, torrents.type, torrents.external, torrents.image1, torrents.image2, torrents.announce, torrents.numfiles, torrents.freeleech, torrents.safe, torrents.category, torrents.nuked, IF(torrents.numratings < 2, NULL, ROUND(torrents.ratingsum / torrents.numratings, 1)) AS rating, torrents.numratings, categories.name AS cat_name, categories.image AS cat_pic, torrentlang.name AS lang_name, torrentlang.image AS lang_image, filmeano.name AS anoteste_name, filmeextensao.name AS testet_name, filmeaudio.name AS filmeaudio1_name, filmequalidade.name AS filmequalidade1_name,  filme3d.name AS filme3d1_name,  legenda.name AS legendaid_name, filmecodecvid.name AS filmecodecvid1_name,  filmecodecaud.name AS filmecodecaud1_name,  filmeduracaoh.name AS filmeduracaoh1_name,  filmeduracaomi.name AS filmeduracaomi1_name,  filmeidiomaorigi.name AS filmeidiomaorigi1_name, aplicrack.name AS aplicrack1_name, apliformarq.name AS apliformarq1_name, musicaqualidade.name AS musicaqualidade1_name, musicatensao.name AS musicatensao1_name, jogosgenero.name AS jogosgenero1_name, jogosformato.name AS jogosformato1_name, jogosmultiplay.name AS jogosmultiplay1_name, revistatensao.name AS revistatensao1_name, categories.parent_cat as cat_parent, users.username, users.privacy FROM torrents LEFT JOIN categories ON torrents.category = categories.id LEFT JOIN torrentlang ON torrents.torrentlang = torrentlang.id LEFT JOIN filmeano ON torrents.filmeano = filmeano.id LEFT JOIN filmeextensao ON torrents.filmeextensao = filmeextensao.id LEFT JOIN filmeaudio ON torrents.filmeaudio = filmeaudio.id LEFT JOIN filmequalidade ON torrents.filmequalidade = filmequalidade.id LEFT JOIN filme3d ON torrents.filme3d = filme3d.id  LEFT JOIN legenda ON torrents.legenda = legenda.id LEFT JOIN  filmecodecvid ON torrents. filmecodecvid = filmecodecvid.id LEFT JOIN   filmecodecaud ON torrents.  filmecodecaud =  filmecodecaud.id LEFT JOIN  filmeduracaoh ON torrents.filmeduracaoh = filmeduracaoh.id LEFT JOIN filmeduracaomi ON torrents.filmeduracaomi = filmeduracaomi.id LEFT JOIN  filmeidiomaorigi ON torrents.filmeidiomaorigi = filmeidiomaorigi.id LEFT JOIN  aplicrack ON torrents.aplicrack = aplicrack.id LEFT JOIN  apliformarq ON torrents.apliformarq = apliformarq.id LEFT JOIN  musicaqualidade ON torrents.musicaqualidade = musicaqualidade.id LEFT JOIN musicatensao ON torrents.musicatensao = musicatensao.id  LEFT JOIN jogosgenero ON torrents.jogosgenero = jogosgenero.id LEFT JOIN jogosformato ON torrents.jogosformato = jogosformato.id LEFT JOIN jogosmultiplay ON torrents.jogosmultiplay = jogosmultiplay.id   LEFT JOIN revistatensao ON torrents.revistatensao = revistatensao.id LEFT JOIN users ON torrents.owner = users.id WHERE torrents.id = $id") or die(mysql_error());
$row = mysql_fetch_assoc($res);
if ($CURUSER["id"] == $row["owner"] || $CURUSER["edit_torrents"] == "yes")
		$owned = 1;
	else
		$owned = 0;
											   $exe_safe = SQL_Query_exec("select safe from torrents where id='". $id ."'");
$arr_safe = mysql_fetch_array($exe_safe);
$safe = $arr_safe[0];
	

        $res_nuked = mysql_query("SELECT banned FROM torrents WHERE id = $id");
                                $row_nuked = mysql_fetch_array($res_nuked);
          
					
			
			
$bookt = SQL_Query_exec("SELECT torrentid FROM bookmarks WHERE torrentid = ".$row["id"]." AND userid = ".$CURUSER["id"]."");
	
$testeuser = mysql_query("SELECT * from moderation WHERE infohash=$id");
            $testeapro = mysql_fetch_array($testeuser);

?>







<script language="JavaScript"> 
function aprovar1(id){ 
if (window.confirm('Deseja aprovar este torrent')) {
 window.location.href = 'mark.php?id='+id
}
else { window.alert('Ok, nenhuma ação foi feita!') }

} 
</script>

<script language="JavaScript"> 
function desbloquear(id){ 
if (window.confirm('Deseja desbloquear este torrent')) {
 window.location.href = 'torrents-bloquear.php?id='+id
}
else { window.alert('Ok, nenhuma ação foi feita!') }

} 
</script>
<center>
<table  width="80%" cellspacing="1" cellpadding="0" align="center" class="tab1">
<tbody>


<?php if ($row["safe"] == "no") {
if($CURUSER["level"]=="Administrador" || $CURUSER["level"]=="Sysop" || $CURUSER["level"]=="Moderador" ||  $CURUSER["level"]=="S.Moderador"|| $CURUSER["level"]=="Liberador" ){
?>
<tr><td class="tab1_col3"  align="left" ><a href="#" onclick="aprovar1(<?php echo $id ;?>)"><img border="0" style="vertical-align: -30%;"  align="left"  src="images/torrent/lock.png"> Aprovar</a></td></tr>
<?php 
}
}
?>





<?php					if ($row_nuked["banned"] == "no"){
if($CURUSER["level"]=="Administrador" || $CURUSER["level"]=="Sysop" ||  $CURUSER["level"]=="Moderador" ||  $CURUSER["level"]=="S.Moderador" || $CURUSER["level"]=="Liberador" ){
?>
<tr><td class="tab1_col3"  align="left" ><A HREF="#" class="Style6"   onClick="window.open('pop_bloquear.php?bloquearid=<?php echo $id ;?>','player1','toolbar=0, location=0, directories=0, status=0,  resizable=0, copyhistory=0, menuBar=0, width=484, height=282, left=0, top=0');return(false)"><img border="0" style="vertical-align: -30%;" src="images/torrent/lock.png"> Bloquear</a></td>
<?php
}
}
?>
<?php 
			if ($row_nuked["banned"] == "yes"){
?>

<tr><td class="tab1_col3"  align="left" ><a href="#"  onclick="desbloquear(<?php echo $id ;?>)"><img border="0" style="vertical-align: -30%;"  align="left"  src="images/torrent/lock.png"> Desbloquear</a></td></tr>

<?php 
}

?>







<?php
if ($owned)
?>
<tr><td class="tab1_col3"  align="left" ><a href="torrents-edit.php?id=<?php echo $id ;?>"><img border="0" style="vertical-align: -30%;"  align="left"  src="images/torrent/lock.png"> Editar</a></td></tr>
<?php

?>
<?php
if($CURUSER["level"]=="Administrador" || $CURUSER["level"]=="Sysop" ||  $CURUSER["level"]=="S.Moderador" ||  $CURUSER["level"]=="Moderador"){
?>
<tr><td class="tab1_col3"  align="left" ><a href="snatchlist.php?tid=<?php echo $id ;?>"><img border="0" style="vertical-align: -30%;"  align="left"  src="images/torrent/lock.png"> Estatística</a></td></tr>
<?php 
}
?>

	</tbody></table></center>

<?php
end_block();
}
?>