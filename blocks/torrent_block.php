<?php
begin_block("Ações");
$id = (int) $_GET["id"];



$res = SQL_Query_exec("SELECT torrents.anon, torrents.freeleechexpire, torrents.tube, torrents.temposeed,  torrents.seeders, torrents.markedby, torrents.filmeresolucalt, torrents.musicalinkloja, torrents.musicalbum, torrents.musicalautor, torrents.filmeresolucao, torrents.markdate, torrents.thanks, torrents.adota, torrents.adotadata, torrents.adota_yes_no, torrents.banned, torrents.leechers, torrents.info_hash, torrents.filename, torrents.points, torrents.nfo, torrents.last_action, torrents.numratings, torrents.name, torrents.screens1, torrents.screens2, torrents.screens3, torrents.screens4, torrents.screens5, torrents.owner, torrents.save_as, torrents.descr, torrents.filmesinopse,  torrents.visible, torrents.size, torrents.added, torrents.views, torrents.hits, torrents.times_completed, torrents.id, torrents.type, torrents.external, torrents.image1, torrents.image2, torrents.announce, torrents.numfiles, torrents.freeleech, torrents.safe, torrents.category, torrents.nuked, IF(torrents.numratings < 2, NULL, ROUND(torrents.ratingsum / torrents.numratings, 1)) AS rating, torrents.numratings, categories.name AS cat_name, categories.image AS cat_pic, torrentlang.name AS lang_name, torrentlang.image AS lang_image, filmeano.name AS anoteste_name, filmeextensao.name AS testet_name, filmeaudio.name AS filmeaudio1_name, filmequalidade.name AS filmequalidade1_name,  filme3d.name AS filme3d1_name,  legenda.name AS legendaid_name, filmecodecvid.name AS filmecodecvid1_name,  filmecodecaud.name AS filmecodecaud1_name,  filmeduracaoh.name AS filmeduracaoh1_name,  filmeduracaomi.name AS filmeduracaomi1_name,  filmeidiomaorigi.name AS filmeidiomaorigi1_name, aplicrack.name AS aplicrack1_name, apliformarq.name AS apliformarq1_name, musicaqualidade.name AS musicaqualidade1_name, musicatensao.name AS musicatensao1_name, jogosgenero.name AS jogosgenero1_name, jogosformato.name AS jogosformato1_name, jogosmultiplay.name AS jogosmultiplay1_name, revistatensao.name AS revistatensao1_name, categories.parent_cat as cat_parent, users.username, users.privacy FROM torrents LEFT JOIN categories ON torrents.category = categories.id LEFT JOIN torrentlang ON torrents.torrentlang = torrentlang.id LEFT JOIN filmeano ON torrents.filmeano = filmeano.id LEFT JOIN filmeextensao ON torrents.filmeextensao = filmeextensao.id LEFT JOIN filmeaudio ON torrents.filmeaudio = filmeaudio.id LEFT JOIN filmequalidade ON torrents.filmequalidade = filmequalidade.id LEFT JOIN filme3d ON torrents.filme3d = filme3d.id  LEFT JOIN legenda ON torrents.legenda = legenda.id LEFT JOIN  filmecodecvid ON torrents. filmecodecvid = filmecodecvid.id LEFT JOIN   filmecodecaud ON torrents.  filmecodecaud =  filmecodecaud.id LEFT JOIN  filmeduracaoh ON torrents.filmeduracaoh = filmeduracaoh.id LEFT JOIN filmeduracaomi ON torrents.filmeduracaomi = filmeduracaomi.id LEFT JOIN  filmeidiomaorigi ON torrents.filmeidiomaorigi = filmeidiomaorigi.id LEFT JOIN  aplicrack ON torrents.aplicrack = aplicrack.id LEFT JOIN  apliformarq ON torrents.apliformarq = apliformarq.id LEFT JOIN  musicaqualidade ON torrents.musicaqualidade = musicaqualidade.id LEFT JOIN musicatensao ON torrents.musicatensao = musicatensao.id  LEFT JOIN jogosgenero ON torrents.jogosgenero = jogosgenero.id LEFT JOIN jogosformato ON torrents.jogosformato = jogosformato.id LEFT JOIN jogosmultiplay ON torrents.jogosmultiplay = jogosmultiplay.id   LEFT JOIN revistatensao ON torrents.revistatensao = revistatensao.id LEFT JOIN users ON torrents.owner = users.id WHERE torrents.id = $id") or die(mysql_error());
$row = mysql_fetch_assoc($res);
if (!$row)
	show_error_msg("ERROR", 'Torrent não encontrado.', 1);
	
