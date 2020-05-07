<?php
############################################################
#######                                             ########
#######                                             ########
#######           brshares.com 2.0                  ########
#######                                             ########
#######                                             ########
############################################################
require_once("backend/functions.php");
dbconn();
function horaseed(){
	return'
  <select name="temposeed" size="1">
  <option value="Madrugada (0:00 as 6:00)">Madrugada (0:00 as 6:00)</option>
  <option value="Manha (6:00 as 12:00)">Manha (6:00 as 12:00)</option>
  <option value="Tarde (12:00 as 18:00)">Tarde (12:00 as 18:00)</option>
  <option value="Noite (18:00 as 00:00)">Noite (18:00 as 00:00)</option>
  <option value="Madrugada e Manha">Madrugada e Manha</option>
  <option value="Manha e Tarde">Manha e Tarde</option>
  <option value="Tarde e Noite">Tarde e Noite</option>
  <option value="Noite e Madrugada">Noite e Madrugada</option>
  <option value="24 Horas">24 Horas</option>
  <option value="Outro(especificar no torrent)">Outro(especificar no torrent)</option>
</select>
		';
					}
// check access and rights
if ($site_config["MEMBERSONLY"]){
	loggedinonly();

	if($CURUSER["can_upload"]=="no")
		show_error_msg(T_("ERROR"), T_("UPLOAD_NO_PERMISSION"), 1);
	if ($site_config["UPLOADERSONLY"] && $CURUSER["class"] < 4)
		show_error_msg(T_("ERROR"), T_("UPLOAD_ONLY_FOR_UPLOADERS"), 1);
			$ver_cat = $_GET["cat"];	

	if (!$ver_cat)
	show_error_msg(("Erro"), ("Você deve selecionar uma categoria"), 1);


}

$announce_urls = explode(",", strtolower($site_config["announce_list"]));  //generate announce_urls[] from config.php

