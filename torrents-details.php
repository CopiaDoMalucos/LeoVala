<?php
############################################################
#######                                             ########
#######                                             ########
#######           www.brshares.com 2.0              ########
#######                                             ########
#######                                             ########
############################################################


require_once("backend/functions_torrent.php");
require_once("backend/BDecode.php");
require_once("backend/parse.php") ;//replace with parse later
dbconn();

$torrent_dir = $site_config["torrent_dir"];	
$nfo_dir = $site_config["nfo_dir"];	

//check permissions
if ($site_config["MEMBERSONLY"]){
	loggedinonly();

	if($CURUSER["view_torrents"]=="no")
		show_error_msg(T_("ERROR"), T_("NO_TORRENT_VIEW"), 1);
}

//************ DO SOME "GET" STUFF BEFORE PAGE LAYOUT ***************

$id = (int) $_GET["id"];
$scrape = (int)$_GET["scrape"];
if (!is_valid_id($id))
	show_error_msg("ERROR", T_("TORRENT_NOT_FOUND"), 1);
	

//GET ALL MYSQL VALUES FOR THIS TORRENT
$res = SQL_Query_exec("SELECT torrents.anon, torrents.freeleechexpire, torrents.tube, torrents.temposeed,  torrents.seeders, torrents.markedby, torrents.filmeresolucalt, torrents.musicalinkloja, torrents.musicalbum, torrents.musicalautor, torrents.filmeresolucao, torrents.apliversao, torrents.markdate, torrents.thanks, torrents.adota, torrents.adotadata, torrents.adota_yes_no, torrents.banned, torrents.leechers, torrents.info_hash, torrents.filename, torrents.points, torrents.nfo, torrents.last_action, torrents.numratings, torrents.name, torrents.screens1, torrents.screens2, torrents.screens3, torrents.screens4, torrents.screens5, torrents.owner, torrents.save_as, torrents.descr, torrents.filmesinopse,  torrents.visible, torrents.size, torrents.added, torrents.views, torrents.hits, torrents.times_completed, torrents.id, torrents.type, torrents.external, torrents.image1, torrents.image2, torrents.announce, torrents.numfiles, torrents.freeleech, torrents.safe,  torrents.adota,  torrents.adota_yes_no, torrents.category, torrents.nuked, IF(torrents.numratings < 2, NULL, ROUND(torrents.ratingsum / torrents.numratings, 1)) AS rating, torrents.numratings, categories.name AS cat_name, categories.image AS cat_pic, torrentlang.name AS lang_name, torrentlang.image AS lang_image, filmeano.name AS anoteste_name, filmeextensao.name AS testet_name, filmeaudio.name AS filmeaudio1_name, filmequalidade.name AS filmequalidade1_name,  filme3d.name AS filme3d1_name,  legenda.name AS legendaid_name, filmecodecvid.name AS filmecodecvid1_name,  filmecodecaud.name AS filmecodecaud1_name,  filmeduracaoh.name AS filmeduracaoh1_name,  filmeduracaomi.name AS filmeduracaomi1_name,  filmeidiomaorigi.name AS filmeidiomaorigi1_name, aplicrack.name AS aplicrack1_name, apliformarq.name AS apliformarq1_name, musicaqualidade.name AS musicaqualidade1_name, musicatensao.name AS musicatensao1_name, jogosgenero.name AS jogosgenero1_name, jogosformato.name AS jogosformato1_name, jogosmultiplay.name AS jogosmultiplay1_name, revistatensao.name AS revistatensao1_name, categories.parent_cat as cat_parent, categories.parent_cat as idex_sort,  apppbloq.motivo as motivo, users.username, users.privacy FROM torrents LEFT JOIN categories ON torrents.category = categories.id LEFT JOIN torrentlang ON torrents.torrentlang = torrentlang.id LEFT JOIN filmeano ON torrents.filmeano = filmeano.id LEFT JOIN filmeextensao ON torrents.filmeextensao = filmeextensao.id LEFT JOIN filmeaudio ON torrents.filmeaudio = filmeaudio.id LEFT JOIN filmequalidade ON torrents.filmequalidade = filmequalidade.id LEFT JOIN filme3d ON torrents.filme3d = filme3d.id  LEFT JOIN legenda ON torrents.legenda = legenda.id LEFT JOIN  filmecodecvid ON torrents. filmecodecvid = filmecodecvid.id LEFT JOIN   filmecodecaud ON torrents.  filmecodecaud =  filmecodecaud.id LEFT JOIN  filmeduracaoh ON torrents.filmeduracaoh = filmeduracaoh.id LEFT JOIN filmeduracaomi ON torrents.filmeduracaomi = filmeduracaomi.id LEFT JOIN  filmeidiomaorigi ON torrents.filmeidiomaorigi = filmeidiomaorigi.id LEFT JOIN  aplicrack ON torrents.aplicrack = aplicrack.id LEFT JOIN  apliformarq ON torrents.apliformarq = apliformarq.id LEFT JOIN  musicaqualidade ON torrents.musicaqualidade = musicaqualidade.id LEFT JOIN musicatensao ON torrents.musicatensao = musicatensao.id  LEFT JOIN jogosgenero ON torrents.jogosgenero = jogosgenero.id LEFT JOIN jogosformato ON torrents.jogosformato = jogosformato.id LEFT JOIN jogosmultiplay ON torrents.jogosmultiplay = jogosmultiplay.id   LEFT JOIN revistatensao ON torrents.revistatensao = revistatensao.id LEFT JOIN users ON torrents.owner = users.id  LEFT JOIN apppbloq ON torrents.id = apppbloq.infohash  WHERE torrents.id = $id") or die(mysql_error());
$row = mysql_fetch_assoc($res);       

//DECIDE IF TORRENT EXISTS
if (!$row)
	show_error_msg("ERROR", 'Torrent não encontrado.', 1);
	
//torrent is availiable so do some stuff

if ($_GET["hit"]) {
	SQL_Query_exec("UPDATE torrents SET views = views + 1 WHERE id = $id");
	header("Location: torrents-details.php?id=$id");
	die;
	}

	stdhead(T_("DETAILS_FOR_TORRENT")." \"" . $row["name"] . "\"");

           echo "<script type='text/javascript' src='scripts/comments.php?id=$id'></script>\n";
		   
	if ($CURUSER["id"] == $row["owner"] || $CURUSER["edit_torrents"] == "yes")
		$owned = 1;
	else
		$owned = 0;

//take rating
if ($_GET["takerating"] == 'yes'){
  $res_ret = SQL_Query_exec("SELECT users.id, users.username, users.uploaded, users.downloaded, users.privacy, completed.date FROM users LEFT JOIN completed ON ".$CURUSER["id"]." = completed.userid WHERE users.enabled = 'yes' AND completed.torrentid = '$id'");
  if (mysql_num_rows($res_ret) == 0){
  	show_error_msg(T_("RATING_ERROR"), 'Você precisa baixar o torrent para avaliá-lo.', 1);
  }


	$rating = (int)$_POST['rating'];

	if ($rating <= 0 || $rating > 5)
		show_error_msg(T_("RATING_ERROR"), T_("INVAILD_RATING"), 1);

	$res = SQL_Query_exec("INSERT INTO ratings (torrent, user, rating, added) VALUES ($id, " . $CURUSER["id"] . ", $rating, '".get_date_time()."')");

	if (!$res) {
		if (mysql_errno() == 1062)
			show_error_msg(T_("RATING_ERROR"), T_("YOU_ALREADY_RATED_TORRENT"), 1);
		else
			show_error_msg(T_("RATING_ERROR"), T_("A_UNKNOWN_ERROR_CONTACT_STAFF"), 1);
	}

	SQL_Query_exec("UPDATE torrents SET numratings = numratings + 1, ratingsum = ratingsum + $rating WHERE id = $id");
	show_error_msg('Obrigado', T_("RATING_THANK")."<br /><br /><a href='torrents-details.php?id=$id'>" .T_("BACK_TO_TORRENT"). "</a>");
}

//take comment add
if ($_GET["takecomment"] == 'yes'){
	loggedinonly();
	$body = $_POST['body'];
	
	if (!$body)
		show_error_msg(T_("RATING_ERROR"), T_("YOU_DID_NOT_ENTER_ANYTHING"), 1);

	SQL_Query_exec("UPDATE torrents SET comments = comments + 1 WHERE id = $id");

	SQL_Query_exec("INSERT INTO comments (user, torrent, added, text) VALUES (".$CURUSER["id"].", ".$id.", '" .get_date_time(). "', " . sqlesc($body).")");

	if (mysql_affected_rows() == 1)
			show_error_msg(T_("COMPLETED"), T_("COMMENT_ADDED"), 0);
		else
			show_error_msg(T_("ERROR"), T_("UNABLE_TO_ADD_COMMENT"), 0);
}//end insert comment