if ($CURUSER["id"] == $row["owner"] )
		$owned = 1;
	else
		$owned = 0;
			
		
		
											   $exe_safe = SQL_Query_exec("select safe from torrents where id='". $id ."'");
$arr_safe = mysql_fetch_array($exe_safe);
$safe = $arr_safe[0];



if ($row["safe"] == "no") {
    if ($row["owner"] == $CURUSER["id"] || $CURUSER["level"]=="Sysop" ||  $CURUSER["level"]=="Administrador" || $CURUSER["level"]=="Moderador" || $CURUSER["level"]=="Liberador" ||  $CURUSER["level"]=="S.Moderador") {
    

print ("<div align=center><a href=\"download.php?id=$id\"><img src=\"".$site_config["SITEURL"]."/images/torrent/baixar.png\" border=\"0\"title='Baixar torrent.'></a></div>");
    }
}
else{
print ("<div align=center><a href=\"download.php?id=$id\"><img src=\"".$site_config["SITEURL"]."/images/torrent/baixar.png\" border=\"0\"title='Baixar torrent.'></a></div>");
    }	
$bookt = SQL_Query_exec("SELECT * FROM bookmarks WHERE torrentid = ".$row["id"]." AND userid = ".$CURUSER["id"]."");
	
	

?>

<script language="JavaScript"> 
function aprovar1(id){ 
if (window.confirm('Deseja aprovar este torrent')) {
 window.location.href = 'mark.php?id='+id
}
else { window.alert('Ok, nenhuma ação foi feita!') }

} 
</script>
<center><table width="80%" cellspacing="1" cellpadding="0" align="center" class="tab1">
<tbody><tr><td class="tab1_col3"  align="left" ><a href="torrents-arquivos.php?id=<?php echo $id ;?>"><img border="0" style="vertical-align: -30%;" align="left"  src="images/torrent/salvar.png"> Ver arquivos</a></td></tr>
<tr><td class="tab1_col3"  align="left" ><a href="pedido_atender.php?id=<?php echo $id ;?>"><img border="0" style="vertical-align: -30%;"  align="left"  src="images/torrent/confirmar.png"> Atender pedido</a></td></tr><tr><td class="tab1_col3"  align="left" ><a href="thanks.php?id=<?php echo $id ;?>"><img border="0" style="vertical-align: -30%;"  align="left"  src="images/torrent/agradecer.png"> Agradecer</a></td></tr><?php if (mysql_num_rows($bookt) == 0) {?><tr><td class="tab1_col3"  align="left" ><a href="bookmarks.php?torrent=<?php echo  $id ;?>"><img border="0" style="vertical-align: -30%;"  align="left"  src="images/torrent/favorito.png"> + Favorito</a></td></tr><?php }?><tr>
	<td class="tab1_col3"  align="left" ><A HREF="#" class="Style6"  onClick="window.open('pop_correcao.php?correid=<?php echo $id ;?>','player1','toolbar=0, location=0, directories=0, status=0,  resizable=0, copyhistory=0, menuBar=0, width=484, height=282, left=0, top=0');return(false)"><img border="0" style="vertical-align: -30%;"  align="left"  src="images/torrent/correcao.png"> Sugerir correção</a></td>
	</tr>
	<tr>
	<td class="tab1_col3"  align="left" ><a href="report.php?torrent=<?php echo $id ;?>"><img border="0" style="vertical-align: -30%;" src="images/torrent/denuciar.png"> Denunciar abuso</a></td>
	</tr>
	<?php	if ($row["times_completed"] > 0) {

		if ($row["seeders"] <= 1) {?>
	<tr>
	<td class="tab1_col3"  align="left" ><a href="torrents-reseed.php?id=<?php echo $id ;?>"><img border="0" style="vertical-align: -30%;" src="images/torrent/pedir_seed.png">Requisitar re-seed</a></td>
	</tr>
		<?php 	}}?>
<?php
	if($CURUSER["class"]== 1 || $CURUSER["class"]== 25 || $CURUSER["class"]== 30 || $CURUSER["class"]== 35 || $CURUSER["class"]== 40 || $CURUSER["class"]== 45 ||   $CURUSER["class"]== 50 || $CURUSER["class"]== 55 || $CURUSER["class"]== 60 || $CURUSER["class"]== 65 || $CURUSER["class"]== 70 ||  $CURUSER["class"]== 80 ){		
if( $CURUSER['id'] == $row['owner']){
?>
<tr><td class="tab1_col3"  align="left" ><a href="torrents-edit.php?id=<?php echo $id ;?>"><img border="0" style="vertical-align: -30%;"  align="left"  src="images/torrent/lock.png"> Editar</a></td></tr>
<?php
}
}
?>
	</tbody></table></center>

<?php
end_block();
?>