if ($_POST["takeupload"] == "yes") {
	require_once("backend/parse.php");

	//check form data
	foreach(explode(":","type:name") as $v) {
		if (!isset($_POST[$v]))
			$message = T_("MISSING_FORM_DATA");
	}

	if (!isset($_FILES["torrent"]))
	$message = T_("MISSING_FORM_DATA");
    
    if (($num = $_FILES['torrent']['error']))
         show_error_msg('Error', T_("UPLOAD_ERR[$num]"), 1);

	$f = $_FILES["torrent"];
	$fname = $f["name"];

	if (empty($fname))
		$message = T_("EMPTY_FILENAME");

	if ($_FILES['nfo']['size'] != 0) {
		$nfofile = $_FILES['nfo'];

		if ($nfofile['name'] == '')
			$message = T_("NO_NFO_UPLOADED");
			
		if (!preg_match('/^(.+)\.nfo$/si', $nfofile['name'], $fmatches))
			$message = T_("UPLOAD_NOT_NFO");

		if ($nfofile['size'] == 0)
			$message = T_("NO_NFO_SIZE");

		if ($nfofile['size'] > 65535)
			$message = T_("NFO_UPLOAD_SIZE");

		$nfofilename = $nfofile['tmp_name'];

        if (($num = $_FILES['nfo']['error']))
             $message = T_("UPLOAD_ERR[$num]");
        
		$nfo = 'yes';
	}
             

		
	$descr = $_POST["descr"];

	if (!$descr)
		$message = "Por favor é obrigatorio preencher a ficha tecnica";
		
		
	$filmesinopse = $_POST["filmesinopse"];	

	if (!$filmesinopse)
		$message = "Por favor é obrigatorio preencher a sinopse";

	

	$langid = (int) $_POST["lang"];
	
	/*if (!is_valid_id($langid))
		$message = "Please be sure to select a torrent language";*/

	$catid = (int) $_POST["type"];

	if (!is_valid_id($catid))
		$message = T_("UPLOAD_NO_CAT");

		$filmeanoid = (int) $_POST["anoteste"];	
	if (!is_valid_id($filmeanoid))
		$message = T_("GERADOR_UPLOAD_ANO_FILMES");
///gerador filmes anos		
		
///gerador filmes audio
		$filmeaudioid = (int) $_POST["filmeaudio"];	
	if (!is_valid_id($filmeaudioid))
		$message = "Por favor tem que selecionar o campo audio";
///gerador filmes audio	
	
///gerador filmes Extensão
	$filmeextensaoid = (int) $_POST["filmeeste"];	
		if (!is_valid_id($filmeextensaoid))
		$message = "Por favor tem que selecionar o campo extensão";
///gerador filmes Extensão

///gerador filmes Qualidade
	$filmequalidadeid = (int) $_POST["filmequalid"];	
			if (!is_valid_id($filmequalidadeid))
		$message = "Por favor tem que selecionar o campo qualidade";
///gerador filmes Qualidade

///gerador filmes 3D
	$filme3did = (int) $_POST["filmes3d"];
	        if (!is_valid_id($filme3did))
		$message = "Por favor tem que selecionar o campo filme em 3D";
///gerador filmes 3D

///gerador filmes Codecs de Vídeo
	$filmecodecvidid = (int) $_POST["filmecodeclan"];
	        if (!is_valid_id($filmecodecvidid))
		$message = "Por favor tem que selecionar o campo codecs de vídeo";
///gerador filmes Codecs de Vídeo

///gerador filmes Codecs de Audio
	$filmecodecaudid = (int) $_POST["filmecodeaudi"];
	    	if (!is_valid_id($filmecodecaudid))
		$message = "Por favor tem que selecionar o campo codecs de audio";
///gerador filmes Codecs de Audio

///gerador filmes Idioma Original
    $filmeidiomaorigiid = (int) $_POST["filmeidioma"];
	        if (!is_valid_id($filmeidiomaorigiid))
		$message = "Por favor tem que selecionar o campo idioma original";
///gerador filmes Idioma Original

///gerador filmes Duração H
	$filmeduracaohid = (int) $_POST["filmedurah"];
	        if (!is_valid_id($filmeduracaohid))
		$message = "Por favor tem que selecionar o campo duração horas";
///gerador filmes Duração H

///gerador filmes Duração M
	$filmeduracaomiid = (int) $_POST["filmedurami"];
	if (!is_valid_id($filmeduracaomiid))
		$message = "Por favor tem que selecionar o campo duração minutos";
///gerador filmes Duração M

///gerador legenda
    $legendaid = (int) $_POST["legendalan"];
	        if (!is_valid_id($legendaid))
		$message = "Por favor tem que selecionar o campo legenda embutida";
///gerador legenda
		
		
	if (!validfilename($fname))
		$message = T_("UPLOAD_INVALID_FILENAME");

	if (!preg_match('/^(.+)\.torrent$/si', $fname, $matches))
		$message = T_("UPLOAD_INVALID_FILENAME_NOT_TORRENT");

		$shortfname = $torrent = $matches[1];

///gerador nome inicio
$name = $_POST['name'];	
$tmpname = $f['tmp_name'];
	if (!$name)
	$message = T_("GERADOR_UPLOAD_NOME_FILMES");
///gerador nome fim    
 if (!empty($_POST['tube']))
            $tube = unesc($_POST['tube']);		
///gerador pixels de largura inicio
$filmeresolucao = $_POST['filmeresolucao'];	
	if (!$filmeresolucao)
	 $message = "Por favor você tem que colocar o pixels de largura";
///gerador pixels de largura fim  
  
///gerador pixels de largura inicio
$filmeresolucalt = $_POST['filmeresolucalt'];	
	if (!$filmeresolucalt)
	 $message = "Por favor você tem que colocar o pixels de largura";
///gerador pixels de largura fim    

///screenshot
        $screens1 = $_POST['screens1'];
               if (!$screens1)
		$message = "Por favor você tem que colocar o link da capa do filme";
        $screens2 = $_POST['screens2'];
               if (!$screens2)
		$message = "Por favor você tem que colocar o link da Screen 1";
        $screens3 = $_POST['screens3'];
             if (!$screens3)
		$message = "Por favor você tem que colocar o link da Screen 2";
        $screens4 = $_POST['screens4'];
         if (!$screens4)
		  $screens4 = "";
$screens5 = $_POST['screens5'];		
        if (!$screens5)
        $screens5 = "";
        ///screenshot
	$catid = (int) $_POST["type"];

	if (!is_valid_id($catid))
		$message = T_("UPLOAD_NO_CAT");

	//end check form data

	if (!$message) {
	//parse torrent file
	$torrent_dir = $site_config["torrent_dir"];	
	$nfo_dir = $site_config["nfo_dir"];	

	//if(!copy($f, "$torrent_dir/$fname"))
	if(!move_uploaded_file($tmpname, "$torrent_dir/$fname"))
		show_error_msg(T_("ERROR"), T_("ERROR"). ": " . T_("UPLOAD_COULD_NOT_BE_COPIED")." $tmpname - $torrent_dir - $fname",1);

    $TorrentInfo = array();
    $TorrentInfo = ParseTorrent("$torrent_dir/$fname");


	$announce = $TorrentInfo[0];
	$infohash = $TorrentInfo[1];
	$creationdate = $TorrentInfo[2];
	$internalname = $TorrentInfo[3];
	$torrentsize = $TorrentInfo[4];
	$filecount = $TorrentInfo[5];
	$annlist = $TorrentInfo[6];
	$comment = $TorrentInfo[7];
	$filelist = $TorrentInfo[8];

/*
//for debug...
	print ("<br /><br />announce: ".$announce."");
	print ("<br /><br />infohash: ".$infohash."");
	print ("<br /><br />creationdate: ".$creationdate."");
	print ("<br /><br />internalname: ".$internalname."");
	print ("<br /><br />torrentsize: ".$torrentsize."");
	print ("<br /><br />filecount: ".$filecount."");
	print ("<br /><br />annlist: ".$annlist."");
	print ("<br /><br />comment: ".$comment."");
*/
	
	//check announce url is local or external
	if (!in_array($announce, $announce_urls, 1)){
		$external='yes';
    }else{
		$external='no';
	}

	//if externals is turned off
	if (!$site_config["ALLOWEXTERNAL"] && $external == 'yes')
		$message = T_("UPLOAD_NO_TRACKER_ANNOUNCE");
	}
	if ($message) {
		@unlink("$torrent_dir/$fname");
		@unlink($tmpname);
		@unlink("$nfo_dir/$nfofilename");
		show_error_msg(T_("UPLOAD_FAILED"), $message,1);
	}

	//release name check and adjust
	if ($name ==""){
		$name = $internalname;
	}
	$name = str_replace(".torrent","",$name);
	$name = str_replace("_", " ", $name);

	//upload images
	$allowed_types = &$site_config["allowed_image_types"];

	$inames = array();
	for ($x=0; $x < 2; $x++) {
		if (!($_FILES[image.$x]['name'] == "")) {
			$y = $x + 1;

			//if (!preg_match('/^(.+)\.(jpg|gif|png)$/si', $_FILES[image.$x]['name']))
			//	show_error_msg(T_("INVAILD_IMAGE"), T_("THIS_FILETYPE_NOT_IMAGE"), 1);

			if ($_FILES['image$x']['size'] > $site_config['image_max_filesize'])
				show_error_msg(T_("ERROR"), T_("INVAILD_FILE_SIZE_IMAGE"), 1);

			$uploaddir = $site_config["torrent_dir"]."/images/";

			$ifile = $_FILES[image.$x]['tmp_name'];

			$im = getimagesize($ifile);

			if (!$im[2])
				show_error_msg(T_("ERROR"), sprintf(T_("INVALID_IMAGE"), $y), 1);

			if (!array_key_exists($im['mime'], $allowed_types))
				show_error_msg(T_("ERROR"), T_("INVALID_FILETYPE_IMAGE"), 1);

			$ret = SQL_Query_exec("SHOW TABLE STATUS LIKE 'torrents'");
			$row = mysql_fetch_array($ret);
			$next_id = $row['Auto_increment'];

			$ifilename = $next_id . $x . $allowed_types[$im['mime']];

			$copy = copy($ifile, $uploaddir.$ifilename);

			if (!$copy)
				show_error_msg(T_("ERROR"), sprintf(T_("IMAGE_UPLOAD_FAILED"), $y), 1);

			$inames[] = $ifilename;

		}

	}
	//end upload images

	//anonymous upload
	$anonyupload = $_POST["anonycheck"]; 
	if ($anonyupload == "yes") {
		$anon = "yes";
	}else{
		$anon = "no";
	}

			$mes = date("m", utc_to_tz_time($row['date']));
$dia = date("d", utc_to_tz_time($row['date']));
$ano = date("y", utc_to_tz_time($row['date']));
	if ($external == "no") {
        require_once("backend/BDecode.php");
        require_once("backend/BEncode.php");
        $dict = BDecode(file_get_contents("$torrent_dir/$fname"));
        $dict["info"]["private"] = 1;
        $fs = fopen("$torrent_dir/$fname", "w");
        fwrite($fs, BEncode($dict));
        fclose($fs);
        $TorrentInfo = array();
        $TorrentInfo = ParseTorrent("$torrent_dir/$fname");
        $infohash = $TorrentInfo[1];
}
	$temposeed = $_POST["temposeed"];
	$ret = SQL_Query_exec("INSERT INTO torrents (temposeed, filename, owner, name, filmeresolucao, filmeresolucalt, screens1, screens2, screens3, screens4, screens5, descr, filmesinopse, image1, image2, category, added,  mes, dia, ano, info_hash, size, numfiles, save_as, announce, external, nfo, torrentlang, filmeano, filmeaudio, filmeextensao, filmequalidade, filme3d, filmecodecvid, filmecodecaud, filmeidiomaorigi, filmeduracaoh, filmeduracaomi, legenda, anon, tube, last_action, userup) VALUES (".sqlesc($temposeed).",".sqlesc($fname).", '".$CURUSER['id']."', ".sqlesc($name).", ".sqlesc($filmeresolucao).", ".sqlesc($filmeresolucalt).", ".sqlesc($screens1).", ".sqlesc($screens2).", ".sqlesc($screens3).", ".sqlesc($screens4).", ".sqlesc($screens5).", ".sqlesc($descr).", ".sqlesc($filmesinopse).", '".$inames[0]."', '".$inames[1]."', '".$catid."', '" . get_date_time() . "', '" . $mes . "' , '" . $dia . "' , '" . $ano . "' , '".$infohash."', '".$torrentsize."', '".$filecount."', ".sqlesc($fname).", '".$announce."', '".$external."', '".$nfo."', '".$langid."', '".$filmeanoid."', '".$filmeaudioid."', '".$filmeextensaoid."', '".$filmequalidadeid."', '".$filme3did."','".$filmecodecvidid."', '".$filmecodecaudid."', '".$filmeidiomaorigiid."', '".$filmeduracaohid."', '".$filmeduracaomiid."', '".$legendaid."', '$anon', '".$tube."','".get_date_time()."','".$CURUSER["username"]."')");

	$id = mysql_insert_id();
	
	if (mysql_errno() == 1062)
		show_error_msg(T_("UPLOAD_FAILED"), T_("UPLOAD_ALREADY_UPLOADED"), 1);

	//Update the members uploaded torrent count
	/*if ($ret){
		SQL_Query_exec("UPDATE users SET torrents = torrents + 1 WHERE id = $userid");*/
        
	if($id == 0){
		unlink("$torrent_dir/$fname");
		$message = T_("UPLOAD_NO_ID");
		show_error_msg(T_("UPLOAD_FAILED"), $message, 1);
	}
    
    rename("$torrent_dir/$fname", "$torrent_dir/$id.torrent"); 

	if (count($filelist)) {
		foreach ($filelist as $file) {
			$dir = '';
			$size = $file["length"];
			$count = count($file["path"]);
			for ($i=0; $i<$count;$i++) {
				if (($i+1) == $count)
					$fname = $dir.$file["path"][$i];
				else
					$dir .= $file["path"][$i]."/";
			}
			SQL_Query_exec("INSERT INTO `files` (`torrent`, `path`, `filesize`) VALUES($id, ".sqlesc($fname).", $size)");
		}
	} else {
		SQL_Query_exec("INSERT INTO `files` (`torrent`, `path`, `filesize`) VALUES($id, ".sqlesc($TorrentInfo[3]).", $torrentsize)");
	}

	if (!count($annlist)) {
		$annlist = array(array($announce));
	}
	foreach ($annlist as $ann) {
		foreach ($ann as $val) {
			if (strtolower(substr($val, 0, 4)) != "udp:") {
				SQL_Query_exec("INSERT INTO `announce` (`torrent`, `url`) VALUES($id, ".sqlesc($val).")");
			}
		}
	}

	if ($nfo == 'yes') { 
            move_uploaded_file($nfofilename, "$nfo_dir/$id.nfo"); 
    } 

	//EXTERNAL SCRAPE
	if ($external=='yes' && $site_config['UPLOADSCRAPE']){
		$tracker=str_replace("/announce","/scrape",$announce);	
		$stats 			= torrent_scrape_url($tracker, $infohash);
		$seeders 		= strip_tags($stats['seeds']);
		$leechers 		= strip_tags($stats['peers']);
		$downloaded 	= strip_tags($stats['downloaded']);

		SQL_Query_exec("UPDATE torrents SET leechers='".$leechers."', seeders='".$seeders."',times_completed='".$downloaded."',last_action= '".get_date_time()."',visible='yes' WHERE id='".$id."'"); 
	}
	//END SCRAPE

		write_loguser("Torrents-lançados","#FF0000","O torrent [url=http://www.brshares.com/torrents-details.php?id=".$id."]".$name."[/url] foi lançado por [url=http://www.brshares.com/account-details.php?id=".$CURUSER["id"]."]".$CURUSER["username"]."[/url]\n");

	//insert email notif, irc, req notif, etc here


	
	// Requests inicio pedido
if (is_valid_id($_POST["request"])) {
    /* PM for requested user */
    $res = SQL_Query_exec("SELECT `userid` FROM `requests` WHERE `id` = ". intval($_POST['request'])) or die(mysql_error());
    $msg = "Your request, [url=$site_config[SITEURL]/reqdetails.php?id=" . $requestid . "][b]" . $arr[request] . "[/b][/url], has been filled by [url=$site_config[SITEURL]/account-details.php?id=" . $CURUSER[id] . "][b]" . $arr2[username] . "[/b][/url]. You can download your request from  [url=" . $filledurl. "][b]" . $filledurl. "[/b][/url].  Please do not forget to leave thanks where due.  If for some reason this is not what you requested, please reset your request so someone else can fill it by following [url=$site_config[SITEURL]/reqreset.php?requestid=" . $requestid . "]this[/url] link.  Do [b]NOT[/b] follow this link unless you are sure that this does not match your request.";
    $subject = "Your request has been filled";
    if ($row = mysql_fetch_assoc($res)) {
        SQL_Query_exec("INSERT INTO messages (poster, sender, receiver, added, subject, msg) VALUES(0, 0, $arr[userid], '" . get_date_time() . "', " . sqlesc($subject) . ", " . sqlesc($msg) . ")");
    }

    /* Fill request */
    $filledurl = "$site_config[SITEURL]/torrents-details.php?id=$id&hit=1";
    SQL_Query_exec ("UPDATE requests SET filled = '$filledurl', filledby = $CURUSER[id] WHERE id = ". $_POST[request] ."") or die(mysql_error());
    SQL_Query_exec("DELETE FROM `addedrequests` WHERE `requestid` = ". ($_POST['request'] + 0));
    write_log("Pedidos atendido","#CD96CD","O pedido ($torrent) Foi atendido por  " . $CURUSER["username"] . " ");
}
	

	//insert email notif, irc, req notif, etc here
	
	//Uploaded ok message (update later)
	if ($external=='no')
		$message = sprintf( T_("TORRENT_UPLOAD_LOCAL"), $name, $id, $id );
	else
		$message = sprintf( T_("TORRENT_UPLOAD_EXTERNAL"), $name, $id );
	show_error_msg(T_("UPLOAD_COMPLETE"), $message, 1);

	die();
}//takeupload