//START OF PAGE LAYOUT HERE
$char1 = 100; //cut length
$shortname = CutName(htmlspecialchars($row["name"]), $char1);
		


          
					
if ($row["banned"] == "yes"){
	$freeleechexpireb = "<font color=red>Este torrent foi bloqueado para novos downloads!.</font>";
	    $freeleechexpiretotalb = "Motivo:<br>".$row["motivo"]."";
  print("<CENTER>". $freeleechexpireb . " <br>".$freeleechexpiretotalb."</CENTER>");
}	



		if ($row["freeleech"]=='1' || $CURUSER["freeleechuser"] == "yes" )
    {	
	 $freeleechexpire = "<font color=green>TORRENT FREE!.</font>";
            $freeleechexpiretotal = "<BR>Isso significa que o <font color=red>download</font> não será contabilizado em sua conta, apenas o <font color=green>upload</font>.<BR><BR><BR>";


			begin_framec("" . $freeleechexpire ." ");
			
			echo "<table class='tab1' cellpadding='0' cellspacing='1' align='center'>";
  print("<CENTER>". $freeleechexpiretotal . " </CENTER>");
}


			echo "<table class='tab1' cellpadding='0' cellspacing='1' align='center'>";
			







$res1235 = mysql_query("SELECT * FROM users WHERE id=".$row["owner"].""); 
		$arr1235 = mysql_fetch_assoc($res1235);	
		
$exe_grupo = mysql_query("SELECT * FROM teams WHERE id={$arr1235["team"]}");	

		if($arr1235["team"]==0)
			{
	$grupo ="";
	}else{	
		$arr_grupo = mysql_fetch_assoc($exe_grupo);	
	$grupo = "<a href='grupos_lancamentos.php?id={$arr_grupo["id"]}'><img src='{$arr_grupo["image2"]}' title='{$arr_grupo["name"]}' /></a>";		}
	if (!$arr_grupo["image2"]){
	}
	else{
 print("<td align=center colspan=2 class=tab1_col3><CENTER>". $grupo . "&nbsp&nbsp</CENTER></td>");
}

 print("<tr><td width=100% align=center colspan=2 class=tab1_cab1><font  class=tab1_cab1 >" . $shortname . "</font></tr>");
if ($row["safe"] == "no") {
 print("<tr><td width=50%  align=right  class=tab1_col3><b>Download:</b></td><td width=50%  align=left class=tab1_col3  ><font  color=red ><B>Aguardando liberação.</B></font></td></tr>");

}


	if ($row["screens1"] != "" OR $row["screens2"] != "" OR $row["screens3"] != "" OR $row["screens3"] != "" ) {
   if ($row["screens1"] != "")
    $screens1 = "<IMG src=".$row['screens1']." align=center width=350 height=500 border=0>";

  print("<tr><td align=center colspan=2 class=tab1_col3><CENTER><b>Capa</b><br>");
$textoxt =  $row['screens1'] ;
$textoxt1 = "[capa]".$textoxt."[/capa]";


 echo format_comment($textoxt1); 
			

 print("&nbsp&nbsp</CENTER></td></tr>");
}







//////////////////

$filme1="<tr><td width=50%  align=right  class=tab1_col3><b>Ano de lançamento:</b></td><td width=50%  align=left class=tab1_col3  >" . $row["anoteste_name"] . "</tr>\n";
$filme2="<tr><td width=50%  align=right  class=tab1_col3><b>Audio:</b></td><td width=50%  align=left class=tab1_col3>" . $row["filmeaudio1_name"] . "\n";
$filme3="<tr><td width=50%  align=right  class=tab1_col3><b>Extensão:</b></td><td width=50%  align=left class=tab1_col3>" . $row["testet_name"] . "\n";
$filme4="<tr><td width=50%  align=right  class=tab1_col3><b>Qualidade:</b></td><td width=50%  align=left class=tab1_col3>" . $row["filmequalidade1_name"] . "\n";
$filme5="<tr><td width=50%  align=right  class=tab1_col3><b>Filme em 3D:</b></td><td width=50%  align=left class=tab1_col3>" . $row["filme3d1_name"] . "\n";
$filme11="<tr><td width=50%  align=right  class=tab1_col3><b>Legenda Embutidas:</b></td><td width=50%  align=left class=tab1_col3>" . $row["legendaid_name"] . "\n";
$filme6="<tr><td width=50%  align=right  class=tab1_col3><b>Codecs de Vídeo:</b></td><td width=50%  align=left class=tab1_col3>" . $row["filmecodecvid1_name"] . "\n";
$filme7="<tr><td width=50%  align=right  class=tab1_col3><b>Codecs de Audio:</b></td><td width=50%  align=left class=tab1_col3>" . $row["filmecodecaud1_name"] . "\n";
$filme8="<tr><td width=50%  align=right  class=tab1_col3><b>Idioma Original:</b></td><td width=50%  align=left class=tab1_col3>" . $row["filmeidiomaorigi1_name"] . "\n";
$filme9="<tr><td width=50%  align=right  class=tab1_col3><b>Duração:</b></td><td width=50%  align=left class=tab1_col3> Horas: " . $row["filmeduracaoh1_name"] . "</font> Minutos: " . $row["filmeduracaomi1_name"] . "\n";
$filme10="<tr><td width=50%  align=right  class=tab1_col3 ><b>Resolução:</b></td ><td width=50%  align=left class=tab1_col3> Pixels de largura por: " . $row["filmeresolucao"] . "</font> Pixels de altura: " . $row["filmeresolucalt"] . "\n";

if ($row["username"]){
	$user123="<tr><td width=50%  align=right  class=tab1_col3><b>Enviado por:</b></td><td width=50%  align=left class=tab1_col3><a href='account-details.php?id=" . $row["owner"] . "'>" . $row["username"] . "</a>\n";
}else{
$user123="<tr><td width=50%  align=right  class=tab1_col3><b>Enviado por:</b></td><td width=50%  align=left class=tab1_col3>Unknown\n";
}
	//gerador de upload

if($row["temposeed"] == ""){ 
	$temposeed="<tr><td width=50%  align=right  class=tab1_col3 ><b>Horário de Seed:</b></td ><td width=50%  align=left class=tab1_col3>Indefinido</font>\n";

}else{
		$temposeed="<tr><td width=50%  align=right  class=tab1_col3 ><b>Horário de Seed:</b></td ><td width=50%  align=left class=tab1_col3>" . $row["temposeed"] . "</font>\n";
}

$tamanho="<tr><td width=50%  align=right  class=tab1_col3><b>Tamanho:</b></td><td width=50%  align=left class=tab1_col3> " . mksize($row["size"]) . "\n";
$generotor="<tr><td width=50%  align=right  class=tab1_col3><b>Categoria:</b></td><td width=50%  align=left class=tab1_col3><a href='torrents.php?parent_cat=" . $row["idex_sort"] . "'>" . $row["cat_parent"] . " </a> > " . $row["cat_name"] . "\n";


 if ($row["category"] == 23 || $row["category"] == 120 || $row["category"] == 4 || $row["category"] == 24 || $row["category"] == 39 || $row["category"] == 3 || $row["category"] == 34 || $row["category"] == 35 || $row["category"] == 37 || $row["category"] == 42 || $row["category"] == 5 || $row["category"] == 40 || $row["category"] == 7 || $row["category"] == 28 || $row["category"] == 1 || $row["category"] == 25 || $row["category"] == 26 || $row["category"] == 2 || $row["category"] == 27 || $row["category"] == 33 || $row["category"] == 36 || $row["category"] == 50 || $row["category"] == 41 || $row["category"] == 6 || $row["category"] == 117 || $row["category"] == 114 || $row["category"] == 124) 
 
	

		{

	
		print "$generotor</tr>\n";
		print "$filme1</tr>\n";
		print "$filme2</tr>\n";
		print "$filme3</tr>\n";
		print "$filme4</tr>\n";
		print "$filme5</tr>\n";
		print "$filme11</tr>\n";
		print "$filme6</tr>\n";
		print "$filme7</tr>\n";
		print "$filme8</tr>\n";
		print "$filme9</tr>\n";
		print "$filme10</tr>\n";
		print "$tamanho</tr>\n";
				 		print "$temposeed</tr>\n";
		print "$user123</tr>\n";
		
		}
	

