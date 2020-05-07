<?php
############################################################
#######                                             ########
#######                                             ########
#######           www.brshares.com 2.0              ########
#######                                             ########
#######                                             ########
############################################################
require_once("backend/functions.php");
require_once("backend/BDecode.php");
dbconn();
loggedinonly();
$id = (int) $_GET["id"];

if($CURUSER["level"]=="Administrador" ||  $CURUSER["level"]=="Sysop" || $CURUSER["level"]=="Moderador" || $CURUSER["level"]=="S.Moderador" || $CURUSER["level"]=="Liberador"){
if (!is_valid_id($id))
	show_error_msg("ERROR", T_("THATS_NOT_A_VALID_ID"), 1);


$res = SQL_Query_exec("SELECT torrents.seeders, torrents.markedby, torrents.filmeresolucalt, torrents.musicalinkloja, torrents.musicalbum, torrents.musicalautor, torrents.filmeresolucao, torrents.markdate, torrents.leechers, torrents.filename, torrents.name, torrents.screens1, torrents.screens2, torrents.screens3, torrents.screens4, torrents.screens5, torrents.owner, torrents.save_as, torrents.descr, torrents.filmesinopse, torrents.size, torrents.added, torrents.views, torrents.hits, torrents.times_completed, torrents.id, torrents.type,  torrents.numfiles, torrents.safe,   torrents.category,  categories.name AS cat_name, categories.image AS cat_pic, torrentlang.name AS lang_name, torrentlang.image AS lang_image, filmeano.name AS anoteste_name, filmeextensao.name AS testet_name, filmeaudio.name AS filmeaudio1_name, filmequalidade.name AS filmequalidade1_name,  filme3d.name AS filme3d1_name,  legenda.name AS legendaid_name, filmecodecvid.name AS filmecodecvid1_name,  filmecodecaud.name AS filmecodecaud1_name,  filmeduracaoh.name AS filmeduracaoh1_name,  filmeduracaomi.name AS filmeduracaomi1_name,  filmeidiomaorigi.name AS filmeidiomaorigi1_name, aplicrack.name AS aplicrack1_name, apliformarq.name AS apliformarq1_name, musicaqualidade.name AS musicaqualidade1_name, musicatensao.name AS musicatensao1_name, jogosgenero.name AS jogosgenero1_name, jogosformato.name AS jogosformato1_name, jogosmultiplay.name AS jogosmultiplay1_name, revistatensao.name AS revistatensao1_name, categories.parent_cat as cat_parent, users.username, users.privacy FROM torrents LEFT JOIN categories ON torrents.category = categories.id LEFT JOIN torrentlang ON torrents.torrentlang = torrentlang.id LEFT JOIN filmeano ON torrents.filmeano = filmeano.id LEFT JOIN filmeextensao ON torrents.filmeextensao = filmeextensao.id LEFT JOIN filmeaudio ON torrents.filmeaudio = filmeaudio.id LEFT JOIN filmequalidade ON torrents.filmequalidade = filmequalidade.id LEFT JOIN filme3d ON torrents.filme3d = filme3d.id  LEFT JOIN legenda ON torrents.legenda = legenda.id LEFT JOIN  filmecodecvid ON torrents. filmecodecvid = filmecodecvid.id LEFT JOIN   filmecodecaud ON torrents.  filmecodecaud =  filmecodecaud.id LEFT JOIN  filmeduracaoh ON torrents.filmeduracaoh = filmeduracaoh.id LEFT JOIN filmeduracaomi ON torrents.filmeduracaomi = filmeduracaomi.id LEFT JOIN  filmeidiomaorigi ON torrents.filmeidiomaorigi = filmeidiomaorigi.id LEFT JOIN  aplicrack ON torrents.aplicrack = aplicrack.id LEFT JOIN  apliformarq ON torrents.apliformarq = apliformarq.id LEFT JOIN  musicaqualidade ON torrents.musicaqualidade = musicaqualidade.id LEFT JOIN musicatensao ON torrents.musicatensao = musicatensao.id  LEFT JOIN jogosgenero ON torrents.jogosgenero = jogosgenero.id LEFT JOIN jogosformato ON torrents.jogosformato = jogosformato.id LEFT JOIN jogosmultiplay ON torrents.jogosmultiplay = jogosmultiplay.id   LEFT JOIN revistatensao ON torrents.revistatensao = revistatensao.id LEFT JOIN users ON torrents.owner = users.id WHERE torrents.id = $id AND torrents.safe = 'no' ") or die(mysql_error());
$row = mysql_fetch_assoc($res);
if (!$row)
	show_error_msg("ERROR", 'Torrent não encontrado.', 1);
if ($row["safe"] == "yes") {
	show_error_msg("ERROR", "O torrent já foi aprovado", 1);	
	}





$taketorrent =  $_POST["torrent"];
$editapostnome = $_POST["torrentnome"];
if ($editapostnome !=""){


	$chgpasswd = $_POST['chgpasswd']=='yes' ? true : false;
	if ($chgpasswd) {
		
	
			SQL_Query_exec("UPDATE torrents SET name='$editapostnome' WHERE id=$id");
 show_error_msg("Gerenciar moderação", "<center>O torrent: " . $arr123mod['name'] . ", <br> <p>Foi editado com sucesso</p><br><a href='modtorrengeren.php?id=$id'>Voltar</a></center>", 1);

die();
		
	}




	
}






$moderamorivos =  $_POST["moderamotivos"];
$moderamorivo =  $_POST["motivos"];
$moderamotivosid = $_POST["moderamotivosid"];
if ($moderamotivosid !=""){


	$chgpasswd = $_POST['moderarok']=='yes' ? true : false;
	if ($chgpasswd) {
		
	SQL_Query_exec("UPDATE moderation SET moderar='yes'   WHERE infohash = $id") or exit(mysql_error());

		
	}

SQL_Query_exec("INSERT INTO messages (poster, sender, receiver, msg, added,subject) VALUES('0','0', " . $row['owner'] . ", " .sqlesc($moderamorivo) . ", '" . get_date_time() . "','Torrent moderação!')") or die (mysql_error());

SQL_Query_exec("UPDATE moderation SET verifica='no', pendete='yes',  com = ".sqlesc($moderamorivos)."   WHERE infohash = $id") or exit(mysql_error());	

show_error_msg("Gerenciar moderação", "<center>O torrent: " . $row['name'] . ", <br> <p>Foi moderado com sucesso</p><br><a href='modtorrengeren.php?id=$id'>Voltar</a></center>", 1);

die();	
}











$takereason =  $_POST["delreason"];
$deleteidtorr = $_POST["deleteid"];
  $torrentname = $_POST["torrentname"];
if ($deleteidtorr !=""){

     if (!is_valid_id($deleteidtorr))
        show_error_msg(T_("FAILED"), T_("INVALID_TORRENT_ID"), 1);

		   deletetorrent($deleteidtorr);
		   
		SQL_Query_exec("INSERT INTO messages (sender, receiver, added, subject, msg, unread, location) VALUES(0, ".$row['owner'].", '".get_date_time()."', 'Seu torrent \'$torrentname\' foi excluído por ".$CURUSER['username']."', ".sqlesc("'$torrentname' foi excluído por ".$CURUSER['username']."\n\nMotivo: $takereason").", 'yes', 'in')");
	write_loguser("Torrents-deletados","#FF0000","O torrent [url=http://www.brshares.com/torrents-details.php?id=".$deleteidtorr."]".$torrentname."[/url] foi deletado por [url=http://www.brshares.com/account-details.php?id=".$CURUSER["id"]."]".$CURUSER["username"]."[/url] Razão: $takereason\n");
		
	
 show_error_msg("Gerenciar moderação", "<center>O torrent: " . $arr123mod['name'] . ", <br> <p>Foi apagado com sucesso</p><br><a href='modtorrengeren.php?id=$id'>Voltar</a></center>", 1);

die();
	
}





$user_res = mysql_query("SELECT users.id, users.username, moderation.uid, moderation.infohash  FROM users  LEFT JOIN moderation ON users.id = moderation.uid WHERE moderation.infohash = '$id'");     
 $row_res = mysql_fetch_array($user_res);  
     

if(mysql_num_rows($user_res)==0){
	$username = "Ninguem";
	if ($row["owner"] !== $CURUSER["id"]) {
	$ts="INSERT INTO `moderation` (`infohash`, `mod`, `addmod`, `uid`) VALUES ('$id', '$x', '".get_date_time()." ','".$CURUSER['id']."')";
	SQL_Query_exec("UPDATE torrents SET upupload='yes'  WHERE id = $id AND safe='no'") or exit(mysql_error());	

@mysql_query($ts);	
}
}
else{

	$username = "<a href='account-details.php?id=" . $row_res["id"] . "'>" . $row_res["username"] . "</a>";

}



$id1 = 1;

$id2 = 2;
 
$id3 = 3;

$id4 = 4;

$id5 = 5;

$shortname = "<a href='torrents-details.php?id=".$row["id"]."'>".$row["name"]."</a>";
$torrenteditor = "Clique aqui para <a href='torrents-edit.php?id=".$row["id"]."'><b >Editar</b></a> o torrent"; 






stdhead("Gerenciar moderação");

begin_framec("Gerenciar moderação");
		if ($news_flag < 0) {
				$disp = "block";
				$pic = "minus";
			} else {
				$disp = "none";
				$pic = "plus";
			}
echo "<br>";	

print "<center><font size=5 >Moderador responsável: <br><B>".$username."</font><B></center>";	
echo "<br><br>";	
print "<center>Torrent: <B>".$shortname."<B></center>";		
echo "<center>";

echo "</center><hr/><br />";


	print("<br /><a href=\"javascript: klappe_modmodera1('a".$id1."')\"><img border=\"0\" src=\"".$site_config["SITEURL"]."/images/$pic.gif\" id=\"pica".$id1."\" alt=\"Show/Hide\" />");
				print("&nbsp;<b> Possíveis Torrents Duplicados </b></a><b> ");
	print("<div id=\"ka".$id1."\" style=\"display: $disp;\">");
				
				echo"teste";
				
	print(" <br /><br /><p align='right' ></p><BR><p align='right'></p></div><br />  ");

	print("<br /><a href=\"javascript: klappe_modmodera2('a".$id2."')\"><img border=\"0\" src=\"".$site_config["SITEURL"]."/images/$pic.gif\" id=\"pica".$id2."\" alt=\"Show/Hide\" />");
				print("&nbsp;<b> Detalhes do Torrent </b></a><b> ");
/////////dentro
				print("<div id=\"ka".$id2."\" style=\"display: $disp;\">");
			///////////
			
			
echo "<table class='tab1' cellpadding='0' cellspacing='1' align='center'>";




//////////////////

$filme1="<tr><td width=50%  align=right  class=tab1_col3  border=0 ><b>Ano de lançamento:</b></td><td width=50%  align=left class=tab1_col3  border=0>" . $row["anoteste_name"] . "</tr>\n";
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
$generotor="<tr><td width=50%  align=right  class=tab1_col3><b>Categoria:</b></td><td width=50%  align=left class=tab1_col3> " . $row["cat_parent"] . " > " . $row["cat_name"] . "\n";


 if ($row["category"] == 23 || $row["category"] == 120 || $row["category"] == 4 || $row["category"] == 24 || $row["category"] == 39 || $row["category"] == 3 || $row["category"] == 34 || $row["category"] == 35 || $row["category"] == 37 || $row["category"] == 42 || $row["category"] == 5 || $row["category"] == 40 || $row["category"] == 7 || $row["category"] == 28 || $row["category"] == 1 || $row["category"] == 25 || $row["category"] == 26 || $row["category"] == 2 || $row["category"] == 27 || $row["category"] == 33 || $row["category"] == 36 || $row["category"] == 50 || $row["category"] == 41 || $row["category"] == 6 || $row["category"] == 117 || $row["category"] == 114) 
 
	

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
$aplicativo5="<tr><td width=50%  align=right  class=tab1_col3><b>Versão:</b></td><td width=50%  align=left class=tab1_col3>" . $row["filmeresolucao"] . "\n";  
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

if ($row["category"] == 102 || $row["category"] == 105 || $row["category"] == 10 || $row["category"] == 15 || $row["category"] == 11 || $row["category"] == 43 || $row["category"] == 12 || $row["category"] == 44 || $row["category"] == 13 || $row["category"] == 14 || $row["category"] == 16 )
	 if ($row["category"] == 112 )   if ($row["category"] == 49 || $row["category"] == 101 || $row["category"] == 99 || $row["category"] == 100 || $row["category"] == 103)   if ($row["category"] == 110 )  if ($row["category"] == 111)  if ($row["category"] == 47 ) 

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
 
 if ($row["category"] == 104) 
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
 
	
	echo "</table>";		
			////////////
echo "<table  cellpadding='0' cellspacing='0' border='0' width='100%'>";

 print(" <TR><td width=100%  align=right colspan=2 class=tab1_cab1><center> Capa</center></td></tr>");
 if ($row["screens1"] != "" OR $row["screens2"] != "" OR $row["screens3"] != "" OR $row["screens3"] != "" ) {
   if ($row["screens1"] != "")
    $screens1 = "<IMG src=".$row['screens1']." align=center width=350 height=500 border=7>";
  print("<CENTER><TR><td width=100%  align=center  class=tab1_col3>". $screens1 . "&nbsp&nbsp</td></tr></CENTER>");
  }

if ($row["category"] == 23 || $row["category"] == 120 || $row["category"] == 4 || $row["category"] == 24 || $row["category"] == 3 || $row["category"] == 34 || $row["category"] == 35 || $row["category"] == 39 || $row["category"] == 37  || $row["category"] == 42 || $row["category"] == 5 || $row["category"] == 40 || $row["category"] == 7 || $row["category"] == 28 || $row["category"] == 1 || $row["category"] == 25 || $row["category"] == 26 || $row["category"] == 2 || $row["category"] == 27 || $row["category"] == 33 || $row["category"] == 36 || $row["category"] == 50 || $row["category"] == 41 || $row["category"] == 6 || $row["category"] == 117 || $row["category"] == 114 ) 
 {
 print("<tr><td width=100% align=center colspan=2 class=tab1_cab1><?><b><font  class=tab1_cab1 >Sinopse</font></tr>");
 print("<tr><td align=center colspan=2 class=tab1_col3><br>" .  format_comment($row['filmesinopse']) . "</td></tr>\n");

 print("<tr><td width=100% align=center colspan=2 class=tab1_cab1><?><b><font  class=tab1_cab1 >Ficha Técnica</font></tr>");
 print("<tr><td align=center colspan=2 class=tab1_col3><br>" .  format_comment($row['descr']) . "</td></tr>\n");

}
 elseif ($row["category"] == 20 || $row["category"] == 19 || $row["category"] == 18 || $row["category"] == 94 || $row["category"] == 122)
 {
 print("<tr><td width=100% align=center colspan=2 class=tab1_cab1><?><b><font  class=tab1_cab1 >Descrição / Ficha Técnica</font></tr>");
 print("<tr><td align=center colspan=2 class=tab1_col3><br>" .  format_comment($row['filmesinopse']) . "</td></tr>\n");

 print("<tr><td width=100% align=center colspan=2 class=tab1_cab1><?><b><font  class=tab1_cab1 >Configurações Mínimas</font></tr>");
 print("<tr><td align=center colspan=2 class=tab1_col3><br>" .  format_comment($row['descr']) . "</td></tr>\n");

}
 elseif ($row["category"] == 51 || $row["category"] == 57 || $row["category"] == 61 || $row["category"] == 63 || $row["category"] == 64 || $row["category"] == 66 || $row["category"] == 81 || $row["category"] == 52 || $row["category"] == 53 || $row["category"] == 54 || $row["category"] == 55 || $row["category"] == 56 || $row["category"] == 58 || $row["category"] == 59 || $row["category"] == 60 || $row["category"] == 62 || $row["category"] == 65 || $row["category"] == 67 || $row["category"] == 68 || $row["category"] == 69 || $row["category"] == 70 || $row["category"] == 107 || $row["category"] == 71 || $row["category"] == 72 || $row["category"] == 73 || $row["category"] == 74 || $row["category"] == 75 || $row["category"] == 76 || $row["category"] == 77 || $row["category"] == 78 || $row["category"] == 79 || $row["category"] == 80 || $row["category"] == 81 || $row["category"] == 82 || $row["category"] == 83 || $row["category"] == 84 || $row["category"] == 85 || $row["category"] == 86 || $row["category"] == 87 || $row["category"] == 88 || $row["category"] == 89  || $row["category"] == 90 || $row["category"] == 91) 
 { 
 print("<tr><td width=100% align=center colspan=2 class=tab1_cab1><?><b><font  class=tab1_cab1 >Lista de Músicas</font></tr>");
 print("<tr><td align=center colspan=2 class=tab1_col3><br>" .  format_comment($row['descr']) . "</td></tr>\n");
}
/////////jogos
elseif ($row["category"] == 102 || $row["category"] == 105 || $row["category"] == 10 || $row["category"] == 15 || $row["category"] == 11 || $row["category"] == 43 || $row["category"] == 12 || $row["category"] == 44 || $row["category"] == 13 || $row["category"] == 14 || $row["category"] == 16)
	
{

 print("<tr><td width=100% align=center colspan=2 class=tab1_cab1><?><b><font  class=tab1_cab1 >Descrição / Ficha Técnica</font></tr>");
 print("<tr><td align=center colspan=2 class=tab1_col3><br>" .  format_comment($row['filmesinopse']) . "</td></tr>\n");

 print("<tr><td width=100% align=center colspan=2 class=tab1_cab1><?><b><font  class=tab1_cab1 >Informações adicionais</font></tr>");
 print("<tr><td align=center colspan=2 class=tab1_col3><br>" .  format_comment($row['descr']) . "</td></tr>\n");
}


 elseif ($row["category"] == 112 ) 
 {
 
 
 print("<tr><td width=100% align=center colspan=2 class=tab1_cab1><?><b><font  class=tab1_cab1 >Sinopse</font></tr>");
 print("<tr><td align=center colspan=2 class=tab1_col3><br>" .  format_comment($row['filmesinopse']) . "</td></tr>\n");

 print("<tr><td width=100% align=center colspan=2 class=tab1_cab1><?><b><font  class=tab1_cab1 >Ficha Técnica</font></tr>");
 print("<tr><td align=center colspan=2 class=tab1_col3><br>" .  format_comment($row['descr']) . "</td></tr>\n");
}
  elseif ($row["category"] == 49 || $row["category"] == 101 || $row["category"] == 99 || $row["category"] == 100 || $row["category"] == 103) 
 {
 
 
 
 print("<tr><td width=100% align=center colspan=2 class=tab1_cab1><?><b><font  class=tab1_cab1 >Sinopse</font></tr>");
 print("<tr><td align=center colspan=2 class=tab1_col3><br>" .  format_comment($row['filmesinopse']) . "</td></tr>\n");

 print("<tr><td width=100% align=center colspan=2 class=tab1_cab1><?><b><font  class=tab1_cab1 >Ficha Técnica</font></tr>");
 print("<tr><td align=center colspan=2 class=tab1_col3><br>" .  format_comment($row['descr']) . "</td></tr>\n");
}
 elseif ($row["category"] == 110 ) 
 { 
 print("<tr><td width=100% align=center colspan=2 class=tab1_cab1><?><b><font  class=tab1_cab1 >Sinopse</font></tr>");
 print("<tr><td align=center colspan=2 class=tab1_col3><br>" .  format_comment($row['filmesinopse']) . "</td></tr>\n");

 print("<tr><td width=100% align=center colspan=2 class=tab1_cab1><?><b><font  class=tab1_cab1 >Ficha Técnica</font></tr>");
 print("<tr><td align=center colspan=2 class=tab1_col3><br>" .  format_comment($row['descr']) . "</td></tr>\n");
 }
  elseif ($row["category"] == 95 || $row["category"] == 98 || $row["category"] == 97 || $row["category"] == 96 ) 
 {
 
 print("<tr><td width=100% align=center colspan=2 class=tab1_cab1><?><b><font  class=tab1_cab1 >Sinopse</font></tr>");
 print("<tr><td align=center colspan=2 class=tab1_col3><br>" .  format_comment($row['filmesinopse']) . "</td></tr>\n");

 print("<tr><td width=100% align=center colspan=2 class=tab1_cab1><?><b><font  class=tab1_cab1 >Ficha Técnica</font></tr>");
 print("<tr><td align=center colspan=2 class=tab1_col3><br>" .  format_comment($row['descr']) . "</td></tr>\n");
}
 elseif ($row["category"] == 109 ) 
 {
 print("<tr><td width=100% align=center colspan=2 class=tab1_cab1><?><b><font  class=tab1_cab1 >Ficha Técnica</font></tr>");
 print("<tr><td align=center colspan=2 class=tab1_col3><br>" .  format_comment($row['descr']) . "</td></tr>\n");
}
 elseif ($row["category"] == 93 ) 
 {
 print("<tr><td width=100% align=center colspan=2 class=tab1_cab1><?><b><font  class=tab1_cab1 >Ficha Técnica</font></tr>");
 print("<tr><td align=center colspan=2 class=tab1_col3><br>" .  format_comment($row['descr']) . "</td></tr>\n");
}
 elseif ($row["category"] == 9 ) 
 {

 print("<tr><td width=100% align=center colspan=2 class=tab1_cab1><?><b><font  class=tab1_cab1 >Ficha Técnica</font></tr>");
 print("<tr><td align=center colspan=2 class=tab1_col3><br>" .  format_comment($row['descr']) . "</td></tr>\n");
}
 elseif ($row["category"] == 111 ) 
 {

 print("<tr><td width=100% align=center colspan=2 class=tab1_cab1><?><b><font  class=tab1_cab1 >Ficha Técnica</font></tr>");
 print("<tr><td align=center colspan=2 class=tab1_col3><br>" .  format_comment($row['descr']) . "</td></tr>\n");
}
elseif ($row["category"] == 47 ) 
 {
 
 
 print("<tr><td width=100% align=center colspan=2 class=tab1_cab1><?><b><font  class=tab1_cab1 >Sinopse</font></tr>");
 print("<tr><td align=center colspan=2 class=tab1_col3><br>" .  format_comment($row['filmesinopse']) . "</td></tr>\n");

 print("<tr><td width=100% align=center colspan=2 class=tab1_cab1><?><b><font  class=tab1_cab1 >Ficha Técnica</font></tr>");
 print("<tr><td align=center colspan=2 class=tab1_col3><br>" .  format_comment($row['descr']) . "</td></tr>\n");
 }
elseif ($row["category"] == 106 ) 
 { 
 print("<tr><td width=100% align=center colspan=2 class=tab1_cab1><?><b><font  class=tab1_cab1 >Sinopse</font></tr>");
 print("<tr><td align=center colspan=2 class=tab1_col3><br>" .  format_comment($row['filmesinopse']) . "</td></tr>\n");

 print("<tr><td width=100% align=center colspan=2 class=tab1_cab1><?><b><font  class=tab1_cab1 >Ficha Técnica</font></tr>");
 print("<tr><td align=center colspan=2 class=tab1_col3><br>" .  format_comment($row['descr']) . "</td></tr>\n");
}
else{
 print("<tr><td width=100% align=center colspan=2 class=tab1_cab1><?><b><font  class=tab1_cab1 >Descrição</font></tr>");
 print("<tr><td align=center colspan=2 class=tab1_col3><br>" .  format_comment($row['descr']) . "</td></tr>\n");
}

$char1 = 100; //cut length
$shortname = CutName(htmlspecialchars($row["name"]), $char1);
if ($row["screens2"] != "" OR $row["screens3"] != "" OR $row["screens3"] != "") {
    if ($row["screens1"] != "")
    $screens1 = "<IMG src=".$row['screens1']." align=center width=400 border=0>";
  if ($row["screens2"] != "")
    $screens2 = "<a href=$row[screens2]  rel='lytebox'[nice_pics] title=" . $shortname . "><IMG src=".$row['screens2']." width=200 align=center height=100 border=1 ><b></b></a><---1";
if ($row["screens3"] != "")
    $screens3 = "<a href=$row[screens3]  rel='lytebox'[nice_pics] title=" . $shortname . "><IMG src=".$row['screens3']." width=200 align=center height=100 border=1><b></b></a><---2";
  if ($row["screens4"] != "")
    $screens4 = "<a href=$row[screens4]   rel='lytebox'[nice_pics] title=" . $shortname . "><IMG src=".$row['screens4']." width=200 align=center height=100 border=1><b></b></a><---3";
  if ($row["screens5"] != "")
    $screens5 = "<a href=$row[screens5]   rel='lytebox'[nice_pics] title=" . $shortname . "><IMG src=".$row['screens5']." width=200 align=center height=100 border=1><b></b></a><---4 ";
      ?>

	  	<tr>
	     <tr>
       <td width="100%" align="center" colspan="2" class="tab1_cab1"><b>Imagens</b></td>


   </tr>

	  
	  <td align="center" colspan="2" class="tab1_col3" ><br><br> <?php echo $screens2 ;?>
	 <?php echo $screens3 ;?><BR><BR> &nbsp;&nbsp;&nbsp; <?php echo $screens4 ;?>
	<?php echo $screens5 ;?>&nbsp;&nbsp;&nbsp;<br>Clique na imagem para ampliar<br></td>
	  
</tr>
		
<?php
}



echo "</table>";


 if ($row["category"] == 23 || $row["category"] == 120 || $row["category"] == 4 || $row["category"] == 24 || $row["category"] == 39 || $row["category"] == 3 || $row["category"] == 34 || $row["category"] == 35 || $row["category"] == 37 || $row["category"] == 42 || $row["category"] == 5 || $row["category"] == 40 || $row["category"] == 7 || $row["category"] == 28 || $row["category"] == 1 || $row["category"] == 25 || $row["category"] == 26 || $row["category"] == 2 || $row["category"] == 27 )
 {
echo "<table  cellpadding='0' cellspacing='0' border='1' width='100%'>";




print "<tr><td width=20%  align=center  class=tab1_cab1><b>Screem:</b></td><td width=20%  align=center  class=tab1_cab1><b>Largura:</b></td><td width=20%  align=center  class=tab1_cab1><b>Altura:</b></td><td width=40%  align=center  class=tab1_cab1><b>Link Rápido:</b></td> </tr>\n";

   if ($row["screens2"] != ""){
     $size2 = getimagesize("".$row['screens2']."");
	if ($size2[0] ==  $row["filmeresolucao"] ){
$screemveri02 = " ---> <font color=#006600>Verdadeira</font>";
}else{
$screemveri02 = " ---> <font color=red>Falsa</font>";
}
	if ($size2[1] ==  $row["filmeresolucalt"] ){
$screemveri12 = " ---> <font color=#006600>Verdadeira</font>";
}else{
$screemveri12 = " ---> <font color=red>Falsa</font>";
}
$screens1linkrapido2 = "<a  target='_blank' href='".$row['screens2']."'> >>>Screem<<< </a>";
    print "<tr><td width=20%  align=center  class=tab1_col3><b>Screem1:</b></td><td width=20%  align=center  class=tab1_col3><b>".$size2[0]."".$screemveri02."</b></td><td width=20%  align=center  class=tab1_col3><b>".$size2[1]."".$screemveri12."</b></td><td width=20%  align=center  class=tab1_col3><b>".$screens1linkrapido2."</b></td></tr>\n";

	}

   if ($row["screens3"] != ""){
     $size3 = getimagesize("".$row['screens3']."");
	if ($size3[0] ==  $row["filmeresolucao"] ){
$screemveri03 = " ---> <font color=#006600>Verdadeira</font>";
}else{
$screemveri03 = " ---> <font color=red>Falsa</font>";
}
	if ($size3[1] ==  $row["filmeresolucalt"] ){
$screemveri13 = " ---> <font color=#006600>Verdadeira</font>";
}else{
$screemveri13 = " ---> <font color=red>Falsa</font>";
}
$screens1linkrapido3 = "<a  target='_blank' href='".$row['screens3']."'> >>>Screem<<< </a>";
    print "<tr><td width=20%  align=center  class=tab1_col3><b>Screem2:</b></td><td width=20%  align=center  class=tab1_col3><b>".$size3[0]."".$screemveri03."</b></td><td width=20%  align=center  class=tab1_col3><b>".$size3[1]."".$screemveri13."</b></td><td width=20%  align=center  class=tab1_col3><b>".$screens1linkrapido3."</b></td></tr>\n";

	}

   if ($row["screens4"] != ""){
   	$size4 = getimagesize("".$row['screens4']."");
	if ($size4[0] ==  $row["filmeresolucao"] ){
$screemveri04 = " ---> <font color=#006600>Verdadeira</font>";
}else{
$screemveri04 = " ---> <font color=red>Falsa</font>";
}
	if ($size4[1] ==  $row["filmeresolucalt"] ){
$screemveri14 = " ---> <font color=#006600>Verdadeira</font>";
}else{
$screemveri14 = " ---> <font color=red>Falsa</font>";
}
$screens1linkrapido4 = "<a  target='_blank' href='".$row['screens4']."'> >>>Screem<<< </a>";
    print "<tr><td width=20%  align=center  class=tab1_col3><b>Screem3:</b></td><td width=20%  align=center  class=tab1_col3><b>".$size4[0]."".$screemveri04."</b></td><td width=20%  align=center  class=tab1_col3><b>".$size4[1]."".$screemveri14."</b></td><td width=20%  align=center  class=tab1_col3><b>".$screens1linkrapido4."</b></td></tr>\n";

	}

   if ($row["screens5"] != ""){
   	$size5 = getimagesize("".$row['screens5']."");
	if ($size5[0] ==  $row["filmeresolucao"] ){
$screemveri05 = " ---> <font color=#006600>Verdadeira</font>";
}else{
$screemveri05 = " ---> <font color=red>Falsa</font>";
}
	if ($size5[1] ==  $row["filmeresolucalt"] ){
$screemveri15 = " ---> <font color=#006600>Verdadeira</font>";
}else{
$screemveri15 = " ---> <font color=red>Falsa</font>";
}
$screens1linkrapido5 = "<a  target='_blank' href='".$row['screens5']."'> >>>Screem<<< </a>";
    print "<tr><td width=20%  align=center  class=tab1_col3><b>Screem4:</b></td><td width=20%  align=center  class=tab1_col3><b>".$size5[0]."".$screemveri05."</b></td><td width=20%  align=center  class=tab1_col3><b>".$size5[1]."".$screemveri15."</b></td><td width=20%  align=center  class=tab1_col3><b>".$screens1linkrapido5."</b></td></tr>\n";

	}
	
		echo "</table>";
	}
	
	
	
	  if ($row["category"] == 33 || $row["category"] == 36 || $row["category"] == 50 || $row["category"] == 41 || $row["category"] == 6 || $row["category"] == 117 || $row["category"] == 114 || $row["category"] == 102 || $row["category"] == 105 || $row["category"] == 10 || $row["category"] == 15 || $row["category"] == 11 || $row["category"] == 43 || $row["category"] == 12 || $row["category"] == 44 || $row["category"] == 13 || $row["category"] == 14 || $row["category"] == 16 || $row["category"] == 112 || $row["category"] == 49 || $row["category"] == 101 || $row["category"] == 99 || $row["category"] == 100 || $row["category"] == 103 || $row["category"] == 110  || $row["category"] == 111 || $row["category"] == 47 || $row["category"] == 102 || $row["category"] == 105 || $row["category"] == 10 || $row["category"] == 15 || $row["category"] == 11 || $row["category"] == 43 || $row["category"] == 12 || $row["category"] == 44 || $row["category"] == 13 || $row["category"] == 14 || $row["category"] == 16) 
	{
	
	echo "<table  cellpadding='0' cellspacing='0' border='1' width='100%'>";




print "<tr><td width=20%  align=center  class=tab1_cab1><b>Screem:</b></td><td width=20%  align=center  class=tab1_cab1><b>Largura:</b></td><td width=20%  align=center  class=tab1_cab1><b>Altura:</b></td><td width=40%  align=center  class=tab1_cab1><b>Link Rápido:</b></td> </tr>\n";

   if ($row["screens2"] != ""){
     $size2 = getimagesize("".$row['screens2']."");
	if ($size2[0] ==  $row["filmeresolucao"] ){
$screemveri02 = " ---> <font color=#006600>Verdadeira</font>";
}else{
$screemveri02 = " ---> <font color=red>Falsa</font>";
}
	if ($size2[1] ==  $row["filmeresolucalt"] ){
$screemveri12 = " ---> <font color=#006600>Verdadeira</font>";
}else{
$screemveri12 = " ---> <font color=red>Falsa</font>";
}
$screens1linkrapido2 = "<a  target='_blank' href='".$row['screens2']."'> >>>Screem<<< </a>";
    print "<tr><td width=20%  align=center  class=tab1_col3><b>Screem1:</b></td><td width=20%  align=center  class=tab1_col3><b>".$size2[0]."".$screemveri02."</b></td><td width=20%  align=center  class=tab1_col3><b>".$size2[1]."".$screemveri12."</b></td><td width=20%  align=center  class=tab1_col3><b>".$screens1linkrapido2."</b></td></tr>\n";

	}

   if ($row["screens3"] != ""){
     $size3 = getimagesize("".$row['screens3']."");
	if ($size3[0] ==  $row["filmeresolucao"] ){
$screemveri03 = " ---> <font color=#006600>Verdadeira</font>";
}else{
$screemveri03 = " ---> <font color=red>Falsa</font>";
}
	if ($size3[1] ==  $row["filmeresolucalt"] ){
$screemveri13 = " ---> <font color=#006600>Verdadeira</font>";
}else{
$screemveri13 = " ---> <font color=red>Falsa</font>";
}
$screens1linkrapido3 = "<a  target='_blank' href='".$row['screens3']."'> >>>Screem<<< </a>";
    print "<tr><td width=20%  align=center  class=tab1_col3><b>Screem2:</b></td><td width=20%  align=center  class=tab1_col3><b>".$size3[0]."".$screemveri03."</b></td><td width=20%  align=center  class=tab1_col3><b>".$size3[1]."".$screemveri13."</b></td><td width=20%  align=center  class=tab1_col3><b>".$screens1linkrapido3."</b></td></tr>\n";

	}

   if ($row["screens4"] != ""){
   	$size4 = getimagesize("".$row['screens4']."");
	if ($size4[0] ==  $row["filmeresolucao"] ){
$screemveri04 = " ---> <font color=#006600>Verdadeira</font>";
}else{
$screemveri04 = " ---> <font color=red>Falsa</font>";
}
	if ($size4[1] ==  $row["filmeresolucalt"] ){
$screemveri14 = " ---> <font color=#006600>Verdadeira</font>";
}else{
$screemveri14 = " ---> <font color=red>Falsa</font>";
}
$screens1linkrapido4 = "<a  target='_blank' href='".$row['screens4']."'> >>>Screem<<< </a>";
    print "<tr><td width=20%  align=center  class=tab1_col3><b>Screem3:</b></td><td width=20%  align=center  class=tab1_col3><b>".$size4[0]."".$screemveri04."</b></td><td width=20%  align=center  class=tab1_col3><b>".$size4[1]."".$screemveri14."</b></td><td width=20%  align=center  class=tab1_col3><b>".$screens1linkrapido4."</b></td></tr>\n";

	}

   if ($row["screens5"] != ""){
   	$size5 = getimagesize("".$row['screens5']."");
	if ($size5[0] ==  $row["filmeresolucao"] ){
$screemveri05 = " ---> <font color=#006600>Verdadeira</font>";
}else{
$screemveri05 = " ---> <font color=red>Falsa</font>";
}
	if ($size5[1] ==  $row["filmeresolucalt"] ){
$screemveri15 = " ---> <font color=#006600>Verdadeira</font>";
}else{
$screemveri15 = " ---> <font color=red>Falsa</font>";
}
$screens1linkrapido5 = "<a  target='_blank' href='".$row['screens5']."'> >>>Screem<<< </a>";
    print "<tr><td width=20%  align=center  class=tab1_col3><b>Screem4:</b></td><td width=20%  align=center  class=tab1_col3><b>".$size5[0]."".$screemveri05."</b></td><td width=20%  align=center  class=tab1_col3><b>".$size5[1]."".$screemveri15."</b></td><td width=20%  align=center  class=tab1_col3><b>".$screens1linkrapido5."</b></td></tr>\n";

	}
	
	echo "</table>";	

}

	print(" <br /><br /><p align='right' ></p><BR><p align='right'></p></div><br />  ");
	///////////dentro
	
	
	
	print("<br /><a href=\"javascript: klappe_modmodera3('a".$id3."')\"><img border=\"0\" src=\"".$site_config["SITEURL"]."/images/$pic.gif\" id=\"pica".$id3."\" alt=\"Show/Hide\" />");
				print("&nbsp;<b> Arquivos do Torrent </b></a><b> ");

				print("<div id=\"ka".$id3."\" style=\"display: $disp;\"> ");
				
					 echo "<center>Arquivos</center>";
		 echo "<BR><table  cellpadding='0' cellspacing='0' border='0' width='100%'>
		 
		 
		 
		 	<TR><td width=100%  align=right colspan=2 class=tab1_cab1><center> " . $row["filename"] . "</center></td></tr>
		 <TR><TD  width=50%  align=right  class=tab1_col3>&nbsp;".T_("FILE")."</TD><TD width=50%  align=left class=tab1_col3>&nbsp;Tamanho</td></tr>";
$fres = SQL_Query_exec("SELECT files.path, files.filesize FROM files WHERE torrent = $id");
if (mysql_num_rows($fres)) {
    while ($frow = mysql_fetch_array($fres)) {

        echo "<TR><td width=50%  align=right  class=tab1_col3>".htmlspecialchars($frow['path'])."</td><TD width=50%  align=left class=tab1_col3>".mksize($frow['filesize'])."</td></tr>";
    }
}else{

    echo "<TR><td width=50%  align=right  class=tab1_col3>".htmlspecialchars($row["name"])."</td><TD width=50%  align=left class=tab1_col3>".mksize($row["size"])."</td></tr>";
}
$quantidade = mysql_num_rows($fres);


echo "<TR><td width=100%  align=right colspan=2 class=tab1_col3><center>Total: $quantidade arquivos</center></td></TR></tr></table>";














				
					print(" <br /><br /><p align='right' ></p><BR><p align='right'></p></div><br />  ");

    print("<br /><a href=\"javascript: klappe_modmodera4('a".$id4."')\"><img border=\"0\" src=\"".$site_config["SITEURL"]."/images/$pic.gif\" id=\"pica".$id4."\" alt=\"Show/Hide\" />");
				print("&nbsp;<b> Baixar / Editar / Modera / Deletar </b></a><b> ");

				print("<div id=\"ka".$id4."\" style=\"display: $disp;\"> ");
					echo "<table  cellpadding='0' cellspacing='0' border='0' width='100%'>
		 
		  
		 
		 	<TR><td width=100%  align=right colspan=2 class=tab1_cab1><center> Ações da moderação</center></td></tr>";
					

 ?>





<script language="JavaScript"> 
function aprovar1(id){ 
if (window.confirm('Deseja aprovar este torrent')) {
 window.location.href = 'mark.php?id='+id
}
else { window.alert('Ok, nenhuma ação foi feita!') }

} 
</script>


<?php
					
					
		
					 print("<tr><td  width=100% align=center colspan=2 class=tab1_col3><br><a href=\"download.php?id=$id&name=" . rawurlencode($row["filename"]) . "\"><img src=\"".$site_config["SITEURL"]."/images/torrent/baixar.png\" border=\"0\"title='Baixar torrent.'></a></td></tr><br><TR><td width=100%  align=right colspan=2 class=tab1_cab1><center> $torrenteditor</center></td></tr>");
					
					
					
					
					 print("<tr><td  width=100% align=center colspan=2 class=tab1_col3><form method='post'  action='modtorrengeren.php?id=$id'><input type='checkbox' name='chgpasswd' value='yes'/>  Editar Título<br><input type='text' name='torrentnome' value='".htmlspecialchars($row["name"])."' size=60/><br><input type='submit' value='Editar Título' /></form></center></td></tr>");
					
					           print(" <TR><td width=100%  align=right colspan=2 class=tab1_cab1><center> Modera</center></td></tr>");
		


		
		
		
		
		

    print("<TR><td width=100%  align=right colspan=2 class=tab1_col3><center><form method='post' action='modtorrengeren.php?id=$id'><input type='hidden' name='moderamotivosid' value='".$row["id"]."' /><textarea COLS=45 ROWS=9 name='motivos'>
[color=#0000ff]Para caracterizar melhor seu lançamento, você deverá editar seu torrent, obedecendo às seguintes regras:[/color]

...(Detalhar o motivo para o usuário) se possível enviar algum tutorial.

Colocar seu nick para o usuário saber quem moderou.

Clique no endereço abaixo para editar seu torrent:
http://www.brshares.com/torrents-edit.php?id=".$row["id"]."


[color=#0000ff]Aguardamos a edição do torrent para a liberação.

Desde já, agradecemos pelo lançamento.[/color]

[b]Moderação - Equipe BR[/b]
	</textarea><br><br>Para que os outros MODs possam liberar em sua ausencia
, deixe o checkbox marcado.<br><br><input type='checkbox' name='moderarok' value='yes'/> checkbox<br><br><b>Motivo do envio da MP?</b><br><input type='text' name='moderamotivos' value='' size=60/><br><input type='submit' value='Moderar?' /></form></center></td></tr>");









					
				 print(" <TR><td width=100%  align=right colspan=2 class=tab1_cab1><center> Deletar Torrent</center></td></tr>");
					

			 print("<tr><td  width=100% align=center colspan=2 class=tab1_col3><form method='post'  action='modtorrengeren.php?id=$id'><input type='hidden' name='torrentname' value='".htmlspecialchars($row["name"])."' /><input type='hidden' name='deleteid' value='".$row["id"]."' />Razão para deletar: * <input type='text' size='60' name='delreason'/><input type='submit' value='Deletar' /><br>Exemplo: Torrent Duplicado: URL</form></center></td></tr>");
					
 

					
				 	
					echo "</table>";
					print(" <br /><br /><p align='right' ></p><BR><p align='right'></p></div><br />  ");
		
	print("<br /><a href=\"javascript: klappe_modmodera5('a".$id5."')\"><img border=\"0\" src=\"".$site_config["SITEURL"]."/images/$pic.gif\" id=\"pica".$id5."\" alt=\"Show/Hide\" />");
				print("&nbsp;<b> Liberar Torrent  </b></a><b> ");

				print("<div id=\"ka".$id5."\" style=\"display: $disp;\"> ");
	
	echo "<table  cellpadding='0' cellspacing='0' border='0' width='100%'><TR><td width=100%  align=right colspan=2 class=tab1_cab1><center>Liberar Torrent</center></td></tr>";
	

		?>
<tr><td width="100%" align="center" colspan="2" class="tab1_col3" ><a href="#"  onclick="aprovar1(<?php echo $id ;?>)"> Aprovar </a></td></tr>
<?php

	echo "</table>";
	
				print(" <br /><br /><p align='right' ></p><BR><p align='right'></p></div><br />  ");
			$news_flag++;		
				


}else{

show_error_msg("STOP", "Desculpe esta página é para os liberadores +");
end_framec();
}
stdfoot();
?>