///////////////////// FORMAT PAGE ////////////////////////

stdhead("UPLOAD");

begin_framec("Enviar Torrent");
?>
<form name="upload" enctype="multipart/form-data" action="torrents-shows.php?cat=<?php echo $_GET["cat"] ;?>" method="post">
<input type="hidden" name="takeupload" value="yes" />
<input type="hidden" name="type" value="<?php echo $_GET["cat"] ;?>" />
<table class='tab1' cellpadding='0' cellspacing='1' align='center'>
<?php

print("<td width='100%'  align='center'  colspan='2' class='ttable_col1'>");
print("<B>Dicas Importantes!</B><br>");
print("<a href='/forums.php?action=viewtopic&topicid=1895'>Clique aqui</a> para aprender a lançar usando o Utorrent.<BR>");
print("<b>Torrents duplicados serão deletados.</b><BR>");
print("Portanto, antes de lançar um torrent, faça uma <a href='/http://www.brshares.com/torrents-pesquisa'>Pesquisa</a> para saber se ele já foi lançado.");


print ("<tr><td width=100%  colspan='2' align=center  class=tab1_col3 >URL de anúncio:<br> ");

while (list($key,$value) = each($announce_urls)) {
	echo "<b><input value='$value' size='45' onclick='this.select();' class='ui-state-default ui-corner-all'></b><br />";
}