//gerador de upload filmes
/// gerador de aplicativos
$aplicativo1="<tr><td width=50%  align=right  class=tab1_col3><b>Ano de lançamento:</b></td><td width=50%  align=left class=tab1_col3>" . $row["anoteste_name"] . "\n";
$aplicativo2="<tr><td width=50%  align=right  class=tab1_col3><b>Idioma Original:</b></td><td width=50%  align=left class=tab1_col3>" . $row["filmeidiomaorigi1_name"] . "\n";
$aplicativo3="<tr><td width=50%  align=right  class=tab1_col3><b>Formato do Arquivo:</b></td><td width=50%  align=left class=tab1_col3>" . $row["apliformarq1_name"] . "\n"; 
$aplicativo4="<tr><td width=50%  align=right  class=tab1_col3><b>Crack:</b></td><td width=50%  align=left class=tab1_col3>" . $row["aplicrack1_name"] . "\n";
$aplicativo5="<tr><td width=50%  align=right  class=tab1_col3><b>Versão:</b></td><td width=50%  align=left class=tab1_col3>" . $row["apliversao"] . "\n";  
 if ($row["category"] == 20 || $row["category"] == 19 || $row["category"] == 18 || $row["category"] == 94 || $row["category"] == 115 || $row["category"] == 122 || $row["category"] == 123) 
 		{

			print "$generotor</tr>\n";
		print "$aplicativo1</tr>\n";
	    print "$aplicativo2</tr>\n";
		print "$aplicativo3</tr>\n";
		print "$aplicativo4</tr>\n";
		print "$aplicativo5</tr>\n";
				print "$tamanho</tr>\n";
						 		print "$temposeed</tr>\n";
		print "$user123</tr>\n";
		}
$char2 = 40; //cut length
$musicasme = CutName(htmlspecialchars($row["musicalinkloja"]), $char2);
		
$musicas1="<tr><td align=right  class=tab1_col3><b>Ano de lançamento:</b></td><td width=50%  align=left class=tab1_col3>" . $row["anoteste_name"] . "\n";
$musicas2="<tr><td align=right  class=tab1_col3><b>Extensão:</b></td><td width=50%  align=left class=tab1_col3>" . $row["musicatensao1_name"] . "\n";
$musicas3="<tr><td align=right  class=tab1_col3><b>Qualidade:</b></td><td width=50%  align=left class=tab1_col3>" . $row["musicaqualidade1_name"] . "\n";	
$musicas6="<tr><td align=right  class=tab1_col3><b>Artista / Grupo:</b></td><td width=50%  align=left class=tab1_col3>" . $row["musicalautor"] . "\n"; 
$musicas4="<tr><td align=right  class=tab1_col3><b>Album:</b></td><td width=50%  align=left class=tab1_col3>" . $row["musicalbum"] . "\n"; 

$musicas5="<tr><td align=right  class=tab1_col3><b>Link de loja/site oficial:</b></td><td width=50%  align=left class=tab1_col3>$musicasme</font>\n"; 
 
 
 if ($row["category"] == 51 || $row["category"] == 57 || $row["category"] == 61 || $row["category"] == 63 || $row["category"] == 64 || $row["category"] == 66 || $row["category"] == 81 || $row["category"] == 52 || $row["category"] == 53 || $row["category"] == 54 || $row["category"] == 55 || $row["category"] == 56 || $row["category"] == 58 || $row["category"] == 59 || $row["category"] == 60 || $row["category"] == 62 || $row["category"] == 65 || $row["category"] == 67 || $row["category"] == 68 || $row["category"] == 69 || $row["category"] == 70 || $row["category"] == 107 || $row["category"] == 71 || $row["category"] == 72 || $row["category"] == 73 || $row["category"] == 74 || $row["category"] == 75 || $row["category"] == 76 || $row["category"] == 77 || $row["category"] == 78 || $row["category"] == 79 || $row["category"] == 80 || $row["category"] == 81 || $row["category"] == 82 || $row["category"] == 83 || $row["category"] == 84 || $row["category"] == 85 || $row["category"] == 86 || $row["category"] == 87 || $row["category"] == 88 || $row["category"] == 89  || $row["category"] == 90 || $row["category"] == 91 || $row["category"] == 118) 
 { 

 	print "$generotor</tr>\n";
 print "$musicas1</tr>\n";
  print "$musicas2</tr>\n";
   print "$musicas3</tr>\n";
    print "$musicas6</tr>\n";
     print "$musicas4</tr>\n";
	   print "$musicas5</tr>\n";
	   		print "$tamanho</tr>\n";
					 		print "$temposeed</tr>\n";
	   print "$user123</tr>\n";
 }
 
 $jogos1="<tr><td width=50%  align=right  class=tab1_col3><b>Ano de lançamento:</b></td><td width=50%  align=left class=tab1_col3>" . $row["anoteste_name"] . "\n";
$jogos2="<tr><td width=50%  align=right  class=tab1_col3><b>Género:</b></td><td width=50%  align=left class=tab1_col3>" . $row["jogosgenero1_name"] . "\n";
$jogos10="<tr><td width=50%  align=right  class=tab1_col3><b>Idioma::</b></td><td width=50%  align=left class=tab1_col3> " . $row["filmeidiomaorigi1_name"] . "\n";

if ($row["category"] == 102 || $row["category"] == 105 || $row["category"] == 10 || $row["category"] == 15 || $row["category"] == 11 || $row["category"] == 43 || $row["category"] == 12 || $row["category"] == 44 || $row["category"] == 13 || $row["category"] == 14 || $row["category"] == 16 || $row["category"] == 121 || $row["category"] == 116)
	

		{

			print "$generotor</tr>\n";
		print "$jogos1</tr>\n";
		print "$jogos2</tr>\n";

		print "$jogos9</tr>\n";
		print "$jogos10</tr>\n";
				print "$tamanho</tr>\n";
						 		print "$temposeed</tr>\n";
	print "$user123</tr>\n";
		}
 ////////////////// video clipe
$videoclip1="<tr><td width=50%  align=right  class=tab1_col3><b>Ano de lançamento:</b></td><td width=50%  align=left class=tab1_col3>" . $row["anoteste_name"] . "\n";
$videoclip2="<tr><td width=50%  align=right  class=tab1_col3><b>Audio:</b></td><td width=50%  align=left class=tab1_col3>" . $row["filmeaudio1_name"] . "\n";
$videoclip3="<tr><td width=50%  align=right  class=tab1_col3><b>Extensão:</b></td><td width=50%  align=left class=tab1_col3>" . $row["testet_name"] . "\n";
$videoclip4="<tr><td width=50%  align=right  class=tab1_col3><b>Qualidade:</b></td><td width=50%  align=left class=tab1_col3>" . $row["filmequalidade1_name"] . "\n";
$videoclip5="<tr><td width=50%  align=right  class=tab1_col3><b>video Clipe em 3D:</b></td><td width=50%  align=left class=tab1_col3>" . $row["filme3d1_name"] . "\n";
$videoclip6="<tr><td width=50%  align=right  class=tab1_col3><b>Legenda Embutidas:</b></td><td width=50%  align=left class=tab1_col3>" . $row["legendaid_name"] . "\n";
$videoclip7="<tr><td width=50%  align=right  class=tab1_col3><b>Codecs de Vídeo:</b></td><td width=50%  align=left class=tab1_col3>" . $row["filmecodecvid1_name"] . "\n";
$videoclip8="<tr><td width=50%  align=right  class=tab1_col3><b>Codecs de Audio:</b></td><td width=50%  align=left class=tab1_col3>" . $row["filmecodecaud1_name"] . "\n";
$videoclip9="<tr><td width=50%  align=right  class=tab1_col3><b>Idioma Original:</b></td><td width=50%  align=left class=tab1_col3>" . $row["filmeidiomaorigi1_name"] . "\n";
$videoclip10="<tr><td width=50%  align=right  class=tab1_col3><b>Duração:</b></td><td width=50%  align=left class=tab1_col3> Horas: " . $row["filmeduracaoh1_name"] . " Minutos: " . $row["filmeduracaomi1_name"] . "\n";
$videoclip11="<tr><td width=50%  align=right  class=tab1_col3><b>Resolução:</b></td><td width=50%  align=left class=tab1_col3> Pixels de largura por: " . $row["filmeresolucao"] . "</font> Pixels de altura: " . $row["filmeresolucalt"] . "\n";
	//gerador de upload
 if ($row["category"] == 112 ) 
 
	

		{

			print "$generotor</tr>\n";
		print "$videoclip1</tr>\n";
		print "$videoclip2</tr>\n";
		print "$videoclip3</tr>\n";
		print "$videoclip4</tr>\n";
		print "$videoclip5</tr>\n";
		print "$videoclip6</tr>\n";
		print "$videoclip7</tr>\n";
		print "$videoclip8</tr>\n";
		print "$videoclip9</tr>\n";
		print "$videoclip10</tr>\n";
		print "$videoclip11</tr>\n";
				print "$tamanho</tr>\n";
						 		print "$temposeed</tr>\n";
		print "$user123</tr>\n";
		}
	

 
 
 
 
 
 
 
 //////////////////