print ("</td></tr>");
print ("<tr><td  width=50%  align=right  class=tab1_col3>" . T_("TORRENT_FILE") . ": </td><td width=50% align=left class=tab1_col3> <input type='file' name='torrent' size='50' value='" . $_FILES['torrent']['name'] . "' />\n</td></tr>");
print ("<tr><td  width=50%  align=right  class=tab1_col3 >Torrent nome: </td><td width=50% align=left class=tab1_col3><input type='text' name='name' size='60' value='" . $_POST['name'] . "' /><br />Este será retirado. torrent se estiver vazio \n</td></tr>");

print ("<TR><TD  width=50%  align=right  class=tab1_col3>Horário de Seed:</td><td width=50% align=left class=tab1_col3>".horaseed()."</td></tr>");
///// Filme Ano de lançamento
$anoid = "<select name=\"anoteste\">\n<option value=\"0\">Escolher</option>\n";

$anos1 = anoslist();
foreach ($anos1 as $row)
	$anoid .= "<option value=\"" . $row["id"] . "\">" . htmlspecialchars($row["name"]) . "</option>\n";

$anoid .= "</select>\n";

print ("<TR><TD  width=50%  align=right  class=tab1_col3>Ano de lançamento: *</td><td width=50% align=left class=tab1_col3>".$anoid."</td></tr>");
/////Filme Ano de lançamento
///// Filme Audio de lançamento
$filmeauid = "<select name=\"filmeaudio\">\n<option value=\"0\">Escolher</option>\n";

$audiid = filmeaudilist();
foreach ($audiid as $row)
	$filmeauid .= "<option value=\"" . $row["id"] . "\">" . htmlspecialchars($row["name"]) . "</option>\n";

$filmeauid .= "</select>\n";

print ("<TR><TD  width=50%  align=right  class=tab1_col3>Audio: *</td><td width=50% align=left class=tab1_col3>".$filmeauid."</td></tr>");
/////Filme Audio de lançamento
///// Filme Extensão de lançamento
$filmeextid = "<select name=\"filmeeste\">\n<option value=\"0\">Escolher</option>\n";

$filmesex = filmeextelist();
foreach ($filmesex as $row)
	$filmeextid .= "<option value=\"" . $row["id"] . "\">" . htmlspecialchars($row["name"]) . "</option>\n";

$filmeextid .= "</select>\n";

print ("<TR><TD  width=50%  align=right  class=tab1_col3>Extensão: *</td><td width=50% align=left class=tab1_col3>".$filmeextid."</td></tr>");
/////Filme Extensão de lançamento
///// Filme Qualidade de lançamento
$filmequali2 = "<select name=\"filmequalid\">\n<option value=\"0\">Escolher</option>\n";

$quali1 = filmequalidlist();
foreach ($quali1 as $row)
	$filmequali2 .= "<option value=\"" . $row["id"] . "\">" . htmlspecialchars($row["name"]) . "</option>\n";