$videotv1="<tr><td width=50%  align=right  class=tab1_col3><b>Ano de lançamento:</b></td><td width=50%  align=left class=tab1_col3>" . $row["anoteste_name"] . "\n";
$videotv2="<tr><td width=50%  align=right  class=tab1_col3><b>Audio:</b></td><td width=50%  align=left class=tab1_col3>" . $row["filmeaudio1_name"] . "\n";
$videotv3="<tr><td width=50%  align=right  class=tab1_col3><b>Extensão:</b></td ><td width=50%  align=left class=tab1_col3>" . $row["testet_name"] . "\n";
$videotv4="<tr><td width=50%  align=right  class=tab1_col3><b>Qualidade:</b></td><td width=50%  align=left class=tab1_col3>" . $row["filmequalidade1_name"] . "\n";
$videotv5="<tr><td width=50%  align=right  class=tab1_col3><b>video Tv em 3D:</b></td><td width=50%  align=left class=tab1_col3>" . $row["filme3d1_name"] . "\n";
$videotv6="<tr><td width=50%  align=right  class=tab1_col3><b>Legenda Embutidas:</b></td><td width=50%  align=left class=tab1_col3>" . $row["legendaid_name"] . "\n";
$videotv7="<tr><td width=50%  align=right  class=tab1_col3><b>Codecs de Vídeo:</b></td><td width=50%  align=left class=tab1_col3>" . $row["filmecodecvid1_name"] . "\n";
$videotv8="<tr><td width=50%  align=right  class=tab1_col3><b>Codecs de Audio:</b></td><td width=50%  align=left class=tab1_col3>" . $row["filmecodecaud1_name"] . "\n";
$videotv9="<tr><td width=50%  align=right  class=tab1_col3><b>Idioma Original:</b></td><td width=50%  align=left class=tab1_col3>" . $row["filmeidiomaorigi1_name"] . "\n";
$videotv10="<tr><td width=50%  align=right  class=tab1_col3><b>Duração:</b></td><td width=50%  align=left class=tab1_col3> Horas: " . $row["filmeduracaoh1_name"] . " Minutos:" . $row["filmeduracaomi1_name"] . "\n";
$videotv11="<tr><td align=right  class=tab1_col3><b>Resolução:</b></td><td width=50%  align=left class=tab1_col3> Pixels de largura por: " . $row["filmeresolucao"] . " Pixels de altura:" . $row["filmeresolucalt"] . "\n";

	//gerador de upload


  



  if ($row["category"] == 49 || $row["category"] == 101 || $row["category"] == 99 || $row["category"] == 100 || $row["category"] == 103) 
	

		{

			print "$generotor</tr>\n";
		print "$videotv1</tr>\n";
		print "$videotv2</tr>\n";
		print "$videotv3</tr>\n";
		print "$videotv4</tr>\n";
		print "$videotv5</tr>\n";
		print "$videotv6</tr>\n";
		print "$videotv7</tr>\n";
		print "$videotv8</tr>\n";
		print "$videotv9</tr>\n";
		print "$videotv10</tr>\n";
		print "$videotv11</tr>\n";
				print "$tamanho</tr>\n";
						 		print "$temposeed</tr>\n";
		print "$user123</tr>\n";
		}
 
 
 
 
  //////////////////

$show1="<tr><td width=50%  align=right  class=tab1_col3><b>Ano de lançamento:</b></td><td width=50%  align=left class=tab1_col3>" . $row["anoteste_name"] . "\n";
$show2="<tr><td width=50%  align=right  class=tab1_col3><b>Audio:</b></td><td width=50%  align=left class=tab1_col3>" . $row["filmeaudio1_name"] . "\n";
$show3="<tr><td width=50%  align=right  class=tab1_col3><b>Extensão:</b></td ><td width=50%  align=left class=tab1_col3>" . $row["testet_name"] . "\n";
$show4="<tr><td width=50%  align=right  class=tab1_col3><b>Qualidade:</b></td><td width=50%  align=left class=tab1_col3>" . $row["filmequalidade1_name"] . "\n";
$show5="<tr><td width=50%  align=right  class=tab1_col3><b>Show em 3D:</b></td><td width=50%  align=left class=tab1_col3>" . $row["filme3d1_name"] . "\n";
$show6="<tr><td width=50%  align=right  class=tab1_col3><b>Legenda Embutidas:</b></td><td width=50%  align=left class=tab1_col3>" . $row["legendaid_name"] . "\n";
$show7="<tr><td width=50%  align=right  class=tab1_col3><b>Codecs de Vídeo:</b></td><td width=50%  align=left class=tab1_col3>" . $row["filmecodecvid1_name"] . "\n";
$show8="<tr><td width=50%  align=right  class=tab1_col3><b>Codecs de Audio:</b></td><td width=50%  align=left class=tab1_col3>" . $row["filmecodecaud1_name"] . "\n";
$show9="<tr><td width=50%  align=right  class=tab1_col3><b>Idioma Original:</b></td><td width=50%  align=left class=tab1_col3>" . $row["filmeidiomaorigi1_name"] . "\n";
$show10="<tr><td width=50%  align=right  class=tab1_col3><b>Duração:</b></td><td width=50%  align=left class=tab1_col3> Horas: " . $row["filmeduracaoh1_name"] . "</font> Minutos: " . $row["filmeduracaomi1_name"] . "\n";
$show11="<tr><td width=50%  align=right  class=tab1_col3><b>Resolução:</b></td><td width=50%  align=left class=tab1_col3> Pixels de largura por: " . $row["filmeresolucao"] . " Pixels de altura: " . $row["filmeresolucalt"] . "\n";

	//gerador de upload


  



  if ($row["category"] == 110 ) 
	

		{

			print "$generotor</tr>\n";
		print "$show1</tr>\n";
		print "$show2</tr>\n";
		print "$show3</tr>\n";
		print "$show4</tr>\n";
		print "$show5</tr>\n";
		print "$show6</tr>\n";
		print "$show7</tr>\n";
		print "$show8</tr>\n";
		print "$show9</tr>\n";
		print "$show10</tr>\n";
		print "$show11</tr>\n";
				print "$tamanho</tr>\n";
						 		print "$temposeed</tr>\n";
		print "$user123</tr>\n";
		}
   //////////////////

$series1="<tr><td width=50%  align=right  class=tab1_col3><b>Ano de lançamento:</b></td><td width=50%  align=left class=tab1_col3>" . $row["anoteste_name"] . "\n";
$series2="<tr><td width=50%  align=right  class=tab1_col3><b>Audio:</b></td><td width=50%  align=left class=tab1_col3>" . $row["filmeaudio1_name"] . "\n";
$series3="<tr><td width=50%  align=right  class=tab1_col3><b>Extensão:</b></td><td width=50%  align=left class=tab1_col3>" . $row["testet_name"] . "\n";
$series4="<tr><td width=50%  align=right  class=tab1_col3><b>Qualidade Embutidas:</b></td><td width=50%  align=left class=tab1_col3>" . $row["filmequalidade1_name"] . "\n";
$series5="<tr><td width=50%  align=right  class=tab1_col3><b>Serie em 3D:</b></td><td width=50%  align=left class=tab1_col3>" . $row["filme3d1_name"] . "\n";
$series6="<tr><td width=50%  align=right  class=tab1_col3><b>Legenda Embutidas:</b></td><td width=50%  align=left class=tab1_col3>" . $row["legendaid_name"] . "\n";
$series7="<tr><td width=50%  align=right  class=tab1_col3><b>Codecs de Vídeo:</b></td><td width=50%  align=left class=tab1_col3>" . $row["filmecodecvid1_name"] . "\n";
$series8="<tr><td width=50%  align=right  class=tab1_col3><b>Codecs de Audio:</b></td><td width=50%  align=left class=tab1_col3>" . $row["filmecodecaud1_name"] . "\n";
$series9="<tr><td width=50%  align=right  class=tab1_col3><b>Idioma Original:</b></td><td width=50%  align=left class=tab1_col3>" . $row["filmeidiomaorigi1_name"] . "\n";
$series10="<tr><td width=50%  align=right  class=tab1_col3><b>Duração:</b></td><td width=50%  align=left class=tab1_col3> Horas: " . $row["filmeduracaoh1_name"] . " Minutos: " . $row["filmeduracaomi1_name"] . "\n";
$series11="<tr><td width=50%  align=right  class=tab1_col3><b>Resolução:</b></td><td width=50%  align=left class=tab1_col3> Pixels de largura por: " . $row["filmeresolucao"] . " Pixels de altura: " . $row["filmeresolucalt"] . "\n";

	//gerador de upload


  




  if ($row["category"] == 95 || $row["category"] == 98 || $row["category"] == 97 || $row["category"] == 96 ) 
	
	

		{

			print "$generotor</tr>\n";
		print "$series1</tr>\n";
		print "$series2</tr>\n";
		print "$series3</tr>\n";
		print "$series4</tr>\n";
		print "$series5</tr>\n";
		print "$series6</tr>\n";
		print "$series7</tr>\n";
		print "$series8</tr>\n";
		print "$series9</tr>\n";
		print "$series10</tr>\n";
		print "$series11</tr>\n";
				print "$tamanho</tr>\n";
						 		print "$temposeed</tr>\n";
		print "$user123</tr>\n";
		}
 
 
 $revist1="<tr><td width=50%  align=right  class=tab1_col3><b>Ano de lançamento:</b></td><td width=50%  align=left class=tab1_col3>" . $row["anoteste_name"] . "\n";