$filmequali2 .= "</select>\n";

print ("<TR><TD  width=50%  align=right  class=tab1_col3>Qualidade: *</td><td width=50% align=left class=tab1_col3>".$filmequali2."</td></tr>");
/////Filme Qualidade de lançamento
///// Filme em 3D de lançamento
$filme3did = "<select name=\"filmes3d\">\n<option value=\"0\">Escolher</option>\n";

$filme3dte = filme3dlist();
foreach ($filme3dte as $row)
	$filme3did .= "<option value=\"" . $row["id"] . "\">" . htmlspecialchars($row["name"]) . "</option>\n";

$filme3did .= "</select>\n";

print ("<TR><TD  width=50%  align=right  class=tab1_col3>Shows em 3D? *</td><td width=50% align=left class=tab1_col3>".$filme3did."</td></tr>");
/////Filme em 3D de lançamento
///// Filme legendas embutidas
$fimeslegenda = "<select name=\"legendalan\">\n<option value=\"0\">Escolher</option>\n";

$legendasfilmes = legendalist();
foreach ($legendasfilmes as $row)
	$fimeslegenda .= "<option value=\"" . $row["id"] . "\">" . htmlspecialchars($row["name"]) . "</option>\n";

$fimeslegenda .= "</select>\n";


print ("<TR><TD  width=50%  align=right  class=tab1_col3>Legenda Embutidas?: *</td><td width=50% align=left class=tab1_col3>".$fimeslegenda."</td></tr>");
///// Filme legendas embutidas
///// Filme Codecs de Vídeo de lançamento
$filmecodecid = "<select name=\"filmecodeclan\">\n<option value=\"0\">Escolher</option>\n";