$revist2="<tr><td width=50%  align=right  class=tab1_col3><b>Extensão:</b></td><td width=50%  align=left class=tab1_col3>" . $row["revistatensao1_name"] . "\n"; 
$revist3="<tr><td width=50%  align=right  class=tab1_col3><b>Autor:</b></td><td width=50%  align=left class=tab1_col3>" . $row["musicalinkloja"] . "\n"; 
$revist4="<tr><td width=50%  align=right  class=tab1_col3><b>Idioma:</b></td><td width=50%  align=left class=tab1_col3>" . $row["filmeidiomaorigi1_name"] . "\n"; 
 
 if ($row["category"] == 109) 
 { 

 	print "$generotor</tr>\n";
 print "$revist1</tr>\n";
  print "$revist2</tr>\n";
   print "$revist4</tr>\n";
   print "$revist3</tr>\n";
   		print "$tamanho</tr>\n";
				 		print "$temposeed</tr>\n";
   print "$user123</tr>\n";
 }
 
  $fotos1="<tr><td width=50%  align=right  class=tab1_col3><b>Ano de lançamento:</b></td><td width=50%  align=left class=tab1_col3>" . $row["anoteste_name"] . "\n";
$fotos2="<tr><td width=50%  align=right  class=tab1_col3><b>Extensão:</b></td><td width=50%  align=left class=tab1_col3>" . $row["revistatensao1_name"] . "\n"; 
$fotos3="<tr><td width=50%  align=right  class=tab1_col3><b>Autor:</b></td><td width=50%  align=left class=tab1_col3>" . $row["musicalbum"] . "\n"; 
$fotos4="<tr><td width=50%  align=right  class=tab1_col3><b>Idioma:</b></td><td width=50%  align=left class=tab1_col3>" . $row["filmeidiomaorigi1_name"] . "\n"; 
 
 if ($row["category"] == 93) 
 { 

 	print "$generotor</tr>\n";
 print "$fotos1</tr>\n";
  print "$fotos2</tr>\n";
   print "$fotos3</tr>\n";
   print "$fotos4</tr>\n";
   		print "$tamanho</tr>\n";
				 		print "$temposeed</tr>\n";
   print "$user123</tr>\n";
 }
 
$cursapos1="<tr><td width=50%  align=right  class=tab1_col3><b>Ano de lançamento:</b></td><td width=50%  align=left class=tab1_col3>" . $row["anoteste_name"] . "\n";
$cursapos2="<tr><td width=50%  align=right  class=tab1_col3><b>Extensão:</b></td><td width=50%  align=left class=tab1_col3>" . $row["revistatensao1_name"] . "\n"; 
$cursapos3="<tr><td width=50%  align=right  class=tab1_col3><b>Autor:</b></td><td width=50%  align=left class=tab1_col3>" . $row["musicalinkloja"] . "\n"; 
$cursapos4="<tr><td width=50%  align=right  class=tab1_col3><b>Idioma:</b></td><td width=50%  align=left class=tab1_col3>" . $row["filmeidiomaorigi1_name"] . "\n"; 
 
 if ($row["category"] == 9) 
 { 

 	print "$generotor</tr>\n";
 print "$cursapos1</tr>\n";
  print "$cursapos2</tr>\n";
   print "$cursapos3</tr>\n";
   print "$cursapos4</tr>\n";
   		print "$tamanho</tr>\n";
				 		print "$temposeed</tr>\n";
   print "$user123</tr>\n";
 }
 
 $cursvideo1="<tr><td width=50%  align=right  class=tab1_col3><b>Ano de lançamento:</b></td><td width=50%  align=left class=tab1_col3>" . $row["anoteste_name"] . "\n";
$cursvideo3="<tr><td width=50%  align=right  class=tab1_col3><b>Autor:</b></td><td width=50%  align=left class=tab1_col3>" . $row["musicalinkloja"] . "\n"; 
$cursvideo4="<tr><td width=50%  align=right  class=tab1_col3><b>Idioma:</b></td><td width=50%  align=left class=tab1_col3>" . $row["filmeidiomaorigi1_name"] . "\n"; 
 
 if ($row["category"] == 111) 
 { 

 	print "$generotor</tr>\n";
 print "$cursvideo1</tr>\n";
   print "$cursvideo3</tr>\n";
   print "$cursvideo4</tr>\n";
   		print "$tamanho</tr>\n";
				 		print "$temposeed</tr>\n";
   print "$user123</tr>\n";
 }
 $filmeadul1="<tr><td width=50%  align=right  class=tab1_col3><b>Ano de lançamento:</b></td><td width=50%  align=left class=tab1_col3>" . $row["anoteste_name"] . "\n";
$filmeadul2="<tr><td width=50%  align=right  class=tab1_col3><b>Audio:</b></td><td width=50%  align=left class=tab1_col3>" . $row["filmeaudio1_name"] . "\n";
$filmeadul3="<tr><td width=50%  align=right  class=tab1_col3><b>Extensão:</b></td><td width=50%  align=left class=tab1_col3>" . $row["testet_name"] . "\n";
$filmeadul4="<tr><td width=50%  align=right  class=tab1_col3><b>Qualidade:</b></td><td width=50%  align=left class=tab1_col3>" . $row["filmequalidade1_name"] . "\n";
$filmeadul5="<tr><td width=50%  align=right  class=tab1_col3><b>Filme em 3D:</b></td><td width=50%  align=left class=tab1_col3>" . $row["filme3d1_name"] . "\n";
$filmeadul11="<tr><td width=50%  align=right  class=tab1_col3><b>Legenda Embutidas:</b></td><td width=50%  align=left class=tab1_col3>" . $row["legendaid_name"] . "\n";
$filmeadul6="<tr><td width=50%  align=right  class=tab1_col3><b>Codecs de Vídeo:</b></td><td width=50%  align=left class=tab1_col3>" . $row["filmecodecvid1_name"] . "\n";
$filmeadul7="<tr><td width=50%  align=right  class=tab1_col3><b>Codecs de Audio:</b></td><td width=50%  align=left class=tab1_col3>" . $row["filmecodecaud1_name"] . "\n";
$filmeadul8="<tr><td width=50%  align=right  class=tab1_col3><b>Idioma Original:</b></td><td width=50%  align=left class=tab1_col3>" . $row["filmeidiomaorigi1_name"] . "\n";
$filmeadul9="<tr><td width=50%  align=right  class=tab1_col3><b>Duração:</b></td><td width=50%  align=left class=tab1_col3> Horas: " . $row["filmeduracaoh1_name"] . " Minutos: " . $row["filmeduracaomi1_name"] . "\n";
$filmeadul10="<tr><td align=right  class=tab1_col3><b>Resolução:</b></td><td width=50%  align=left class=tab1_col3> Pixels de largura por: " . $row["filmeresolucao"] . " Pixels de altura: " . $row["filmeresolucalt"] . "\n";

	//gerador de upload


  


 if ($row["category"] == 47 ) 
 
	

		{

			print "$generotor</tr>\n";
		print "$filmeadul1</tr>\n";
		print "$filmeadul2</tr>\n";
		print "$filmeadul3</tr>\n";
		print "$filmeadul4</tr>\n";
		print "$filmeadul5</tr>\n";
		print "$filmeadul11</tr>\n";
		print "$filmeadul6</tr>\n";
		print "$filmeadul7</tr>\n";
		print "$filmeadul8</tr>\n";
		print "$filmeadul9</tr>\n";
		print "$filmeadul10</tr>\n";
				print "$tamanho</tr>\n";
						 		print "$temposeed</tr>\n";
		print "$user123</tr>\n";
		}
		
 $filmeadulh1="<tr><td width=50%  align=right  class=tab1_col3><b>Ano de lançamento:</b></td><td width=50%  align=left class=tab1_col3>" . $row["anoteste_name"] . "\n";
$filmeadulh2="<tr><td width=50%  align=right  class=tab1_col3><b>Audio:</b></td><td width=50%  align=left class=tab1_col3>" . $row["filmeaudio1_name"] . "\n";
$filmeadulh3="<tr><td width=50%  align=right  class=tab1_col3><b>Extensão:</b></td width=50%  align=left class=tab1_col3><td>" . $row["testet_name"] . "\n";
$filmeadulh4="<tr><td width=50%  align=right  class=tab1_col3><b>Qualidade:</b></td><td width=50%  align=left class=tab1_col3>" . $row["filmequalidade1_name"] . "\n";
$filmeadulh5="<tr><td width=50%  align=right  class=tab1_col3><b>Filme em 3D:</b></td><td width=50%  align=left class=tab1_col3>" . $row["filme3d1_name"] . "\n";
$filmeadulh11="<tr><td width=50%  align=right  class=tab1_col3><b>Legenda Embutidas:</b></td><td width=50%  align=left class=tab1_col3>" . $row["legendaid_name"] . "\n";
$filmeadulh6="<tr><td width=50%  align=right  class=tab1_col3><b>Codecs de Vídeo:</b></td><td width=50%  align=left class=tab1_col3>" . $row["filmecodecvid1_name"] . "\n";
$filmeadulh7="<tr><td width=50%  align=right  class=tab1_col3><b>Codecs de Audio:</b></td><td width=50%  align=left class=tab1_col3>" . $row["filmecodecaud1_name"] . "\n";
$filmeadulh8="<tr><td width=50%  align=right  class=tab1_col3><b>Idioma Original:</b></td><td width=50%  align=left class=tab1_col3>" . $row["filmeidiomaorigi1_name"] . "\n";
$filmeadulh9="<tr><td width=50%  align=right  class=tab1_col3><b>Duração:</b></td><td width=50%  align=left class=tab1_col3> Horas: " . $row["filmeduracaoh1_name"] . " Minutos: " . $row["filmeduracaomi1_name"] . "\n";
$filmeadulh10="<tr><td width=50%  align=right  class=tab1_col3><b>Resolução:</b></td><td width=50%  align=left class=tab1_col3> Pixels de largura por: " . $row["filmeresolucao"] . " Pixels de altura: " . $row["filmeresolucalt"] . "\n";

	//gerador de upload


  


 if ($row["category"] == 106 ) 
 
	

		{

			print "$generotor</tr>\n";
		print "$filmeadulh1</tr>\n";
		print "$filmeadulh2</tr>\n";
		print "$filmeadulh3</tr>\n";
		print "$filmeadulh4</tr>\n";
		print "$filmeadulh5</tr>\n";
		print "$filmeadulh11</tr>\n";
		print "$filmeadulh6</tr>\n";
		print "$filmeadulh7</tr>\n";
		print "$filmeadulh8</tr>\n";
		print "$filmeadulh9</tr>\n";
		print "$filmeadulh10</tr>\n";
				print "$tamanho</tr>\n";
						 		print "$temposeed</tr>\n";
		print "$user123</tr>\n";
		}		
  $fotosadu1="<tr><td width=50%  align=right  class=tab1_col3><b>Ano de lançamento:</b></td><td width=50%  align=left class=tab1_col3>" . $row["anoteste_name"] . "\n";
$fotosadu2="<tr><td width=50%  align=right  class=tab1_col3><b>Extensão:</b></td><td width=50%  align=left class=tab1_col3>" . $row["revistatensao1_name"] . "\n"; 
$fotosadu3="<tr><td width=50%  align=right  class=tab1_col3><b>Autor:</b></td><td width=50%  align=left class=tab1_col3>" . $row["musicalinkloja"] . "\n"; 
$fotosadu4="<tr><td width=50%  align=right  class=tab1_col3><b>Idioma:</b></td><td width=50%  align=left class=tab1_col3>" . $row["filmeidiomaorigi1_name"] . "\n"; 
 
 if ($row["category"] == 104  || $row["category"] == 113) 
 { 

 	print "$generotor</tr>\n";
 print "$fotosadu1</tr>\n";
  print "$fotosadu2</tr>\n";
   print "$fotosadu3</tr>\n";
   print "$fotosadu4</tr>\n";
   		print "$tamanho</tr>\n";
				 		print "$temposeed</tr>\n";
   print "$user123</tr>\n";
 }	
   $fotosadu1="<tr><td width=50%  align=right  class=tab1_col3><b>Ano de lançamento:</b></td><td width=50%  align=left class=tab1_col3>" . $row["anoteste_name"] . "\n";
$fotosadu2="<tr><td width=50%  align=right  class=tab1_col3><b>Extensão:</b></td><td width=50%  align=left class=tab1_col3>" . $row["revistatensao1_name"] . "\n"; 
$fotosadu3="<tr><td width=50%  align=right  class=tab1_col3><b>Autor:</b></td><td width=50%  align=left class=tab1_col3>" . $row["musicalinkloja"] . "\n"; 
$fotosadu4="<tr><td width=50%  align=right  class=tab1_col3><b>Idioma:</b></td><td width=50%  align=left class=tab1_col3>" . $row["filmeidiomaorigi1_name"] . "\n"; 
 
 if ($row["category"] == 108) 
 { 

 	print "$generotor</tr>\n";

   		print "$tamanho</tr>\n";
				 		print "$temposeed</tr>\n";
   print "$user123</tr>\n";
 }	
 

if ($safe == "no"){
if ($row["owner"] == $CURUSER["id"] ) {
$sql="SELECT moderation.*, users.username from moderation left join users on moderation.uid=users.id WHERE infohash='$id'";
$r=mysql_query($sql);
  $t=mysql_fetch_assoc($r);
  $staffmem = ($t['uid'] >0 ? $t['username'] : "Staff");



}
print(" </td></tr>\n");
}






if ($row["category"] == 23 || $row["category"] == 120 || $row["category"] == 4 || $row["category"] == 24 || $row["category"] == 3 || $row["category"] == 34 || $row["category"] == 35 || $row["category"] == 39 || $row["category"] == 37  || $row["category"] == 42 || $row["category"] == 5 || $row["category"] == 40 || $row["category"] == 7 || $row["category"] == 28 || $row["category"] == 1 || $row["category"] == 25 || $row["category"] == 26 || $row["category"] == 2 || $row["category"] == 27 || $row["category"] == 33 || $row["category"] == 36 || $row["category"] == 50 || $row["category"] == 41 || $row["category"] == 6 || $row["category"] == 117 || $row["category"] == 114 || $row["category"] == 124) 
 {
 print("<tr><td width=100% align=center colspan=2 class=tab1_cab1><?><b><font  class=tab1_cab1 >Sinopse</font></tr>");
 print("<tr><td align=center class=tab1_col3 colspan=2><br>" .  format_comment($row['filmesinopse']) . "</td></tr>\n");

 print("<tr><td width=100% align=center colspan=2 class=tab1_cab1><?><b><font  class=tab1_cab1 >Ficha Técnica</font></tr>");
 print("<tr><td align=center class=tab1_col3 colspan=2><br>" .  format_comment($row['descr']) . "</td></tr>\n");

}
 elseif ($row["category"] == 20 || $row["category"] == 19 || $row["category"] == 18 || $row["category"] == 94 || $row["category"] == 122)
 {
 print("<tr><td width=100% align=center colspan=2 class=tab1_cab1><?><b><font  class=tab1_cab1 >Descrição / Ficha Técnica</font></tr>");
 print("<tr><td align=center class=tab1_col3 colspan=2><br>" .  format_comment($row['filmesinopse']) . "</td></tr>\n");

 print("<tr><td width=100% align=center colspan=2 class=tab1_cab1><?><b><font  class=tab1_cab1 >Configurações Mínimas</font></tr>");
 print("<tr><td align=center class=tab1_col3 colspan=2><br>" .  format_comment($row['descr']) . "</td></tr>\n");

}
 elseif ($row["category"] == 51 || $row["category"] == 57 || $row["category"] == 61 || $row["category"] == 63 || $row["category"] == 64 || $row["category"] == 66 || $row["category"] == 81 || $row["category"] == 52 || $row["category"] == 53 || $row["category"] == 54 || $row["category"] == 55 || $row["category"] == 56 || $row["category"] == 58 || $row["category"] == 59 || $row["category"] == 60 || $row["category"] == 62 || $row["category"] == 65 || $row["category"] == 67 || $row["category"] == 68 || $row["category"] == 69 || $row["category"] == 70 || $row["category"] == 107 || $row["category"] == 71 || $row["category"] == 72 || $row["category"] == 73 || $row["category"] == 74 || $row["category"] == 75 || $row["category"] == 76 || $row["category"] == 77 || $row["category"] == 78 || $row["category"] == 79 || $row["category"] == 80 || $row["category"] == 81 || $row["category"] == 82 || $row["category"] == 83 || $row["category"] == 84 || $row["category"] == 85 || $row["category"] == 86 || $row["category"] == 87 || $row["category"] == 88 || $row["category"] == 89  || $row["category"] == 90 || $row["category"] == 91) 
 { 
 print("<tr><td width=100% align=center colspan=2 class=tab1_cab1><?><b><font  class=tab1_cab1 >Lista de Músicas</font></tr>");
 print("<tr><td align=center class=tab1_col3 colspan=2><br>" .  format_comment($row['descr']) . "</td></tr>\n");
}
/////////jogos
elseif ($row["category"] == 102 || $row["category"] == 105 || $row["category"] == 10 || $row["category"] == 15 || $row["category"] == 11 || $row["category"] == 43 || $row["category"] == 12 || $row["category"] == 44 || $row["category"] == 13 || $row["category"] == 14 || $row["category"] == 16 || $row["category"] == 121 || $row["category"] == 116)
	