$codecfilme = filmecodvilist();
foreach ($codecfilme as $row)
	$filmecodecid .= "<option value=\"" . $row["id"] . "\">" . htmlspecialchars($row["name"]) . "</option>\n";

$filmecodecid .= "</select>\n";

print ("<TR><TD  width=50%  align=right  class=tab1_col3>Codecs de Vídeo: *</td><td width=50% align=left class=tab1_col3>".$filmecodecid."</td></tr>");
/////Filme Codecs de Vídeo de lançamento
///// Filme Codecs de Audio de lançamento
$filmecodevidid = "<select name=\"filmecodeaudi\">\n<option value=\"0\">Escolher</option>\n";

$codecaudi = filmecodecaudlist();
foreach ($codecaudi as $row)
	$filmecodevidid .= "<option value=\"" . $row["id"] . "\">" . htmlspecialchars($row["name"]) . "</option>\n";

$filmecodevidid .= "</select>\n";

print ("<TR><TD  width=50%  align=right  class=tab1_col3>Codecs de Audio: *</td><td width=50% align=left class=tab1_col3>".$filmecodevidid."</td></tr>");
/////Filme Codecs de Audio de lançamento
///// Filme Idioma Original de lançamento
$idiomafilme = "<select name=\"filmeidioma\">\n<option value=\"0\">Escolher</option>\n";

$filmeidomao = filmeidiorilist();
foreach ($filmeidomao as $row)
	$idiomafilme .= "<option value=\"" . $row["id"] . "\">" . htmlspecialchars($row["name"]) . "</option>\n";

$idiomafilme .= "</select>\n";

print ("<TR><TD  width=50%  align=right  class=tab1_col3>Idioma Original: *</td><td width=50% align=left class=tab1_col3>".$idiomafilme."</td></tr>");
/////Filme Idioma Original de lançamento
///// Filme Duração hora de lançamento
$filmeduratio = "<select name=\"filmedurah\">\n<option value=\"0\">Escolher</option>\n";

$duratiomhor = filmedurhorlist();
foreach ($duratiomhor as $row)
	$filmeduratio .= "<option value=\"" . $row["id"] . "\">" . htmlspecialchars($row["name"]) . "</option>\n";

$filmeduratio .= "</select>\n";
/////Filme Duração hora de lançamento 
///// Filme Duração minutos de lançamento
$fimesminutosi = "<select name=\"filmedurami\">\n<option value=\"0\">Escolher</option>\n";