{

 print("<tr><td width=100% align=center colspan=2 class=tab1_cab1><?><b><font  class=tab1_cab1 >Descrição / Ficha Técnica</font></tr>");
 print("<tr><td align=center class=tab1_col3 colspan=2><br>" .  format_comment($row['filmesinopse']) . "</td></tr>\n");

 print("<tr><td width=100% align=center colspan=2 class=tab1_cab1><?><b><font  class=tab1_cab1 >Informações adicionais</font></tr>");
 print("<tr><td align=center class=tab1_col3 colspan=2><br>" .  format_comment($row['descr']) . "</td></tr>\n");
}


 elseif ($row["category"] == 112 ) 
 {
 
 
 print("<tr><td width=100% align=center colspan=2 class=tab1_cab1><?><b><font  class=tab1_cab1 >Sinopse</font></tr>");
 print("<tr><td align=center class=tab1_col3 colspan=2><br>" .  format_comment($row['filmesinopse']) . "</td></tr>\n");

 print("<tr><td width=100% align=center colspan=2 class=tab1_cab1><?><b><font  class=tab1_cab1 >Ficha Técnica</font></tr>");
 print("<tr><td align=center class=tab1_col3 colspan=2><br>" .  format_comment($row['descr']) . "</td></tr>\n");
}
  elseif ($row["category"] == 49 || $row["category"] == 101 || $row["category"] == 99 || $row["category"] == 100 || $row["category"] == 103) 
 {
 
 
 
 print("<tr><td width=100% align=center colspan=2 class=tab1_cab1><?><b><font  class=tab1_cab1 >Sinopse</font></tr>");
 print("<tr><td align=center class=tab1_col3 colspan=2><br>" .  format_comment($row['filmesinopse']) . "</td></tr>\n");

 print("<tr><td width=100% align=center colspan=2 class=tab1_cab1><?><b><font  class=tab1_cab1 >Ficha Técnica</font></tr>");
 print("<tr><td align=center class=tab1_col3 colspan=2><br>" .  format_comment($row['descr']) . "</td></tr>\n");
}
 elseif ($row["category"] == 110 ) 
 { 
 print("<tr><td width=100% align=center colspan=2 class=tab1_cab1><?><b><font  class=tab1_cab1 >Sinopse</font></tr>");
 print("<tr><td align=center class=tab1_col3 colspan=2><br>" .  format_comment($row['filmesinopse']) . "</td></tr>\n");

 print("<tr><td width=100% align=center colspan=2 class=tab1_cab1><?><b><font  class=tab1_cab1 >Ficha Técnica</font></tr>");
 print("<tr><td align=center class=tab1_col3 colspan=2><br>" .  format_comment($row['descr']) . "</td></tr>\n");
 }
  elseif ($row["category"] == 95 || $row["category"] == 98 || $row["category"] == 97 || $row["category"] == 96 ) 
 {
 
 print("<tr><td width=100% align=center colspan=2 class=tab1_cab1><?><b><font  class=tab1_cab1 >Sinopse</font></tr>");
 print("<tr><td align=center class=tab1_col3 colspan=2><br>" .  format_comment($row['filmesinopse']) . "</td></tr>\n");

 print("<tr><td width=100% align=center colspan=2 class=tab1_cab1><?><b><font  class=tab1_cab1 >Ficha Técnica</font></tr>");
 print("<tr><td align=center class=tab1_col3 colspan=2><br>" .  format_comment($row['descr']) . "</td></tr>\n");
}
 elseif ($row["category"] == 109 ) 
 {
 print("<tr><td width=100% align=center colspan=2 class=tab1_cab1><?><b><font  class=tab1_cab1 >Ficha Técnica</font></tr>");
 print("<tr><td align=center class=tab1_col3 colspan=2><br>" .  format_comment($row['descr']) . "</td></tr>\n");
}
 elseif ($row["category"] == 93 ) 
 {
 print("<tr><td width=100% align=center colspan=2 class=tab1_cab1><?><b><font  class=tab1_cab1 >Ficha Técnica</font></tr>");
 print("<tr><td align=center class=tab1_col3 colspan=2><br>" .  format_comment($row['descr']) . "</td></tr>\n");
}
 elseif ($row["category"] == 9 ) 
 {

 print("<tr><td width=100% align=center colspan=2 class=tab1_cab1><?><b><font  class=tab1_cab1 >Ficha Técnica</font></tr>");
 print("<tr><td align=center class=tab1_col3 colspan=2><br>" .  format_comment($row['descr']) . "</td></tr>\n");
}
 elseif ($row["category"] == 111 ) 
 {

 print("<tr><td width=100% align=center colspan=2 class=tab1_cab1><?><b><font  class=tab1_cab1 >Ficha Técnica</font></tr>");
 print("<tr><td align=center  class=tab1_col3 colspan=2><br>" .  format_comment($row['descr']) . "</td></tr>\n");
}
elseif ($row["category"] == 47 ) 
 {
 
 
 print("<tr><td width=100% align=center colspan=2 class=tab1_cab1><?><b><font  class=tab1_cab1 >Sinopse</font></tr>");
 print("<tr><td align=center class=tab1_col3 colspan=2><br>" .  format_comment($row['filmesinopse']) . "</td></tr>\n");

 print("<tr><td width=100% align=center colspan=2 class=tab1_cab1><?><b><font  class=tab1_cab1 >Ficha Técnica</font></tr>");
 print("<tr><td align=center class=tab1_col3 colspan=2><br>" .  format_comment($row['descr']) . "</td></tr>\n");
 }
elseif ($row["category"] == 106 ) 
 { 
 print("<tr><td width=100% align=center colspan=2 class=tab1_cab1><?><b><font  class=tab1_cab1 >Sinopse</font></tr>");
 print("<tr><td align=center class=tab1_col3 colspan=2><br>" .  format_comment($row['filmesinopse']) . "</td></tr>\n");

 print("<tr><td width=100% align=center colspan=2 class=tab1_cab1><?><b><font  class=tab1_cab1 >Ficha Técnica</font></tr>");
 print("<tr><td align=center class=tab1_col3 colspan=2><br>" .  format_comment($row['descr']) . "</td></tr>\n");
}
else{
 print("<tr><td width=100% align=center colspan=2 class=tab1_cab1><?><b><font  class=tab1_cab1 >Descrição</font></tr>");
 print("<tr><td align=center class=tab1_col3 colspan=2><br>" .  format_comment($row['descr']) . "</td></tr>\n");
}



if ($row["screens2"] != "" OR $row["screens3"] != "" OR $row["screens3"] != "") {
    if ($row["screens1"] != "")
    $screens1 = "<IMG src=".$row['screens1']." align=center width=400 border=0>";
  if ($row["screens2"] != "")
    $screens2 = "<a href=$row[screens2]  rel='lytebox'[nice_pics] title=" . $shortname . "><IMG src=".$row['screens2']." width=200 align=center height=100 border=1 ><b></b></a>";
if ($row["screens3"] != "")
    $screens3 = "<a href=$row[screens3]  rel='lytebox'[nice_pics] title=" . $shortname . "><IMG src=".$row['screens3']." width=200 align=center height=100 border=1><b></b></a>";
  if ($row["screens4"] != "")
    $screens4 = "<a href=$row[screens4]   rel='lytebox'[nice_pics] title=" . $shortname . "><IMG src=".$row['screens4']." width=200 align=center height=100 border=1><b></b></a>";
  if ($row["screens5"] != "")
    $screens5 = "<a href=$row[screens5]   rel='lytebox'[nice_pics] title=" . $shortname . "><IMG src=".$row['screens5']." width=200 align=center height=100 border=1><b></b></a>";

	

	
	
	
	
	
	
	
	
	 print("<tr><td class='tab1_cab1' colspan='2' align='center' >Imagens</td></tr>\n");
	 print("<tr><td align='center' class='tab1_col3' colspan='2' ><br><br>".$screens2." ".$screens3." <br><br>".$screens4." ".$screens5." &nbsp;&nbsp;&nbsp;<br>Clique na imagem para ampliar<br></td></tr>\n");
	
	
	
	
	
	
	
	
	
	

}



$res12356 = mysql_query("SELECT * FROM users WHERE id=".$row["owner"].""); 
		$arr12356 = mysql_fetch_assoc($res12356);	
		
$exe_grupo1 = mysql_query("SELECT * FROM teams WHERE id={$arr12356["team"]}");	
	
		if($arr12356["team"]==0)
			{
	$grupo1 ="";
	}else{	
		$arr_grupo1 = mysql_fetch_assoc($exe_grupo1);
	$grupo1 = "<a href='grupos_lancamentos.php?id={$arr_grupo1["id"]}'><img src='{$arr_grupo1["image3"]}' title='{$arr_grupo1["name"]}' /></a>";		}
	if (!$arr_grupo1["image3"]){
	}
	else{
	        ?>

			<tr>
	
			
	<td align="center"  class="tab1_col3" colspan="2" ><?php echo $grupo1 ;?></td>
		</tr>

		<?php

}

           $thanksl = explode(",", $row['thanks']);
if ($CURUSER['username'] == $row['username'] || !$CURUSER) $leftthanks = true;
foreach ($thanksl as $u) {
    $thanks .= ($i>0)?", $u":$u;
    if ($u == $CURUSER['username']) $leftthanks = true;
    $i++;
}

if (!$thanks){
echo "";
}else{
      ?>

	  	<tr>
	     <tr>
       <td class="tab1_cab1" colspan="2" align="center"><b>Agradecimentos</b></td>
   </tr>
	  
	  
	  <td align="center" class="tab1_col3"  colspan="2"> <?php echo $thanks ;?>
	</td>
	  
</tr>
		
<?php
}                                        



$tres = SQL_Query_exec("SELECT * FROM `announce` WHERE `torrent` = $id");


echo "<table align='center' cellpadding='0' cellspacing='0' class='table_table' border='1' width='100%'>";



echo "</table></div>";

//DISPLAY NFO BLOCK
function my_nfo_translate($nfo){
        $trans = array(
        "\x80" => "&#199;", "\x81" => "&#252;", "\x82" => "&#233;", "\x83" => "&#226;", "\x84" => "&#228;", "\x85" => "&#224;", "\x86" => "&#229;", "\x87" => "&#231;", "\x88" => "&#234;", "\x89" => "&#235;", "\x8a" => "&#232;", "\x8b" => "&#239;", "\x8c" => "&#238;", "\x8d" => "&#236;", "\x8e" => "&#196;", "\x8f" => "&#197;", "\x90" => "&#201;",
        "\x91" => "&#230;", "\x92" => "&#198;", "\x93" => "&#244;", "\x94" => "&#246;", "\x95" => "&#242;", "\x96" => "&#251;", "\x97" => "&#249;", "\x98" => "&#255;", "\x99" => "&#214;", "\x9a" => "&#220;", "\x9b" => "&#162;", "\x9c" => "&#163;", "\x9d" => "&#165;", "\x9e" => "&#8359;", "\x9f" => "&#402;", "\xa0" => "&#225;", "\xa1" => "&#237;",
        "\xa2" => "&#243;", "\xa3" => "&#250;", "\xa4" => "&#241;", "\xa5" => "&#209;", "\xa6" => "&#170;", "\xa7" => "&#186;", "\xa8" => "&#191;", "\xa9" => "&#8976;", "\xaa" => "&#172;", "\xab" => "&#189;", "\xac" => "&#188;", "\xad" => "&#161;", "\xae" => "&#171;", "\xaf" => "&#187;", "\xb0" => "&#9617;", "\xb1" => "&#9618;", "\xb2" => "&#9619;",
        "\xb3" => "&#9474;", "\xb4" => "&#9508;", "\xb5" => "&#9569;", "\xb6" => "&#9570;", "\xb7" => "&#9558;", "\xb8" => "&#9557;", "\xb9" => "&#9571;", "\xba" => "&#9553;", "\xbb" => "&#9559;", "\xbc" => "&#9565;", "\xbd" => "&#9564;", "\xbe" => "&#9563;", "\xbf" => "&#9488;", "\xc0" => "&#9492;", "\xc1" => "&#9524;", "\xc2" => "&#9516;", "\xc3" => "&#9500;",
        "\xc4" => "&#9472;", "\xc5" => "&#9532;", "\xc6" => "&#9566;", "\xc7" => "&#9567;", "\xc8" => "&#9562;", "\xc9" => "&#9556;", "\xca" => "&#9577;", "\xcb" => "&#9574;", "\xcc" => "&#9568;", "\xcd" => "&#9552;", "\xce" => "&#9580;", "\xcf" => "&#9575;", "\xd0" => "&#9576;", "\xd1" => "&#9572;", "\xd2" => "&#9573;", "\xd3" => "&#9561;", "\xd4" => "&#9560;",
        "\xd5" => "&#9554;", "\xd6" => "&#9555;", "\xd7" => "&#9579;", "\xd8" => "&#9578;", "\xd9" => "&#9496;", "\xda" => "&#9484;", "\xdb" => "&#9608;", "\xdc" => "&#9604;", "\xdd" => "&#9612;", "\xde" => "&#9616;", "\xdf" => "&#9600;", "\xe0" => "&#945;", "\xe1" => "&#223;", "\xe2" => "&#915;", "\xe3" => "&#960;", "\xe4" => "&#931;", "\xe5" => "&#963;",
        "\xe6" => "&#181;", "\xe7" => "&#964;", "\xe8" => "&#934;", "\xe9" => "&#920;", "\xea" => "&#937;", "\xeb" => "&#948;", "\xec" => "&#8734;", "\xed" => "&#966;", "\xee" => "&#949;", "\xef" => "&#8745;", "\xf0" => "&#8801;", "\xf1" => "&#177;", "\xf2" => "&#8805;", "\xf3" => "&#8804;", "\xf4" => "&#8992;", "\xf5" => "&#8993;", "\xf6" => "&#247;",
        "\xf7" => "&#8776;", "\xf8" => "&#176;", "\xf9" => "&#8729;", "\xfa" => "&#183;", "\xfb" => "&#8730;", "\xfc" => "&#8319;", "\xfd" => "&#178;", "\xfe" => "&#9632;", "\xff" => "&#160;",
        );
        $trans2 = array("\xe4" => "&auml;",        "\xF6" => "&ouml;",        "\xFC" => "&uuml;",        "\xC4" => "&Auml;",        "\xD6" => "&Ouml;",        "\xDC" => "&Uuml;",        "\xDF" => "&szlig;");
        $all_chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $last_was_ascii = False;
        $tmp = "";
        $nfo = $nfo . "\00";
        for ($i = 0; $i < (strlen($nfo) - 1); $i++)
        {
                $char = $nfo[$i];
                if (isset($trans2[$char]) and ($last_was_ascii or strpos($all_chars, ($nfo[$i + 1]))))
                {
                        $tmp = $tmp . $trans2[$char];
                        $last_was_ascii = True;
                }
                else
                {
                        if (isset($trans[$char]))
                        {
                                $tmp = $tmp . $trans[$char];
                        }
                        else
                        {
                            $tmp = $tmp . $char;
                        }
                        $last_was_ascii = strpos($all_chars, $char);
                }
        }
        return $tmp;
}
//-----------------------------------------------

//DISPLAY NFO BLOCK
if($row["nfo"]== "yes"){
	$nfofilelocation = "$nfo_dir/$row[id].nfo";
	$filegetcontents = file_get_contents($nfofilelocation);
	$nfo = htmlspecialchars($filegetcontents);
		if ($nfo) {	
			$nfo = my_nfo_translate($nfo);

			print("<textarea class='nfo' style='width:98%;height:100%;' rows='20' cols='20' readonly='readonly'>".stripslashes($nfo)."</textarea>");
        }else{
            print(T_("ERROR")." reading .nfo file!");
        }
}
end_framec();
if ($row["safe"] == "yes") {

begin_framec(T_("COMMENTS"));
	//echo "<p align=center><a class=index href=torrents-comment.php?id=$id>" .T_("ADDCOMMENT"). "</a></p>\n";
$dossier = $CURUSER['bbcode'];
		      print("<table align=center cellpadding='3' cellspacing='0' class='download' width='100%' border='0'><tr><td align='center' colspan='2' >\n");
	   echo "<div id='commentsdel'></div>";
	           if ($CURUSER) {
                echo "<BR><iframe name='commentframe' id='commentframe' src='comments_ajax.php?id=$id&do=postcomment' frameborder='0' width='100%' height='460'/></iframe>";
        	      print("</td> </tr></table>\n");
		}
			
        echo "<div id='commentsdiv' name='comments'><center><img src='images/loading.gif' border='0'><BR>Loading...</center><script language='JavaScript'>loadComments(-20)</script></div>";
 					
	

	
	end_framec();
}

stdfoot();
?>