$minutosfilme = filmeduraminulist();
foreach ($minutosfilme as $row)
	$fimesminutosi .= "<option value=\"" . $row["id"] . "\">" . htmlspecialchars($row["name"]) . "</option>\n";

$fimesminutosi .= "</select>\n";

print ("<TR><TD  width=50%  align=right  class=tab1_col3>Duração: *</td><td width=50% align=left class=tab1_col3>".$filmeduratio."horas e".$fimesminutosi."minutos.</td></tr>");

///// Filme Duração minutos de lançamento

print ("<TR><TD  width=50%  align=right  class=tab1_col3>Resolução: *</td><td width=50% align=left class=tab1_col3><input type=text name=filmeresolucao size=4 value=" . $_POST['filmeresolucao'] . ">pixels de largura por<input type=text name=filmeresolucalt size=4 value=" . $_POST['filmeresolucalt'] . ">pixels de altura.<BR>\n</td></tr>");
// screenshots //
print ("<TR><TD  width=50%  align=right  class=tab1_col3>Capa: *</td><td width=50% align=left class=tab1_col3><input type=text name=screens1 size=30 value=" . $_POST['screens1'] . "><a href=\"javascript: void(0);\" onclick=\"window.open('http://img.brshares.com/', 'Upload','width=610,height=600,resizable=no,scrollbars=yes,toolbar=no,location=no,directories=no,status=no');\">Hospedar imagem</a><BR>\n</td></tr>");
print ("<TR><TD  width=50%  align=right  class=tab1_col3>Screen 1: *</td><td width=50% align=left class=tab1_col3><input type=text name=screens2 size=30 value=" . $_POST['screens2'] . "><BR>\n</td></tr>");
print ("<TR><TD  width=50%  align=right  class=tab1_col3>Screen 2: *</td><td width=50% align=left class=tab1_col3><input type=text name=screens3 size=30 value=" . $_POST['screens3'] . "><BR>\n</td></tr>");
print ("<TR><TD  width=50%  align=right  class=tab1_col3>Screen 3:  </td><td width=50% align=left class=tab1_col3><input type=text name=screens4 size=30 value=" . $_POST['screens4'] . "><BR>\n</td></tr>");
print ("<TR><TD  width=50%  align=right  class=tab1_col3>Screen 4:  </td><td width=50% align=left class=tab1_col3><input type=text name=screens5 size=30 value=" . $_POST['screens5'] . "><BR>\n</td></tr>");
//screenshots //


if ($site_config['ANONYMOUSUPLOAD'] && $site_config["MEMBERSONLY"] ){ ?>
	<tr><td align="right"><?php echo T_("UPLOAD_ANONY");?>: </td><td><?php printf("<input name='anonycheck' value='yes' type='radio' " . ($anonycheck ? " checked='checked'" : "") . " />Yes <input name='anonycheck' value='no' type='radio' " . (!$anonycheck ? " checked='checked'" : "") . " />No"); ?> &nbsp;<i><?php echo T_("UPLOAD_ANONY_MSG");?></i>
	</td></tr>
	<?php
}

 

/////fim pedido de torrentes
echo "<TR><TD width=100%  align=center  colspan=2 class=tab1_col3>";
echo "Sinopse: *";
require_once("backend/bbcodecerto.php");
$dossier1 = $CURUSER['bbcode'];
print ("".textbbcode1("upload","filmesinopse",$dossier1,"$filmesinopse")."");
echo "</TD></TR>";

echo "<TR><TD width=100%  align=center colspan=2  class=tab1_col3>";
echo "Ficha Técnica: *";

$dossier = $CURUSER['bbcode'];
print ("".textbbcode("upload","descr",$dossier,"$descr")."");
echo "</TD></TR>";



?>

<tr><td width='100%'  align='center'  colspan='2' class='tab1_col3'><center><input type="submit" value="<?php echo T_("UPLOAD_TORRENT"); ?>" />
<i><br><?php echo T_("CLICK_ONCE_IMAGE");?></i>
</center></TD></TR>
</form>

<?php
  print("</table>\n");
end_framec();
stdfoot();
?>